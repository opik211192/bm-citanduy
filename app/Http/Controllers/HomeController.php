<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Benchmark;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $jenisPekerjaanAset = Aset::select('jenis_aset')
            ->distinct()
            ->orderBy('jenis_aset')
            ->pluck('jenis_aset');

         $jenisPekerjaanListBenchmark = Benchmark::select('jenis_pekerjaan')
            ->distinct()
            ->orderBy('jenis_pekerjaan')
            ->pluck('jenis_pekerjaan');
            
        return view('home', compact('jenisPekerjaanListBenchmark', 'jenisPekerjaanAset'));
    }
}
