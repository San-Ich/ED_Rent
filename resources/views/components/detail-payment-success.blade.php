@vite(['resources/css/payment-success.css'])
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center py-5 bg-light">

    <div class="row justify-content-center w-100 mx-0">
        <div class="col-10 col-sm-8 col-md-6 col-lg-4">
            <div
                class="card border-0 shadow-lg rounded-4 p-4 p-sm-5 text-center bg-white position-relative overflow-hidden animate__animated animate__fadeIn">

                <div class="position-absolute top-0 start-0 w-100"
                    style="height: 6px; background: linear-gradient(90deg, #2ec4b6, #20bf55);"></div>

                <div class="mx-auto mb-4 d-flex align-items-center justify-content-center position-relative"
                    style="width: 100px; height: 100px;">
                    <div class="position-absolute w-100 h-100 bg-success bg-opacity-20 rounded-circle animate-pulse"
                        style="animation: pulse 1.8s infinite ease-in-out;"></div>

                    <div class="bg-success rounded-circle d-flex align-items-center justify-content-center shadow"
                        style="width: 80px; height: 80px; z-index: 2;">
                        <div
                            style="width: 22px; height: 38px; border: solid white; border-width: 0 5px 5px 0; transform: rotate(45deg); margin-top: -5px; margin-left: 2px; animation: drawCheck 0.5s ease-out;">
                        </div>
                    </div>
                </div>

                <h2 class="fw-extrabold text-dark mb-2" style="letter-spacing: -0.5px;">Pembayaran Berhasil!</h2>
                <p class="text-secondary small mb-4 px-2">
                    Terima kasih, pembayaran Anda telah diverifikasi oleh sistem. Unit motor siap digas. Selamat
                    berkendara bersama <span class="fw-bold text-dark">KudaBesiRent</span>!
                </p>

                <div class="bg-light border border-dashed rounded-3 p-3 text-start mb-4"
                    style="font-size: 0.85rem; border-color: #dee2e6 !important;">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Kode Booking</span>
                        <span
                            class="fw-bold font-monospace text-dark bg-secondary bg-opacity-10 px-2 py-0.5 rounded">{{ $rental->kode_booking }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Unit Motor</span>
                        <span class="fw-bold text-dark"><i class="bi bi-bicycle me-1 text-secondary"></i>
                            {{ $rental->motor->brand }} {{ $rental->motor->model }}</span>
                    </div>
                    <hr class="my-2 text-muted opacity-25">
                    <div class="d-flex justify-content-between align-items-center pt-1">
                        @if (request()->query('type') === 'denda')
                            <span class="text-muted fw-medium">Total Denda Dibayar</span>
                            <span class="fw-extrabold fs-5 text-danger">
                                Rp
                                {{ number_format($rental->penalty ?: request()->query('gross_amount'), 0, ',', '.') }}
                            </span>
                        @else
                            <span class="text-muted fw-medium">Total Bayar Sewa</span>
                            <span class="fw-extrabold fs-5 text-success">
                                Rp {{ number_format($rental->total_harga, 0, ',', '.') }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('customer.orders') }}"
                        class="btn btn-dark btn-md rounded-pill fw-bold py-2.5 shadow-sm transition-all hover-scale">
                        <i class="bi bi-box-seam me-2"></i> Lihat Daftar Pesanan
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
