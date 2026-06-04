@extends('layouts.app')

@section('tittle', 'KudaBesiRent | Daftar')

@section('content')
    <div class="container d-flex justify-content-center align-items-center min-vh-100"
        style="margin-top: -30px; margin-bottom: 50px;">
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="max-width: 500px; width: 100%;">
            <div class="card-body p-5">

                <div class="text-center mb-4">
                    <x-application-logo class="mb-3" />
                    <h4 class="fw-bold text-dark mt-3">Buat Akun Baru</h4>
                    <p class="text-muted small">Daftar sekarang untuk mulai sewa motor impianmu</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="form-floating mb-3">
                        <input type="text" id="name" name="name"
                            class="form-control @error('name') is-invalid @enderror" placeholder="Nama Lengkap"
                            value="{{ old('name') }}" required autofocus autocomplete="name">
                        <label for="name" class="text-muted"><i class="bi bi-person me-2"></i>Nama Lengkap</label>

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-floating mb-3">
                        <input type="email" id="email" name="email"
                            class="form-control @error('email') is-invalid @enderror" placeholder="name@example.com"
                            value="{{ old('email') }}" required autocomplete="username">
                        <label for="email" class="text-muted"><i class="bi bi-envelope me-2"></i>Alamat Email</label>

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" id="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" placeholder="Password" required
                            autocomplete="new-password">
                        <label for="password" class="text-muted"><i class="bi bi-lock me-2"></i>Password</label>

                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-floating mb-4">
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="form-control @error('password_confirmation') is-invalid @enderror"
                            placeholder="Konfirmasi Password" required autocomplete="new-password">
                        <label for="password_confirmation" class="text-muted"><i
                                class="bi bi-shield-check me-2"></i>Konfirmasi Password</label>

                        @error('password_confirmation')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-carbon w-100 py-2.5 rounded-pill fw-bold shadow-sm mb-3">
                        <i class="bi bi-person-plus me-2"></i> Daftar Akun
                    </button>

                    <div class="text-center mt-4">
                        <span class="text-muted small">Sudah punya akun? </span>
                        <a href="{{ route('login') }}" class="text-warning fw-bold small text-decoration-none">Masuk di
                            sini</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
