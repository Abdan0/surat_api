<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            'name' => 'Admin Fakultas',
            'nidn' => '000000000000001',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);

        User::create([
            'name' => 'Dekan FT',
            'nidn' => '000000000000002',
            'role' => 'dekan',
            'password' => Hash::make('dekan123'),
        ]);

        User::create([
            'name' => 'Wakil Dekan I',
            'nidn' => '000000000000003',
            'role' => 'wakil dekan',
            'password' => Hash::make('wakildekan123'),
        ]);

        User::create([
            'name' => 'Staff Akademik',
            'nidn' => '000000000000004',
            'role' => 'staff',
            'password' => Hash::make('staff123'),
        ]);
    }
}
