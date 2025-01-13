<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Admin
        \App\Models\User::factory()->create([
            'name' => 'kepalacabang',
            'email' => 'kepalacabang@gmail.com',
            'password' => bcrypt('admin123'),
            'role' => 'superadmin',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'operasional',
            'email' => 'operasional@gmail.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'finance',
            'email' => 'finance@gmail.com',
            'password' => bcrypt('admin123'),
            'role' => 'user',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'sales',
            'email' => 'sales@gmail.com',
            'password' => bcrypt('admin123'),
            'role' => 'sales',
        ]);
    }
}
