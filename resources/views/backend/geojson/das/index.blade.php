@extends('layouts.app')
@section('das','active')

@section('content')
<div class="col">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="font-weight-bold mb-3">Batas Das</h4>

                {{-- Alert Box --}}
                <div id="alertBox" class="mt-3"></div>

                <hr>

                {{-- Tabel File --}}
                <table class="table table-sm mt-3">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Nama File</th>
                            <th>File</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="fileTable">
                        @php
                        $folderPath = public_path('js');
                        $files = glob($folderPath . '/*.geojson');
                        @endphp

                        @if (count($files) > 0)
                        @foreach ($files as $index => $file)
                        @php $fileName = basename($file); @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td></td>
                            <td>{{ $fileName }}</td>
                            <td><a href="{{ asset('js/' . $fileName) }}" target="_blank">Lihat File</a></td>
                            <td>
                                <button class="btn btn-success btn-sm" onclick="editFile('{{ $fileName }}')">
                                    Edit
                                </button>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada file GeoJSON</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Upload --}}
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Ulang File GeoJSON</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                {{-- Keterangan file yang akan ditimpa --}}
                <div class="alert alert-warning py-2 mb-3">
                    <strong>Perhatian:</strong> File yang akan diganti adalah:
                    <span id="fileNameDisplay" class="fw-bold text-dark"></span>
                </div>

                <form id="uploadForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="old_file" id="oldFile">
                    <div class="mb-3">
                        <label for="fileInput" class="form-label">Pilih File GeoJSON Baru</label>
                        <input type="file" name="file" id="fileInput" class="form-control" accept=".geojson,.json"
                            required>
                    </div>
                    <button type="submit" id="uploadBtn" class="btn btn-success w-100">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span class="btn-text">Upload</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function editFile(fileName) {
    $('#fileNameDisplay').text(fileName);
    $('#oldFile').val(fileName);
    $('#uploadModal').modal('show');
}

$(function() {
    $('#uploadForm').on('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);
        let $btn = $('#uploadBtn');
        let $spinner = $btn.find('.spinner-border');
        let $btnText = $btn.find('.btn-text');

        $.ajax({
            url: "{{ route('batas-das.upload') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $btn.prop('disabled', true);
                $spinner.removeClass('d-none');
                $btnText.text('Mengupload...');
                $('#alertBox').html('');
            },
            success: function(res) {
                if (res.success) {
                    $('#alertBox').html('<div class="alert alert-success">' + res.message + '</div>');
                    $('#uploadModal').modal('hide');

                    // Update tabel tanpa reload
                    $('#fileTable').html(`
                        <tr>
                            <td>1</td>
                            <td>${res.file_name}</td>
                            <td><a href="/js/${res.file_name}" target="_blank">Lihat File</a></td>
                            <td><button class="btn btn-success btn-sm" onclick="editFile('${res.file_name}')">Edit</button></td>
                        </tr>
                    `);
                } else {
                    $('#alertBox').html('<div class="alert alert-danger">' + (res.message || 'Gagal upload.') + '</div>');
                }
            },
            error: function(xhr) {
                $('#alertBox').html('<div class="alert alert-danger">Terjadi kesalahan: ' + xhr.statusText + '</div>');
            },
            complete: function() {
                $btn.prop('disabled', false);
                $spinner.addClass('d-none');
                $btnText.text('Upload');
                $('#fileInput').val('');
            }
        });
    });
});
</script>
@endpush