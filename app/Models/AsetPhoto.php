<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsetPhoto extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function aset()
    {
        return $this->belongsTo(Aset::class);
    }
}
