<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AirbakuResource extends JsonResource
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
            'id'               => $this->id,
            'kode_integrasi'   => $this->kode_integrasi,
            'kode_bmn'         => $this->kode_bmn,
            'nama_aset'        => $this->nama_aset,
            'jenis_aset'       => $this->jenis_aset,
            'wilayah_sungai'   => $this->wilayah_sungai,
            'das'              => $this->das,
            'province'         => $this->province,
            'city'             => $this->city,
            'district'         => $this->district,
            'village'          => $this->village,
            'lat'              => $this->lat,
            'long'             => $this->long,
            'tahun_pembangunan'=> $this->tahun_pembangunan,
            'status_operasi'   => $this->status_operasi,
            'status_pekerjaan' => $this->status_pekerjaan,
            'sungai'           => $this->sungai,
        ];
    }
}
