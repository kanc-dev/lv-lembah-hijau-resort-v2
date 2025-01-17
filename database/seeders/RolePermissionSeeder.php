<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Permissions;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = Role::create(['name' => 'Super Admin']);
        $admin = Role::create(['name' => 'Admin']);
        $pic = Role::create(['name' => 'PIC']);
        $staff = Role::create(['name' => 'Staf']);

        // Permissions
        $permissions = [
            'View Dashboard',
            'Manage Users',
            'Manage Roles',
            'View Reports',
            'Manage Bookings',
            'Manage Events',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign all permissions to Super Admin
        $superAdmin->permissions()->attach(Permission::all());
    }
}
