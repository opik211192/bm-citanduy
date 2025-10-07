<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeojsonController extends Controller
{
    public function dasIndex()
    {
        $pathDas = public_path('js/batasDas.geojson');
        return view('backend.geojson.das.index', compact('pathDas'));
    }

    public function sungaiIndex()
    {

    }
}
