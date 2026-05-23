<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
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
                            ->live()
                            ->imageEditor()
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                            ->validationMessages([
                                'max' => 'Ukuran foto KTP terlalu besar, maksimal 2MB.',
                                'accepted_file_types' => 'Format harus JPG atau PNG.',
                            ])
                            ->directory('identitas-ktp')
                            ->visibility('private')
                            ->getUploadedFileNameForStorageUsing(function ($file) {
                                return "ktp-" . Auth::id() . "-" . time() . "." . $file->getClientOriginalExtension();
                            })
                            ->afterStateUpdated(function (Set $set) {
                                $set('is_verified', false);
                            })
                            ->previewable(true)
                            ->downloadable()
                            ->columnSpan(1),

                        FileUpload::make('sim_path')
                            ->label('Foto SIM C')
                            ->image()
                            ->maxSize(2048)
                            ->directory('identitas-sim')
                            ->visibility('private')
                            ->helperText('Pastikan masa berlaku SIM masih aktif.'),

                        Toggle::make('is_verified')
                            ->label('Verifikasi Identitas User')
                            ->onColor('success')
                            ->helperText('Aktifkan jika KTP dan SIM sudah dicek keasliannya.')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
