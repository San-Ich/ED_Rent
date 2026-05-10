<?php

namespace App\Filament\Resources\Rentals\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use App\Models\Motor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class RentalForm
{
    public static function configure($schema)
    {
        // Kita gunakan ->columns(3) langsung pada schema utama
        // Ini akan membagi form menjadi 3 kolom tanpa butuh class Grid
        return $schema
            ->columns(3)
            ->components([

                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required()
                    ->columnSpan(2), // Mengambil 2 kolom di kiri

                TextInput::make('total_harga')
                    ->label('Total Bayar')
                    ->numeric()
                    ->prefix('Rp')
                    ->readOnly()
                    ->dehydrated()
                    ->columnSpan(1), // 1 kolom di kanan

                Select::make('motor_id')
                    ->relationship(
                        'motor',
                        'model',
                        fn(Builder $query, $get) =>
                        $query->where('status', 'tersedia')->orWhere('id', $get('motor_id'))
                    )
                    ->required()
                    ->live()
                    ->preload()
                    ->afterStateUpdated(fn($set, $get) => self::calculateTotal($set, $get))
                    ->columnSpan(2),

                TextInput::make('penalty')
                    ->label('Denda Keterlambatan')
                    ->numeric()
                    ->prefix('Rp')
                    ->readOnly()
                    ->dehydrated()
                    ->columnSpan(1),

                DatePicker::make('tanggal_mulai')
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn($set, $get) => self::calculateTotal($set, $get))
                    ->columnSpan(1),

                DatePicker::make('tanggal_rencana_kembali')
                    ->required()
                    ->live()
                    ->after('tanggal_mulai')
                    ->afterStateUpdated(fn($set, $get) => self::calculateTotal($set, $get))
                    ->columnSpan(1),

                Select::make('status')
                    ->options([
                        'tersedia' => 'Tersedia',
                        'dipesan' => 'Dipesan',
                        'selesai' => 'Selesai',
                        'dibatalkan' => 'Dibatalkan',
                    ])
                    ->required()
                    ->columnSpan(1),

                DatePicker::make('tanggal_pengembalian')
                    ->label('Tanggal Motor Kembali (Aktual)')
                    ->live()
                    ->afterStateUpdated(fn($set, $get) => self::calculateTotal($set, $get))
                    ->columnSpan(3), // Baris baru penuh di bawah
            ]);
    }

    public static function calculateTotal($set, $get): void
    {
        $motorId = $get('motor_id');
        $tglMulai = $get('tanggal_mulai');
        $tglRencanaKembali = $get('tanggal_rencana_kembali');
        $tglKembaliAsli = $get('tanggal_pengembalian');

        if ($motorId && $tglMulai && $tglRencanaKembali) {
            $motor = Motor::find($motorId);
            if (!$motor) return;

            $start = Carbon::parse($tglMulai);
            $rencanaKembali = Carbon::parse($tglRencanaKembali);

            $durasiSewa = $start->diffInDays($rencanaKembali) ?: 1;
            $hargaSewa = $durasiSewa * $motor->harga_per_hari;

            $penalty = 0;
            if ($tglKembaliAsli) {
                $kembaliAsli = Carbon::parse($tglKembaliAsli);
                if ($kembaliAsli->greaterThan($rencanaKembali)) {
                    $hariTerlambat = $rencanaKembali->diffInDays($kembaliAsli);
                    $penalty = $hariTerlambat * 50000; // Contoh denda
                }
            }

            $set('penalty', $penalty);
            $set('total_harga', $hargaSewa + $penalty);
        }
    }
}
