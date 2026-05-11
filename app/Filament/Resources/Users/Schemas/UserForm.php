<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Dasar')
                    ->description('Data utama akun pengguna')
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context): bool => $context === 'create')
                            ->label(fn(string $context): string => $context === 'edit' ? 'Ubah Password' : 'Password'),
                        Select::make('role')
                            ->options(['admin' => 'Admin', 'user' => 'User'])
                            ->default('user')
                            ->required(),
                        TextInput::make('phone')
                            ->tel(),
                        Textarea::make('address')
                            ->columnSpanFull(),
                    ])->columns(2),
                        TextInput::make('rental_limit')
                            ->label('Limit Sewa Aktif')
                            ->numeric()
                            ->default(2)
                            ->minValue(2)
                            ->helperText('Jumlah maksimal motor yang bisa disewa secara bersamaan.'),
                Section::make('Verifikasi Identitas')
                    ->description('Dokumen pendukung untuk validasi penyewa motor')
                    ->schema([
                        FileUpload::make('ktp_path')
                            ->label('Foto KTP')
                            ->image()
                            ->directory('identitas-ktp')
                            ->visibility('public')
                            ->columnSpan(1),

                        FileUpload::make('sim_path')
                            ->label('Foto SIM C')
                            ->image()
                            ->directory('identitas-sim')
                            ->columnSpan(1),

                        Toggle::make('is_verified')
                            ->label('Verifikasi Identitas User')
                            ->onColor('success')
                            ->helperText('Aktifkan jika KTP dan SIM sudah dicek keasliannya.')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
