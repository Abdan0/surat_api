<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SuratController extends Controller
{
    public function index()
    {
        // Menampilkan semua data surat
        // return response()->json(Surat::with('user')->latest()->get());
        $surat = Surat::with('kategori')->get();

        return response()->json([
            'status' => 'success',
            'data' => $surat
        ], 200);
    }

    // Menyimpan data surat
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomor_surat' => 'required|unique:surats',
            'tipe' => 'required|in:masuk,keluar',
            'kategori' => 'required|in:internal,eksternal',
            // 'asal_surat' => 'required|string',
            // 'tujuan_surat' => 'nullable|string',
            'tanggal_surat' => 'required|date',

            // validasi kondisional
            'asal_surat' => 'required_if:tipe,masuk',
            'tujuan_surat' => 'required_if:tipe,keluar',

            'perihal' => 'required|string',
            'isi' => 'required|string',
            'file' => 'nullable|file|mimes:pdf|max:2048',
            'status' => 'required|in:draft,dikirim,diverifikasi,ditolak',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()]);
        }

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('surat', 'public');
        }

        $surat = Surat::create([
            'nomor_surat' => $request->nomor_surat,
            'tipe' => $request->tipe,
            'kategori' => $request->kategori,
            'asal_surat' => $request->asal_surat,
            'tujuan_surat' => $request->tujuan_surat,
            'tanggal_surat' => $request->tanggal_surat,
            'perihal' => $request->perihal,
            'isi' => $request->isi,
            'file' => $filePath,
            'status' => $request->status,
            'user_id' => Auth::id(),
        ]);

        // return response()->json(['status' => 'success', 'data' => $surat]);
        return response()->json([
            'message' => 'Surat berhasil disimpan',
            'data' => $surat
        ], 201);
    }

    // Menampilkan detail surat
    public function show($id)
    {
        $surat = Surat::with('user')->find($id);
        if (!$surat) return response()->json(['status' => 'failed', 'message' => 'Surat not found'], 404);
        return response()->json(['status' => 'success', 'data' => $surat]);
    }

    // Mengupdate data surat
    public function update(Request $request, $id)
    {
        $surat = Surat::find($id);
        if (!$surat) return response()->json(['status' => 'failed', 'message' => 'Surat not found'], 404);

        $validator = Validator::make($request->all(), [
            'nomor_surat' => 'sometimes|required|unique:surats,nomor_surat,' . $id,
            'tipe' => 'sometimes|required|in:masuk,keluar',
            'kategori' => 'sometimes|required|in:internal,eksternal',
            'tanggal_surat' => 'sometimes|required|date',
            // 'asal_surat' => 'required_if:tipe,masuk',
            // 'tujuan_surat' => 'required_if:tipe,keluar',
            'perihal' => 'sometimes|required|string',
            'isi' => 'sometimes|required|string',
            'file' => 'sometimes|nullable|file|mimes:pdf|max:2048',
            'status' => 'sometimes|required|in:draft,dikirim,diverifikasi,ditolak',
        ]);

        if ($request->has('tipe')) {
            if ($request->tipe === 'masuk' && !$request->filled('asal_surat')) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Field asal_surat wajib diisi jika tipe = masuk'
                ], 422);
            }

            if ($request->tipe === 'keluar' && !$request->filled('tujuan_surat')) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Field tujuan_surat wajib diisi jika tipe = keluar'
                ], 422);
            }
        }


        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()], 422);
        }

        $filePath = $surat->file;
        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($surat->file);
            $filePath = $request->file('file')->store('surat', 'public');
        }

        $surat->update(array_merge(
            $request->only([
                'nomor_surat',
                'tipe',
                'kategori',
                'asal_surat',
                'tujuan_surat',
                'tanggal_surat',
                'perihal',
                'isi',
                'status'
            ]),
            ['file' => $filePath]
        ));


        return response()->json([
            'message' => 'Surat berhasil diperbarui',
            'data' => $surat
        ], 200);
    }

    // Menghapus data surat
    public function destroy($id)
    {
        $surat = Surat::find($id);
        if (!$surat) return response()->json(['status' => 'failed', 'message' => 'Surat not found'], 404);

        if ($surat->file) {
            Storage::disk('public')->delete($surat->file);
        }

        $surat->delete();

        return response()->json(['status' => 'success', 'message' => 'Surat deleted']);
    }
}
