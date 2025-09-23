@extends('layouts.app')
@section('benchmark','active')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<style>
    .badge.bg-purple {
        background-color: #6f42c1 !important;
        color: #fff;
    }

    .badge.bg-sage {
        background-color: #9c956d !important;
        color: #fff;
    }

    .badge.bg-pink {
        background-color: #ff4fa3 !important;
        color: #000;
    }

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
    <div class="card shadow-sm border-0 rounded-4 shadow-sm">
        <div class="card-header bg-primary border-0 py-3 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold text-white">
                    <i class="fas fa-building me-2"></i> Data Infrastruktur
                </h4>
                @can('import infrastruktur')
                <a href="#" class="btn btn-sm btn-success shadow-sm rounded-pill px-3" data-bs-toggle="modal"
                    data-bs-target="#importModal">
                    <i class="fas fa-file-excel me-1"></i> Import Infrastruktur
                </a>
                @endcan
                {{-- <a href="{{ route('aset.create') }}" class="btn btn-sm btn-light shadow-sm rounded-pill px-3">
                    <i class="fas fa-plus-circle me-1 text-primary"></i> Tambah Aset
                </a> --}}
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="aset-table" class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Jenis</th>
                            <th>Kode BMN</th>
                            <th>Koordinat</th>
                            <th>Tahun Bangun</th>
                            <th>Kondisi</th>
                            <th>Tgl. Pembaharuan</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Import Aset -->
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="importForm" action="{{ route('aset.import') }}" method="POST" enctype="multipart/form-data"
            class="modal-content">
            @csrf
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-file-excel"></i> Import Data Infrastruktur</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="jenis_aset" class="form-label">Pilih Jenis Infrastruktur</label>
                    <select name="jenis_aset" id="jenis_aset" class="form-select" required>
                        <option value="">-- Pilih Jenis Infrastruktur --</option>
                        <option value="Embung">Embung</option>
                        <option value="Bendung">Bendung</option>
                        <option value="Bendungan">Bendungan</option>
                        <option value="Pengaman Pantai">Pengaman Pantai</option>
                        <option value="Pengendali Sedimen">Pengendali Sedimen</option>
                        <option value="Pengendali Banjir">Pengendali Banjir</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="file" class="form-label">Upload File Excel</label>
                    <input type="file" name="file" id="file" class="form-control" accept=".xlsx,.xls" required>
                    <small class="text-muted d-block mt-2">
                        File yang diunggah harus sesuai format Excel yang diunduh dari
                        <a href="https://pdsda.sda.pu.go.id/" target="_blank">pdsda.sda.pu.go.id</a>.
                        Pastikan pilih <strong>jenis aset</strong> yang sesuai dengan data di file sebelum mengimpor.
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Import</button>
            </div>
        </form>
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

