<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotifikasiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'judul' => $this->judul,
            'pesan' => $this->pesan,
            'tipe' => $this->tipe,
            'reference_id' => $this->reference_id,
            'dibaca' => (bool) $this->dibaca,
            'created_at' => $this->created_at,
            'read_at' => $this->read_at
        ];
    }
}
