<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_agenda',
        'surat_id',
        'tanggal_agenda',
        'pengirim',
        'penerima',
    ];

    /**
     * Get the surat that owns this agenda.
     */
    public function surat()
    {
        return $this->belongsTo(Surat::class);
    }

    /**
     * Get the pengirim name, fallback to surat's asal_surat if not set.
     */
    public function getNamaPengirimAttribute()
    {
        return $this->pengirim ?? $this->surat->asal_surat;
    }

    /**
     * Get the penerima name, fallback to surat's tujuan_surat if not set.
     */
    public function getNamaPenerimaAttribute()
    {
        return $this->penerima ?? $this->surat->tujuan_surat;
    }
}
