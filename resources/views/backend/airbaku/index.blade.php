@extends('layouts.app')
@section('airbaku','active')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />
<style>
    #loading-overlay {
        display: none;
        /* awalnya hidden */
        position: fixed;
        z-index: 9999;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }
</style>
@endpush

@section('content')
<div class="col">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-primary border-0 py-3 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold text-white">
                    <i class="fas fa-tint me-2"></i> Data Air Baku
                </h4>
                <a href="#" class="btn btn-sm btn-success shadow-sm rounded-pill px-3" data-bs-toggle="modal"
                    data-bs-target="#importModal">
                    <i class="fas fa-file-excel me-1"></i> Import Air Baku
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="airbaku-table" class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Jenis</th>
                            <th>Kode BMN</th>
                            <th>Koordinat</th>
                            <th>Tahun Pembangunan</th>
                            <th>Status Operasi</th>
                            <th>Status Pekerjaan</th>
                            <th>Updated At</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Import -->
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="importForm" action="{{ route('airbaku.import') }}" method="POST" enctype="multipart/form-data"
            class="modal-content">
            @csrf
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-file-excel"></i> Import Data Air Baku</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="jenis_aset" class="form-label">Pilih Jenis Aset</label>
                    <select name="jenis_aset" id="jenis_aset" class="form-select" required>
                        <option value="">-- Pilih Jenis Aset --</option>
                        <option value="Sumur">Sumur</option>
                        <option value="Mata Air">Mata Air</option>
                        <option value="Intake Sungai">Intake Sungai</option>
                        <option value="PAH/ABSAH">PAH / ABSAH</option>
                        <option value="Tampungan Air Baku">Tampungan Air Baku</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="file" class="form-label">Upload File Excel</label>
                    <input type="file" name="file" id="file" class="form-control" accept=".xlsx,.xls" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Import</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detail Air Baku -->
<div class="modal fade" id="airbakuDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content rounded-4 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-info-circle me-2"></i> Detail Air Baku</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="detailContent">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Informasi Aset</h6>
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <th>Nama Aset</th>
                                        <td id="detail_nama_aset"></td>
                                    </tr>
                                    <tr>
                                        <th>Jenis Aset</th>
                                        <td id="detail_jenis_aset"></td>
                                    </tr>
                                    <tr>
                                        <th>Kode BMN</th>
                                        <td id="detail_kode_bmn"></td>
                                    </tr>
                                    <tr>
                                        <th>Tahun Pembangunan</th>
                                        <td id="detail_tahun"></td>
                                    </tr>
                                    <tr>
                                        <th>Status Operasi</th>
                                        <td id="detail_status_operasi"></td>
                                    </tr>
                                    <tr>
                                        <th>Status Pekerjaan</th>
                                        <td id="detail_status_pekerjaan"></td>
                                    </tr>
                                    <tr>
                                        <th>Wilayah Sungai</th>
                                        <td id="detail_ws"></td>
                                    </tr>
                                    <tr>
                                        <th>DAS</th>
                                        <td id="detail_das"></td>
                                    </tr>
                                    <tr>
                                        <th>Provinsi</th>
                                        <td id="detail_province"></td>
                                    </tr>
                                    <tr>
                                        <th>Kab/Kota</th>
                                        <td id="detail_city"></td>
                                    </tr>
                                    <tr>
                                        <th>Kecamatan</th>
                                        <td id="detail_district"></td>
                                    </tr>
                                    <tr>
                                        <th>Desa</th>
                                        <td id="detail_village"></td>
                                    </tr>
                                    <tr>
                                        <th>Sungai</th>
                                        <td id="detail_sungai"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Peta Lokasi</h6>
                            <div id="mapDetail" style="height:300px;" class="rounded border"></div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6 class="fw-bold">Galeri Foto</h6>
                        <div class="row" id="detail_photos"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Overlay Loading -->
<div id="loading-overlay">
    <p class="fw-bold text-primary mb-3">Sedang mengimpor data, mohon tunggu...</p>
    <div class="progress w-50">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar"
            style="width: 100%">
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
    $('#airbaku-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        scrollX: true, // biar ada horizontal scroll
        fixedHeader: true,
        ajax: "{{ route('airbaku.index') }}",
        order: [[0, 'desc']],
        language: {
            lengthMenu: "Tampilkan _MENU_ data",
            zeroRecords: "Tidak ada data ditemukan",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data tersedia",
            search: "Cari:",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "›",
                previous: "‹"
            },
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'nama_aset' },
            { data: 'jenis_aset' },
            { data: 'kode_bmn' },
            { data: 'koordinat' , orderable: false, searchable: false },
            { data: 'tahun_pembangunan' },
            { data: 'status_operasi' },
            { data: 'status_pekerjaan' },
            { data: 'updated_at', 
                render: function(data) {
                    if (!data) return '';
                        const d = new Date(data);
                        
                        const day = ('0' + d.getDate()).slice(-2);
                        const month = ('0' + (d.getMonth() + 1)).slice(-2);
                        const year = d.getFullYear();
                        
                        const hours = ('0' + d.getHours()).slice(-2);
                        const minutes = ('0' + d.getMinutes()).slice(-2);
                        const seconds = ('0' + d.getSeconds()).slice(-2);
                        
                        return `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
                    } 
                },
            { data: 'options', orderable: false, searchable: false },
        ]
    });
});

// Tampilkan overlay saat import
 document.getElementById('importForm').addEventListener('submit', function() {
    let modal = bootstrap.Modal.getInstance(document.getElementById('importModal'));
    modal.hide();
    document.getElementById('loading-overlay').style.display = 'flex';
});
</script>

<script>
    // Modal Detail
    $(document).on('click', '.show-airbaku', function () {
        let id = $(this).data('id');
        $.ajax({
            url: "{{ url('api/data/airbaku') }}/" + id,
            type: "GET",
            success: function (res) {
                // isi data ke modal
                $('#detail_nama_aset').text(res.nama_aset);
                $('#detail_jenis_aset').text(res.jenis_aset);
                $('#detail_kode_bmn').text(res.kode_bmn);
                $('#detail_tahun').text(res.tahun_pembangunan);
                $('#detail_status_operasi').text(res.status_operasi);
                $('#detail_status_pekerjaan').text(res.status_pekerjaan);
                $('#detail_ws').text(res.wilayah_sungai);
                $('#detail_das').text(res.das);
                $('#detail_province').text(res.province);
                $('#detail_city').text(res.city);
                $('#detail_district').text(res.district);
                $('#detail_village').text(res.village);
                $('#detail_sungai').text(res.sungai);

                // tampilkan foto
                let photosHtml = '';
                if (res.photos && res.photos.length > 0) {
                    res.photos.forEach(p => {
                        photosHtml += `
                            <div class="col-md-3 col-6 mb-3">
                                <div class="card shadow-sm">
                                    <img src="${p.url}" class="card-img-top" style="height:150px;object-fit:cover;">
                                </div>
                            </div>`;
                    });
                } else {
                    photosHtml = '<p class="text-muted">Belum ada foto</p>';
                }
                $('#detail_photos').html(photosHtml);

                // buka modal
                $('#airbakuDetailModal').modal('show');

                // inisialisasi peta Leaflet
                setTimeout(function () {
                    let map = L.map('mapDetail').setView([res.lat, res.long], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap'
                    }).addTo(map);
                    L.marker([res.lat, res.long]).addTo(map)
                        .bindPopup(res.nama_aset).openPopup();
                }, 400);
            },
            error: function () {
                alert("Gagal mengambil detail data!");
            }
        });
    });
</script>
@endpush