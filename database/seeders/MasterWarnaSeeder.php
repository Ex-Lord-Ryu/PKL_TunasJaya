<?php

namespace Database\Seeders;

use App\Models\MasterWarna;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MasterWarnaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warna = [
            ['id_warna' => 'clr_black', 'nama_warna' => 'Black'],
            ['id_warna' => 'clr_red', 'nama_warna' => 'Red'],
            ['id_warna' => 'clr_matte_black', 'nama_warna' => 'Matte Black'],
            ['id_warna' => 'clr_white', 'nama_warna' => 'White'],
            ['id_warna' => 'clr_silver', 'nama_warna' => 'Silver'],
            ['id_warna' => 'clr_blue', 'nama_warna' => 'Blue'],
            ['id_warna' => 'clr_matte_blue', 'nama_warna' => 'Matte Blue'],
            ['id_warna' => 'clr_green', 'nama_warna' => 'Green'],
            ['id_warna' => 'clr_yellow', 'nama_warna' => 'Yellow'],
            ['id_warna' => 'clr_orange', 'nama_warna' => 'Orange'],
            ['id_warna' => 'clr_pearl_white', 'nama_warna' => 'Pearl White'],
            ['id_warna' => 'clr_repsol_edition', 'nama_warna' => 'Repsol Edition'],
            ['id_warna' => 'clr_racing_red', 'nama_warna' => 'Racing Red'],
            ['id_warna' => 'clr_matte_brown', 'nama_warna' => 'Matte Brown'],
            ['id_warna' => 'clr_prestige_silver', 'nama_warna' => 'Prestige Silver'],
            ['id_warna' => 'clr_candy_red', 'nama_warna' => 'Candy Red'],
            ['id_warna' => 'clr_matte_green', 'nama_warna' => 'Matte Green'],
            ['id_warna' => 'clr_glossy_blue', 'nama_warna' => 'Glossy Blue'],
            ['id_warna' => 'clr_dark_green_matte', 'nama_warna' => 'Dark Green Matte'],
            ['id_warna' => 'clr_urban_titanium', 'nama_warna' => 'Urban Titanium'],
            ['id_warna' => 'clr_pearl_nightfall_blue', 'nama_warna' => 'Pearl Nightfall Blue'],
            ['id_warna' => 'clr_matte_gunpowder_black', 'nama_warna' => 'Matte Gunpowder Black'],
        ];

        foreach ($warna as $color) {
            MasterWarna::create($color);
        }
    }
    
}
