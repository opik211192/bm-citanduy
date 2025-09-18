@extends('layouts.app')
@section('menu','active')

@push('styles')
<style>
    /* warna konsisten untuk semua komponen (badge, small-box, chart, dll.) */
    .color-sumur {
        background-color: #198754 !important;
        /* hijau */
        color: #fff !important;
    }

    .color-mata-air {
        background-color: #20c997 !important;
        /* teal */
        color: #fff !important;
    }

    .color-intake {
        background-color: #0dcaf0 !important;
        /* cyan */
        color: #fff !important;
    }

    .color-pah {
        background-color: #ffc107 !important;
        /* kuning */
        color: #212529 !important;
        /* teks gelap biar kebaca */
    }

    .color-tampungan {
        background-color: #9f4951 !important;
        /* merah */
        color: #fff !important;
    }
</style>
@endpush
@section('content')
<div class="row">
    <!-- Infrastruktur Card -->
    <div class="col-lg-6 col-12 mb-4">
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Data Infrastruktur</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Embung -->
                    <div class="col-md-6 col-12 mb-3">
                        <div class="small-box bg-primary shadow rounded-lg">
                            <div class="inner">
                                <h3>{{ $embung_count }}</h3>
                                <p class="font-weight-bold">Embung</p>
                            </div>
                            <div class="icon"><i class="fas fa-water"></i></div>
                        </div>
                    </div>
                    <!-- Bendung -->
                    <div class="col-md-6 col-12 mb-3">
                        <div class="small-box bg-secondary shadow rounded-lg">
                            <div class="inner">
                                <h3>{{ $bendung_count }}</h3>
                                <p class="font-weight-bold">Bendung</p>
                            </div>
                            <div class="icon"><i class="fas fa-stream"></i></div>
                        </div>
                    </div>
                    <!-- Bendungan -->
                    <div class="col-md-6 col-12 mb-3">
                        <div class="small-box bg-info shadow rounded-lg">
                            <div class="inner">
                                <h3>{{ $bendungan_count }}</h3>
                                <p class="font-weight-bold">Bendungan</p>
                            </div>
                            <div class="icon"><i class="fas fa-landmark"></i></div>
                        </div>
                    </div>
                    <!-- Pengaman Pantai -->
                    <div class="col-md-6 col-12 mb-3">
                        <div class="small-box shadow rounded-lg" style="background-color:#6f42c1; color:white;">
                            <div class="inner">
                                <h3>{{ $pengaman_pantai_count }}</h3>
                                <p class="font-weight-bold">Pengaman Pantai</p>
                            </div>
                            <div class="icon"><i class="fas fa-umbrella-beach"></i></div>
                        </div>
                    </div>
                    <!-- Pengendali Sedimen -->
                    <div class="col-md-6 col-12 mb-3">
                        <div class="small-box shadow rounded-lg" style="background-color:#9c956d; color:white;">
                            <div class="inner">
                                <h3>{{ $pengendali_sedimen_count }}</h3>
                                <p class="font-weight-bold">Pengendali Sedimen</p>
                            </div>
                            <div class="icon"><i class="fas fa-mountain"></i></div>
                        </div>
                    </div>
                    <!-- Pengendali Banjir -->
                    <div class="col-md-6 col-12 mb-3">
                        <div class="small-box shadow rounded-lg" style="background-color:#ff4fa3; color:black;">
                            <div class="inner">
                                <h3>{{ $pengendali_banjir_count }}</h3>
                                <p class="font-weight-bold">Pengendali Banjir</p>
                            </div>
                            <div class="icon"><i class="fas fa-house-flood-water"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Air Baku Card -->
    <div class="col-lg-6 col-12 mb-4">
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Data Air Baku</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Sumur -->
                    <div class="col-md-6 col-12 mb-3">
                        <div class="small-box color-sumur shadow rounded-lg">
                            <div class="inner">
                                <h3>{{ $sumur_count }}</h3>
                                <p class="font-weight-bold">Sumur</p>
                            </div>
                            <div class="icon"><i class="fas fa-tint"></i></div>
                        </div>
                    </div>
                    <!-- Mata Air -->
                    <div class="col-md-6 col-12 mb-3">
                        <div class="small-box color-mata-air shadow rounded-lg">
                            <div class="inner">
                                <h3>{{ $mata_air_count }}</h3>
                                <p class="font-weight-bold">Mata Air</p>
                            </div>
                            <div class="icon"><i class="fas fa-water"></i></div>
                        </div>
                    </div>
                    <!-- Intake Sungai -->
                    <div class="col-md-6 col-12 mb-3">
                        <div class="small-box color-intake shadow rounded-lg">
                            <div class="inner">
                                <h3>{{ $intake_count }}</h3>
                                <p class="font-weight-bold">Intake Sungai</p>
                            </div>
                            <div class="icon"><i class="fas fa-faucet"></i></div>
                        </div>
                    </div>
                    <!-- PAH / ABSAH -->
                    <div class="col-md-6 col-12 mb-3">
                        <div class="small-box color-pah shadow rounded-lg">
                            <div class="inner">
                                <h3>{{ $pah_count }}</h3>
                                <p class="font-weight-bold">PAH / ABSAH</p>
                            </div>
                            <div class="icon"><i class="fas fa-oil-can"></i></div>
                        </div>
                    </div>
                    <!-- Tampungan Air Baku -->
                    <div class="col-md-6 col-12 mb-3">
                        <div class="small-box color-tampungan shadow rounded-lg">
                            <div class="inner">
                                <h3>{{ $tampungan_count }}</h3>
                                <p class="font-weight-bold">Tampungan Air Baku</p>
                            </div>
                            <div class="icon"><i class="fas fa-database"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Distribusi & Total Card -->
<div class="row">
    <!-- Distribusi -->
    <div class="col-lg-8 col-12 mb-4">
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header bg-dark text-white text-center">
                <h5 class="mb-0">Grafik Distribusi</h5>
            </div>
            <div class="card-body row">
                <div class="col-md-6 d-flex justify-content-center">
                    <canvas id="infraDoughnut" style="max-height:250px;"></canvas>
                </div>
                <div class="col-md-6 d-flex justify-content-center">
                    <canvas id="airDoughnut" style="max-height:250px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Total -->
    <div class="col-lg-4 col-12 mb-4">
        <div class="card shadow-lg border-0 rounded-lg text-center">
            <div class="card-header bg-light">
                <h5 class="mb-0">Total Data</h5>
            </div>
            <div class="card-body">
                <h6 class="text-muted">Infrastruktur</h6>
                <h2 class="text-primary font-weight-bold">
                    {{ $embung_count + $bendung_count + $bendungan_count + $pengaman_pantai_count +
                    $pengendali_sedimen_count + $pengendali_banjir_count }}
                </h2>
                <hr>
                <h6 class="text-muted">Air Baku</h6>
                <h2 class="text-success font-weight-bold">
                    {{ $sumur_count + $mata_air_count + $intake_count + $pah_count + $tampungan_count }}
                </h2>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Infrastruktur Doughnut
    new Chart(document.getElementById('infraDoughnut'), {
        type: 'doughnut',
        data: {
            labels: ['Embung','Bendung','Bendungan','Pengaman Pantai','Pengendali Sedimen','Pengendali Banjir'],
            datasets: [{
                data: [{{ $embung_count }}, {{ $bendung_count }}, {{ $bendungan_count }},
                       {{ $pengaman_pantai_count }}, {{ $pengendali_sedimen_count }}, {{ $pengendali_banjir_count }}],
                backgroundColor: ['#007bff','#6c757d','#17a2b8','#6f42c1','#9c956d','#ff4fa3'],
                borderColor: ['#007bff','#6c757d','#17a2b8','#6f42c1','#9c956d','#ff4fa3'],
                borderWidth: 2
            }]
        },
        options: {responsive: true, plugins: {legend: {position: 'bottom'}}}
    });

    // Air Baku Doughnut
    new Chart(document.getElementById('airDoughnut'), {
        type: 'doughnut',
        data: {
            labels: ['Sumur','Mata Air','Intake Sungai','PAH/ABSAH','Tampungan Air Baku'],
            datasets: [{
                data: [{{ $sumur_count }}, {{ $mata_air_count }}, {{ $intake_count }}, {{ $pah_count }}, {{ $tampungan_count }}],
                backgroundColor: ['#198754','#20c997','#0dcaf0','#ffc107','#9f4951'],
                borderColor: ['#198754','#20c997','#0dcaf0','#ffc107','#9f4951'],
                borderWidth: 2
            }]
        },
        options: {responsive: true, plugins: {legend: {position: 'bottom'}}}
    });
</script>
@endpush