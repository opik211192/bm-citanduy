@extends('layouts.app')
@section('benchmark','active')

@section('content')
<div class="col">
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h4 class="font-weight-bold mb-0">Data Bench Mark (BM)</h4>

            <button class="btn btn-success" style="margin-left: auto;" data-bs-toggle="modal"
                data-bs-target="#importModal">
                <i class="fas fa-file-excel mr-1"></i> Import Excel
            </button>
        </div>
        <div class="card-body">

            <table id="bm-table" class="table table-hover table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode BM</th>
                        <th>Nama Pekerjaan</th>
                        <th>UTM X</th>
                        <th>UTM Y</th>
                        <th>Elevasi (Z)</th>
                        <th>Keterangan</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $bm)
                    <tr>
                        <td></td>
                        <td>{{ $bm->kode_bm }}</td>
                        <td>{{ $bm->nama_pekerjaan }}</td>
                        <td>{{ $bm->utm_x }}</td>
                        <td>{{ $bm->utm_y }}</td>
                        <td>{{ $bm->tinggi_orthometrik }}</td>
                        <td>{{ $bm->keterangan }}</td>
                        <td>{{ $bm->latitude }}</td>
                        <td>{{ $bm->longitude }}</td>
                        <td>
                            <a href="{{ route('bm.show', $bm->id) }}" class="btn btn-sm btn-info" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="btn btn-sm btn-warning btn-edit" data-id="{{ $bm->id }}"
                                data-kode_bm="{{ $bm->kode_bm }}" data-nama_pekerjaan="{{ $bm->nama_pekerjaan }}"
                                data-provinsi="{{ $bm->provinsi }}" data-kota="{{ $bm->kota }}"
                                data-kecamatan="{{ $bm->kecamatan }}" data-desa="{{ $bm->desa }}"
                                data-utm_x="{{ $bm->utm_x }}" data-utm_y="{{ $bm->utm_y }}"
                                data-tinggi_orthometrik="{{ $bm->tinggi_orthometrik }}"
                                data-keterangan="{{ $bm->keterangan }}" data-nfc_id="{{ $bm->nfc_id }}"
                                data-latitude="{{ $bm->latitude }}" data-longitude="{{ $bm->longitude }}" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Import Excel --}}
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-file-excel mr-1"></i> Import Data BM dari Excel
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                {{-- STEP 1: Upload File --}}
                <div id="step1">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-1"></i>
                        <strong>Langkah 1:</strong> Upload file Excel yang berisi data BM.
                        File bisa memiliki banyak sheet — setiap sheet akan menjadi satu nama pekerjaan.
                    </div>

                    <form id="formPreview" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih File Excel</label>
                            <input type="file" name="file" id="fileInput" accept=".xlsx,.xls" class="form-control"
                                required>
                        </div>
                        <button type="submit" class="btn btn-primary" id="btnPreview">
                            <i class="fas fa-search mr-1"></i> Baca Sheet
                        </button>
                    </form>
                </div>

                {{-- STEP 2: Pilih Sheet --}}
                <div id="step2" style="display: none;">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle mr-1"></i>
                        <strong>Langkah 2:</strong> Pilih sheet yang ingin diimport.
                        Nama sheet akan otomatis menjadi <strong>Nama Pekerjaan</strong>.
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">File: <span id="fileName" class="text-primary"></span></label>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label fw-bold mb-0">Daftar Sheet (<span id="sheetCount">0</span> sheet
                                ditemukan)</label>
                            <div>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="btnSelectAll">
                                    <i class="fas fa-check-double"></i> Pilih Semua
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="btnDeselectAll">
                                    <i class="fas fa-times"></i> Batal Semua
                                </button>
                            </div>
                        </div>

                        <div id="sheetList" class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                            {{-- Sheet checkboxes will be inserted here --}}
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" id="btnBack">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </button>
                        <button type="button" class="btn btn-success" id="btnImport" disabled>
                            <i class="fas fa-upload mr-1"></i> Import Sheet Terpilih
                        </button>
                    </div>
                </div>

                {{-- Loading Overlay --}}
                <div id="loadingOverlay" style="display: none;">
                    <div class="text-center py-5">
                        <div class="spinner-border text-success" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 fw-bold text-muted" id="loadingText">Memproses...</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

