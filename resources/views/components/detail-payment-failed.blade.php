<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow rounded-4 p-5 bg-white">
                <div class="mb-4 text-danger">
                    <i class="bi bi-x-circle-fill display-1"></i>
                </div>
                <h2 class="fw-bold text-dark mb-2">Pembayaran Gagal</h2>
                <p class="text-secondary mb-4">Mohon maaf, transaksi Anda dibatalkan atau gagal diproses oleh sistem keuangan.</p>
                
                <div class="bg-light rounded-3 p-3 text-start mb-4" style="font-size: 0.9rem;">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Kode Booking:</span>
                        <span class="fw-bold text-dark">{{ $rental->kode_booking }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Kendala:</span>
                        <span class="fw-bold text-danger">Sesi Berakhir / Batalkan Manual</span>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ url('/payment/' . $rental->id) }}" class="btn btn-danger btn-md rounded-pill fw-bold">
                        <i class="bi bi-arrow-clockwise me-2"></i> Coba Bayar Lagi
                    </a>
                    <a href="{{ route('customer.orders') }}" class="btn btn-outline-secondary btn-md rounded-pill">
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>