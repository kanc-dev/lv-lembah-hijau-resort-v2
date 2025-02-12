<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            1 => 'Super Admin',
            2 => 'Admin',
            3 => 'PIC',
        ];

        foreach ($roles as $id => $name) {
            Role::updateOrCreate(['id' => $id], ['name' => $name]);
        }

        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@lhrh.me',
                'password' => Hash::make('password'),
                'branch_id' => null,
                'role_id' => 1, // Super Admin
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@lhrh.me',
                'password' => Hash::make('password'),
                'branch_id' => null,
                'role_id' => 2, // Admin
            ],
        ];

        $branches = [
            1 => 'bandung@lhrh.me',
            2 => 'yogyakarta@lhrh.me',
            3 => 'surabaya@lhrh.me',
            4 => 'padang@lhrh.me',
            5 => 'makasar@lhrh.me',
        ];

        foreach ($branches as $branchId => $email) {
            $users[] = [
                'name' => 'PIC ' . ucfirst(explode('@', $email)[0]),
                'email' => $email,
                'password' => Hash::make('password'),
                'branch_id' => $branchId,
                'role_id' => 3, // PIC
            ];
        }

        // Insert users and assign roles
        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                    'branch_id' => $userData['branch_id'],
                    'role_id' =>  $userData['branch_id']
                ]
            );

            // Attach role to user
            DB::table('user_role')->updateOrInsert([
                'user_id' => $user->id,
                'role_id' => $userData['role_id'],
            ]);
        }
    }
}