{{-- Modal Edit BM --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-warning">
                <h5 class="modal-title">
                    <i class="fas fa-edit mr-1"></i> Edit Data BM — <span id="editKodeBmTitle"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="formEdit">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editId" name="id">

                    {{-- Data Utama --}}
                    <h6 class="fw-bold text-primary mb-2"><i class="fas fa-database mr-1"></i> Data Utama</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Kode BM</label>
                            <input type="text" class="form-control" id="editKodeBm" name="kode_bm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Pekerjaan</label>
                            <input type="text" class="form-control" id="editNamaPekerjaan" name="nama_pekerjaan">
                        </div>
                    </div>

                    <hr>

                    {{-- Lokasi --}}
                    <h6 class="fw-bold text-success mb-2"><i class="fas fa-map-marked-alt mr-1"></i> Lokasi</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Provinsi</label>
                            <input type="text" class="form-control" id="editProvinsi" name="provinsi">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Kota / Kabupaten</label>
                            <input type="text" class="form-control" id="editKota" name="kota">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Kecamatan</label>
                            <input type="text" class="form-control" id="editKecamatan" name="kecamatan">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Desa</label>
                            <input type="text" class="form-control" id="editDesa" name="desa">
                        </div>
                    </div>

                    <hr>

                    {{-- Koordinat & Elevasi --}}
                    <h6 class="fw-bold text-warning mb-2"><i class="fas fa-crosshairs mr-1"></i> Koordinat & Elevasi
                    </h6>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">UTM X</label>
                            <input type="text" class="form-control" id="editUtmX" name="utm_x">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">UTM Y</label>
                            <input type="text" class="form-control" id="editUtmY" name="utm_y">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Tinggi Orthometrik</label>
                            <input type="text" class="form-control" id="editTinggiOrthometrik"
                                name="tinggi_orthometrik">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Latitude</label>
                            <input type="text" class="form-control" id="editLatitude" name="latitude">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Longitude</label>
                            <input type="text" class="form-control" id="editLongitude" name="longitude">
                        </div>
                    </div>

                    <hr>

                    {{-- Lainnya --}}
                    <h6 class="fw-bold text-dark mb-2"><i class="fas fa-info-circle mr-1"></i> Lainnya</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">NFC ID</label>
                            <input type="text" class="form-control" id="editNfcId" name="nfc_id"
                                placeholder="Scan atau isi manual">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Keterangan</label>
                            <textarea class="form-control" id="editKeterangan" name="keterangan" rows="2"></textarea>
                        </div>
                    </div>

                </form>

                <hr>

                {{-- Galeri Foto --}}
                <h6 class="fw-bold text-dark mb-2"><i class="fas fa-images mr-1"></i> Foto BM</h6>

                <div id="editPhotoGallery" class="row mb-3">
                    {{-- Foto akan diload via AJAX --}}
                    <div class="text-center text-muted py-3" id="photoLoading">
                        <i class="fas fa-spinner fa-spin"></i> Memuat foto...
                    </div>
                </div>

                {{-- Upload Foto Baru --}}
                <div class="mb-2">
                    <label class="form-label fw-bold"><i class="fas fa-upload mr-1"></i> Upload Foto Baru</label>
                    <input type="file" id="editPhotoInput" class="form-control" accept=".jpg,.jpeg,.png" multiple>
                    <small class="text-muted">Format: JPG/PNG. Bisa pilih beberapa file sekaligus.</small>
                </div>
                <button type="button" class="btn btn-success btn-sm" id="btnUploadPhoto" disabled>
                    <i class="fas fa-cloud-upload-alt mr-1"></i> Upload Foto
                </button>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Batal
                </button>
                <button type="button" class="btn btn-primary" id="btnSaveEdit">
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function(){

    // Initialize DataTable
    let table = $('#bm-table').DataTable({

        responsive: true,

        // Atur posisi kontrol DataTables
        dom:
            '<"row align-items-end mb-3"' +
                '<"col-md-4"l>' +
                '<"col-md-4"<"filter-pekerjaan">>' +
                '<"col-md-4"f>' +
            '>' +
            't' +
            '<"row mt-3"' +
                '<"col-md-6"i>' +
                '<"col-md-6"p>' +
            '>',

        order: [[2, 'asc'], [1, 'asc']],

        columnDefs: [
            {
                targets: 0,
                orderable: false,
                searchable: false
            }
        ],

        drawCallback: function () {
            let api = this.api();

            api.column(0, { page: 'current' }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
        },

        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            paginate: {
                previous: "Sebelumnya",
                next: "Selanjutnya"
            },
            zeroRecords: "Tidak ada data yang cocok"
        }
    });

    // Buat filter Nama Pekerjaan di tengah
    $('.filter-pekerjaan').html(`
        <label for="filterNamaPekerjaan" class="form-label fw-bold mb-1">
           Cari Nama Pekerjaan
        </label>

        <select id="filterNamaPekerjaan" class="form-control">
            <option value="">Semua Nama Pekerjaan</option>
        </select>
    `);

    // ============================================
    // FILTER NAMA PEKERJAAN // ============================================
    // Ambil semua nama pekerjaan dari kolom ke-3
    table.column(2).data().unique().sort().each(function (nama) { 
        if (nama && nama.trim() !== '') {
            $('#filterNamaPekerjaan').append( 
                $('<option>', { 
                    value: nama, text: nama 
                }) 
            ); 
        } 
    });

    // Saat pilihan Nama Pekerjaan berubah
    $('#filterNamaPekerjaan').on('change', function () { 
        let value = $(this).val();
        table .column(2) .search(
            value ? '^' + $.fn.dataTable.util.escapeRegex(value) + '$' : '', true, false) .draw(); 
    });

    // ============================================
    // STEP 1: Preview — Baca nama sheet dari Excel
    // ============================================
    $('#formPreview').submit(function(e){
        e.preventDefault();

        let formData = new FormData(this);

        // Show loading
        $('#step1').hide();
        $('#loadingOverlay').show();
        $('#loadingText').text('Membaca sheet dari file Excel...');

        $.ajax({
            url: "{{ route('bm.preview') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,

            success: function(res){
                $('#loadingOverlay').hide();

                if(res.success && res.sheets.length > 0){
                    // Tampilkan step 2
                    $('#fileName').text($('#fileInput')[0].files[0].name);
                    $('#sheetCount').text(res.sheets.length);

                    // Render checkboxes
                    let html = '';
                    res.sheets.forEach(function(sheet, index){
                        html += `
                            <div class="form-check mb-2 p-2 rounded hover-bg">
                                <input class="form-check-input sheet-checkbox" type="checkbox"
                                       value="${sheet}" id="sheet_${index}">
                                <label class="form-check-label fw-bold" for="sheet_${index}">
                                    <i class="fas fa-file-alt text-success mr-1"></i>
                                    ${sheet}
                                </label>
                            </div>
                        `;
                    });
                    $('#sheetList').html(html);
                    $('#step2').show();
                    updateImportButton();
                } else {
                    alert('Tidak ada sheet yang ditemukan di file Excel.');
                    $('#step1').show();
                }
            },

            error: function(err){
                $('#loadingOverlay').hide();
                $('#step1').show();

                let msg = 'Gagal membaca file Excel!';
                if(err.responseJSON && err.responseJSON.message){
                    msg = err.responseJSON.message;
                }
                alert(msg);
            }
        });
    });

    // ============================================
    // STEP 2: Select sheets & Import
    // ============================================

    // Select All / Deselect All
    $('#btnSelectAll').click(function(){
        $('.sheet-checkbox').prop('checked', true);
        updateImportButton();
    });

    $('#btnDeselectAll').click(function(){
        $('.sheet-checkbox').prop('checked', false);
        updateImportButton();
    });

    // Update import button state when checkboxes change
    $(document).on('change', '.sheet-checkbox', function(){
        updateImportButton();
    });

    function updateImportButton(){
        let checked = $('.sheet-checkbox:checked').length;
        let btn = $('#btnImport');

        if(checked > 0){
            btn.prop('disabled', false);
            btn.html(`<i class="fas fa-upload mr-1"></i> Import ${checked} Sheet`);
        } else {
            btn.prop('disabled', true);
            btn.html(`<i class="fas fa-upload mr-1"></i> Import Sheet Terpilih`);
        }
    }

    // Back to step 1
    $('#btnBack').click(function(){
        $('#step2').hide();
        $('#step1').show();
    });

    // Import
    $('#btnImport').click(function(){
        let selectedSheets = [];
        $('.sheet-checkbox:checked').each(function(){
            selectedSheets.push($(this).val());
        });

        if(selectedSheets.length === 0){
            alert('Pilih minimal 1 sheet!');
            return;
        }

        // Build FormData with file + selected sheets
        let formData = new FormData();
        formData.append('file', $('#fileInput')[0].files[0]);
        formData.append('_token', '{{ csrf_token() }}');
        selectedSheets.forEach(function(sheet){
            formData.append('sheets[]', sheet);
        });

        // Show loading
        $('#step2').hide();
        $('#loadingOverlay').show();
        $('#loadingText').text(`Mengimport ${selectedSheets.length} sheet... Mohon tunggu.`);

        $.ajax({
            url: "{{ route('bm.import') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,

            success: function(res){
                $('#loadingOverlay').hide();
                alert(res.message || 'Import berhasil!');
                $('#importModal').modal('hide');
                location.reload();
            },

            error: function(err){
                $('#loadingOverlay').hide();
                $('#step2').show();

                let msg = 'Gagal import!';
                if(err.responseJSON && err.responseJSON.message){
                    msg = err.responseJSON.message;
                }
                alert(msg);
            }
        });
    });

    // Reset modal saat ditutup
    $('#importModal').on('hidden.bs.modal', function(){
        $('#step1').show();
        $('#step2').hide();
        $('#loadingOverlay').hide();
        $('#formPreview')[0].reset();
        $('#sheetList').html('');
    });

    // ============================================
    // EDIT BM
    // ============================================

    // Klik tombol edit → isi form modal
    $(document).on('click', '.btn-edit', function(){
        let btn = $(this);
        let bmId = btn.data('id');
        $('#editId').val(bmId);
        $('#editKodeBmTitle').text(btn.data('kode_bm'));
        $('#editKodeBm').val(btn.data('kode_bm'));
        $('#editNamaPekerjaan').val(btn.data('nama_pekerjaan'));
        $('#editProvinsi').val(btn.data('provinsi'));
        $('#editKota').val(btn.data('kota'));
        $('#editKecamatan').val(btn.data('kecamatan'));
        $('#editDesa').val(btn.data('desa'));
        $('#editUtmX').val(btn.data('utm_x'));
        $('#editUtmY').val(btn.data('utm_y'));
        $('#editTinggiOrthometrik').val(btn.data('tinggi_orthometrik'));
        $('#editKeterangan').val(btn.data('keterangan'));
        $('#editNfcId').val(btn.data('nfc_id'));
        $('#editLatitude').val(btn.data('latitude'));
        $('#editLongitude').val(btn.data('longitude'));

        // Reset foto
        $('#editPhotoInput').val('');
        $('#btnUploadPhoto').prop('disabled', true);

        // Load foto
        loadPhotos(bmId);

        $('#editModal').modal('show');
    });

    // Load foto dari server
    function loadPhotos(bmId){
        $('#editPhotoGallery').html('<div class="text-center text-muted py-3" id="photoLoading"><i class="fas fa-spinner fa-spin"></i> Memuat foto...</div>');

        $.get(`/bm/${bmId}/photos`, function(photos){
            let html = '';
            if(photos.length > 0){
                photos.forEach(function(photo){
                    html += `
                        <div class="col-md-3 col-sm-4 col-6 mb-3 photo-item" id="photo-${photo.id}">
                            <div class="position-relative">
                                <img src="${photo.url}" class="img-fluid rounded shadow-sm"
                                     style="height: 120px; width: 100%; object-fit: cover;">
                                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 btn-delete-photo"
                                        data-photo-id="${photo.id}" title="Hapus foto"
                                        style="padding: 2px 6px; font-size: 11px;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    `;
                });
            } else {
                html = '<div class="col-12 text-center text-muted py-3"><i class="fas fa-camera fa-2x mb-2"></i><p>Belum ada foto</p></div>';
            }
            $('#editPhotoGallery').html(html);
        }).fail(function(){
            $('#editPhotoGallery').html('<div class="col-12 text-center text-danger py-3">Gagal memuat foto</div>');
        });
    }

    // Enable upload button saat file dipilih
    $('#editPhotoInput').on('change', function(){
        $('#btnUploadPhoto').prop('disabled', this.files.length === 0);
    });

    // Upload foto
    $('#btnUploadPhoto').click(function(){
        let bmId = $('#editId').val();
        let files = $('#editPhotoInput')[0].files;
        if(files.length === 0) return;

        let formData = new FormData();
        formData.append('bm_id', bmId);
        formData.append('_token', '{{ csrf_token() }}');
        for(let i = 0; i < files.length; i++){
            formData.append('file[]', files[i]);
        }

        let btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Mengupload...');

        $.ajax({
            url: "{{ route('bm.photos.store') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res){
                btn.prop('disabled', true).html('<i class="fas fa-cloud-upload-alt mr-1"></i> Upload Foto');
                $('#editPhotoInput').val('');
                loadPhotos(bmId);
                alert(res.message || 'Foto berhasil diupload!');
            },
            error: function(err){
                let msg = 'Gagal upload foto!';
                if(err.responseJSON && err.responseJSON.message){
                    msg = err.responseJSON.message;
                }
                alert(msg);
                btn.prop('disabled', false).html('<i class="fas fa-cloud-upload-alt mr-1"></i> Upload Foto');
            }
        });
    });

    // Hapus foto
    $(document).on('click', '.btn-delete-photo', function(){
        if(!confirm('Yakin ingin menghapus foto ini?')) return;

        let photoId = $(this).data('photo-id');
        let bmId = $('#editId').val();

        $.ajax({
            url: `/bm/photos/${photoId}`,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res){
                $(`#photo-${photoId}`).fadeOut(300, function(){ $(this).remove(); });
            },
            error: function(){
                alert('Gagal menghapus foto!');
            }
        });
    });

    // Simpan edit
    $('#btnSaveEdit').click(function(){
        let id = $('#editId').val();
        let btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...');

        $.ajax({
            url: `/bm/update/${id}`,
            type: 'POST',
            data: $('#formEdit').serialize(),
            success: function(res){
                if(res.success){
                    $('#editModal').modal('hide');
                    alert(res.message || 'Data berhasil diupdate!');
                    location.reload();
                }
            },
            error: function(err){
                let msg = 'Gagal menyimpan!';
                if(err.responseJSON && err.responseJSON.message){
                    msg = err.responseJSON.message;
                }
                alert(msg);
                btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Simpan');
            }
        });
    });

});
</script>
@endpush

@push('styles')
<style>
    .hover-bg:hover {
        background-color: #f0f9f0;
    }

    .form-check-input:checked {
        background-color: #28a745;
        border-color: #28a745;
    }
</style>
@endpush