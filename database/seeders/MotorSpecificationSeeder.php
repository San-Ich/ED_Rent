<?php

namespace Database\Seeders;

use App\Models\Motor;
use App\Models\MotorSpecification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MotorSpecificationSeeder extends Seeder
{
    public function run(): void
    {
        // Data spesifikasi riil sesuai dengan model motor
        $specsData = [
            'Vario 160' => [
                'kapasitas_mesin' => '156.9 cc',
                'konfigurasi_silinder' => 'Tunggal, 4-Katup, SOHC, eSP+',
                'transmisi' => 'Otomatis (V-Matic)',
                'bahan_bakar_min' => 'Pertamax (RON 92)',
                'sistem_pengereman' => 'Depan Cakram (ABS/CBS), Belakang Cakram/Tromol',
                'tenaga_maksimum' => '15.1 HP / 8.500 rpm',
            ],
            'PCX' => [
                'kapasitas_mesin' => '156.9 cc',
                'konfigurasi_silinder' => 'Tunggal, 4-Katup, SOHC, eSP+',
                'transmisi' => 'Otomatis (V-Matic)',
                'bahan_bakar_min' => 'Pertamax (RON 92)',
                'sistem_pengereman' => 'Triple Pots Hydraulic (CBS) / Anti-lock Braking System (ABS)',
                'tenaga_maksimum' => '15.8 HP / 8.500 rpm',
            ],
            'Beat' => [
                'kapasitas_mesin' => '109.5 cc',
                'konfigurasi_silinder' => 'Tunggal, SOHC, 2-Katup, eSP',
                'transmisi' => 'Otomatis (V-Matic)',
                'bahan_bakar_min' => 'Pertalite (RON 90)',
                'sistem_pengereman' => 'Depan Cakram, Belakang Tromol (Combi Brake System)',
                'tenaga_maksimum' => '8.8 HP / 7.500 rpm',
            ],
            'CRF150L' => [
                'kapasitas_mesin' => '149.15 cc',
                'konfigurasi_silinder' => 'Tunggal, SOHC, 2-Katup',
                'transmisi' => 'Manual, 5-Percepatan',
                'bahan_bakar_min' => 'Pertalite (RON 90)',
                'sistem_pengereman' => 'Depan Wavy Disc Brake, Belakang Wavy Disc Brake',
                'tenaga_maksimum' => '12.7 HP / 8.000 rpm',
            ],
            'CB150R' => [
                'kapasitas_mesin' => '149.16 cc',
                'konfigurasi_silinder' => 'Tunggal, DOHC, 4-Katup',
                'transmisi' => 'Manual, 6-Percepatan',
                'bahan_bakar_min' => 'Pertamax (RON 92)',
                'sistem_pengereman' => 'Depan Wavy Disc Brake, Belakang Wavy Disc Brake',
                'tenaga_maksimum' => '16.6 HP / 9.000 rpm',
            ],
            'CBR150' => [
                'kapasitas_mesin' => '149.16 cc',
                'konfigurasi_silinder' => 'Tunggal, DOHC, 4-Katup (Assist/Slipper Clutch)',
                'transmisi' => 'Manual, 6-Percepatan',
                'bahan_bakar_min' => 'Pertamax (RON 92)',
                'sistem_pengereman' => 'Depan Cakram Hidrolik (Wave), Belakang Cakram Hidrolik',
                'tenaga_maksimum' => '16.8 HP / 9.000 rpm',
            ],
            'NMax' => [
                'kapasitas_mesin' => '155 cc',
                'konfigurasi_silinder' => 'Tunggal, SOHC, 4-Katup, VVA Blue Core',
                'transmisi' => 'Otomatis (V-Belt Automatic)',
                'bahan_bakar_min' => 'Pertamax (RON 92)',
                'sistem_pengereman' => 'Depan Cakram, Belakang Cakram (Dual Channel ABS)',
                'tenaga_maksimum' => '15.1 HP / 8.000 rpm',
            ],
            'Fazzio' => [
                'kapasitas_mesin' => '124.86 cc',
                'konfigurasi_silinder' => 'Tunggal, SOHC, 2-Katup, Blue Core Hybrid',
                'transmisi' => 'Otomatis (V-Belt Automatic)',
                'bahan_bakar_min' => 'Pertalite (RON 90)',
                'sistem_pengereman' => 'Depan Cakram, Belakang Tromol',
                'tenaga_maksimum' => '8.3 HP / 6.500 rpm',
            ],
            'Aerox' => [
                'kapasitas_mesin' => '155 cc',
                'konfigurasi_silinder' => 'Tunggal, SOHC, 4-Katup, VVA Blue Core',
                'transmisi' => 'Otomatis (V-Belt Automatic)',
                'bahan_bakar_min' => 'Pertamax (RON 92)',
                'sistem_pengereman' => 'Depan Cakram (ABS), Belakang Tromol',
                'tenaga_maksimum' => '15.1 HP / 8.000 rpm',
            ],
            'R15' => [
                'kapasitas_mesin' => '155.09 cc',
                'konfigurasi_silinder' => 'Tunggal, SOHC, 4-Katup, VVA (Traction Control System)',
                'transmisi' => 'Manual, 6-Percepatan (Quick Shifter)',
                'bahan_bakar_min' => 'Pertamax (RON 92)',
                'sistem_pengereman' => 'Depan Cakram Hidrolik, Belakang Cakram Hidrolik',
                'tenaga_maksimum' => '19.0 HP / 10.000 rpm',
            ],
            'Ninja ZX250R' => [
                'kapasitas_mesin' => '249.8 cc',
                'konfigurasi_silinder' => 'In-line 4 Silinder, DOHC, 16-Katup',
                'transmisi' => 'Manual, 6-Percepatan',
                'bahan_bakar_min' => 'Pertamax Turbo (RON 98)',
                'sistem_pengereman' => 'Depan Semi-Floating Disc ABS, Belakang Single Disc ABS',
                'tenaga_maksimum' => '50.3 HP / 15.500 rpm',
            ],
            'KLX' => [
                'kapasitas_mesin' => '144 cc',
                'konfigurasi_silinder' => 'Tunggal, SOHC, 2-Katup / Karburator',
                'transmisi' => 'Manual, 5-Percepatan',
                'bahan_bakar_min' => 'Pertalite (RON 90)',
                'sistem_pengereman' => 'Depan Semi-Floating Petal Disc, Belakang Petal Disc',
                'tenaga_maksimum' => '11.8 HP / 8.000 rpm',
            ],
            'Nex' => [
                'kapasitas_mesin' => '113 cc',
                'konfigurasi_silinder' => 'Tunggal, SOHC, 2-Katup, SEP (Suzuki Eco Performance)',
                'transmisi' => 'Otomatis (CVT)',
                'bahan_bakar_min' => 'Pertalite (RON 90)',
                'sistem_pengereman' => 'Depan Cakram, Belakang Tromol',
                'tenaga_maksimum' => '9.2 HP / 8.000 rpm',
            ],
            'Satria F150' => [
                'kapasitas_mesin' => '147.3 cc',
                'konfigurasi_silinder' => 'Tunggal, DOHC, 4-Katup, Liquid Cooled',
                'transmisi' => 'Manual, 6-Percepatan',
                'bahan_bakar_min' => 'Pertamax (RON 92)',
                'sistem_pengereman' => 'Depan Cakram (Petal), Belakang Cakram (Petal)',
                'tenaga_maksimum' => '18.2 HP / 10.000 rpm',
            ],
            'Scoopy' => [
                'kapasitas_mesin' => '109.5 cc',
                'konfigurasi_silinder' => 'Tunggal, SOHC, 2-Katup, eSP',
                'transmisi' => 'Otomatis (V-Matic)',
                'bahan_bakar_min' => 'Pertalite (RON 90)',
                'sistem_pengereman' => 'Depan Cakram Hidrolik, Belakang Tromol (CBS)',
                'tenaga_maksimum' => '8.8 HP / 7.500 rpm',
            ],
        ];

        foreach ($specsData as $modelName => $spec) {
            // Cari id motor berdasarkan kolom 'model' di database kamu
            // Gunakan LIKE untuk menghindari masalah case-sensitive kecil/besar
            $motor = Motor::where('model', 'LIKE', '%' . $modelName . '%')->first();

            // Jika motor ditemukan di database, buatkan spesifikasinya
            if ($motor) {
                MotorSpecification::updateOrCreate(
                    ['motor_id' => $motor->id], // Mencegah data duplikat jika dijalankan ulang
                    [
                        'kapasitas_mesin' => $spec['kapasitas_mesin'],
                        'konfigurasi_silinder' => $spec['konfigurasi_silinder'],
                        'transmisi' => $spec['transmisi'],
                        'bahan_bakar_min' => $spec['bahan_bakar_min'],
                        'sistem_pengereman' => $spec['sistem_pengereman'],
                        'tenaga_maksimum' => $spec['tenaga_maksimum'],
                    ]
                );
            }
        }

        $this->command->info('Spesifikasi riil untuk 15 motor berhasil ditambahkan!');
    }
}
