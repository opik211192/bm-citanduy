<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BmPhoto extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function bm()
    {
        return $this->belongsTo(Bm::class, 'bm_id', 'id');
    }
}

