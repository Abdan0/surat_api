<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategoris';
    protected $primaryKey = 'nomor_kategori';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'nomor_kategori',
        'kategori',
        'deskripsi'
    ];

    // Relasi dengan surat one to many
    // public function surat(){
    //     return $this->hasMany(Surat::class);
    // }

    public function surats()
    {
        return $this->hasMany(Surat::class, 'nomor_kategori', 'nomor_kategori');
    }
}
