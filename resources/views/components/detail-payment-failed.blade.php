<div class="container min-vh-100 d-flex align-items-center justify-content-center bg-white">

    <div class="card border-light-subtle rounded-0 p-4 p-sm-5 text-center shadow-sm"
        style="max-width: 450px; width: 100%;">



        <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4"
            style="width: 70px; height: 70px; font-size: 28px;">

            <i class="bi bi-x-lg"></i>

        </div>



        <h3 class="fw-bold text-black mb-2" style="letter-spacing: -0.5px;">Waktu Pembayaran Habis</h3>

        <p class="text-muted small mb-4 lh-base">

            Batas durasi transaksi online Anda pada sistem Midtrans telah kedaluwarsa atau dibatalkan. Jangan khawatir,
            dana Anda tidak terpotong.

        </p>



        <div class="bg-light p-3 mb-4 rounded-3 text-start small">

            <div class="d-flex justify-content-between mb-2">

                <span class="text-secondary">Kode Transaksi</span>

                <span class="fw-bold text-dark">{{ $rental->kode_booking }}</span>

            </div>

            <div class="d-flex justify-content-between">

                <span class="text-secondary">Unit Kendaraan</span>

                <span class="fw-semibold text-dark">{{ $rental->motor->model }}</span>

            </div>

        </div>



        <div class="d-grid gap-2">

            <a href="{{ route('customer.orders.payment', $rental->id) }}"
                class="btn btn-dark rounded-pill py-2.5 fw-semibold shadow-sm border-0">

                <i class="bi bi-arrow-counterclockwise me-2"></i>Coba Bayar Ulang

            </a>



            <a href="{{ route('customer.orders') }}"
                class="btn btn-outline-dark rounded-pill py-2.5 fw-semibold border-light-subtle text-dark">

                Kembali ke Riwayat Pesanan

            </a>

        </div>

    </div>

</div>
