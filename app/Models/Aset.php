<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;
use Laravolt\Indonesia\Models\Province;

class Aset extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = true; 

    // public function photos()
    // {
    //     return $this->hasMany(AsetPhoto::class);
    // }
    public function photos()
    {
        return $this->hasMany(AsetPhoto::class, 'kode_integrasi', 'kode_integrasi');
    }


    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function village()
    {
        return $this->belongsTo(Village::class, 'village_id');
    }
}
