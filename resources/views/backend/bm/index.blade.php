@extends('layouts.app')
@section('benchmark','active')

@section('content')
<div class="col">
    <div class="card">
        <div class="card-header">
            <h4 class="font-weight-bold ">Data Benchmark</h4>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
                Import Excel
            </button>
        </div>
        <div class="card-body">
            <table id="benchmark-table" class="table table-hover ">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode BM</th>
                        <th>Pekerjaan</th>
                        <th>Desa</th>
                        <th>Kecamatan</th>
                        <th>Kota</th>
                        <th>Options</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Import Data Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="formImport" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    <div class="mb-3">
                        <label>Pilih File Excel</label>
                        <input type="file" name="file" accept=".xlsx,.xls" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Upload
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function(){

    $('#formImport').submit(function(e){
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('bm.import') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,

            beforeSend: function(){
                $('.btn-primary').text('Uploading...').prop('disabled', true);
            },

            success: function(res){
                alert('Import berhasil!');

                $('#importModal').modal('hide');
                location.reload();
            },

            error: function(err){
                alert('Gagal import!');
                console.log(err);
            },

            complete: function(){
                $('.btn-primary').text('Upload').prop('disabled', false);
            }
        });
    });

});
</script>
@endpush