{{-- modal detail --}}
<div class="modal fade" id="asetModal" tabindex="-1" aria-labelledby="asetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header bg-primary text-white">
                <div class="d-flex flex-column w-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="modal-title mb-0" id="asetModalLabel">Detail Infrastruktur</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="d-flex justify-content-end mt-1">
                        <small class="text-light">
                            <i class="fas fa-clock me-1"></i>
                            <span id="detailUpdatedAt"></span>
                        </small>
                    </div>
                </div>
            </div>

            <!-- Body -->
            <div class="modal-body d-flex" style="gap:20px; max-height:85vh; overflow-y:auto;">
                <!-- Tabel detail aset -->
                <table class="table table-bordered" style="width:60%;">
                    <tr>
                        <th>Nama Infrastruktur</th>
                        <td id="detailNama"></td>
                    </tr>
                    <tr>
                        <th>Jenis Infrastruktur </th>
                        <td id="detailJenis"></td>
                    </tr>
                    <tr>
                        <th>Kode BMN</th>
                        <td id="detailBMN"></td>
                    </tr>
                    <tr>
                        <th>Tahun Bangun</th>
                        <td id="detailTahun"></td>
                    </tr>
                    <tr>
                        <th>Koordinat</th>
                        <td id="detailKoordinat"></td>
                    </tr>
                    <tr>
                        <th>Lokasi Peta</th>
                        <td>
                            <div id="mapDetail" style="height:200px; width:100%; margin-top:4px; border-radius:8px;">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td id="detailAlamat"></td>
                    </tr>
                    <tr>
                        <th>Wilayah Sungai / DAS</th>
                        <td id="detailWilayah"></td>
                    </tr>
                    <tr>
                        <th>Kondisi Bangunan</th>
                        <td id="detailKondisi"></td>
                    </tr>
                    <tr>
                        <th>Status Operasi</th>
                        <td id="detailStatus"></td>
                    </tr>
                    <tr>
                        <th>Kondisi Infrastruktur</th>
                        <td id="detailInfrastruktur"></td>
                    </tr>
                </table>

                <!-- Tabel foto aset -->
                <table class="table table-bordered" style="width:40%;">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center">Foto Aset</th>
                        </tr>
                    </thead>
                    <tbody id="detailFoto">
                        <!-- Foto akan di-load via JS -->
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<!-- Modal Tambah Foto -->
<div class="modal fade" id="modalPhoto" tabindex="-1" aria-labelledby="modalPhotoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="form-photo" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalPhotoLabel">Tambah Foto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">

                    <input type="hidden" name="kode_integrasi" id="photo-kode-integrasi">

                    <!-- input upload -->
                    <div id="photo-wrapper">
                        <!-- baris pertama dengan tombol + -->
                        <div class="input-group mb-2">
                            <input id="photo-input" type="file" name="file[]" class="form-control photo-input"
                                accept="image/*">
                            <button type="button" class="btn btn-outline-success btn-add">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Tombol upload -->
                    <div class="d-flex justify-content-end mb-3">
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fas fa-upload me-1"></i> Upload
                        </button>
                    </div>

                    <hr>

                    <!-- preview foto yang sudah ada -->
                    <div id="photo-list" class="row g-2">
                        <!-- Ajax load preview disini -->
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Preview Foto -->
<div class="modal fade" id="photoPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-dark">
            <div class="modal-body text-center p-0 mt-4">
                <img id="previewImage" src="" class="img-fluid w-100" style="max-height:80vh; border-radius:8px;">
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>



