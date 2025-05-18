<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{

        // GET /api/kategori
        public function index()
        {
            try {
                $kategori = Kategori::all();
                return response()->json($kategori, 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Failed to fetch buses'], 500);
            }
        }
    // POST kategori
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomor_kategori' => 'required|unique:kategoris',
            'kategori' => 'required|string',
            'deskripsi' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()]);
        }

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('surat', 'public');
        }

        $kategori = Kategori::create([
            'nomor_kategori' => $request->nomor_kategori,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,

            'user_id' => Auth::id(),
        ]);

        // return response()->json(['status' => 'success', 'data' => $surat]);
        return response()->json([
            'message' => 'Kategori berhasil disimpan berhasil disimpan',
            'data' => $kategori
        ], 201);
    }

    // GET kategori
    public function show($id)
    {
        $kategori = Kategori::with('user')->find($id);
        if (!$kategori) return response()->json(['status' => 'failed', 'message' => 'Kategori not found'], 404);
        return response()->json(['status' => 'success', 'data' => $kategori]);
    }

    // PUT kategori
    public function update(Request $request, $nomor_kategori)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'kategori' => 'required|string',
                    'deskripsi' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Cari kategori berdasarkan nomor_kategori
            $kategori = Kategori::where('nomor_kategori', $nomor_kategori)->first();

            if (!$kategori) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Kategori tidak ditemukan'
                ], 404);
            }

            // Update hanya field yang diperlukan
            $kategori->update([
                'kategori' => $request->kategori,
                'deskripsi' => $request->deskripsi,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Kategori berhasil diperbarui',
                'data' => $kategori
            ], 200);
        } catch (\Throwable $error) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Error: ' . $error->getMessage()
            ], 500);
        }
    }

    // DESTROY Kategori
    public function destroy($id)
    {
        $kategori = Kategori::find($id);

        if (!$kategori) {
            return response()->json(['message' => 'Kategori Tidak Ditemukan'], 404);
        }

        $kategori->delete();

        return response()->json(['message' => 'Kategori Berhasil Dihapus'], 200);
    }
}
