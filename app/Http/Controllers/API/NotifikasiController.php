<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use App\Http\Resources\NotifikasiResource;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    /**
     * Mendapatkan semua notifikasi user yang login
     */
    public function index()
    {
        $userId = Auth::id();
        $notifikasi = Notifikasi::where('user_id', $userId)
                               ->orderBy('created_at', 'desc')
                               ->get();

        return response()->json([
            'status' => true,
            'message' => 'Data notifikasi berhasil ditemukan',
            'data' => NotifikasiResource::collection($notifikasi)
        ]);
    }

    /**
     * Menyimpan notifikasi baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'judul' => 'required|string|max:255',
            'pesan' => 'required|string',
            'tipe' => 'nullable|string|max:50',
            'reference_id' => 'nullable|integer'
        ]);

        $notifikasi = Notifikasi::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Notifikasi berhasil dibuat',
            'data' => new NotifikasiResource($notifikasi)
        ], 201);
    }

    /**
     * Menampilkan detail notifikasi
     */
    public function show($id)
    {
        $userId = Auth::id();
        $notifikasi = Notifikasi::where('id', $id)
                               ->where('user_id', $userId)
                               ->first();

        if (!$notifikasi) {
            return response()->json([
                'status' => false,
                'message' => 'Notifikasi tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Detail notifikasi',
            'data' => new NotifikasiResource($notifikasi)
        ]);
    }

    /**
     * Menandai notifikasi sebagai dibaca
     */
    public function markAsRead($id)
    {
        $userId = Auth::id();
        $notifikasi = Notifikasi::where('id', $id)
                               ->where('user_id', $userId)
                               ->first();

        if (!$notifikasi) {
            return response()->json([
                'status' => false,
                'message' => 'Notifikasi tidak ditemukan'
            ], 404);
        }

        $notifikasi->markAsRead();

        return response()->json([
            'status' => true,
            'message' => 'Notifikasi ditandai sudah dibaca',
            'data' => new NotifikasiResource($notifikasi)
        ]);
    }

    /**
     * Menandai semua notifikasi user sebagai dibaca
     */
    public function markAllAsRead()
    {
        $userId = Auth::id();
        Notifikasi::where('user_id', $userId)
                 ->where('dibaca', false)
                 ->update([
                     'dibaca' => true,
                     'read_at' => now()
                 ]);

        return response()->json([
            'status' => true,
            'message' => 'Semua notifikasi ditandai sudah dibaca'
        ]);
    }

    /**
     * Menghitung jumlah notifikasi yang belum dibaca
     */
    public function unreadCount()
    {
        $userId = Auth::id();
        $count = Notifikasi::where('user_id', $userId)
                          ->where('dibaca', false)
                          ->count();

        return response()->json([
            'status' => true,
            'unread_count' => $count
        ]);
    }

    /**
     * Menghapus notifikasi
     */
    public function destroy($id)
    {
        $userId = Auth::id();
        $notifikasi = Notifikasi::where('id', $id)
                               ->where('user_id', $userId)
                               ->first();

        if (!$notifikasi) {
            return response()->json([
                'status' => false,
                'message' => 'Notifikasi tidak ditemukan'
            ], 404);
        }

        $notifikasi->delete();

        return response()->json([
            'status' => true,
            'message' => 'Notifikasi berhasil dihapus'
        ]);
    }
}