@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    // Datatable init
    $(document).ready(function() {
        var table = $('#aset-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            scrollX: true, // biar ada horizontal scroll
            fixedHeader: true,
            ajax: {
                url: "{{ route('aset.index') }}",
                // data: function(d) {
                //     d.jenis_aset = $('#filter-jenis').val();
                // }
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
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'nama_aset' },
                { data: 'jenis_aset',
                  render: function(data) {
                      let colorClass = 'bg-dark';
                      if (data === 'Embung') colorClass = 'bg-primary';
                      else if (data === 'Bendung') colorClass = 'bg-secondary';
                      else if (data === 'Bendungan') colorClass = 'bg-info';
                      else if (data === 'Pengaman Pantai') colorClass = 'bg-purple';
                      else if (data === 'Pengendali Sedimen') colorClass = 'bg-sage';
                      else if (data === 'Pengendali Banjir') colorClass = 'bg-pink';
                      return `<span class="badge ${colorClass}">${data}</span>`;
                  }
                },
                { data: 'kode_bmn' },
                { data: 'koordinat' },
                { data: 'tahun_mulai_bangunan' },
                { data: 'kondisi_infrastruktur',
                  render: function(data) {
                      if (data === 'Baik / Beroperasi') return `<span class="badge bg-success">${data}</span>`;
                      if (data === 'Rusak Ringan') return `<span class="badge bg-warning">${data}</span>`;
                      if (data === 'Rusak Berat') return `<span class="badge bg-danger">${data}</span>`;
                      if (data === 'Hilang') return `<span class="badge bg-dark text-white">${data}</span>`;
                      else {
                          return `<div class="text-center">-</div>`;
                      }
                      return data;
                  }
                },
               {
            data: 'updated_at',
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

        // $('#filter-jenis').change(function() {
        //     table.ajax.reload();
        // });
    });

    // Tampilkan overlay saat import
    document.getElementById('importForm').addEventListener('submit', function() {
        let modal = bootstrap.Modal.getInstance(document.getElementById('importModal'));
        modal.hide();
        document.getElementById('loading-overlay').style.display = 'flex';
    });
</script>
<script>
    // Saat klik tombol "lihat detail"
   $(document).on('click', '.show-aset', function () {
        function toTitleCase(str) {
            if (!str) return '';
            return str.replace(/\w\S*/g, function(txt) {
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        });
        }


        let id = $(this).data('id');
        let url = "{{ route('aset.show', ':id') }}".replace(':id', id);

        $.get(url, function (data) {     
            console.log(data);
                   
            $('#detailNama').text(toTitleCase(data.nama_aset));
            $('#detailJenis').text(toTitleCase(data.jenis_aset));
            $('#detailBMN').text(data.kode_bmn);
            $('#detailTahun').text(`${data.tahun_mulai_bangunan} - ${data.tahun_selesai_bangunan}`);
            $('#detailKoordinat').text(data.lat + ', ' + data.long);
            $('#detailAlamat').text(
            toTitleCase(data.province) + ', ' +
            toTitleCase(data.city) + ', ' +
            toTitleCase(data.district) + ', ' +
            toTitleCase(data.village)
            );
            $('#detailWilayah').text(toTitleCase(data.wilayah_sungai) + ' - ' + toTitleCase(data.das));
            $('#detailKondisi').text(toTitleCase(data.kondisi_bangunan));
            $('#detailStatus').text(toTitleCase(data.status_operasi));
            $('#detailInfrastruktur').text(toTitleCase(data.kondisi_infrastruktur));

            function formatDate(dateStr) {
            if (!dateStr) return '-';
            const d = new Date(dateStr);
            const day = ('0' + d.getDate()).slice(-2);
            const month = ('0' + (d.getMonth() + 1)).slice(-2);
            const year = d.getFullYear();
            const hours = ('0' + d.getHours()).slice(-2);
            const minutes = ('0' + d.getMinutes()).slice(-2);
            const seconds = ('0' + d.getSeconds()).slice(-2);
            return `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
            }

            $('#detailUpdatedAt').text("Terakhir diperbarui: " + formatDate(data.updated_at));

            // Reset foto
            let detailFoto = $('#detailFoto');
            detailFoto.empty();
            
            if (data.photos && data.photos.length > 0) {
                data.photos.forEach(photo => {
                    detailFoto.append(`
                    <tr>
                        <td class="text-center">
                            <img src="/storage/${photo.path}" class="img-thumbnail photo-click"
                                style="max-width:220px; height:auto; cursor:pointer;">
                        </td>
                    </tr>
                    `);
                });
            } else {
            detailFoto.append(`
                <tr>
                    <td class="text-center text-muted">Tidak ada foto</td>
                </tr>
                `);
            }

            // simpan data ke modal
            $('#asetModal')
                .data('lat', data.lat)
                .data('long', data.long)
                .data('nama', data.nama_aset)
                .modal('show');
        });
    });

    // Event klik foto untuk preview
    $(document).on('click', '.photo-click', function () {
        let src = $(this).attr('src');
        $('#previewImage').attr('src', src);
        $('#photoPreviewModal').modal('show');
    });

    $('#asetModal').on('shown.bs.modal', function () {
        let modalBody = $(this).find('.modal-body');
        let lat = $(this).data('lat');
        let long = $(this).data('long');
        let nama = $(this).data('nama');

        if (!lat || !long) return;

        // hapus map lama jika ada
        if (window.mapDetail) {
            window.mapDetail.remove();
            window.mapDetail = null;
        }

        // hapus div mapDetail lama jika ada di td
        modalBody.find('tr:has(th:contains("Lokasi Peta")) td #mapDetail').remove();

        // Buat div map baru
        let $mapDiv = $('<div id="mapDetail" style="height:200px; width:100%; margin-top:4px; border-radius:8px;"></div>');
        modalBody.find('tr:has(th:contains("Lokasi Peta")) td').append($mapDiv);

        // Inisialisasi map
        window.mapDetail = L.map('mapDetail').setView([lat, long], 11);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19
        }).addTo(window.mapDetail);

        L.marker([lat, long]).addTo(window.mapDetail)
            .bindPopup(nama)
            .openPopup();

        // Paksa refresh ukuran
        setTimeout(() => {
            window.mapDetail.invalidateSize();
        }, 200);
    });
</script>

<script>
    function updateAddButton() {
        let existingCount = $('#photo-list').data('count') || 0; // foto lama
        
        // hitung berapa file yang sudah dipilih di input baru
        let inputCount = 0;
        $('#photo-wrapper .photo-input').each(function () {
            if (this.files && this.files.length > 0) {
                inputCount += this.files.length;
            }
        });
        
        let total = existingCount + inputCount;
        
        if (total >= 4) {
        // disable tombol + dan input baru
        $('.btn-add').prop('disabled', true);
            $('#photo-wrapper .photo-input').prop('disabled', true);
        } else {
            $('.btn-add').prop('disabled', false);
        $('#photo-wrapper .photo-input').prop('disabled', false);
        }
        
        toggleUploadButton(total);
    }

    // Tambah input baru
    $(document).on('click', '.btn-add', function () {
        let existingCount = $('#photo-list').data('count') || 0; // jumlah foto lama dari DB

        // hitung jumlah input file baru yang sudah ada
        let inputCount = $('#photo-wrapper .photo-input').length;

        let total = existingCount + inputCount;

        if (total >= 4) {
            alert("Maksimal hanya 4 foto per aset!");
            return; // cegah tambah input
        }

        // kalau masih < 4, baru tambahkan input
        let newInput = `
            <div class="input-group mb-2">
                <input type="file" name="file[]" class="form-control photo-input" accept="image/*">
                <button type="button" class="btn btn-outline-danger btn-remove">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        $('#photo-wrapper').append(newInput);

        updateAddButton();
    });

    // Hapus input file baru
    $(document).on('click', '.btn-remove', function () {
        $(this).closest('.input-group').remove();
        updateAddButton();
    });


    // Hapus foto lama (pakai Ajax)
    $(document).on('click', '.btn-delete-photo', function () {
        let photoId = $(this).data('id');
        let url = "{{ route('photos.destroy', ':id') }}".replace(':id', photoId);

        if (confirm("Yakin hapus foto ini?")) {
        $.ajax({
            url: url,
            type: "DELETE",
            data: { _token: "{{ csrf_token() }}" },
            success: function () {
                $(`#photo-item-${photoId}`).remove();

                // Kurangi count lalu toggle tombol
                let count = ($('#photo-list').data('count') || 1) - 1;
                $('#photo-list').data('count', count);
                toggleUploadButton(count);
            },
            error: function (err) {
                alert("Gagal hapus foto!");
                console.log(err.responseText);
            }
         });
        }
    });


    // Ajax Submit upload foto baru
    $('#form-photo').on('submit', function (e) {
        e.preventDefault();

        let existingCount = $('#photo-list').data('count') || 0;

        // hitung semua file yang dipilih di semua input
        let newFiles = 0;
            $('.photo-input').each(function () {
        if (this.files && this.files.length > 0) {
            newFiles += this.files.length;
        }
        });

        let total = existingCount + newFiles;

        if (total > 4) {
            alert("Maksimal hanya 4 foto per aset!");
        return; // stop submit
        }

        let formData = new FormData();

        // append kode integrasi
        formData.append('kode_integrasi', $('#photo-kode-integrasi').val());

        // append token
        let token = $('meta[name="csrf-token"]').attr('content');
        formData.append('_token', token);

        // append semua file
        $('.photo-input').each(function () {
        if (this.files && this.files.length > 0) {
            for (let i = 0; i < this.files.length; i++) { formData.append('file[]', this.files[i]); } } }); 
            $.ajax({
                url: "{{ route('photos.store') }}" , 
                method: "POST" , 
                data: formData, 
                contentType: false, 
                processData: false,
                success: function (res) { 
                    alert('Foto berhasil diupload!'); $('#modalPhoto').modal('hide');
                    $('#form-photo')[0].reset(); $('#photo-wrapper').html(` <div class="input-group mb-2">
                        <input type="file" name="file[]" class="form-control photo-input" accept="image/*">
                        <button type="button" class="btn btn-outline-success btn-add">
                            <i class="fas fa-plus"></i>
                        </button>
                        </div>
                    `);
                    loadPhotos($('#photo-kode-integrasi').val());
                },
           error: function (err) {
            let msg = 'Gagal upload foto!\n';
            
            // kalau error dari Laravel Validator
            if (err.responseJSON && err.responseJSON.errors) {
                $.each(err.responseJSON.errors, function (key, value) {
                msg += `- ${value}\n`;
            });
            } else if (err.responseJSON && err.responseJSON.message) {
                msg += err.responseJSON.message;
            } else {
                msg += 'Terjadi kesalahan tak terduga.';
            }
            
            alert(msg);
            console.error(err);
            }
        });
    });

    // Saat klik tombol "Tambah Foto" di tabel
    $(document).on('click', '.btn-tambah-foto', function () {
        let kodeIntegrasi = $(this).data('id');
        $('#photo-kode-integrasi').val(kodeIntegrasi);
        $('#modalPhoto').modal('show');
        loadPhotos(kodeIntegrasi);
    });

    // Fungsi untuk enable/disable tombol upload
    function toggleUploadButton(count) {
        if (count >= 4) {
            $('#form-photo button[type="submit"]').prop('disabled', true);
        } else {
            $('#form-photo button[type="submit"]').prop('disabled', false);
        }
    }


    // Fungsi load foto lama
    function loadPhotos(kodeIntegrasi) {
        // Tampilkan spinner dulu
        $('#photo-list').html(`
            <div class="d-flex justify-content-center align-items-center w-100 py-5">
                <div class="spinner-border text-dark" role="status">
                    <span class="visually-hidden"></span>
                </div>
            </div>
        `);
        
        $.get("{{ route('aset.photos', ':kode') }}".replace(':kode', kodeIntegrasi), function (res) {
            let html = "";
            let count = res.length; // jumlah foto dari DB
            
            if (count > 0) {
                res.forEach(function (photo, index) {
                html += `
                <div id="photo-item-${photo.id}" class="col-md-6 col-sm-6 col-6 mb-3">
                    <div class="card shadow-sm position-relative h-100">
                        <img src="${photo.url}" class="card-img-top" style="height:150px; object-fit:cover; border-radius:8px;">
                        <button type="button"
                            class="btn btn-sm btn-danger btn-delete-photo position-absolute top-0 end-0 m-1 rounded-circle"
                            data-id="${photo.id}" title="Hapus foto">
                            <i class="fas fa-times"></i>
                        </button>
                        <div class="card-body p-2 text-center">
                            <small class="text-muted">Foto ${index + 1}</small>
                        </div>
                    </div>
                </div>
                `;
            });
            } else {
                html = `
                    <div class="col-12 text-center text-muted">
                        Belum ada foto
                    </div>
                `;
            }
        
            // Simpan jumlah foto
            $('#photo-list').data('count', count);
            $('#photo-list').html(`<div class="row justify-content-center">${html}</div>`);
            
            // Cek & toggle tombol upload
            toggleUploadButton(count);
            updateAddButton();
        });
    }
</script>
@endpush