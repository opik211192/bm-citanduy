@extends('layouts.app')

@push('styles')
<style>
    /* Tag role (multi select) – hanya ubah warna teks */
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        color: #000 !important;
        font-size: 12px;
    }
</style>
@endpush
@section('content')
<div class="container">
    <h3 class="mb-3">Data User</h3>

    {{-- Alert --}}
    <div id="alert-area"></div>

    {{-- Form Tambah / Update User --}}
    <div class="card mb-3">
        <div class="card-header py-2">Form User</div>
        <div class="card-body py-2">
            <form id="form-user">
                @csrf

                <!-- Baris Nama & Email -->
                <!-- Baris Username, Nama & Email -->
                <div class="row g-2 mb-3">
                    <div class="col-md-4">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" name="name" id="name" class="form-control form-control-sm"
                            placeholder="Masukkan Nama" required>
                    </div>
                    <div class="col-md-4">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" id="username" class="form-control form-control-sm"
                            placeholder="Masukkan Username" required>
                    </div>
                    <div class="col-md-4">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control form-control-sm"
                            placeholder="Masukkan Email" required>
                    </div>
                </div>


                <!-- Baris Password & Konfirmasi -->
                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control form-control-sm"
                            placeholder="Masukkan Password">
                    </div>
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="form-control form-control-sm" placeholder="Ulangi Password">
                    </div>
                </div>

                <!-- Baris Role -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="role-select" class="form-label">Role</label>
                        <select name="role[]" id="role-select" class="form-control" multiple required>
                            @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Baris Tombol Submit -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <button type="submit" id="btn-submit" class="btn btn-sm btn-primary w-100">Tambah</button>
                    </div>
                    <input type="hidden" name="user_id" id="user-id">
                </div>
            </form>
        </div>
    </div>

    {{-- Daftar User --}}
    <div class="card">
        <div class="card-header py-2">Daftar User</div>
        <div class="card-body p-2">
            <table class="table table-sm table-bordered align-middle" id="user-table">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr data-id="{{ $user->id }}">
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->roles->pluck('name')->implode(', ') }}</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-outline-dark btn-edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if(!$user->hasRole('Admin'))
                            <button class="btn btn-sm btn-outline-danger btn-delete" title="Hapus">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(function(){
    // aktifkan select2
    $('#role-select').select2({
        placeholder: "Pilih role",
        width: '100%'
    });
    let isEdit = false;

    // Submit Form (Tambah / Update)
    $('#form-user').on('submit', function(e){
        e.preventDefault();
        let formData = $(this).serialize();
        let url = "{{ route('users.store') }}";
        let method = "POST";

        if(isEdit){
            let id = $('#user-id').val();
            url = "/users/" + id;
            formData += "&_method=PUT"; // spoofing
        }

        $.ajax({
            url: url,
            method: method,
            data: formData,
            success: function(res){
                $('#alert-area').html('<div id="alert-success" class="alert alert-success">'+res.message+'</div>');
                setTimeout(() => { $("#alert-success").fadeOut(500, () => $(this).remove()); }, 3000);

                if(isEdit){
                    // Update row di tabel
                    let tr = $('#user-table tbody').find(`tr[data-id="${res.data.id}"]`);
                    tr.find('td:eq(0)').text(res.data.name);
                    tr.find('td:eq(1)').text(res.data.username);
                    tr.find('td:eq(2)').text(res.data.email);
                    tr.find('td:eq(3)').text(res.data.role);
                }else{
                    // Tambah row baru
                    $('#user-table tbody').append(`
                        <tr data-id="${res.data.id}">
                            <td>${res.data.name}</td>
                            <td>${res.data.username}</td>
                            <td>${res.data.email}</td>
                            <td>${res.data.role}</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-warning btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-danger btn-delete" title="Hapus">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    `);
                }

                // Reset form ke mode tambah
                $('#form-user')[0].reset();
                $('#role-select').val(null).trigger('change');
                $('#user-id').val('');
                isEdit = false;
                $('#btn-submit').text('Tambah').removeClass('btn-warning').addClass('btn-primary');
            },
            error: function(xhr){
                let errors = xhr.responseJSON.errors;
                let msg = '';
                $.each(errors, function(k, v){ msg += v[0]+'<br>'; });
                $('#alert-area').html('<div class="alert alert-danger">'+msg+'</div>');
            }
        });
    });

    // Klik Edit
    $(document).on('click','.btn-edit',function(e){
        e.preventDefault();
        let tr = $(this).closest('tr');
        let id = tr.data('id');
        let name = tr.find('td:eq(0)').text();
        let username = tr.find('td:eq(1)').text();
        let email = tr.find('td:eq(2)').text();
        let roles = tr.find('td:eq(3)').text().split(',').map(r => r.trim());

        $('input[name="name"]').val(name);
        $('input[name="username"]').val(username);
        $('input[name="email"]').val(email);
       $('select[name="role[]"]').val(roles).trigger('change');

        $('#user-id').val(id);
        isEdit = true;

        // ubah teks tombol
        $('#btn-submit').text('Update').removeClass('btn-primary').addClass('btn-warning');
    });

    // Delete User
    $(document).on('click','.btn-delete',function(){
        if(!confirm('Hapus user ini?')) return;
        let tr = $(this).closest('tr');
        let id = tr.data('id');

        $.ajax({
            url: "/users/"+id,
            method: "POST",
            data: {_method:'DELETE', _token:"{{ csrf_token() }}"},
            success: function(res){
                $('#alert-area').html('<div id="alert-success" class="alert alert-success">'+res.message+'</div>');
                setTimeout(() => { $("#alert-success").fadeOut(500, () => $(this).remove()); }, 3000);
                tr.fadeOut(500, function(){ $(this).remove(); });
            }
        });
    });
});
</script>
@endpush