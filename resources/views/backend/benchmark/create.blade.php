@extends('layouts.app')
@section('benchmark-create','active')

@section('content')
@push('styles')
<style>
    #foto-preview,
    #sketsa-preview {
        max-width: 100px;
        max-height: 100px;
    }
</style>
@endpush
<div class="col-md-10">
    <div class="card">
        <div class="card-header bg-dark">
            <h4 class="font-weight-bold">Tamabah Data Benchmark</h4>
        </div>
        <div class="card-body">
            {{-- @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif --}}
            <form method="POST" action="{{ route('benchmark.store') }}" class="row g-3" enctype="multipart/form-data">
                @csrf
                <div class="col-md-6 mb-2">
                    <label for="kode_bm" class="form-label">Kode BM</label>
                    <input type="text" class="form-control @error('kode_bm') is-invalid @enderror" id=" kode_bm"
                        name="kode_bm" value="{{ old('kode_bm') }}" placeholder="Kode BM">
                    @error('kode_bm')
                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <div class="col-md-6 mb-2">
                    <label for="no_registrasi" class="form-label">No. Registrasi</label>
                    <input type="text" class="form-control @error('no_registrasi') is-invalid @enderror"
                        id="no_registrasi" name="no_registrasi" value="{{ old('no_registrasi') }}"
                        placeholder="No. Registrasi">
                    @error('no_registrasi')
                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <div class="col-md-6 mb-2">
                    <label for="nama_pekerjaan" class="form-label">Nama Pekerjaan</label>
                    <input type="text" class="form-control @error('nama_pekerjaan') is-invalid @enderror"
                        id="nama_pekerjaan" name="nama_pekerjaan" value="{{ old('nama_pekerjaan') }}"
                        placeholder="Nama Pekerjaan">
                    @error('nama_pekerjaan')
                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <div class="col-md-6 mb-2">
                    <label for="no_registrasi" class="form-label">Jenis Pekerjaan</label>
                    <select id="jenis_pekerjaan" name="jenis_pekerjaan"
                        class="form-control @error('jenis_pekerjaan') is-invalid @enderror">
                        <option value="">Pilih Pekerjaan</option>
                        <option value="embung">Embung</option>
                        <option value="bendungan">Bendungan</option>
                        <option value="bendung">Bendung</option>
                    </select>
                    @error('jenis_pekerjaan')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-md-3 mb-2">
                    <label for="no_registrasi" class="form-label">Provinsi</label>
                    <select id="province_id" name="province_id"
                        class="form-control @error('province_id') is-invalid @enderror">
                        <option value="">Pilih Provinsi</option>
                        @foreach ($provinces as $item)
                        <option value="{{ $item->id ?? '' }}">{{ $item->name ?? '' }}</option>
                        @endforeach
                    </select>
                    @error('province_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-md-3 mb-2">
                    <label for="no_registrasi" class="form-label">Kota</label>
                    <select id="city_id" name="city_id" class="form-control @error('city_id') is-invalid @enderror">
                        <option value="">Pilih Kota</option>
                        <option value=""></option>

                    </select>

                    @error('city_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-md-3 mb-2">
                    <label for="no_registrasi" class="form-label">Kecamatan</label>
                    <select id="district_id" name="district_id"
                        class="form-control @error('district_id') is-invalid @enderror">
                        <option value="">Pilih Kecamatan</option>
                        <option value=""></option>

                    </select>

                    @error('district_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-md-3 mb-2">
                    <label for="no_registrasi" class="form-label">Desa</label>
                    <select id="village_id" name="village_id"
                        class="form-control @error('village_id') is-invalid @enderror">
                        <option value="">Pilih Desa</option>
                        <option value=""></option>

                    </select>

                    @error('village_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-md-4 mb-2">
                    <label for="utm_x" class="form-label">Koordinat X</label>
                    <input type="text" class="form-control @error('utm_x') is-invalid @enderror" id="utm_x" name="utm_x"
                        placeholder="UTM X" value="{{ old('utm_x') }}" pattern="[0-9.,]*">
                    @error('utm_x')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="col-md-4 mb-2">
                    <label for="utm_y" class="form-label">Koordinat Y</label>
                    <input type="text" class="form-control @error('utm_y') is-invalid @enderror" id="utm_y" name="utm_y"
                        placeholder="UTM Y" value="{{ old('utm_y') }}" pattern="[0-9.,]*">
                    @error('utm_y')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="col-md-4 mb-2">
                    <label for="zone" class="form-label">Zone</label>
                    <select name="zone" id="zone" class="form-control @error('zone') is-invalid @enderror">
                        <option value="">Pilih Zone</option>
                        <option value="48" {{ old('zone')=='48' ? 'selected' : '' }}>48</option>
                        <option value="49" {{ old('zone')=='49' ? 'selected' : '' }}>49</option>
                        <option value="50" {{ old('zone')=='50' ? 'selected' : '' }}>50</option>
                        <option value="51" {{ old('zone')=='51' ? 'selected' : '' }}>51</option>
                        <option value="52" {{ old('zone')=='52' ? 'selected' : '' }}>52</option>
                    </select>
                    @error('zone')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="col-md-6 mb-2">
                    <label for="lat" class="form-label">Latitude</label>
                    <input type="text" class="form-control" id="lat" name="lat" value="{{ old('lat') }}" readonly>
                </div>

                <div class="col-md-6 mb-2">
                    <label for="long" class="form-label">Longitude</label>
                    <input type="text" class="form-control" id="long" name="long" value="{{ old('long') }}" readonly>
                </div>

                <div class="col-md-6 mb-2">
                    <label for="tinggi_orthometrik" class="form-label">Tinggi Orthometrik</label>
                    <input type="text" class="form-control" id="tinggi_orthometrik" name="tinggi_orthometrik"
                        value="{{ old('tinggi_orthometrik') }}" placeholder="Tinggi Orthometrik">
                </div>
                <div class="col-md-6 mb-2">
                    <label for="tinggi_elipsoid" class="form-label">Tinggi Elipsoid</label>
                    <input type="text" class="form-control" id="tinggi_elipsoid" name="tinggi_elipsoid"
                        value="{{ old('tinggi_elipsoid') }}" placeholder="Tinggi Elipsoid">
                </div>

                <div class="col-md-12 mb-2">
                    <label for="Keterangan" class="form-label">Keterangan</label>
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3"
                        placeholder="Keterangan">{{ old('keterangan') }}</textarea>
                </div>

                <!-- Tambahkan animasi loading -->
                <div class="col-md-6 mb-2">
                    <label for="sketsa" class="form-label">Sketsa</label>
                    <input class="form-control" type="file" id="sketsa" name="sketsa" accept="image/*">
                    <!-- Animasi loading -->
                    <div id="sketsa-upload-loading" style="display: none;">
                        Loading...
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <label for="foto" class="form-label">Foto</label>
                    <input class="form-control" type="file" id="foto" name="foto" accept="image/*">
                    <!-- Animasi loading -->
                    <div id="foto-upload-loading" style="display: none;">
                        Loading...
                    </div>
                </div>

                <!-- Menampilkan gambar yang dipilih -->
                <div class="col-md-6 mb-2">
                    <img id="sketsa-preview" class="img-thumbnail" style="display: none;">
                </div>
                <div class="col-md-6 mb-2">
                    <img id="foto-preview" class="img-thumbnail" style="display: none;">
                </div>

                <div class="col-md-12 mb-2 mt-2">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>

        </div>
    </div>
</div>
<div class="row"></div>
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