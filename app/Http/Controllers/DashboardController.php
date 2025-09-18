<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\AirBaku;
use App\Models\Benchmark;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
        public function index() {

        $benchmark_count = Benchmark::count();

        $total_asset = Aset::count();

        // Aset
        $embung_count = Aset::where('jenis_aset', 'Embung')->count();
        $bendung_count = Aset::where('jenis_aset', 'Bendung')->count();
        $bendungan_count = Aset::where('jenis_aset', 'Bendungan')->count();
        $pengaman_pantai_count = Aset::where('jenis_aset', 'Pengaman Pantai')->count();
        $pengendali_sedimen_count = Aset::where('jenis_aset', 'Pengendali Sedimen')->count();
        $pengendali_banjir_count = Aset::where('jenis_aset', 'Pengendali Banjir')->count();

        // Air Baku
        $sumur_count = AirBaku::where('jenis_aset', 'Sumur')->count();
        $mata_air_count = AirBaku::where('jenis_aset', 'Mata Air')->count();
        $intake_count = AirBaku::where('jenis_aset', 'Intake Sungai')->count();
        $pah_count = AirBaku::where('jenis_aset', 'PAH/ABSAH')->count();
        $tampungan_count = AirBaku::where('jenis_aset', 'Tampungan Air Baku')->count();

        return view('backend.dashboard', compact(
            'benchmark_count', 'total_asset',
            'embung_count', 'bendung_count', 'bendungan_count',
            'pengaman_pantai_count', 'pengendali_sedimen_count', 'pengendali_banjir_count',
            'sumur_count', 'mata_air_count', 'intake_count', 'pah_count', 'tampungan_count'
        ));
    }

}
