<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Notifikasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'tipe',
        'reference_id',
        'dibaca',
        'read_at'
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Method statis untuk pembuatan notifikasi disposisi
    public static function createDisposisiNotifikasi($userId, $pengirimName, $disposisi, $surat)
    {
        // Debug untuk memastikan fungsi ini terpanggil
        Log::info('Creating Disposisi Notification', [
            'user_id' => $userId,
            'pengirim' => $pengirimName,
            'disposisi_id' => $disposisi->id,
            'surat_id' => $disposisi->surat_id
        ]);

        return self::create([
            'user_id' => $userId,
            'judul' => 'Disposisi Baru',
            'pesan' => "Anda menerima disposisi baru dari {$pengirimName} terkait surat {$surat->nomor_surat}",
            'tipe' => 'disposisi',
            'reference_id' => $disposisi->id,
            'dibaca' => false
        ]);
    }
}
