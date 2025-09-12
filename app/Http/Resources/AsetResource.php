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
            'kode_integrasi' => $this->kode_integrasi,
            'kode_bmn' => $this->kode_bmn,
            'nama_aset' => $this->nama_aset,
            'jenis_aset' => $this->jenis_aset,
            'wilayah_sungai' => $this->wilayah_sungai,
            'das' => $this->das,
            'province' => $this->province,
            'city' => $this->city,
            'district' => $this->district,
            'village' => $this->village,
            'lat' => $this->lat,
            'long' => $this->long,
            'tahun_mulai_bangunan' => $this->tahun_mulai_bangunan,
            'tahun_selesai_bangunan' => $this->tahun_selesai_bangunan,
            'kondisi_bangunan' => $this->kondisi_bangunan,
            'status_operasi' => $this->status_operasi,
            'kondisi_infrastruktur' => $this->kondisi_infrastruktur
        ];
    }
}
