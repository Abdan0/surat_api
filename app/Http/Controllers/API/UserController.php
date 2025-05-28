<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return response()->json([
            'status' => true,
            'message' => 'Daftar user berhasil diambil',
            'data' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Detail user',
            'data' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Log semua data yang diterima untuk debugging
        Log::info('Update user request data:', [
            'id' => $id,
            'request_data' => $request->all(),
            'headers' => $request->header()
        ]);

        // Tangani format role yang dikirim
        $role = $request->role;
        if ($role === 'wakil_dekan') {
            $role = 'wakil dekan';
        }

        // Validasi
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'nidn' => 'required|string|max:15|unique:users,nidn,'.$id,
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed:', ['errors' => $validator->errors()]);
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::find($id);
        if (!$user) {
            Log::warning('User not found:', ['id' => $id]);
            return response()->json(['error' => 'User not found'], 404);
        }

        // Update user data
        $user->name = $request->name;
        $user->nidn = $request->nidn;
        $user->role = $role; // Gunakan role yang sudah diubah formatnya

        // Update password jika ada
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        Log::info('User updated successfully:', ['user' => $user]);
        return response()->json(['message' => 'User updated successfully', 'data' => $user]);
    }

    // Alternatif update endpoint
    public function updateUser(Request $request)
    {
        Log::info('Update user alternative method request:', [
            'request_data' => $request->all(),
            'headers' => $request->header()
        ]);

        if (!$request->has('id')) {
            return response()->json(['error' => 'User ID required'], 422);
        }

        $id = $request->id;

        // Tangani format role yang dikirim
        $role = $request->role;
        if ($role === 'wakil_dekan') {
            $role = 'wakil dekan';
        }

        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Update user data
        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('nidn')) {
            // Validasi NIDN unik kecuali untuk user ini sendiri
            $existingUser = User::where('nidn', $request->nidn)
                ->where('id', '!=', $id)
                ->first();

            if ($existingUser) {
                return response()->json(['error' => 'NIDN already used by another user'], 422);
            }

            $user->nidn = $request->nidn;
        }

        if ($request->has('role')) {
            $user->role = $role;
        }

        // Update password jika ada
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json(['message' => 'User updated successfully', 'data' => $user]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }
}
