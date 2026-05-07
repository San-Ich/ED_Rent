@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">

    <section class="position-relative text-white" style="background-image: url('https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=1600&q=80'); background-size: cover; background-position: center; min-height: 80vh;">
        <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100"></div>
        <div class="container position-relative d-flex align-items-center" style="min-height: 80vh;">
            <div class="col-lg-7">
                <h1 class="display-4 fw-bold">Rental Motor Cepat & Aman,<br>Mulai Rp75.000/hari</h1>
                <p class="lead mt-3">Pilihan terlengkap, harga transparan, dan layanan 24 jam.</p>
                <a href="#" class="btn btn-primary btn-lg mt-3">Sewa Sekarang</a>
            </div>
        </div>
    </section>

    <section class="container py-5">
        <h2 class="section-title mb-3">Mulai Petualangan Anda dalam 3 Langkah Mudah</h2>
        <p class="text-muted mb-4">Tidak perlu repot! Proses penyewaan motor tercepat, paling aman, dan transparan.</p>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Pilih & Booking</h5>
                        <p class="card-text">Pilih motor impian Anda dan tentukan tanggal rental.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Konfirmasi & Pembayaran</h5>
                        <p class="card-text">Selesaikan pembayaran dan dapatkan konfirmasi cepat.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Ambil / Diantar</h5>
                        <p class="card-text">Ambil motor di lokasi kami atau minta diantar ke lokasi Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="section-title">Pilihan Motor Terpopuler</h2>
                <p class="text-muted">Pilih kendaraan yang paling sesuai dengan gaya perjalanan dan budget Anda.</p>
            </div>
            <a href="#" class="text-primary text-decoration-none">Lihat semua</a>
        </div>

        <div class="row g-4">
            @for ($i = 0; $i < 4; $i++)
            <div class="col-md-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Vega Force</h5>
                        <p class="text-primary fw-semibold">Matic</p>
                        <img src="https://via.placeholder.com/300x180" class="img-fluid mb-3" alt="Motor">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>Rp75.000/hari</strong>
                            <a href="#" class="btn btn-primary btn-sm">Booking</a>
                        </div>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </section>

    <section class="container py-5">
        <h2 class="section-title mb-3">Syarat & Ketentuan Rental Motor</h2>
        <ol class="text-muted">
            <li>Lakukan pemesanan minimal 1x24 jam sebelum penggunaan.</li>
            <li>Membawa kartu identitas dan nomor WhatsApp aktif.</li>
            <li>Data akan dicek sebelum kendaraan diserahkan.</li>
        </ol>
    </section>

</div>
@endsection