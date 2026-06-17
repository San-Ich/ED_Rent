@vite(['resources/css/payment.css'])

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-9">

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden bg-white animate__animated animate__fadeIn">
                <div class="p-4 text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h4 class="fw-extrabold mb-1">Gerbang Pembayaran Sesi {{ $rental->kode_booking }}</h4>
                            <p class="text-white-50 small mb-0">Sistem terintegrasi otomatis secara aman dengan Midtrans Payment Gateway.</p>
                        </div>
                        <span class="badge bg-light text-dark px-3 py-2 rounded-pill fw-bold font-monospace small">
                            ID: #{{ $rental->kode_booking }}
                        </span>
                    </div>
                </div>

                <div class="card-body p-4 p-sm-5">
                    <div class="row g-4">

                        <div class="col-md-6 border-end" style="border-color: #f1f5f9 !important;">
                            
                            <h6 class="fw-bold text-dark text-uppercase mb-3" style="letter-spacing: 0.5px; font-size: 0.85rem; color: #0f172a;">
                                <i class="bi bi-person-badge-fill text-secondary me-2"></i>1. Identitas & Unit Motor
                            </h6>
                            <div class="bg-light rounded-3 p-3 mb-4" style="font-size: 0.9rem;">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Nama Penyewa:</span>
                                    <span class="fw-bold text-dark">{{ $rental->user->name }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Unit Kendaraan:</span>
                                    <span class="fw-bold text-dark">{{ $rental->motor->brand }} {{ $rental->motor->model }}</span>
                                </div>
                            </div>

                            <h6 class="fw-bold text-dark text-uppercase mb-3" style="letter-spacing: 0.5px; font-size: 0.85rem; color: #0f172a;">
                                <i class="bi bi-calendar3 text-secondary me-2"></i>2. Logistik & Waktu Sewa
                            </h6>
                            <div class="bg-light rounded-3 p-3 mb-4" style="font-size: 0.9rem;">
                                <div class="mb-2">
                                    <label class="text-muted small d-block"><i class="bi bi-box-arrow-up text-success me-1"></i> Tanggal & Jam Ambil</label>
                                    <span class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($rental->tanggal_mulai)->format('d M Y - H:i') }} WIB</span>
                                </div>
                                <div>
                                    <label class="text-muted small d-block"><i class="bi bi-box-arrow-in-down text-danger me-1"></i> Tanggal & Jam Kembali</label>
                                    <span class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($rental->tanggal_rencana_kembali)->format('d M Y - H:i') }} WIB</span>
                                </div>
                            </div>

                            <h6 class="fw-bold text-dark text-uppercase mb-3" style="letter-spacing: 0.5px; font-size: 0.85rem; color: #0f172a;">
                                <i class="bi bi-geo-alt-fill text-secondary me-2"></i>3. Pengantaran Unit
                            </h6>
                            <div class="bg-light rounded-3 p-3 mb-4" style="font-size: 0.9rem;">
                                <div class="mb-2">
                                    <span class="text-muted d-block small">Metode Penyerahan:</span>
                                    @if($rental->metode_pengantaran === 'delivery')
                                        <span class="badge bg-primary rounded-pill px-3 py-1.5 mt-1 fw-bold">
                                            <i class="bi bi-truck me-1"></i> Antar-Jemput (Bandara / Hotel)
                                        </span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill px-3 py-1.5 mt-1 fw-bold">
                                            <i class="bi bi-house-door-fill me-1"></i> Ambil Sendiri di Garasi Pusat
                                        </span>
                                    @endif
                                </div>
                                
                                @if($rental->metode_pengantaran === 'delivery' && ($rental->alamat_pengantaran ?? $rental->alamat_pengantaran_final))
                                    <div class="mt-3 pt-2 border-top border-secondary border-opacity-10">
                                        <span class="text-muted d-block small fw-bold text-uppercase">Alamat Detail Pengantaran:</span>
                                        <p class="text-dark mb-0 mt-1 fw-medium lh-base bg-white p-2 rounded border border-light">
                                            {{ $rental->alamat_pengantaran ?? $rental->alamat_pengantaran_final }}
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <h6 class="fw-bold text-dark text-uppercase mb-3" style="letter-spacing: 0.5px; font-size: 0.85rem; color: #0f172a;">
                                <i class="bi bi-tools text-secondary me-2"></i>4. Perlengkapan Tambahan
                            </h6>
                            <div class="bg-light rounded-3 p-3 mb-2" style="font-size: 0.9rem;">
                                @if($rental->perlengkapan && $rental->perlengkapan->count() > 0)
                                    <ul class="list-unstyled mb-0">
                                        @foreach($rental->perlengkapan as $item)
                                            <li class="d-flex justify-content-between align-items-center mb-2 last-mb-0">
                                                <span class="text-dark fw-medium"><i class="bi bi-check2 text-success me-2"></i>{{ $item->nama_perlengkapan }}</span>
                                                <span class="text-muted small">Rp {{ number_format($item->harga_per_hari, 0, ',', '.') }}/hari</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-muted small d-block py-1 text-center"><i class="bi bi-info-circle me-1"></i> Tidak memilih perlengkapan opsional</span>
                                @endif
                            </div>

                        </div>

                        <div class="col-md-6 ps-md-4 d-flex flex-column justify-content-center">
                            <h5 class="fw-bold text-dark text-uppercase mb-3" style="letter-spacing: 0.5px; font-size: 0.85rem; color: #0f172a;">
                                <i class="bi bi-wallet2 text-secondary me-2"></i>Invoice Pembayaran Resmi
                            </h5>

                            <div class="p-4 rounded-4 mb-4 text-center shadow-sm" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                                <span class="text-muted small text-uppercase fw-extrabold d-block mb-1" style="letter-spacing: 0.5px;">
                                    @if (($rental->status ?? $order->status) === 'Pending Denda')
                                        <span class="text-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i> Total Tagihan Denda Keterlambatan</span>
                                    @else
                                        <i class="bi bi-receipt-cutoff me-1"></i> Total Tagihan Bersih Kontrak
                                    @endif
                                </span>

                                <h2 class="fw-extrabold text-dark mb-0 fs-1">
                                    @if (($rental->status ?? $order->status) === 'Pending Denda')
                                        <span class="text-danger">
                                            Rp {{ number_format($penalty ?? $rental->penalty ?? 0, 0, ',', '.') }}
                                        </span>
                                    @else
                                        Rp {{ number_format($rental->total_harga ?? 0, 0, ',', '.') }}
                                    @endif
                                </h2>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="button" id="pay-button" class="btn btn-primary py-3 rounded-pill fw-bold shadow mb-1 hover-scale transition-all" style="background-color: #2563eb; border-color: #2563eb;">
                                    <i class="bi bi-credit-card-2-front-fill me-2"></i> Bayar Online Instan (Transfer/QRIS)
                                </button>

                                <div class="text-center my-2">
                                    <span class="badge bg-light text-muted border px-3 py-1.5 rounded-pill small fw-medium" style="font-size: 0.75rem;">
                                        Atau pilih opsi bayar langsung di tempat
                                    </span>
                                </div>

                                <form action="{{ route('customer.rental.pay-cash', $rental->id) }}" method="POST" id="cash-payment-form">
                                    @csrf
                                    <button type="button" onclick="confirmCashPayment()" class="btn btn-success py-3 rounded-pill fw-bold shadow w-100 hover-scale transition-all" style="background-color: #10b981; border-color: #10b981;">
                                        <i class="bi bi-cash-stack me-2"></i> Bayar Cash Manual di Garasi
                                    </button>
                                </form>

                                <a href="{{ route('customer.orders') }}" class="btn btn-link text-secondary text-decoration-none small text-center mt-3 fw-semibold">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat Order List
                                </a>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function confirmCashPayment() {
        if (confirm("Apakah Anda yakin ingin memilih metode Pembayaran Cash di tempat?\n\nAdmin KudaBesiRent akan langsung diberi notifikasi untuk menyiapkan berkas kontrak dan unit motor Anda.")) {
            document.getElementById('cash-payment-form').submit();
        }
    }
</script>