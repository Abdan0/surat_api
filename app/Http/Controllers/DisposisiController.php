<?php

namespace App\Http\Controllers;

use App\Models\Disposisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DisposisiController extends Controller
{
    /**
     * Menampilkan semua data disposisi
     */
    public function index()
    {
        $disposisi = Disposisi::with(['surat', 'dariUser', 'kepadaUser'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $disposisi
        ], 200);
    }

    /**
     * Menyimpan data disposisi baru
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'surat_id' => 'required|exists:surats,id',
            'kepada_user_id' => 'required|exists:users,id',
            'instruksi' => 'nullable|string',
            'status' => 'required|in:diajukan,ditindaklanjuti,selesai',
            'tanggal_disposisi' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $disposisi = Disposisi::create([
            'surat_id' => $request->surat_id,
            'dari_user_id' => Auth::id(),
            'kepada_user_id' => $request->kepada_user_id,
            'instruksi' => $request->instruksi,
            'status' => $request->status,
            'tanggal_disposisi' => $request->tanggal_disposisi,
        ]);

        return response()->json([
            'message' => 'Disposisi berhasil dibuat',
            'data' => $disposisi
        ], 201);
    }

    /**
     * Menampilkan detail data disposisi
     */
    public function show($id)
    {
        $disposisi = Disposisi::with(['surat', 'dariUser', 'kepadaUser'])->find($id);

        if (!$disposisi) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Disposisi not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $disposisi
        ]);
    }

    /**
     * Mengupdate data disposisi
     */
    public function update(Request $request, $id)
    {
        $disposisi = Disposisi::find($id);

        if (!$disposisi) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Disposisi not found'
            ], 404);
        }

        // Validasi fleksibel (partial update)
        $validator = Validator::make($request->all(), [
            'surat_id' => 'sometimes|required|exists:surats,id',
            'kepada_user_id' => 'sometimes|required|exists:users,id',
            'instruksi' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:diajukan,ditindaklanjuti,selesai',
            'tanggal_disposisi' => 'sometimes|required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Update hanya data yang dikirim
        $disposisi->update($request->only([
            'surat_id',
            'kepada_user_id',
            'instruksi',
            'status',
            'tanggal_disposisi',
        ]));

        return response()->json([
            'message' => 'Disposisi berhasil diperbarui',
            'data' => $disposisi
        ], 200);
    }

    /**
     * Menghapus data disposisi
     */
    public function destroy($id)
    {
        $disposisi = Disposisi::find($id);

        if (!$disposisi) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Disposisi not found'
            ], 404);
        }

        $disposisi->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Disposisi berhasil dihapus'
        ]);
    }
}
