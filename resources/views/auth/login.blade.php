@extends('layouts.app')

@section('tittle', 'KudaBesiRent | Masuk')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100" style="margin-top: -50px;">
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="max-width: 450px; width: 100%;">
        <div class="card-body p-5">
            
            <div class="text-center mb-4">
                <x-application-logo class="mb-3" />
                <h4 class="fw-bold text-dark mt-3">Selamat Datang Kembali</h4>
                <p class="text-muted small">Silakan masuk ke akun KudaBesiRent kamu</p>
            </div>

            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 small" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- FORM LOGIN -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-floating mb-3">
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           placeholder="name@example.com" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus 
                           autocomplete="username">
                    <label for="email" class="text-muted"><i class="bi bi-envelope me-2"></i>Alamat Email</label>
                    
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-floating mb-3">
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           placeholder="Password" 
                           required 
                           autocomplete="current-password">
                    <label for="password" class="text-muted"><i class="bi bi-lock me-2"></i>Password</label>
                    
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="d-flex justify-content-between align-items-center mb-4 small">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                        <label class="form-check-label text-secondary" for="remember_me">
                            Ingat saya
                        </label>
                    </div>
                    @if (Route::has('password.request'))
                        <a class="text-warning text-decoration-none fw-semibold" href="{{ route('password.request') }}">
                            Lupa Password?
                        </a>
                    @endif
                </div>

                <button type="submit" class="btn btn-carbon w-100 py-2.5 rounded-pill fw-bold shadow-sm mb-3">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Masuk Sekarang
                </button>

                <div class="text-center mt-4">
                    <span class="text-muted small">Belum punya akun? </span>
                    <a href="{{ route('register') }}" class="text-warning fw-bold small text-decoration-none">Daftar di sini</a>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection