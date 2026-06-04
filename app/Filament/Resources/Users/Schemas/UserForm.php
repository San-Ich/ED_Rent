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
                // INFORMASI DASAR
                Section::make('Informasi Dasar')
                    ->description('Data utama akun pengguna')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('Alamat Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),

                        TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context): bool => $context === 'create')
                            ->label(fn(string $context): string => $context === 'edit' ? 'Ubah Password (Kosongkan jika tidak diganti)' : 'Password'),

                        Select::make('role')
                            ->label('Hak Akses / Role')
                            ->options([
                                'admin' => 'Admin',
                                'user' => 'User',
                            ])
                            ->default('user')
                            ->required(),

                        TextInput::make('phone')
                            ->label('Nomor WhatsApp')
                            ->tel()
                            ->maxLength(20),

                        TextInput::make('rental_limit')
                            ->label('Limit Sewa Aktif')
                            ->numeric()
                            ->default(15)
                            ->minValue(1)
                            ->helperText('Jumlah maksimal hari / motor yang diizinkan untuk disewa.'),

                        Textarea::make('address')
                            ->label('Alamat Lengkap')
                            ->columnSpanFull(),
                    ])->columns(2),

                // VERIFIKASI IDENTITAS
                Section::make('Verifikasi Identitas')
                    ->description('Dokumen pendukung untuk validasi penyewa motor')
                    ->schema([
                        FileUpload::make('ktp_path')
                            ->label('Foto KTP')
                            ->image()
                            ->live()
                            ->imageEditor()
                            ->maxSize(2048)
                            ->disk('public')
                            ->directory('identity/ktp')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp'])
                            ->validationMessages([
                                'max' => 'Ukuran foto KTP terlalu besar, maksimal 2MB.',
                                'accepted_file_types' => 'Format harus JPG, PNG, atau WEBP.',
                            ])
                            ->getUploadedFileNameForStorageUsing(function ($file, $get) {
                                $userId = $get('id') ?? time();
                                return "ktp-" . $userId . "-" . time() . "." . $file->getClientOriginalExtension();
                            })
                            ->afterStateUpdated(function (Set $set) {
                                $set('is_verified', false);
                            })
                            ->previewable(true)
                            ->downloadable(),

                        FileUpload::make('sim_path')
                            ->label('Foto SIM C')
                            ->image()
                            ->live()
                            ->imageEditor()
                            ->maxSize(2048)
                            ->disk('public')
                            ->directory('identity/sim')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp'])
                            ->validationMessages([
                                'max' => 'Ukuran foto SIM terlalu besar, maksimal 2MB.',
                                'accepted_file_types' => 'Format harus JPG, PNG, atau WEBP.',
                            ])
                            ->getUploadedFileNameForStorageUsing(function ($file, $get) {
                                $userId = $get('id') ?? time();
                                return "sim-" . $userId . "-" . time() . "." . $file->getClientOriginalExtension();
                            })
                            ->afterStateUpdated(function (Set $set) {
                                $set('is_verified', false);
                            })
                            ->helperText('Pastikan masa berlaku SIM masih aktif.')
                            ->previewable(true)
                            ->downloadable(),

                        Toggle::make('is_verified')
                            ->label('Verifikasi Identitas User')
                            ->onColor('success')
                            ->offColor('danger')
                            ->helperText('Aktifkan jika foto KTP dan SIM di atas sudah diperiksa keasliannya.')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
