<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // User Management
            'manage users',

            // infrastruktur
            'view infrastruktur', 'import infrastruktur', 'upload foto infrastruktur',

            // Air Baku
            'view airbaku', 'import airbaku', 'upload foto airbaku',

            // Benchmark
            'view benchmark', 'create benchmark', 'edit benchmark', 'delete benchmark', 'import benchmark',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // === Roles ===
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->syncPermissions(Permission::all());

        $infrastrukturManager = Role::firstOrCreate(['name' => 'Infrastruktur Manager']);
        $infrastrukturManager->syncPermissions(['view infrastruktur', 'import infrastruktur', 'upload foto infrastruktur']);

        $infrastrukturViewer = Role::firstOrCreate(['name' => 'Infrastruktur Viewer']);
        $infrastrukturViewer->syncPermissions(['view infrastruktur']);

        $airbakuManager = Role::firstOrCreate(['name' => 'Air Baku Manager']);
        $airbakuManager->syncPermissions(['view airbaku', 'import airbaku', 'upload foto airbaku']);

        $airbakuViewer = Role::firstOrCreate(['name' => 'Air Baku Viewer']);
        $airbakuViewer->syncPermissions(['view airbaku']);

        $benchmarkManager = Role::firstOrCreate(['name' => 'Benchmark Manager']);
        $benchmarkManager->syncPermissions(['view benchmark', 'create benchmark', 'edit benchmark', 'delete benchmark']);

        $benchmarkWriter = Role::firstOrCreate(['name' => 'Benchmark writer']);
        $benchmarkWriter->syncPermissions(['view benchmark', 'create benchmark', 'edit benchmark']);

        // === Assign ke user admin ===
        $userAdmin = \App\Models\User::where('email', 'admin@gmail.com')->first();
        if ($userAdmin) {
            $userAdmin->syncRoles(['Admin']);
        }

        $user1 = \App\Models\User::where('email', 'user1@gmail.com')->first();
        if ($user1) {
            $user1->syncRoles(['Infrastruktur Manager']);
        }

        $user2 = \App\Models\User::where('email', 'user2@gmail.com')->first();
        if ($user2) {
            $user2->syncRoles(['Air Baku Manager']);
        }
    }
}
