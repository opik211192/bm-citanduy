@extends('layouts.app')
@section('benchmark-create','active')

@section('content')
<div class="col-md-10">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-primary text-white py-3 px-4 border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold">
                    <i class="fas fa-plus-circle me-2"></i> Tambah Data Aset
                </h4>
                <a href="{{ route('aset.index') }}" class="btn btn-light btn-sm shadow-sm rounded-pill px-3">
                    <i class="fas fa-arrow-left me-1 text-primary"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('aset.store') }}" enctype="multipart/form-data" class="row g-3">
                @csrf

                {{-- Nama Aset --}}
                <div class="col-md-6">
                    <label for="nama_aset">Nama Aset</label>
                    <input type="text" class="form-control @error('nama_aset') is-invalid @enderror" name="nama_aset"
                        value="{{ old('nama_aset') }}">
                    @error('nama_aset') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Jenis Aset --}}
                <div class="col-md-6">
                    <label for="jenis_aset">Jenis Aset</label>
                    <select name="jenis_aset" class="form-control @error('jenis_aset') is-invalid @enderror">
                        <option value="">Pilih Jenis Aset</option>
                        <option value="Embung" {{ old('jenis_aset')=='Embung' ? 'selected' : '' }}>Embung</option>
                        <option value="Bendung" {{ old('jenis_aset')=='Bendung' ? 'selected' : '' }}>Bendung</option>
                        <option value="Bendungan" {{ old('jenis_aset')=='Bendungan' ? 'selected' : '' }}>Bendungan
                        </option>
                        <option value="Pengaman Pantai" {{ old('jenis_aset')=='Pengaman Pantai' ? 'selected' : '' }}>
                            Pengaman Pantai</option>
                        <option value="Pengendali Sedimen" {{ old('jenis_aset')=='Pengendali Sedimen' ? 'selected' : ''
                            }}>Pengendali Sedimen</option>
                        <option value="Pengendali Banjir" {{ old('jenis_aset')=='Pengendali Banjir' ? 'selected' : ''
                            }}> Pengendali Banjir</option>
                    </select>
                    @error('jenis_aset') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Registrasi dan Kode BMN --}}
                <div class="col-md-6">
                    <label>No Integrasi</label>
                    <input type="text" class="form-control @error('no_registrasi') is-invalid @enderror"
                        name="no_registrasi" value="{{ old('no_registrasi') }}">
                    @error('no_registrasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label>Kode BMN</label>
                    <input type="text" class="form-control @error('kode_bmn') is-invalid @enderror" name="kode_bmn"
                        value="{{ old('kode_bmn') }}">
                    @error('kode_bmn') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Wilayah --}}
                @foreach ([
                ['id' => 'province_id', 'label' => 'Provinsi', 'data' => $provinces],
                ['id' => 'city_id', 'label' => 'Kota/Kabupaten'],
                ['id' => 'district_id', 'label' => 'Kecamatan'],
                ['id' => 'village_id', 'label' => 'Desa']
                ] as $field)
                <div class="col-md-3">
                    <label>{{ $field['label'] }}</label>
                    <select id="{{ $field['id'] }}" name="{{ $field['id'] }}"
                        class="form-control @error($field['id']) is-invalid @enderror">
                        <option value="">Pilih {{ $field['label'] }}</option>
                        @isset($field['data'])
                        @foreach ($field['data'] as $item)
                        <option value="{{ $item->id }}" {{ old($field['id'])==$item->id ? 'selected' : '' }}>{{
                            $item->name }}</option>
                        @endforeach
                        @endisset
                    </select>
                    @error($field['id']) <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                @endforeach

                {{-- Koordinat --}}
                @foreach ([
                'lat' => 'Latitude',
                'long' => 'Longitude',
                'utm_x' => 'UTM X',
                'utm_y' => 'UTM Y'
                ] as $name => $label)
                <div class="col-md-6">
                    <label>{{ $label }}</label>
                    <input type="text" name="{{ $name }}" id="{{ $name }}"
                        class="form-control @error($name) is-invalid @enderror" value="{{ old($name) }}" {{
                        in_array($name, ['utm_x', 'utm_y' ]) ? 'readonly' : '' }}>
                    @error($name) <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                @endforeach

                {{-- Tahun dan Kondisi --}}
                <div class="col-md-6">
                    <label>Tahun Mulai Bangunan</label>
                    <input type="number" name="tahun_mulai_bangunan"
                        class="form-control @error('tahun_mulai_bangunan') is-invalid @enderror"
                        placeholder="Contoh: 2020" value="{{ old('tahun_mulai_bangunan') }}">
                    @error('tahun_mulai_bangunan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label>Tahun Selesai Bangunan</label>
                    <input type="number" name="tahun_selesai_bangunan"
                        class="form-control @error('tahun_selesai_bangunan') is-invalid @enderror"
                        placeholder="Contoh: 2025" value="{{ old('tahun_selesai_bangunan') }}">
                    @error('tahun_selesai_bangunan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label>Kondisi Bangunan</label>
                    <select name="kondisi_bangunan" id="kondisi_bangunan"
                        class="form-control @error('kondisi_bangunan') is-invalid @enderror">
                        <option value="">Pilih Kondisi Bangunan</option>
                        <option value="Baik">Baik</option>
                        <option value="Rusak Ringan">Rusak Ringan</option>
                        <option value="Rusak Berat">Rusak Berat</option>
                        <option value="Hilang">Hilang</option>
                        <option value="-">-</option>
                    </select>
                    @error('kondisi_bangunan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Keterangan --}}
                <div class="col-md-6">
                    <label>Keterangan</label>
                    <textarea name="keterangan"
                        class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan') }}</textarea>
                    @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Upload Foto Dinamis --}}
                <div class="col-md-12">
                    <label for="photos" class="form-label">Foto Aset</label>
                    <div id="photo-wrapper">
                        <div class="input-group mb-2">
                            <input type="file" name="photos[]"
                                class="form-control photo-input @error('photos.*') is-invalid @enderror"
                                accept="image/*">
                            <button type="button" class="btn btn-outline-danger btn-remove"
                                style="display: none;">Hapus</button>
                        </div>
                        @error('photos.*') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <small class="text-muted">Bisa unggah lebih dari satu foto (jpg, png). Maksimal 6 file.</small>
                    <div class="d-flex justify-content-end">
                        <button type="button" id="add-photo" class="btn btn-secondary btn-sm mt-1">+ Tambah
                            Foto</button>
                    </div>
                </div>

                {{-- Tombol Simpan --}}
                <div class="col-12 mt-4">
                    <div class="d-flex justify-content-start">
                        <button type="submit" class="btn btn-primary shadow-sm rounded-pill px-4">
                            <i class="fas fa-save me-2"></i> Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<!-- Proj4JS untuk konversi koordinat -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.8.0/proj4.js"></script>
<script>
    const utmProjection = "+proj=utm +zone=48 +south +datum=WGS84 +units=m +no_defs";

    function convertLatLongToUTM() {
        let lat = parseFloat($('input[name="lat"]').val());
        let lon = parseFloat($('input[name="long"]').val());

        if (!isNaN(lat) && !isNaN(lon)) {
            const [utmX, utmY] = proj4('WGS84', utmProjection, [lon, lat]);
            $('#utm_x').val(utmX.toFixed(2));
            $('#utm_y').val(utmY.toFixed(2));
        }
    }

    $(function () {
        $('input[name="lat"], input[name="long"]').on('input', convertLatLongToUTM);
    });
</script>

<!-- Upload foto dinamis -->
<script>
    $(document).ready(function () {
        const maxFiles = 6;

        function updateAddButtonState() {
            const currentInputs = $('#photo-wrapper .photo-input').length;
            $('#add-photo').toggle(currentInputs < maxFiles);
        }

        updateAddButtonState();

        $('#add-photo').click(function () {
            if ($('#photo-wrapper .photo-input').length < maxFiles) {
                $('#photo-wrapper').append(`
                    <div class="input-group mb-2">
                        <input type="file" name="photos[]" class="form-control photo-input" accept="image/*">
                        <button type="button" class="btn btn-outline-danger btn-remove">Hapus</button>
                    </div>
                `);
                updateAddButtonState();
            }
        });

        $(document).on('click', '.btn-remove', function () {
            $(this).closest('.input-group').remove();
            updateAddButtonState();
        });
    });
</script>

<!-- Dropdown berantai wilayah -->
<script>
    function onChangeSelect(url, id, name, selected = '') {
        $.ajax({
            url: url,
            type: 'GET',
            data: { id: id },
            success: function (data) {
                const $select = $('#' + name);
                $select.empty().append('<option value="">Pilih Salah Satu</option>');
                $.each(data, function (key, value) {
                    $select.append(`<option value="${key}" ${key == selected ? 'selected' : ''}>${value}</option>`);
                });
            }
        });
    }

    $(function () {
        $('#province_id').on('change', function () {
            onChangeSelect('{{ route("cities") }}', $(this).val(), 'city_id');
            $('#district_id, #village_id').empty().append('<option value="">Pilih Salah Satu</option>');
        });

        $('#city_id').on('change', function () {
            onChangeSelect('{{ route("districts") }}', $(this).val(), 'district_id');
            $('#village_id').empty().append('<option value="">Pilih Salah Satu</option>');
        });

        $('#district_id').on('change', function () {
            onChangeSelect('{{ route("villages") }}', $(this).val(), 'village_id');
        });

        // Re-populate saat reload jika ada old input
        const oldProvince = "{{ old('province_id') }}";
        const oldCity = "{{ old('city_id') }}";
        const oldDistrict = "{{ old('district_id') }}";
        const oldVillage = "{{ old('village_id') }}";

        if (oldProvince) {
            $('#province_id').val(oldProvince).trigger('change');

            setTimeout(() => {
                onChangeSelect('{{ route("cities") }}', oldProvince, 'city_id', oldCity);
                setTimeout(() => {
                    onChangeSelect('{{ route("districts") }}', oldCity, 'district_id', oldDistrict);
                    setTimeout(() => {
                        onChangeSelect('{{ route("villages") }}', oldDistrict, 'village_id', oldVillage);
                    }, 400);
                }, 400);
            }, 400);
        }
    });
</script>
<script>
    $(function () {
        // otomatis ganti koma jadi titik
        $('input[name="lat"], input[name="long"]').on('input', function () {
            this.value = this.value.replace(',', '.');
        });

        // tetap panggil konversi UTM
        $('input[name="lat"], input[name="long"]').on('input', convertLatLongToUTM);
    });
</script>
@endpush