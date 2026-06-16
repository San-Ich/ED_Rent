<?php

namespace App\Filament\Resources\Rentals\Schemas;

use App\Models\Motor;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Content;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class RentalForm
{
    public static function configure($schema)
    {
        return $schema
            ->columns(3)
            ->components([

                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required()
                    ->columnSpan(2),

                TextInput::make('total_harga')
                    ->label('Total Bayar')
                    ->numeric()
                    ->prefix('Rp')
                    ->readOnly()
                    ->dehydrated()
                    ->columnSpan(1),

                Select::make('motor_id')
                    ->relationship(
                        'motor',
                        'model',
                        fn(Builder $query, $get) =>
                        $query->where('status', 'tersedia')->orWhere('id', $get('motor_id'))
                    )
                    ->required()
                    ->live()
                    ->searchable()
                    ->preload()
                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->brand} - {$record->model} ({$record->plate_nomor})")
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
                        'Disewa' => 'Disewa',
                        'Selesai' => 'Selesai',
                        'Menunggu' => 'Menunggu',
                        'Gagal' => 'Gagal'
                    ])
                    ->required()
                    ->columnSpan(1),

                DatePicker::make('tanggal_pengembalian')
                    ->label('Tanggal Motor Kembali (Aktual)')
                    ->live()
                    ->afterStateUpdated(fn($set, $get) => self::calculateTotal($set, $get))
                    ->columnSpan(3),

                TextInput::make('kode_booking')
                    ->label('Kode Booking')
                    ->default(fn() => 'KBR-' . date('ymd') . '-' . strtoupper(Str::random(4)))
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->columnSpan(3),

                TextInput::make('perlengkapan_tambahan')
                    ->label('Keterangan Perlengkapan Tambahan :')
                    ->disabled()
                    ->dehydrated(false)
                    ->formatStateUsing(function ($record) {
                        if ($record && $record->perlengkapan) {
                            return $record->perlengkapan->pluck('nama_perlengkapan')->join(', ');
                        }
                        return 'Tidak ada perlengkapan tambahan yang dipilih.';
                    })
                    ->columnSpan(3),
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
                    $penalty = $hariTerlambat * 50000;
                }
            }

            $set('penalty', $penalty);
            $set('total_harga', $hargaSewa + $penalty);
        }
    }
}
