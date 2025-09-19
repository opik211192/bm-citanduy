<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirBakuPhoto extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function airBaku()
    {
        return $this->belongsTo(AirBaku::class, 'kode_integrasi', 'kode_integrasi');
    }

}
