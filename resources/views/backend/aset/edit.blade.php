@extends('layouts.app')
@section('benchmark-create', 'active')

@section('content')
<div class="col-md-10">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-primary text-white py-3 px-4 border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold">
                    <i class="fas fa-edit me-2"></i> Edit Data Aset
                </h4>
                <a href="{{ route('aset.index') }}" class="btn btn-light btn-sm shadow-sm rounded-pill px-3">
                    <i class="fas fa-arrow-left me-1 text-primary"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('aset.update', $aset->id) }}" enctype="multipart/form-data"
                class="row g-3">
                @csrf
                @method('PUT')

                {{-- Nama Aset --}}
                <div class="col-md-6">
                    <label for="nama_aset">Nama Aset</label>
                    <input type="text" class="form-control @error('nama_aset') is-invalid @enderror" name="nama_aset"
                        value="{{ old('nama_aset', $aset->nama_aset) }}">
                    @error('nama_aset') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Jenis Aset --}}
                <div class="col-md-6">
                    <label for="jenis_aset">Jenis Aset</label>
                    <select name="jenis_aset" class="form-control @error('jenis_aset') is-invalid @enderror">
                        <option value="">Pilih Jenis Aset</option>
                        <option value="Embung" {{ old('jenis_aset', $aset->jenis_aset) == 'Embung' ? 'selected' : ''
                            }}>Embung</option>
                        <option value="Bendung" {{ old('jenis_aset', $aset->jenis_aset) == 'Bendung' ? 'selected' : ''
                            }}>Bendung
                        </option>
                        <option value="Bendungan" {{ old('jenis_aset', $aset->jenis_aset) == 'Bendungan' ? 'selected' :
                            '' }}>Bendungan
                        </option>
                        <option value="Pengaman Pantai" {{ old('jenis_aset', $aset->jenis_aset) == 'Pengaman Pantai' ?
                            'selected' : ''
                            }}>Pengaman Pantai</option>
                        <option value="Pengendali Sedimen" {{ old('jenis_aset', $aset->jenis_aset)
                            == 'Pengendali Sedimen' ? 'selected'
                            : '' }}>Pengendali Sedimen</option>
                        <option value="Pengendali Banjir" {{ old('jenis_aset', $aset->jenis_aset) == 'Pengendali Banjir'
                            ? 'selected' :
                            '' }}>Pengendali Banjir</option>
                    </select>
                    @error('jenis_aset') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Registrasi dan Kode BMN --}}
                <div class="col-md-6">
                    <label>No Integrasi</label>
                    <input type="text" class="form-control @error('no_registrasi') is-invalid @enderror"
                        name="no_registrasi" value="{{ old('no_registrasi', $aset->no_registrasi) }}">
                    @error('no_registrasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label>Kode BMN</label>
                    <input type="text" class="form-control @error('kode_bmn') is-invalid @enderror" name="kode_bmn"
                        value="{{ old('kode_bmn', $aset->kode_bmn) }}">
                    @error('kode_bmn') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Wilayah --}}
                @foreach ([['id' => 'province_id', 'label' => 'Provinsi', 'data' => $provinces],
                ['id' => 'city_id', 'label' => 'Kota/Kabupaten', 'data' => $cities],
                ['id' => 'district_id', 'label' => 'Kecamatan', 'data' => $districts],
                ['id' => 'village_id', 'label' => 'Desa', 'data' => $villages]] as $field)
                <div class="col-md-3">
                    <label>{{ $field['label'] }}</label>
                    <select id="{{ $field['id'] }}" name="{{ $field['id'] }}"
                        class="form-control @error($field['id']) is-invalid @enderror">
                        <option value="">Pilih {{ $field['label'] }}</option>
                        @foreach ($field['data'] as $key => $val)
                        <option value="{{ is_object($val) ? $val->id : $key }}" {{ old($field['id'], $aset->
                            {$field['id']}) == (is_object($val) ? $val->id : $key) ? 'selected' : '' }}>
                            {{ is_object($val) ? $val->name : $val }}
                        </option>
                        @endforeach
                    </select>
                    @error($field['id']) <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                @endforeach

                {{-- Koordinat --}}
                @foreach (['lat' => 'Latitude', 'long' => 'Longitude', 'utm_x' => 'UTM X', 'utm_y' => 'UTM Y'] as $name
                => $label)
                <div class="col-md-6">
                    <label>{{ $label }}</label>
                    <input type="text" name="{{ $name }}" id="{{ $name }}"
                        class="form-control @error($name) is-invalid @enderror" value="{{ old($name, $aset->$name) }}"
                        {{ in_array($name, ['utm_x','utm_y']) ? 'readonly' : '' }}>
                    @error($name) <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                @endforeach

                {{-- Tahun dan Kondisi --}}
                <div class="col-md-6">
                    <label>Tahun Mulai Bangunan</label>
                    <input type="number" name="tahun_mulai_bangunan"
                        class="form-control @error('tahun_mulai_bangunan') is-invalid @enderror"
                        value="{{ old('tahun_mulai_bangunan', $aset->tahun_mulai_bangunan) }}">
                    @error('tahun_mulai_bangunan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label>Tahun Selesai Bangunan</label>
                    <input type="number" name="tahun_selesai_bangunan"
                        class="form-control @error('tahun_selesai_bangunan') is-invalid @enderror"
                        value="{{ old('tahun_selesai_bangunan', $aset->tahun_selesai_bangunan) }}">
                    @error('tahun_selesai_bangunan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label>Kondisi Bangunan</label>
                    <select name="kondisi_bangunan" id="kondisi_bangunan"
                        class="form-control @error('kondisi_bangunan') is-invalid @enderror">
                        <option value="">Pilih Kondisi Bangunan</option>
                        <option value="Baik" {{ $aset->kondisi_bangunan == 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Rusak Ringan" {{ $aset->kondisi_bangunan == 'Rusak Ringan' ? 'selected' : ''
                            }}>Rusak Ringan
                        </option>
                        <option value="Rusak Berat" {{ $aset->kondisi_bangunan == 'Rusak Berat' ? 'selected' : ''
                            }}>Rusak Berat
                        </option>
                        <option value="Hilang" {{ $aset->kondisi_bangunan == 'Hilang' ? 'selected' : '' }}>Hilang
                        </option>
                        <option value="-" {{ $aset->kondisi_bangunan == '-' ? 'selected' : '' }}>-</option>
                    </select>
                    @error('kondisi_bangunan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Keterangan --}}
                <div class="col-md-6">
                    <label>Keterangan</label>
                    <textarea name="keterangan"
                        class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan', $aset->keterangan) }}</textarea>
                    @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Upload Foto Dinamis --}}
                <div class="col-md-12">
                    <label for="photos">Foto Aset</label>
                    <div class="row mb-2">
                        @foreach ($aset->photos as $photo)
                        <div class="col-md-2 text-center mb-2">
                            <img src="{{ asset('storage/' . $photo->file_path) }}" class="img-thumbnail" width="100">
                            <div><small>{{ $photo->filename }}</small></div>
                            <div class="form-check mt-1">
                                <input class="form-check-input" type="checkbox" name="delete_photos[]"
                                    value="{{ $photo->id }}" id="delete_{{ $photo->id }}">
                                <label class="form-check-label text-danger" for="delete_{{ $photo->id }}">
                                    Hapus
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div id="photo-wrapper">
                        <div class="input-group mb-2">
                            <input type="file" name="photos[]" class="form-control photo-input" accept="image/*">
                            <button type="button" class="btn btn-outline-danger btn-remove"
                                style="display:none;">Hapus</button>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" id="add-photo" class="btn btn-secondary btn-sm mt-1">+ Tambah
                            Foto</button>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="col-12 mt-4">
                    <div class="d-flex justify-content-start">
                        <button type="submit" class="btn btn-primary shadow-sm rounded-pill px-4">
                            <i class="fas fa-save me-2"></i> Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.8.0/proj4.js"></script>

<script>
    $(function () {
    let maxPhotos = 6; 
    let existingPhotos = {{ $aset->photos->count() }};
    

    function updateAddButtonVisibility() {
        let currentInputs = $('#photo-wrapper .input-group').length;
        let totalPhotos = existingPhotos;

        if (totalPhotos >= maxPhotos) {
            $('#add-photo').hide();
            $('#photo-wrapper').hide();
        } else {
            $('#add-photo').show();
            $('#photo-wrapper').show();
        }
    }

    updateAddButtonVisibility();

    $('#add-photo').on('click', function () {
        let currentInputs = $('#photo-wrapper .input-group').length;

        if (existingPhotos + currentInputs >= maxPhotos) {
            alert(`Maksimal total foto adalah ${maxPhotos}.`);
            return;
        }

        let newInput = `
            <div class="input-group mb-2">
                <input type="file" name="photos[]" class="form-control photo-input" accept="image/*">
                <button type="button" class="btn btn-outline-danger btn-remove">Hapus</button>
            </div>
        `;
        $('#photo-wrapper').append(newInput);
        updateAddButtonVisibility();
    });

    $(document).on('click', '.btn-remove', function () {
        $(this).closest('.input-group').remove();
        updateAddButtonVisibility();
    });
});


</script>

<script>
    $(document).on('change', 'input[name="delete_photos[]"]', function () {
        const checkbox = $(this);
        const photoId = checkbox.val();
        const container = checkbox.closest('.col-md-2');

        if (checkbox.is(':checked')) {
            if (confirm('Yakin ingin menghapus foto ini?')) {
                $.ajax({
                    url: "{{ route('foto-aset.hapus', ':id') }}".replace(':id', photoId),
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.success) {
                            container.fadeOut(300, function () {
                                $(this).remove();
                                window.location.reload();
                            });
                        } else {
                            alert('Gagal menghapus foto!');
                            checkbox.prop('checked', false);
                        }
                    },
                    error: function () {
                        alert('Terjadi kesalahan saat menghapus foto.');
                        checkbox.prop('checked', false);
                    }
                });
            } else {
                checkbox.prop('checked', false);
            }
        }
    });
</script>
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
@endpush