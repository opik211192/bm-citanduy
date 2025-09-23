@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="container my-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light py-2">
                    <strong>Data Role</strong>
                </div>
                <div class="card-body">

                    <!-- Alert -->
                    <div id="alert-area" class="mb-3"></div>

                    <!-- Form input role -->
                    <form id="form-role" class="mb-3">
                        @csrf
                        <input type="hidden" id="role-id" name="role_id">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-8">
                                <label for="role-name" class="form-label mb-1">Nama Role</label>
                                <input type="text" name="name" id="role-name" class="form-control form-control-sm"
                                    placeholder="Masukkan Nama Role" required>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" id="btn-submit" class="btn btn-sm btn-primary w-100">
                                    Tambah Role
                                </button>
                            </div>
                            <div class="col-md-2">
                                <button type="button" id="btn-cancel" class="btn btn-sm btn-secondary w-100 d-none">
                                    Batal
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Tabel role -->
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle" id="role-table">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Role</th>
                                    <th width="80">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $role)
                                <tr data-id="{{ $role->id }}">
                                    <td>{{ $role->name }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-dark btn-edit">
                                            <i class="fa fa-pencil" title="Edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger btn-delete">
                                            <i class="fa fa-trash" title="Hapus"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div>
    </div>

    {{-- Permission --}}
    <div class="col-md-6">
        <div class="container my-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light py-2">
                    <strong>Data Permission</strong>
                </div>
                <div class="card-body">

                    <!-- Alert -->
                    <div id="alert-permission" class="mb-3"></div>

                    <!-- Form input permission -->
                    <form id="form-permission" class="mb-3">
                        @csrf
                        <input type="hidden" id="permission-id" name="permission_id">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-8">
                                <label for="permission-name" class="form-label mb-1">Nama Permission</label>
                                <input type="text" name="name" id="permission-name" class="form-control form-control-sm"
                                    placeholder="Masukkan Nama Permission" required>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" id="btn-permission-submit" class="btn btn-sm btn-primary w-100">
                                    Tambah Permission
                                </button>
                            </div>
                            <div class="col-md-2">
                                <button type="button" id="btn-permission-cancel"
                                    class="btn btn-sm btn-secondary w-100 d-none">
                                    Batal
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Tabel permission -->
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle" id="permission-table">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Permission</th>
                                    <th width="80">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($permissions as $permission)
                                <tr data-id="{{ $permission->id }}">
                                    <td>{{ $permission->name }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-dark btn-edit-permission">
                                            <i class="fa fa-pencil" title="Edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger btn-delete-permission">
                                            <i class="fa fa-trash" title="Hapus"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div>
    </div>
</div>

<div class="container my-4">
    <div class="row g-3">
        <!-- Form Assign (kiri) -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <strong>Assign Permission ke Role</strong>
                </div>
                <div class="card-body">
                    <form id="form-assign">
                        @csrf
                        <div class="mb-3">
                            <label for="role-select" class="form-label">Pilih Role</label>
                            <select name="role_id" id="role-select" class="form-control" required>
                                <option value="">-- Pilih Role --</option>
                                @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pilih Permission</label>
                            <div class="form-check">
                                @foreach($permissions as $permission)
                                <div>
                                    <input class="form-check-input permission-checkbox" type="checkbox"
                                        name="permissions[]" value="{{ $permission->id }}"
                                        id="perm-{{ $permission->id }}">
                                    <label class="form-check-label" for="perm-{{ $permission->id }}">{{
                                        $permission->name }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-12" id="assign-btn-col">
                                <button type="submit" id="btn-assign-submit" class="btn btn-sm btn-primary w-100">
                                    Assign Permissions
                                </button>
                            </div>
                            <div class="col-md-6 d-none" id="assign-cancel-col">
                                <button type="button" id="btn-assign-cancel" class="btn btn-sm btn-secondary w-100">
                                    Batal
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabel Role + Permissions (kanan) -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <strong>Daftar Role + Permissions</strong>
                </div>
                <div class="card-body p-2">
                    <table class="table table-sm table-bordered align-middle" id="role-permission-table">
                        <thead class="table-light">
                            <tr>
                                <th>Role</th>
                                <th>Permissions</th>
                                <th width="80">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                            <tr data-id="{{ $role->id }}">
                                <td>{{ $role->name }}</td>
                                <td>
                                    @if($role->permissions->count())
                                    {{ $role->permissions->pluck('name')->implode(', ') }}
                                    @else
                                    <span class="text-muted">Belum ada permission</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-dark btn-edit-assign"><i class="fa fa-pencil"
                                            title="Edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger btn-delete-assign"><i
                                            class="fa fa-trash" title="Hapus"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function refreshPermissionsCheckbox() {
    $.get("{{ route('roles.form-check') }}", function(res){
        let container = $(".form-check");
        container.empty(); // kosongkan dulu
        
        res.permissions.forEach(function(p){
            let checkbox = `
            <div>
                <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]" value="${p.id}"
                    id="perm-${p.id}">
                <label class="form-check-label" for="perm-${p.id}">
                    ${p.name}
                </label>
            </div>`;
            container.append(checkbox);
            });
        });
    }
    

    function refreshRoleSelect() {
        $.get("{{ route('roles.get-roles') }}", function(res){
            let container = $("#role-select");
            container.empty(); // kosongkan dulu

            // Tambah option default
            container.append('<option value="">-- Pilih Role --</option>');

            res.roles.forEach(function(r){
                let option = `<option value="${r.id}">${r.name}</option>`;
                container.append(option);
            });
        });
    }

    function resetRoleForm() {
        $('#form-role')[0].reset();
        $('#role-id').val('');
        $('#btn-submit')
            .text('Tambah Role')
            .removeClass('btn-warning')
            .addClass('btn-primary');
        
        // Balikin col dari 2 ke 4
        let col = $('#btn-submit').closest('.col-md-2, .col-md-4');
        col.removeClass('col-md-2').addClass('col-md-4');

        // Sembunyikan tombol batal
        $('#btn-cancel').addClass('d-none');
    }

    function resetPermissionForm() {
        $('#form-permission')[0].reset();
        $('#permission-id').val('');
        $('#btn-permission-submit')
            .text('Tambah Permission')
            .removeClass('btn-warning')
            .addClass('btn-primary');

        // Balikin col dari 2 ke 4
        let col = $('#btn-permission-submit').closest('.col-md-2, .col-md-4');
        col.removeClass('col-md-2').addClass('col-md-4');

        // Sembunyikan tombol batal
        $('#btn-permission-cancel').addClass('d-none');
    }

    function resetAssignForm() {
        $('#form-assign')[0].reset();
        $('#btn-assign-submit')
        .text('Assign Permissions')
        .removeClass('btn-warning')
        .addClass('btn-primary');

        // Balikin ke col-md-12
        $('#assign-btn-col')
        .removeClass('col-md-6')
        .addClass('col-md-12');
        $('#assign-cancel-col').addClass('d-none');
    }


</script>

<script>
    // FORM ROLE
    $(function(){
    
    // Fungsi untuk menampilkan alert
    function showAlert(message, type = 'success') {
        let html = `<div class="alert alert-${type} fade show" role="alert">
            ${message}
        </div>`;
        $('#alert-area').html(html);
        
        // Hilangkan otomatis setelah 5 detik
        setTimeout(function(){
            $('#alert-area .alert').fadeOut(500, function(){ $(this).remove(); });
        }, 5000);
    }
    
    // Tambah / Update role
    $('#form-role').on('submit', function(e){
        e.preventDefault();
        let roleId = $('#role-id').val();
        let url = roleId ? '/roles/'+roleId : "{{ route('roles.store') }}";
        let method = roleId ? 'PUT' : 'POST';
        let data = $(this).serialize();
    
        $.ajax({
            url: url,
            type: 'POST',
            data: data + (method === 'PUT' ? '&_method=PUT' : ''),
            success: function(res){
                let role = res.role;
            
                if(method === 'POST'){
                    // Tambah baris baru di tabel
                    let newRow = `<tr data-id="${role.id}">
                        <td>${role.name}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-dark btn-edit"><i class="fa fa-pencil" title="Edit"></i></button>
                            <button class="btn btn-sm btn-outline-danger btn-delete"><i class="fa fa-trash" title="Hapus"></i></button>
                        </td>
                    </tr>`;
                    $('#role-table tbody').append(newRow);
                } else {
                    // Update baris yang ada
                    let tr = $('#role-table tbody').find(`tr[data-id='${role.id}']`);
                    tr.find('td:eq(0)').text(role.name);
                }
            
                // Reset form
                $('#form-role')[0].reset();
                $('#role-id').val('');
                $('#btn-submit').text('Tambah Role').removeClass('btn-warning').addClass('btn-primary');
                
                resetRoleForm();
                showAlert(res.message, 'success');
                refreshRoleSelect(); 

                },
                error: function(xhr){
                    let msg = '';
                    if(xhr.responseJSON && xhr.responseJSON.errors){
                        $.each(xhr.responseJSON.errors, function(k,v){ msg += v[0]+'<br>'; });
                    } else {
                        msg = 'Terjadi kesalahan server';
                    }
                    showAlert(msg, 'danger');
                }
            });
        });
        
        // Edit role
        $(document).on('click', '.btn-edit', function(){
            let tr = $(this).closest('tr');
            let id = tr.data('id');
            let name = tr.find('td:eq(0)').text();
        
            $('#role-name').val(name);
            $('#role-id').val(id);
            $('#btn-submit').text('Update').removeClass('btn-primary').addClass('btn-warning');
            $('#btn-submit').closest('.col-md-4').removeClass('col-md-4').addClass('col-md-2');
            $('#btn-cancel').removeClass('d-none');
            $('#role-name').focus();
        });
        
        // Klik Batal
        $(document).on('click', '#btn-cancel', function(){
            resetRoleForm();
        });

        // Delete role
        $(document).on('click', '.btn-delete', function(){
            if(!confirm('Hapus role ini?')) return;
            let tr = $(this).closest('tr');
            let id = tr.data('id');
        
            $.ajax({
                url: '/roles/'+id,
                type: 'POST',
                data: {_method:'DELETE', _token:"{{ csrf_token() }}"},
                success: function(res){
                    tr.remove();
                    showAlert(res.message, 'success');
                    refreshRoleSelect();
                },
                error: function(){
                        showAlert('Gagal menghapus role', 'danger');
                }
            });
        });    
    });
</script>

<script>
    // FORM PERMISSION
    $(function(){

    // Fungsi alert khusus permission
    function showPermissionAlert(message, type = 'success') {
        let html = `<div class="alert alert-${type} fade show" role="alert">${message}</div>`;
        $('#alert-permission').html(html);

        setTimeout(function(){
            $('#alert-permission .alert').fadeOut(500, function(){ $(this).remove(); });
        }, 5000);
    }

    // Tambah / Update Permission
    $('#form-permission').on('submit', function(e){
        e.preventDefault();

        let id = $('#permission-id').val();
        let url = id 
            ? `/roles/permissions/${id}`   // update
            : "{{ route('permissions.store') }}"; // create
        let method = id ? 'PUT' : 'POST';
        let data = $(this).serialize();

        $.ajax({
            url: url,
            type: 'POST',
            data: data + (method === 'PUT' ? '&_method=PUT' : ''),
            success: function(res){
                let permission = res.permission;

                if(method === 'POST'){
                    // Tambah row baru
                    let newRow = `<tr data-id="${permission.id}">
                        <td>${permission.name}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-dark btn-edit-permission"><i class="fa fa-pencil"></i></button>
                            <button class="btn btn-sm btn-outline-danger btn-delete-permission"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>`;
                    $('#permission-table tbody').append(newRow);
                } else {
                    // Update row
                    let tr = $('#permission-table tbody').find(`tr[data-id='${permission.id}']`);
                    tr.find('td:eq(0)').text(permission.name);
                }

                // Reset form
                $('#form-permission')[0].reset();
                $('#permission-id').val('');
                $('#btn-permission-submit').text('Tambah Permission')
                    .removeClass('btn-warning').addClass('btn-primary');

                resetPermissionForm();
                showPermissionAlert(res.message, 'success');
                refreshPermissionsCheckbox();
            },
            error: function(xhr){
                let msg = 'Terjadi kesalahan server';
                if(xhr.responseJSON && xhr.responseJSON.errors){
                    msg = Object.values(xhr.responseJSON.errors).join('<br>');
                }
                showPermissionAlert(msg, 'danger');
            }
        });
    });

    // Edit permission
    $(document).on('click', '.btn-edit-permission', function(){
        let tr = $(this).closest('tr');
        let id = tr.data('id');
        let name = tr.find('td:eq(0)').text();

        $('#permission-name').val(name);
        $('#permission-id').val(id);
        $('#btn-permission-submit').text('Update')
            .removeClass('btn-primary').addClass('btn-warning');

        $('#btn-permission-submit').closest('.col-md-4').removeClass('col-md-4').addClass('col-md-2');
        $('#btn-permission-cancel').removeClass('d-none');

        $('#permission-name').focus();
        
    });

    // Klik batal di form permission
    $(document).on('click', '#btn-permission-cancel', function(){
        resetPermissionForm();
    });

    // Delete permission
    $(document).on('click', '.btn-delete-permission', function(){
        if(!confirm("Yakin hapus permission ini?")) return;

        let tr = $(this).closest('tr');
        let id = tr.data('id');

        $.ajax({
            url: `/roles/permissions/${id}`,
            type: 'POST',
            data: {_token:"{{ csrf_token() }}", _method:"DELETE"},
            success: function(res){
                tr.remove();

                resetPermissionForm();
                showPermissionAlert(res.message, 'success');
                refreshPermissionsCheckbox();
            },
            error: function(){
                showPermissionAlert("Gagal menghapus permission", 'danger');
            }
        });
    });

});
</script>

<script>
    // FORM ASSIGN PERMISSION
    $(function(){

    // Assign Permission ke Role
    $('#form-assign').on('submit', function(e){
        e.preventDefault();
        let data = $(this).serialize();

        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "{{ route('roles.assign-permission') }}",
            type: 'POST',
            data: data,
            success: function(res){                
                alert(res.message);

                // Update tabel role + permissions
                let tr = $('#role-permission-table tbody').find(`tr[data-id='${res.role.id}']`);
                tr.find('td:eq(1)').text(res.role.permissions.length
                    ? res.role.permissions.map(p => p.name).join(', ')
                    : 'Belum ada permission');
            },
            error: function(xhr){
                alert('Gagal assign permissions');
            }
        });
    });

      // Tombol Edit Assign (isi form di kiri sesuai role + permission)
    $(document).on('click', '.btn-edit-assign', function(){
        let tr = $(this).closest('tr');
        let roleId = tr.data('id');
        let roleName = tr.find('td:eq(0)').text();

        // Set select role
        $('#role-select').val(roleId);

        // Set role name
        $('#role-select').focus();
        // Reset dulu semua checkbox permission
        $('.permission-checkbox').prop('checked', false);

        // Ambil permission dari text kolom kedua
        let perms = tr.find('td:eq(1)').text().split(',').map(p => p.trim());

        // Ceklist yang sesuai
        perms.forEach(function(p){
            $(".permission-checkbox").each(function(){
                if($(this).siblings('label').text().trim() === p){
                    $(this).prop('checked', true);
                }
            });
        });

        $('#btn-assign-submit').text('Update Assign').removeClass('btn-primary').addClass('btn-warning');

        // Ubah layout tombol jadi sejajar
        $('#assign-btn-col').removeClass('col-md-12').addClass('col-md-6');
        $('#assign-cancel-col').removeClass('d-none');
        
        $('#role-select').focus();
        // Scroll ke form assign biar kelihatan
        $('html, body').animate({
            scrollTop: $("#form-assign").offset().top - 100
        }, 500);
    });

    // Klik tombol batal
    $(document).on('click', '#btn-assign-cancel', function(){
        resetAssignForm();
    });

    // Tombol Hapus Assign (hapus semua permission dari role)
    $(document).on('click', '.btn-delete-assign', function(){
        if(!confirm("Hapus semua permission untuk role ini?")) return;
        let tr = $(this).closest('tr');
        let roleId = tr.data('id');

        $.ajax({
            url: "/roles/"+roleId+"/revoke-all", // bikin route revoke semua permission
            type: "POST",
            data: {_token:"{{ csrf_token() }}", _method:"DELETE"},
            success: function(res){
                tr.find('td:eq(1)').html('<span class="text-muted">Belum ada permission</span>');
                alert(res.message);
            },
            error: function(){
                alert("Gagal hapus permissions");
            }
        });
    });

});
</script>
@endpush