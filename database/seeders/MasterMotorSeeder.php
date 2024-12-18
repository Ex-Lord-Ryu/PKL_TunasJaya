<?php

namespace Database\Seeders;

use App\Models\MasterMotor;
use Illuminate\Database\Seeder;

class MasterMotorSeeder extends Seeder
{
    public function run()
    { 
        $motors = [
            ['nama_motor' => 'Honda Vario 125 CBS', 'nomor_mesin' => 'JMD1E', 'nomor_rangka' => 'MH1JMD1'],
            ['nama_motor' => 'Honda Vario 125 CBS ISS', 'nomor_mesin' => 'JMD1E', 'nomor_rangka' => 'MH1JMD1'],
            ['nama_motor' => 'Honda Vario 125 CBS ISS SP', 'nomor_mesin' => 'JMD1E', 'nomor_rangka' => 'MH1JMD1'],
            ['nama_motor' => 'Honda Scoopy Sporty', 'nomor_mesin' => 'JMH1E', 'nomor_rangka' => 'MH1JMH1'],
            ['nama_motor' => 'Honda Scoopy Fashion', 'nomor_mesin' => 'JMH1E', 'nomor_rangka' => 'MH1JMH1'],
            ['nama_motor' => 'Honda Scoopy Stylish', 'nomor_mesin' => 'JMH1E', 'nomor_rangka' => 'MH1JMH1'],
            ['nama_motor' => 'Honda Scoopy Prestige', 'nomor_mesin' => 'JMH1E', 'nomor_rangka' => 'MH1JMH1'],
            ['nama_motor' => 'Honda Beat CBS', 'nomor_mesin' => 'JMG1E', 'nomor_rangka' => 'MH1JMG1'],
            ['nama_motor' => 'Honda Beat Deluxe', 'nomor_mesin' => 'JMG1E', 'nomor_rangka' => 'MH1JMG1'],
            ['nama_motor' => 'Honda Beat Smart Key', 'nomor_mesin' => 'JMG1E', 'nomor_rangka' => 'MH1JMG1'],
            ['nama_motor' => 'Honda PCX160 CBS', 'nomor_mesin' => 'KFE1E', 'nomor_rangka' => 'MH1KFE1'],
            ['nama_motor' => 'Honda PCX160 ABS', 'nomor_mesin' => 'KFE1E', 'nomor_rangka' => 'MH1KFE1'],
            ['nama_motor' => 'Honda Vario 160 CBS', 'nomor_mesin' => 'KFA1E', 'nomor_rangka' => 'MH1KFA1'],
            ['nama_motor' => 'Honda Vario 160 CBS Grande', 'nomor_mesin' => 'KFA1E', 'nomor_rangka' => 'MH1KFA1'],
            ['nama_motor' => 'Honda Vario 160 ABS', 'nomor_mesin' => 'KFA1E', 'nomor_rangka' => 'MH1KFA1'],
            ['nama_motor' => 'Honda Beat Street CBS', 'nomor_mesin' => 'JFZ2E', 'nomor_rangka' => 'MH1JFZ2'],
            ['nama_motor' => 'Honda Stylo 160 CBS', 'nomor_mesin' => 'KFD1E', 'nomor_rangka' => 'MH1KFD11'],
            ['nama_motor' => 'Honda Stylo 160 ABS', 'nomor_mesin' => 'KFC1E', 'nomor_rangka' => 'MH1KFC11'],
            ['nama_motor' => 'Honda CRF150L Standard', 'nomor_mesin' => 'KD11E1', 'nomor_rangka' => 'MH1KD1'],
            ['nama_motor' => 'Honda ADV 160 CBS', 'nomor_mesin' => 'KFB1E', 'nomor_rangka' => 'MH1KFB1'],
            ['nama_motor' => 'Honda ADV 160 ABS', 'nomor_mesin' => 'KFB2E', 'nomor_rangka' => 'MH1KFB2'],
            ['nama_motor' => 'Honda Genio CBS', 'nomor_mesin' => 'JMA1E', 'nomor_rangka' => 'MH1JMB1'],
            ['nama_motor' => 'Honda Genio CBS ISS', 'nomor_mesin' => 'JMA1E', 'nomor_rangka' => 'MH1JMA1'],
            ['nama_motor' => 'Honda Supra GTR 150 Sporty', 'nomor_mesin' => 'JB4*E', 'nomor_rangka' => 'MH1KB11'],
            ['nama_motor' => 'Honda Supra GTR 150 Exclusive', 'nomor_mesin' => 'JB4*E', 'nomor_rangka' => 'MH1KB11'],
            ['nama_motor' => 'Honda Sonic 150R Racing Red', 'nomor_mesin' => 'KB11E', 'nomor_rangka' => 'MH1KB11'],
            ['nama_motor' => 'Honda Supra X 125 FI Spoke FI', 'nomor_mesin' => 'JB4*E', 'nomor_rangka' => 'MH1JB4'],
            ['nama_motor' => 'Honda Supra X 125 FI CW Luxury', 'nomor_mesin' => 'JB5*E', 'nomor_rangka' => 'MH1JB5'],
            ['nama_motor' => 'Honda CBR250RR Standard', 'nomor_mesin' => 'MC71E', 'nomor_rangka' => 'MH1MC71'],
            ['nama_motor' => 'Honda CBR250RR SP', 'nomor_mesin' => 'MC82E', 'nomor_rangka' => 'MH1MC82'],
            ['nama_motor' => 'Honda CBR150R Standard', 'nomor_mesin' => 'KCB1E', 'nomor_rangka' => 'MH1KCB1'],
            ['nama_motor' => 'Honda CBR150R Racing Red Standard', 'nomor_mesin' => 'KCC1E', 'nomor_rangka' => 'MH1KCC1'],
            ['nama_motor' => 'Honda CBR150R MotoGP Edition ABS', 'nomor_mesin' => 'KCB1E', 'nomor_rangka' => 'MH1KCB2'],
            ['nama_motor' => 'Honda CBR150R Racing Red ABS', 'nomor_mesin' => 'KCC1E', 'nomor_rangka' => 'MH1KCC2'],
            ['nama_motor' => 'Honda CBR150R ABS', 'nomor_mesin' => 'KCB1E', 'nomor_rangka' => 'MH1KCB3'],
            ['nama_motor' => 'Honda Revo Fit', 'nomor_mesin' => 'JBK1E', 'nomor_rangka' => 'MH1JBK1'],
            ['nama_motor' => 'Honda Revo X', 'nomor_mesin' => 'JBK2E', 'nomor_rangka' => 'MH1JBK2'],
            ['nama_motor' => 'Honda CB150 Verza Spoke', 'nomor_mesin' => 'KC51E', 'nomor_rangka' => 'MH1KC511'],
            ['nama_motor' => 'Honda CB150 Verza CW', 'nomor_mesin' => 'KC51E', 'nomor_rangka' => 'MH1KC511'],
            ['nama_motor' => 'Honda CB150R Streetfire Standard', 'nomor_mesin' => 'KCC1E', 'nomor_rangka' => 'MH1KCC2'],
            ['nama_motor' => 'Honda CB150R Streetfire Special Edition', 'nomor_mesin' => 'KCB1E', 'nomor_rangka' => 'MH1KCB3'],
            ['nama_motor' => 'Honda CB150X Standard', 'nomor_mesin' => 'KCE1E', 'nomor_rangka' => 'MH1KCE1'],
            ['nama_motor' => 'Honda Forza 250 Standard', 'nomor_mesin' => 'MD38E', 'nomor_rangka' => 'MLHMD44'],
            ['nama_motor' => 'Honda CRF250Rally Standard', 'nomor_mesin' => 'MD38E', 'nomor_rangka' => 'MLHMD44'],
            ['nama_motor' => 'Honda CB650R Standard', 'nomor_mesin' => 'RC74E', 'nomor_rangka' => 'MLHRC7'],
            ['nama_motor' => 'Honda CRF250L Standard', 'nomor_mesin' => 'MD38E', 'nomor_rangka' => 'MLHMD44'],
            ['nama_motor' => 'Honda EM1 E Electric', 'nomor_mesin' => 'EF22E', 'nomor_rangka' => 'MH1EF22'],
            ['nama_motor' => 'Honda EM1 E Plus', 'nomor_mesin' => 'EF21E', 'nomor_rangka' => 'MH1EF21'],
            ['nama_motor' => 'Honda Rebel Standard', 'nomor_mesin' => 'REB1E', 'nomor_rangka' => 'MH1REB1'],
            ['nama_motor' => 'Honda CB500X Standard', 'nomor_mesin' => 'PC44E', 'nomor_rangka' => 'MLHPC46'],
            ['nama_motor' => 'Honda Monkey Standard', 'nomor_mesin' => 'JB03E', 'nomor_rangka' => 'MLHJB039'],
            ['nama_motor' => 'Honda Super Cub 125 Standard', 'nomor_mesin' => 'JA48E', 'nomor_rangka' => 'MLHJA48'],
            ['nama_motor' => 'Honda CT125 Standard', 'nomor_mesin' => 'JA55E', 'nomor_rangka' => 'MLHJA559'],
            ['nama_motor' => 'Honda CBR1000RR-R STD', 'nomor_mesin' => 'CBR1E', 'nomor_rangka' => 'MH1CBR1'],
            ['nama_motor' => 'Honda ST125 Dax Standard', 'nomor_mesin' => 'JB04E', 'nomor_rangka' => 'MLHJB049'],
            ['nama_motor' => 'Honda Goldwing Standard', 'nomor_mesin' => 'GW11E', 'nomor_rangka' => 'MH1GW11'],
            ['nama_motor' => 'Honda CRF1100L Africa Twin Manual', 'nomor_mesin' => 'AF11E', 'nomor_rangka' => 'MH1AF11'],
            ['nama_motor' => 'Honda CRF1100L Africa Twin DCT', 'nomor_mesin' => 'AF12E', 'nomor_rangka' => 'MH1AF12'],
            ['nama_motor' => 'Honda XL750 Transalp Standard', 'nomor_mesin' => 'XL75E', 'nomor_rangka' => 'MH1XL75'],
            ['nama_motor' => 'Honda Rebel 1100 Standard', 'nomor_mesin' => 'RB11E', 'nomor_rangka' => 'MH1RB11'],
        ];

        foreach ($motors as $m) {
            MasterMotor::create($m);
        }
    }
}