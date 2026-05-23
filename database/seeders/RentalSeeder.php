<?php

namespace Database\Seeders;

use App\Models\Motor;
use App\Models\Rental;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RentalSeeder extends Seeder
{
    public function run(): void
    {
        $userIds = User::pluck('id')->toArray();
        $motorIds = Motor::pluck('id')->toArray();

        if (empty($userIds) || empty($motorIds)) {
            $this->command->warn("Data User atau Motor kosong. Silakan isi dulu sebelum menjalankan RentalSeeder.");
            return;
        }

        for ($i = 0; $i < 50; $i++) {
            $mulai = Carbon::create(2026, 1, 1)->addDays(rand(0, 135));
            $durasi = rand(1, 5);
            $rencanaKembali = (clone $mulai)->addDays($durasi);

            $statusOptions = ['selesai', 'selesai', 'selesai', 'dipesan', 'dibatalkan'];
            $status = $statusOptions[array_rand($statusOptions)];

            $pengembalian = ($status === 'selesai') ? $rencanaKembali : null;

            $totalHarga = rand(50000, 500000);

            Rental::create([
                'user_id' => $userIds[array_rand($userIds)],
                'motor_id' => $motorIds[array_rand($motorIds)],
                'tanggal_mulai' => $mulai,
                'tanggal_rencana_kembali' => $rencanaKembali,
                'tanggal_pengembalian' => $pengembalian,
                'total_harga' => $totalHarga,
                'penalty' => ($status === 'selesai' && rand(0, 10) > 8) ? 50000 : 0,
                'status' => $status,
                'created_at' => $mulai,
                'updated_at' => $mulai,
            ]);
        }

        $this->command->info("50 data dummy rental berhasil dibuat!");
    }
}
