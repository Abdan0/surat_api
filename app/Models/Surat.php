<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Surat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_surat',
        'tipe',
        'kategori',
        'asal_surat',
        'tujuan_surat',
        'tanggal_surat',
        'perihal',
        'isi',
        'file',
        'status',
        'user_id',
    ];

    // Relasi dengan user one to one
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan kategori one to one
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'nomor_kategori', 'nomor_kategori');
    }

    // Relasi dengan disposisi one to many
    public function disposisi()
    {
        return $this->hasMany(Disposisi::class);
    }

    // Relasi dengan agenda one to one
    public function agenda()
    {
        return $this->hasOne(Agenda::class);
    }
}
