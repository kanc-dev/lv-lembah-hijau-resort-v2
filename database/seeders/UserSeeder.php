<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@lhrh.me',
            'password' => Hash::make('password'), // Ganti password sesuai kebutuhan
            'branch_id' => null, // Bisa melihat semua cabang
        ]);

        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@lhrh.me',
            'password' => Hash::make('password'), // Ganti password sesuai kebutuhan
            'branch_id' => null, // Bisa melihat semua cabang
        ]);

        // PIC untuk masing-masing cabang
        $branches = [
            1 => 'bandung@lhrh.me',
            2 => 'yogyakarta@lhrh.me',
            3 => 'surabaya@lhrh.me',
            4 => 'padang@lhrh.me',
            5 => 'makasar@lhrh.me',
        ];

        foreach ($branches as $branchId => $email) {
            User::create([
                'name' => 'PIC ' . ucfirst(explode('@', $email)[0]),
                'email' => $email,
                'password' => Hash::make('password'), // Ganti password sesuai kebutuhan
                'branch_id' => $branchId,
            ]);
        }
    }
}
