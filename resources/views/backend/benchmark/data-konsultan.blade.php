@extends('layouts.app')
@section('title', 'Data Konsultan')

@section('content')
<div class="container-fluid">

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Konsultan</h3>
            <div class="card-tools">
                <button class="btn btn-primary btn-sm" id="btnTambah">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped" id="tabelKonsultan">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No Telp</th>
                        <th>Email</th>
                        <th>Logo</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</div>

{{-- MODAL --}}
<div class="modal fade" id="modalForm">
    <div class="modal-dialog">
        <form id="formKonsultan" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" id="id">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Konsultan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Alamat</label>
                        <input type="text" name="alamat" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>No Telp</label>
                        <input type="number" name="no_telp" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Logo</label>
                        <input type="file" name="logo" class="form-control">
                        <div id="preview-logo" class="mt-2"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function () {

    let table = $('#tabelKonsultan').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('benchmark.data.konsultan') }}",
        columns: [
            { data: 'DT_RowIndex', orderable:false, searchable:false },
            { data: 'nama' },
            { data: 'alamat' },
            { data: 'no_telp' },
            { data: 'email' },
            { data: 'logo', orderable:false, searchable:false },
            { data: 'aksi', orderable:false, searchable:false }
        ]
    });

    // TAMBAH
    $('#btnTambah').click(function () {
        $('#formKonsultan')[0].reset();
        $('#id').val('');
        $('#preview-logo').html('');
        $('#modalForm').modal('show');
    });

    // SIMPAN
    $('#formKonsultan').submit(function(e){
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('benchmark.data.konsultan.store') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function () {
                $('#modalForm').modal('hide');
                table.ajax.reload();
            }
        });
    });

    // EDIT
    $('body').on('click','.btn-edit', function(){
        let id = $(this).data('id');

        $.get("{{ route('benchmark.data.konsultan.show', ':id') }}".replace(':id', id), function(data){
            $('#id').val(data.id);
            $('[name=nama]').val(data.nama);
            $('[name=alamat]').val(data.alamat);
            $('[name=no_telp]').val(data.no_telp);
            $('[name=email]').val(data.email);

            if(data.logo){
                $('#preview-logo').html(
                    '<img src="/storage/img/konsultan/'+data.logo+'" width="80">'
                );
            }
            $('#modalForm').modal('show');
        });
    });

    // DELETE
    $('body').on('click','.btn-delete', function(){
        let id = $(this).data('id');
        if(confirm('Hapus data ini?')){
            $.ajax({
                url: "{{ route('benchmark.data.konsultan.destroy', ':id') }}".replace(':id', id),
                type: "DELETE",
                data: {_token:"{{ csrf_token() }}"},
                success: function(){
                    table.ajax.reload();
                }
            });
        }
    });

});
</script>
@endpush