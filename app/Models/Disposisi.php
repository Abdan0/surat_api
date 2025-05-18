<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disposisi extends Model
{
    protected $fillable = [
        'surat_id',
        'dari_user_id',
        'kepada_user_id',
        'instruksi',
        'status',
        'tanggal_disposisi',
    ];

    public function surat()
    {
        return $this->belongsTo(Surat::class);
    }

    public function dariUser()
    {
        return $this->belongsTo(User::class, 'dari_user_id');
    }

    public function kepadaUser()
    {
        return $this->belongsTo(User::class, 'kepada_user_id');
    }
}
