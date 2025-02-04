<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BenchmarkResource extends JsonResource
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
            'kode_bm' => $this->kode_bm,
            'nama_pekerjaan' => $this->nama_pekerjaan,
            'lat' => $this->lat,
            'long' => $this->long,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
