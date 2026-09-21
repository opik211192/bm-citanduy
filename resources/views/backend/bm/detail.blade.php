@extends('layouts.app')
@section('benchmark','active')

@section('content')
<div class="col">
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h4 class="font-weight-bold mb-0">
                <i class="fas fa-map-marker-alt mr-1"></i> Detail Bench Mark — {{ $bm->kode_bm }}
            </h4>
            <a href="{{ route('bm.index') }}" class="btn btn-secondary" style="margin-left: auto;">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
        <div class="card-body">

            {{-- Data Utama --}}
            <div class="mb-4">
                <h5 class="font-weight-bold text-white bg-primary p-2 rounded">
                    <i class="fas fa-database mr-1"></i> Data Utama
                </h5>
                <table class="table table-bordered table-striped mb-0">
                    <tbody>
                        <tr>
                            <td class="font-weight-bold" style="width: 30%;">Kode BM</td>
                            <td>{{ $bm->kode_bm ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Nama Pekerjaan</td>
                            <td>{{ $bm->nama_pekerjaan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Tahun</td>
                            <td>{{ $bm->tahun ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Keterangan</td>
                            <td>{{ $bm->keterangan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">NFC ID</td>
                            <td>
                                @if($bm->nfc_id)
                                <span class="badge badge-success"><i class="fas fa-wifi mr-1"></i> {{ $bm->nfc_id
                                    }}</span>
                                @else
                                <span class="text-muted">Belum diset</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Lokasi --}}
            <div class="mb-4">
                <h5 class="font-weight-bold text-white bg-success p-2 rounded">
                    <i class="fas fa-map-marked-alt mr-1"></i> Lokasi
                </h5>
                <table class="table table-bordered table-striped mb-0">
                    <tbody>
                        <tr>
                            <td class="font-weight-bold" style="width: 30%;">Provinsi</td>
                            <td>{{ $bm->provinsi ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Kota / Kabupaten</td>
                            <td>{{ $bm->kota ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Kecamatan</td>
                            <td>{{ $bm->kecamatan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Desa</td>
                            <td>{{ $bm->desa ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Koordinat & Elevasi --}}
            <div class="mb-4">
                <h5 class="font-weight-bold text-white bg-warning p-2 rounded">
                    <i class="fas fa-crosshairs mr-1"></i> Koordinat & Elevasi
                </h5>
                <table class="table table-bordered table-striped mb-0">
                    <tbody>
                        <tr>
                            <td class="font-weight-bold" style="width: 30%;">UTM X</td>
                            <td>{{ $bm->utm_x ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">UTM Y</td>
                            <td>{{ $bm->utm_y ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Zone</td>
                            <td>{{ $bm->zone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Latitude</td>
                            <td>{{ $bm->latitude ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Longitude</td>
                            <td>{{ $bm->longitude ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Tinggi Orthometrik (Elevasi Z)</td>
                            <td>{{ $bm->tinggi_orthometrik ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Galeri Foto --}}
            <div class="mb-4">
                <h5 class="font-weight-bold text-white bg-dark p-2 rounded">
                    <i class="fas fa-images mr-1"></i> Galeri Foto
                </h5>
                @if($bm->photos->isNotEmpty())
                <div class="row">
                    @foreach($bm->photos as $photo)
                    <div class="col-md-3 col-sm-4 col-6 mb-3">
                        <img src="{{ asset('storage/' . $photo->file_path) }}" alt="Foto BM"
                            class="img-fluid rounded shadow-sm previewable"
                            style="cursor: pointer; height: 180px; width: 100%; object-fit: cover;"
                            onclick="showImageInModal('{{ asset('storage/' . $photo->file_path) }}')">
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center text-muted py-4">
                    <i class="fas fa-camera fa-3x mb-2"></i>
                    <p>Belum ada foto untuk BM ini.</p>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>

{{-- Modal Preview Gambar --}}
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body text-center position-relative">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3"
                    data-bs-dismiss="modal" aria-label="Close"></button>
                <img id="modalImage" src="" class="img-fluid rounded shadow" alt="Preview Gambar">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showImageInModal(src) {
        const modalImg = document.getElementById("modalImage");
        modalImg.src = src;
        const modal = new bootstrap.Modal(document.getElementById('imageModal'));
        modal.show();
    }
</script>
@endpush

@push('styles')
<style>
    .previewable {
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .previewable:hover {
        transform: scale(1.03);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3) !important;
    }

    .modal-backdrop.show {
        opacity: 0.85 !important;
        background-color: #000 !important;
    }
</style>
@endpush