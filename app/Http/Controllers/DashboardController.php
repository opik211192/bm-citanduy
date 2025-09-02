<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Benchmark;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {

        $benchmark_count = Benchmark::count();

        $total_asset = Aset::count();

        $embung_count = Aset::where('jenis_aset', 'Embung')->count();
        $bendung_count = Aset::where('jenis_aset', 'Bendung')->count();
        $bendungan_count = Aset::where('jenis_aset', 'Bendungan')->count();
        $pengaman_pantai_count = Aset::where('jenis_aset', 'Pengaman Pantai')->count();
        $pengendali_sedimen_count = Aset::where('jenis_aset', 'Pengendali Sedimen')->count();
        $pengendali_banjir_count = Aset::where('jenis_aset', 'Pengendali Banjir')->count();

        return view('backend.dashboard', compact('benchmark_count', 'total_asset', 'embung_count', 'bendung_count', 'bendungan_count', 'pengaman_pantai_count', 'pengendali_sedimen_count', 'pengendali_banjir_count'));
    }
}
