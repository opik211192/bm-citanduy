<?php

namespace App\Http\Controllers;

use App\Models\AirBaku;
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
        // === ASET ===
        $jenisPekerjaanAset = Aset::select('jenis_aset')
            ->distinct()
            ->orderBy('jenis_aset')
            ->pluck('jenis_aset');

        $asetCounts = Aset::select('jenis_aset', \DB::raw('count(*) as total'))
            ->groupBy('jenis_aset')
            ->pluck('total', 'jenis_aset');

        // === BENCHMARK ===
        $jenisPekerjaanListBenchmark = Benchmark::select('jenis_pekerjaan')
            ->distinct()
            ->orderBy('jenis_pekerjaan')
            ->pluck('jenis_pekerjaan');

        $benchmarkCounts = Benchmark::select('jenis_pekerjaan', \DB::raw('count(*) as total'))
            ->groupBy('jenis_pekerjaan')
            ->pluck('total', 'jenis_pekerjaan');

        // === AIR BAKU ===
        $jenisAirBaku = AirBaku::select('jenis_aset')
            ->distinct()
            ->orderBy('jenis_aset')
            ->pluck('jenis_aset');

        $airBakuCounts = AirBaku::select('jenis_aset', \DB::raw('count(*) as total'))
            ->groupBy('jenis_aset')
            ->pluck('total', 'jenis_aset');

        return view('home', compact(
            'jenisPekerjaanListBenchmark',
            'jenisPekerjaanAset',
            'jenisAirBaku',
            'asetCounts',
            'benchmarkCounts',
            'airBakuCounts'
        ));
    }

}
