<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow rounded-4 p-5 bg-white">
                <div class="mb-4 text-success">
                    <i class="bi bi-check-circle-fill display-1"></i>
                </div>
                <h2 class="fw-bold text-dark mb-2">Pembayaran Berhasil!</h2>
                <p class="text-secondary mb-4">Terima kasih, pembayaran untuk booking motor Anda telah kami terima. Selamat berkendara bersama KudaBesiRent!</p>
                
                <div class="bg-light rounded-3 p-3 text-start mb-4" style="font-size: 0.9rem;">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Kode Booking:</span>
                        <span class="fw-bold text-dark">{{ $rental->kode_booking }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Unit Motor:</span>
                        <span class="fw-bold text-dark">{{ $rental->motor->brand }} {{ $rental->motor->model }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Total Bayar:</span>
                        <span class="fw-bold text-success">Rp {{ number_format($rental->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('customer.orders') }}" class="btn btn-dark btn-md rounded-pill fw-bold">
                        <i class="bi bi-box-seam me-2"></i> Lihat Daftar Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>