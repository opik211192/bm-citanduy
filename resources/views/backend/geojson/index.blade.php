@extends('layouts.app')
@section('das', 'active')

@section('content')
<div class="col">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold mb-0">Data Das</h4>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="fas fa-plus"></i> Tambah
                    </button>
                </div>

                {{-- Alert --}}
                <div id="alertBox" class="mt-2"></div>

                {{-- Tabel Data --}}
                <table class="table table-bordered table-sm mt-3 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Nama</th>
                            <th>File</th>
                            <th style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="fileTable">
                        @foreach($files as $index => $file)
                        <tr data-id="{{ $file->id }}">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $file->name }}</td>
                            <td>
                                <a href="{{ asset($file->file_path) }}" target="_blank"
                                    class="btn btn-sm btn-info">Lihat</a>
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm btn-edit" data-id="{{ $file->id }}"
                                    data-name="{{ $file->name }}">
                                    <i class="fas fa-edit" title="Edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm btn-delete" data-id="{{ $file->id }}">
                                    <i class="fas fa-trash-alt" title="Hapus"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ==================== MODAL TAMBAH ==================== --}}
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formAdd" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Das</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>File GeoJSON</label>
                        <input type="file" name="file" class="form-control" accept=".geojson,.json" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ==================== MODAL EDIT ==================== --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEdit" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="editId">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Das</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama</label>
                        <input type="text" name="name" id="editName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>File GeoJSON (opsional)</label>
                        <input type="file" name="file" class="form-control" accept=".geojson,.json">
                        <small class="text-muted">Kosongkan jika tidak ingin mengganti file.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- jQuery & Script --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function() {

    // 🔹 Tambah Data
    $('#formAdd').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('batas-das.store') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(res) {
                $('#addModal').modal('hide');
                showAlert('success', 'Data berhasil ditambahkan.');
                loadTable();
                $('#formAdd')[0].reset();
            },
            error: function(xhr) {
                showAlert('danger', xhr.responseJSON?.message || 'Gagal menambah data.');
            }
        });
    });

    // 🔹 Klik Edit
    $(document).on('click', '.btn-edit', function() {
        $('#editId').val($(this).data('id'));
        $('#editName').val($(this).data('name'));
        $('#editModal').modal('show');
    });

    // 🔹 Update Data
    $('#formEdit').on('submit', function(e) {
        e.preventDefault();
        let id = $('#editId').val();
        let formData = new FormData(this);
        formData.append('_method', 'PUT');

        $.ajax({
            url: "/das/batas-das/edit/" + id,
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(res) {
                $('#editModal').modal('hide');
                showAlert('success', 'Data berhasil diupdate.');
                loadTable();
            },
            error: function(xhr) {
                showAlert('danger', xhr.responseJSON?.message || 'Gagal mengupdate data.');
            }
        });
    });

    // 🔹 Hapus Data
    $(document).on('click', '.btn-delete', function() {
        if (!confirm('Yakin ingin menghapus data ini?')) return;
        let id = $(this).data('id');

        $.ajax({
            url: "{{ route('batas-das.destroy', ':id') }}".replace(':id', id),
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                _method: 'DELETE'
            },
            success: function() {
                showAlert('success', 'Data berhasil dihapus.');
                loadTable();
            },
            error: function(xhr) {
                showAlert('danger', xhr.responseJSON?.message || 'Gagal menghapus data.');
            }
        });
    });

    // 🔹 Fungsi reload tabel
    function loadTable() {
        $.get("{{ route('batas-das.index') }}", function(data) {
            const tbody = $(data).find('#fileTable').html();
            $('#fileTable').html(tbody);
        });
    }

    // 🔹 Alert helper
    function showAlert(type, msg) {
        $('#alertBox').html(`<div class="alert alert-${type} mt-2">${msg}</div>`);
        setTimeout(() => $('#alertBox').html(''), 3000);
    }

});
</script>
@endsection