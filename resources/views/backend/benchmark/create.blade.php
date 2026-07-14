@extends('layouts.app')

@section('benchmark-create', 'active')

@push('styles')
<style>
    #foto-preview,
    #sketsa-preview {
        max-width: 100px;
        max-height: 100px;
    }
</style>
@endpush

@section('content')
<div class="col-md-10">
    <div class="card">
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0">Tambah Data Benchmark</h4>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('benchmark.store') }}" enctype="multipart/form-data" class="row g-3">
                @csrf

                {{-- ================== DATA UTAMA ================== --}}
                <div class="col-md-6">
                    <label class="form-label">Kode BM</label>
                    <input type="text" name="kode_bm" class="form-control @error('kode_bm') is-invalid @enderror"
                        value="{{ old('kode_bm') }}">
                    @error('kode_bm')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">No Registrasi</label>
                    <input type="text" name="no_registrasi"
                        class="form-control @error('no_registrasi') is-invalid @enderror"
                        value="{{ old('no_registrasi') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nama Pekerjaan</label>
                    <input type="text" name="nama_pekerjaan"
                        class="form-control @error('nama_pekerjaan') is-invalid @enderror"
                        value="{{ old('nama_pekerjaan') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Jenis Pekerjaan</label>
                    <select name="jenis_pekerjaan" class="form-control">
                        <option value="">Pilih</option>
                        <option value="embung">Embung</option>
                        <option value="bendungan">Bendungan</option>
                        <option value="bendung">Bendung</option>
                    </select>
                </div>

                {{-- ================== WILAYAH ================== --}}
                <div class="col-md-3">
                    <label class="form-label">Provinsi</label>
                    <select id="province_id" name="province_id" class="form-control">
                        <option value="">Pilih</option>
                        @foreach ($provinces as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Kota</label>
                    <select id="city_id" name="city_id" class="form-control"></select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Kecamatan</label>
                    <select id="district_id" name="district_id" class="form-control"></select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Desa</label>
                    <select id="village_id" name="village_id" class="form-control"></select>
                </div>

                {{-- ================== KOORDINAT ================== --}}
                <div class="col-md-4">
                    <label class="form-label">UTM X</label>
                    <input type="text" id="utm_x" name="utm_x" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">UTM Y</label>
                    <input type="text" id="utm_y" name="utm_y" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Zone</label>
                    <select id="zone" name="zone" class="form-control">
                        <option value="">Pilih</option>
                        @foreach ([48,49,50,51,52] as $z)
                        <option value="{{ $z }}">{{ $z }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Latitude</label>
                    <input type="text" id="lat" name="lat" class="form-control" readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Longitude</label>
                    <input type="text" id="long" name="long" class="form-control" readonly>
                </div>

                {{-- ================== LAINNYA ================== --}}
                <div class="col-md-6">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control"></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Konsultan</label>
                    <input type="text" name="konsultan" class="form-control">
                </div>

                {{-- ================== FILE ================== --}}
                <div class="col-md-6">
                    <label class="form-label">Sketsa</label>
                    <input type="file" id="sketsa" class="form-control">
                    <img id="sketsa-preview" class="img-thumbnail mt-2 d-none">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Foto</label>
                    <input type="file" id="foto" class="form-control">
                    <img id="foto-preview" class="img-thumbnail mt-2 d-none">
                </div>

                <div class="col-md-12 text-end mt-3">
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function onChangeSelect(url, id, name) {
            // send ajax request to get the cities of the selected province and append to the select tag
            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    id: id
                },
                success: function (data) {
                    $('#' + name).empty();
                    $('#' + name).append('<option>Pilih Salah Satu</option>');

                    $.each(data, function (key, value) {
                        $('#' + name).append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });
        }
        $(function () {
            $('#province_id').on('change', function () {
                onChangeSelect('{{ route("cities") }}', $(this).val(), 'city_id');
            });
            $('#city_id').on('change', function () {
                onChangeSelect('{{ route("districts") }}', $(this).val(), 'district_id');
            })
            $('#district_id').on('change', function () {
                onChangeSelect('{{ route("villages") }}', $(this).val(), 'village_id');
            })
        });
</script>

<!-- Tambahkan berkas UTMLatLng.js -->

<script>
    // Buat objek UTMLatLng
    const utm = new UTMLatLng();

    // Fungsi untuk mengonversi UTM ke Latitude Longitude
    function convertUTMToLatLng(utmZone, utmHemisphere, utmEast, utmNorth) {
        // Memanggil fungsi convertUTMToLatLng dari objek global utm
        return utm.convertUtmToLatLng(utmEast, utmNorth, utmZone, utmHemisphere);
    }

    // Menggunakan fungsi convertUTMToLatLng untuk mengisi nilai latitude dan longitude
    $(document).ready(function() {
        $('#utm_x, #utm_y, #zone').on('change', function() {
            var utmX = parseFloat($('#utm_x').val());
            var utmY = parseFloat($('#utm_y').val());
            var zone = $('#zone').val();
            
            // Menggunakan zona 49S dan Hemisphere M sebagai contoh
            var utmZone = zone;
            var utmHemisphere = 'M';
        
            var latLng = convertUTMToLatLng(utmZone, utmHemisphere, utmX, utmY);
            $('#lat').val(latLng.lat);
            $('#long').val(latLng.lng);
        });
    });
</script>
<script>
    // Mendapatkan elemen input
    var utmXInput = document.getElementById('utm_x');
    var utmYInput = document.getElementById('utm_y');

    // Menambahkan event listener untuk membatasi input hanya menerima angka, koma, dan titik
    utmXInput.addEventListener('input', function () {
        // Mengubah koma menjadi titik
        this.value = this.value.replace(',', '.');
        // Menghapus semua karakter kecuali angka dan titik
        this.value = this.value.replace(/[^\d.]/g, '');
    });

    utmYInput.addEventListener('input', function () {
        // Mengubah koma menjadi titik
        this.value = this.value.replace(',', '.');
        // Menghapus semua karakter kecuali angka dan titik
        this.value = this.value.replace(/[^\d.]/g, '');
    });
</script>

<script>
    // Tambahkan event listener untuk input file sketsa
$('#sketsa').on('change', function() {
    var input = this;
    var reader = new FileReader();

    // Tampilkan animasi loading saat proses pengunggahan
    $('#sketsa-upload-loading').show();
    
    reader.onload = function(e) {
        // Sembunyikan animasi loading dan tampilkan gambar yang dipilih
        $('#sketsa-upload-loading').hide();
        $('#sketsa-preview').attr('src', e.target.result).show();
    }
    
    reader.readAsDataURL(input.files[0]);
});

// Tambahkan event listener untuk input file foto
$('#foto').on('change', function() {
    var input = this;
    var reader = new FileReader();

    // Tampilkan animasi loading saat proses pengunggahan
    $('#foto-upload-loading').show();
    
    reader.onload = function(e) {
        // Sembunyikan animasi loading dan tampilkan gambar yang dipilih
        $('#foto-upload-loading').hide();
        $('#foto-preview').attr('src', e.target.result).show();
    }
    
    reader.readAsDataURL(input.files[0]);
});

</script>
@endpush