@extends('layouts.app')
@section('benchmark','active')

@section('content')
<div class="col">
    <div class="card">
        <div class="card-header">
            <h4 class="font-weight-bold ">Data Benchmark</h4>
            <a href="{{ route('benchmark.create') }}" class="btn btn-primary btn-sm">Tambah</a>
        </div>
        <div class="card-body">
            <table id="benchmark-table" class="table table-hover ">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode BM</th>
                        <th>No. Registrasi</th>
                        <th>Nama Pekerjaan</th>
                        <th>Jenis Pekerjaan</th>
                        <th>Keterangan</th>
                        <th>Options</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#benchmark-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('benchmark.index') }}",
            language: {
                'lengthMenu': 'Show _MENU_ entries',
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'kode_bm', name: 'kode_bm' },
                { data: 'no_registrasi', name: 'no_registrasi' },
                { data: 'nama_pekerjaan', name: 'nama_pekerjaan' },
                { data: 'jenis_pekerjaan', name: 'jenis_pekerjaan' },
                { data: 'keterangan', name: 'keterangan' },
                { data: 'options', name: 'options', orderable: false, searchable: false }
            ]
        });
    });
</script>
@endpush