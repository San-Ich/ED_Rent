<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="p-4 text-white" style="background: linear-gradient(135deg, #111827 0%, #1f2937 100%);">
                    <h4 class="fw-extrabold mb-1">Metode Pembayaran Sesi #{{ $rental->kode_booking }}</h4>
                    <p class="text-white-50 small mb-0">Sistem terintegrasi otomatis secara aman dengan Midtrans Payment
                        Gateway.</p>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">

                        <div class="col-md-6 border-end">
                            <h5 class="fw-bold text-dark mb-3" style="font-size: 1rem;">1. Informasi Penyewa & Unit</h5>
                            <div class="mb-3">
                                <label class="text-muted small d-block">Nama Penyewa</label>
                                <span class="fw-bold text-dark">{{ $rental->user->name }}</span>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small d-block">Unit Motor</label>
                                <span class="fw-bold text-dark">{{ $rental->motor->brand }}
                                    {{ $rental->motor->model }}</span>
                            </div>
                            <hr class="border-light">
                            <h5 class="fw-bold text-dark mb-3" style="font-size: 1rem;">2. Waktu Logistik Sewa</h5>
                            <div class="mb-3">
                                <label class="text-muted small d-block"><i
                                        class="bi bi-box-arrow-up text-success me-1"></i> Waktu Ambil</label>
                                <span
                                    class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($rental->tanggal_mulai)->format('d M Y - H:i') }}
                                    WIB</span>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small d-block"><i
                                        class="bi bi-box-arrow-in-down text-danger me-1"></i> Batas Kembali</label>
                                <span
                                    class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($rental->tanggal_rencana_kembali)->format('d M Y - H:i') }}
                                    WIB</span>
                            </div>
                        </div>

                        <div class="col-md-6 ps-md-4 d-flex flex-column justify-content-center">
                            <h5 class="fw-bold text-dark mb-3" style="font-size: 1rem;">3. Gerbang Pembayaran Resmi</h5>

                            <div class="p-4 rounded-3 mb-4 text-center"
                                style="background-color: #f8fafc; border: 1px solid #e2e8f0;">

                                <span class="text-muted small text-uppercase fw-bold d-block mb-1">
                                    @if (($rental->status ?? $order->status) === 'Pending Denda')
                                        <span class="text-danger">Total Tagihan Denda</span>
                                    @else
                                        Total Tagihan Bersih
                                    @endif
                                </span>

                                <h2 class="fw-extrabold text-dark mb-0">
                                    @if (($rental->status ?? $order->status) === 'Pending Denda')
                                        <span class="text-danger">Rp
                                            {{ number_format($rental->total_harga, 0, ',', '.') }}</span>
                                    @else
                                        Rp {{ number_format($rental->total_harga, 0, ',', '.') }}
                                    @endif
                                </h2>

                            </div>

                            <div class="d-grid gap-2">
                                <button type="button" id="pay-button"
                                    class="btn btn-primary py-3 rounded-pill fw-bold shadow"
                                    style="background-color: #2563eb; border-color: #2563eb;">
                                    <i class="bi bi-shield-lock-fill me-2"></i> Bayar Sekarang
                                </button>

                                <a href="{{ route('customer.orders') }}"
                                    class="btn btn-link text-secondary text-decoration-none small text-center mt-2">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Order List
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
