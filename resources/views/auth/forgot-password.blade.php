@extends('layouts.app')

@section('tittle', 'KudaBesiRent | Lupa Password')

@section('content')
    <div class="container d-flex justify-content-center align-items-center min-vh-100" style="margin-top: -50px;">
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="max-width: 450px; width: 100%;">
            <div class="card-body p-5">

                <div class="text-center mb-4">
                    <x-application-logo class="mb-3" />
                    <h4 class="fw-bold text-dark mt-3">Pulihkan Password</h4>
                    <p class="text-muted small">
                        Jangan khawatir, Masukkan alamat email kamu di bawah ini, dan kami akan mengirimkan tautan untuk
                        mengatur ulang password barumu.
                    </p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show rounded-3 small mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="form-floating mb-4">
                        <input type="email" id="email" name="email"
                            class="form-control @error('email') is-invalid @enderror" placeholder="name@example.com"
                            value="{{ old('email') }}" required autofocus>
                        <label for="email" class="text-muted"><i class="bi bi-envelope me-2"></i>Alamat Email
                            Akun</label>

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-carbon w-100 py-2.5 rounded-pill fw-bold shadow-sm mb-3">
                        <i class="bi bi-send me-2"></i> Kirim Link Reset Password
                    </button>

                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" class="text-secondary fw-semibold small text-decoration-none">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Halaman Masuk
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
