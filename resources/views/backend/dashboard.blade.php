@extends('layouts.app')
@section('menu','active')

@section('content')
<div class="col">
    <div class="row">
        <!-- Embung -->
        <div class="col-lg-4 col-12 mb-3">
            <div class="small-box bg-primary shadow rounded-lg">
                <div class="inner">
                    <h3>{{ $embung_count }}</h3>
                    <p class="font-weight-bold">Embung</p>
                </div>
                <div class="icon">
                    <i class="fas fa-water"></i>
                </div>
            </div>
        </div>

        <!-- Bendung -->
        <div class="col-lg-4 col-12 mb-3">
            <div class="small-box bg-secondary shadow rounded-lg">
                <div class="inner">
                    <h3>{{ $bendung_count }}</h3>
                    <p class="font-weight-bold">Bendung</p>
                </div>
                <div class="icon">
                    <i class="fas fa-stream"></i>
                </div>
            </div>
        </div>

        <!-- Bendungan -->
        <div class="col-lg-4 col-12 mb-3">
            <div class="small-box bg-info shadow rounded-lg">
                <div class="inner">
                    <h3>{{ $bendungan_count }}</h3>
                    <p class="font-weight-bold">Bendungan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-landmark"></i>
                </div>
            </div>
        </div>

        <!-- Pengaman Pantai -->
        <div class="col-lg-4 col-12 mb-3">
            <div class="small-box shadow rounded-lg" style="background-color:#6f42c1; color:white;">
                <div class="inner">
                    <h3>{{ $pengaman_pantai_count }}</h3>
                    <p class="font-weight-bold">Pengaman Pantai</p>
                </div>
                <div class="icon">
                    <i class="fas fa-umbrella-beach"></i>
                </div>
            </div>
        </div>

        <!-- Pengendali Sedimen -->
        <div class="col-lg-4 col-12 mb-3">
            <div class="small-box shadow rounded-lg" style="background-color:#9c956d; color:white;">
                <div class="inner">
                    <h3>{{ $pengendali_sedimen_count }}</h3>
                    <p class="font-weight-bold">Pengendali Sedimen</p>
                </div>
                <div class="icon">
                    <i class="fas fa-mountain"></i>
                </div>
            </div>
        </div>

        <!-- Pengendali Banjir -->
        <div class="col-lg-4 col-12 mb-3">
            <div class="small-box shadow rounded-lg" style="background-color:#ff4fa3; color:black;">
                <div class="inner">
                    <h3>{{ $pengendali_banjir_count }}</h3>
                    <p class="font-weight-bold">Pengendali Banjir</p>
                </div>
                <div class="icon">
                    <i class="fas fa-house-flood-water"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Doughnut + Total Asset -->
    <div class="row mt-4 d-flex align-items-stretch">
        <!-- Grafik Doughnut -->
        <div class="col-lg-8 d-flex">
            <div class="card shadow-lg border-0 rounded-lg flex-fill">
                <div class="card-header bg-dark text-white text-center">
                    <h5 class="mb-0">Distribusi Aset</h5>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="assetDoughnut" style="max-height:280px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Card Total Asset -->
        <div class="col-lg-4 d-flex">
            <div class="card shadow-lg border-0 rounded-lg flex-fill d-flex">
                <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                    <h4 class="text-dark font-weight-bold mb-4" style="font-size:1.5rem;">
                        <i class="fas fa-database mr-2 text-primary"></i>
                        Total Aset
                    </h4>
                    <h1 class="text-success font-weight-bold" style="font-size:4rem; line-height:1;">
                        {{ $total_asset }}
                    </h1>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('assetDoughnut').getContext('2d');

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Embung', 'Bendung', 'Bendungan', 'Pengaman Pantai', 'Pengendali Sedimen', 'Pengendali Banjir'],
            datasets: [{
                label: 'Jumlah Aset',
                data: [{{ $embung_count }}, {{ $bendung_count }}, {{ $bendungan_count }}, {{ $pengaman_pantai_count }}, {{ $pengendali_sedimen_count }}, {{ $pengendali_banjir_count }}],
                backgroundColor: [
                    'rgba(0, 123, 255, 0.7)', // Embung (primary)
                    'rgba(108, 117, 125, 0.7)', // Bendung (secondary)
                    'rgba(23, 162, 184, 0.7)', // Bendungan (info)
                    'rgba(111, 66, 193, 0.7)', // Pengaman Pantai (purple)
                    'rgba(156, 149, 109, 0.7)', // Pengendali Sedimen (sage)
                    'rgba(255, 79, 163, 0.7)' // Pengendali Banjir (pink)
                ],
                borderColor: [
                    'rgba(0, 123, 255, 1)',
                    'rgba(108, 117, 125, 1)',
                    'rgba(23, 162, 184, 1)',
                    'rgba(111, 66, 193, 1)',
                    'rgba(156, 149, 109, 1)',
                    'rgba(255, 79, 163, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // biar fleksibel di card
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { size: 14, weight: 'bold' }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            let value = context.raw || 0;
                            return `${label}: ${value}`;
                        }
                    }
                }
            }
        }
    });
</script>
@endpush