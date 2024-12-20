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
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
            'role' => 'superadmin',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'biji',
            'email' => 'biji@gmail.com',
            'password' => bcrypt('biji1234'),
            'role' => 'admin',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'user',
            'email' => 'joshevachristian@gmail.com',
            'password' => bcrypt('user1234'),
            'role' => 'user',
        ]);
    }
}
