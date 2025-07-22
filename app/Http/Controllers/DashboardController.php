<?php

namespace App\Http\Controllers;

use App\Models\Benchmark;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {

        $benchmark_count = Benchmark::count();
        return view('backend.dashboard', compact('benchmark_count'));
    }
}
