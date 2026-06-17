@vite(['resources/css/payment-failed.css'])

<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center py-5 bg-light">

    <div class="row justify-content-center w-100 mx-0">
        <div class="col-10 col-sm-8 col-md-6 col-lg-4">
            <div
                class="card border-0 shadow-lg rounded-4 p-4 p-sm-5 text-center bg-white position-relative overflow-hidden animate__animated animate__fadeIn">

                <div class="position-absolute top-0 start-0 w-100"
                    style="height: 6px; background: linear-gradient(90deg, #dc3545, #fd7e14);"></div>

                <div class="mx-auto mb-4 d-flex align-items-center justify-content-center position-relative"
                    style="width: 100px; height: 100px;">
                    <div class="position-absolute w-100 h-100 bg-danger bg-opacity-20 rounded-circle"
                        style="animation: shake 0.5s ease-in-out;"></div>

                    <div class="bg-danger rounded-circle d-flex align-items-center justify-content-center shadow position-relative"
                        style="width: 80px; height: 80px; z-index: 2; animation: shake 0.5s ease-in-out;">
                        <div class="position-absolute bg-white rounded"
                            style="width: 6px; height: 36px; transform: rotate(45deg);"></div>
                        <div class="position-absolute bg-white rounded"
                            style="width: 6px; height: 36px; transform: rotate(-45deg);"></div>
                    </div>
                </div>

                <h3 class="fw-extrabold text-dark mb-2" style="letter-spacing: -0.5px;">Waktu Bayar Habis</h3>
                <p class="text-muted small mb-4 lh-base px-1">
                    Batas durasi transaksi Anda di payment gateway telah kedaluwarsa atau dibatalkan. Jangan khawatir,
                    dana saldo Anda aman tidak terpotong.
                </p>

                <div class="bg-light border border-dashed rounded-3 p-3 text-start mb-4"
                    style="font-size: 0.85rem; border-color: #dee2e6 !important;">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Kode Transaksi</span>
                        <span class="fw-bold font-monospace text-dark">{{ $rental->kode_booking }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Unit Kendaraan</span>
                        <span class="fw-semibold text-dark">{{ $rental->motor->brand }}
                            {{ $rental->motor->model }}</span>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('customer.orders.payment', $rental->id) }}"
                        class="btn btn-danger rounded-pill py-2.5 fw-bold shadow-sm animate__animated animate__pulse animate__infinite">
                        <i class="bi bi-arrow-counterclockwise me-2"></i>Coba Bayar Ulang
                    </a>

                    <a href="{{ route('customer.orders') }}"
                        class="btn btn-outline-secondary rounded-pill py-2.5 fw-semibold small text-secondary mt-1"
                        style="font-size: 0.85rem;">
                        Kembali ke Riwayat Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
