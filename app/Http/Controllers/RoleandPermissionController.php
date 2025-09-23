<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class RoleandPermissionController extends Controller
{
    // ------------------------ FORM ROLE  --------------------------------//

    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('backend.rolepermission.index', compact('roles', 'permissions'));
    }

    // Simpan role baru
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|unique:roles,name',
            ]);

            $role = Role::create(['name' => $request->name]);

            return response()->json(['message' => 'Role berhasil ditambahkan', 'role' => $role]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    // Update role
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles,name,'.$role->id,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $role->update(['name' => $request->name]);

        return response()->json(['message' => 'Role berhasil diupdate', 'role' => $role]);
    }

    // Hapus role
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json(['message' => 'Role berhasil dihapus']);
    }


    // ------------------------ FROM Permission  --------------------------------//
    public function storePermission(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name',
        ]);

        $permission = Permission::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);

        return response()->json([
            'message' => 'Permission berhasil ditambahkan',
            'permission' => $permission
        ]);
    }

    // Update permission
    public function updatePermission(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:permissions,name,'.$permission->id,
        ]);

        $permission->update(['name' => $request->name]);

        return response()->json([
            'message' => 'Permission berhasil diupdate',
            'permission' => $permission
        ]);
    }

    // Hapus permission
    public function destroyPermission($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return response()->json([
            'message' => 'Permission berhasil dihapus'
        ]);
    }
    


    // ------------------------ ASSIGN PERMISSISON  --------------------------------//
    public function assignPermission(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role = Role::findOrFail($request->role_id);

        // Ambil nama permission hanya yang guard = web
        $permissions = Permission::whereIn('id', $request->permissions ?? [])
            ->where('guard_name', 'web')
            ->pluck('name')
            ->toArray();

        $role->syncPermissions($permissions);

        $role->load('permissions:id,name');

        return response()->json([
            'message' => 'Permissions berhasil di-assign!',
            'role' => [
                'id' => $role->id,
                'name' => $role->name,
                'permissions' => $role->permissions->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'name' => $p->name,
                    ];
                })
            ]
        ]);
    }



    public function revokeAll($id)
    {
        $role = Role::findOrFail($id);

        // Lepas semua permission
        $role->permissions()->detach();

        return response()->json([
            'message' => 'Semua permissions berhasil dihapus',
            'role_id' => $role->id
        ]);
    }

    //untuk update select option role
    public function getRoles()
    {
        $roles = Role::all();
        return response()->json(['roles' => $roles]);
    }


    //untuk update checkbox otomatis
    public function formCheck()
    {
        $permissions = Permission::all();
        return response()->json(['permissions' => $permissions]);
    }
}
