@extends('layouts.app')
@section('benchmark','active')

@push('styles')
<style>
    .badge.bg-purple {
        background-color: #6f42c1 !important;
        /* ungu */
        color: #fff;
    }

    .badge.bg-sage {
        background-color: #9c956d !important;
        /* hijau sage pastel */
        color: #fff;
        /* teks hitam supaya kontras */
    }

    .badge.bg-pink {
        background-color: #ff4fa3 !important;
        /* amber / emas cerah */
        color: #000;
    }
</style>
@endpush
@section('content')
<div class="col">
    <div class="card shadow-sm border-0 rounded-4 shadow-sm">
        <div class="card-header bg-primary  border-0 py-3 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold text-white">
                    <i class="fas fa-database me-2"></i> Data Aset
                </h4>
                <a href="{{ route('aset.create') }}" class="btn btn-sm btn-light shadow-sm rounded-pill px-3">
                    <i class="fas fa-plus-circle me-1 text-primary"></i> Tambah Aset
                </a>
            </div>
        </div>
        <div class="card-body">
            {{-- <div class="row">
                <div class="col">
                    <div class="d-flex justify-content-center">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <select id="filter-jenis" class="form-select">
                                    <option value="">-- Semua Jenis Aset --</option>
                                    <option value="Embung">Embung</option>
                                    <option value="Bendung">Bendung</option>
                                    <option value="Bendungan">Bendungan</option>
                                    <option value="Pengaman Pantai">Pengaman Pantai</option>
                                    <option value="Pengendali Sedimen">Pengendali Sedimen</option>
                                    <option value="Pengendali Banjir">Pengendali Banjir</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
            <table id="aset-table" class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Aset</th>
                        <th>Jenis Aset</th>
                        {{-- <th>No. Registrasi</th> --}}
                        <th>Kode BMN</th>
                        <th>Koordinat</th>
                        <th>Tahun Bangun</th>
                        <th>Kondisi</th>
                        <th>Keterangan</th>
                        <th>Options</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Modal Show Aset -->
<div class="modal fade" id="showAsetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-info-circle"></i> Detail Aset</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <table class="table table-bordered align-middle">
                        <tbody>
                            <tr>
                                <th width="25%">Nama Aset</th>
                                <td id="detail-nama"></td>
                            </tr>
                            <tr>
                                <th>Jenis Aset</th>
                                <td id="detail-jenis"></td>
                            </tr>
                            <tr>
                                <th>No Integrasi</th>
                                <td id="detail-no-reg"></td>
                            </tr>
                            <tr>
                                <th>Kode BMN</th>
                                <td id="detail-kode"></td>
                            </tr>
                            <tr>
                                <th>Koordinat</th>
                                <td id="detail-koordinat"></td>
                            </tr>
                            <tr>
                                <th>Tahun Bangunan</th>
                                <td>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong class="mr-2">Mulai:</strong> <span id="detail-tahun-mulai"></span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong class="mr-2">Selesai:</strong> <span
                                                id="detail-tahun-selesai"></span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>Kondisi</th>
                                <td id="detail-kondisi"></td>
                            </tr>
                            <tr>
                                <th>Keterangan</th>
                                <td id="detail-keterangan"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h6 class="fw-bold mb-3">Foto Aset</h6>
                <div id="detail-foto" class="row row-cols-2 row-cols-md-4 g-3"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Foto Besar -->
<div class="modal fade" id="fotoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0 shadow-none">
            <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3"
                data-bs-dismiss="modal"></button>
            <img id="fotoModalImg" src="" class="img-fluid rounded shadow">
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).on('click', '.show-aset', function() {
    let id = $(this).data('id');
    $.ajax({
       url: '{{ route('aset.show', ':id') }}'.replace(':id', id),
        type: 'GET',
        success: function(res) {  
            console.log(res);
            
            $('#detail-nama').text(res.nama_aset);
            $('#detail-jenis').text(res.jenis_aset);
            $('#detail-no-reg').text(res.no_registrasi);
            $('#detail-kode').text(res.kode_bmn);
            $('#detail-alamat').text(res.province + ', ' + res.city + ', ' + res.district + ', ' + res.village);
            $('#detail-koordinat').text(res.lat + ', ' + res.long);
            $('#detail-tahun-mulai').text(res.tahun_mulai_bangunan);
            $('#detail-tahun-selesai').text(res.tahun_selesai_bangunan);
            $('#detail-kondisi').text(res.kondisi_bangunan);
            $('#detail-keterangan').text(res.keterangan);

            // Foto
            let fotoHtml = '';
            
            if (res.photos && res.photos.length > 0) {
                res.photos.forEach(function(foto) {
                    fotoHtml += `
                        <div class="col-md-3">
                            <img src="/storage/${foto.path}" 
                                class="img-fluid rounded border clickable-foto" 
                                style="cursor:pointer"
                                data-src="/storage/${foto.path}">
                        </div>
                    `;
                });
            } else {
                fotoHtml = '<p class="text-muted">Tidak ada foto</p>';
            }
            $('#detail-foto').html(fotoHtml);

            $('#showAsetModal').modal('show');
        }
    });
});

</script>

<script>
    // Event klik untuk menampilkan foto besar
    $(document).on('click', '.clickable-foto', function() {
    let src = $(this).data('src');
    $('#fotoModalImg').attr('src', src);
    $('#fotoModal').modal('show');
    });
</script>

<script>
    $(document).ready(function() {
       var table = $('#aset-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('aset.index') }}",
                data : function (d) {
                    d.jenis_aset = $('#filter-jenis').val();
                }
            },
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
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'nama_aset', name: 'nama_aset' },
                { data: 'jenis_aset', name: 'jenis_aset', 
                    render: function(data, type, row) {
                        let colorClass = 'bg-dark'; // default
                        
                        if (data === 'Embung') {
                            colorClass = 'bg-primary'; // biru
                        } else if (data === 'Bendung') {
                            colorClass = 'bg-secondary'; // abu
                        } else if (data === 'Bendungan') {
                            colorClass = 'bg-info'; // biru muda
                        } else if (data === 'Pengaman Pantai') {
                            colorClass = 'bg-purple'; // custom
                        } else if (data === 'Pengendali Sedimen') {
                            colorClass = 'bg-sage'; // custom
                        } else if (data === 'Pengendali Banjir') {
                            colorClass = 'bg-pink'; // custom
                        }
                    
                    return `<span class="badge ${colorClass}">${data}</span>`;
                    }
                 },
                // { data: 'no_registrasi', name: 'no_registrasi' },
                { data: 'kode_bmn', name: 'kode_bmn' },
                {data:  'koordinat', name: 'koordinat'},
                {data:  'tahun_mulai_bangunan', name: 'tahun_mulai_bangunan'},
                { data: 'kondisi_bangunan', name: 'kondisi_bangunan', 
                    render: function(data, type, row) {
                        if (data === 'Baik') {
                            return `<span class="badge bg-success">${data}</span>`;
                        } else if (data === 'Rusak Ringan') {
                            return `<span class="badge bg-warning">${data}</span>`;
                        } else if (data === 'Rusak Berat') {
                            return `<span class="badge bg-danger">${data}</span>`;
                        } else if (data === 'Hilang') {
                            return `<span class="badge bg-dark text-white">${data}</span>`;
                        } else {
                            return data;
                        }
                    }
                 },
                { data: 'keterangan', name: 'keterangan' },
                { data: 'options', name: 'options', orderable: false, searchable: false },
            ]
        });

        // event ganti filter
        $('#filter-jenis').change(function() {
            table.ajax.reload();
        });
    });
</script>
@endpush