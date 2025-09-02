<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AsetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nama_aset' => $this->nama_aset,
            'jenis_aset' => $this->jenis_aset,
            'no_registrasi' => $this->no_registrasi,
            'kode_bmn' => $this->kode_bmn,
            'province' => $this->province->name,
            'city' => $this->city->name,
            'district' => $this->district->name,
            'village' => $this->village->name,
            'lat' => $this->lat,
            'long' => $this->long,
            'utm_x' => $this->utm_x,
            'utm_y' => $this->utm_y,
            'tahun_mulai_bangunan' => $this->tahun_mulai_bangunan,
            'tahun_selesai_bangunan' => $this->tahun_selesai_bangunan,
            'kondisi_bangunan' => $this->kondisi_bangunan,
            'keterangan' => $this->keterangan
        ];
    }
}
