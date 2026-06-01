<div class="container py-4">
    <div class="mt-3">
        <a href="/catalog" class="text-carbon fw-bold text-decoration-none d-inline-flex align-items-center gap-2"
            style="color: #0f172a;">
            <i class="bi bi-arrow-left"></i> Kembali ke Katalog
        </a>
    </div>
</div>

<main class="container pb-5">
    <div class="row g-4">

        <div class="col-lg-7">
            <div class="detail-img-main-wrapper shadow-sm border rounded-4 p-4 d-flex align-items-center justify-content-center"
                style="height: 400px; background-color: #f8fafc; border-color: #e2e8f0 !important;">
                <img id="mainVehicleImg"
                    src="{{ $motor->image ? asset('storage/' . $motor->image) : 'https://placehold.co/800x600/f8fafc/0f172a?text=No+Image' }}"
                    alt="{{ $motor->model }} Utama" class="img-fluid object-fit-contain h-100"
                    style="filter: drop-shadow(0 15px 25px rgba(0,0,0,0.06));">
            </div>

            <div class="mt-5">
                <h5 class="fw-extrabold text-carbon mb-3" style="color: #0f172a; letter-spacing: -0.5px;">Spesifikasi
                    Mekanis Kendaraan</h5>
                <div class="row g-3">

                    <div class="col-sm-4 col-6">
                        <div class="spec-card p-3 rounded-3 border h-100"
                            style="background-color: #ffffff; border-color: #f1f5f9 !important;">
                            <i class="bi bi-speedometer text-muted mb-2 d-block fs-4"></i>
                            <span class="d-block text-muted small" style="font-size: 0.8rem;">Kapasitas Mesin</span>
                            <span class="fw-bold text-carbon" style="color: #1e293b;">
                                {{ $motor->specification->kapasitas_mesin ?? '250' }} CC
                            </span>
                        </div>
                    </div>

                    <div class="col-sm-4 col-6">
                        <div class="spec-card p-3 rounded-3 border h-100"
                            style="background-color: #ffffff; border-color: #f1f5f9 !important;">
                            <i class="bi bi-lightning-charge text-muted mb-2 d-block fs-4"></i>
                            <span class="d-block text-muted small" style="font-size: 0.8rem;">Konfigurasi
                                Silinder</span>
                            <span class="fw-bold text-carbon" style="color: #1e293b;">
                                {{ $motor->specification->konfigurasi_silinder ?? '1-Silinder Standard' }}
                            </span>
                        </div>
                    </div>

                    <div class="col-sm-4 col-6">
                        <div class="spec-card p-3 rounded-3 border h-100"
                            style="background-color: #ffffff; border-color: #f1f5f9 !important;">
                            <i class="bi bi-gear-wide-connected text-muted mb-2 d-block fs-4"></i>
                            <span class="d-block text-muted small" style="font-size: 0.8rem;">Transmisi</span>
                            <span class="fw-bold text-carbon" style="color: #1e293b;">
                                {{ $motor->specification->transmisi ?? 'Otomatis (CVT)' }}
                            </span>
                        </div>
                    </div>

                    <div class="col-sm-4 col-6">
                        <div class="spec-card p-3 rounded-3 border h-100"
                            style="background-color: #ffffff; border-color: #f1f5f9 !important;">
                            <i class="bi bi-droplet text-muted mb-2 d-block fs-4"></i>
                            <span class="d-block text-muted small" style="font-size: 0.8rem;">Bahan Bakar Min.</span>
                            <span class="fw-bold text-carbon" style="color: #1e293b;">
                                {{ $motor->specification->bahan_bakar_min ?? 'Pertamax' }}
                            </span>
                        </div>
                    </div>


                    <div class="col-sm-4 col-6">
                        <div class="spec-card p-3 rounded-3 border h-100"
                            style="background-color: #ffffff; border-color: #f1f5f9 !important;">
                            <i class="bi bi-shield-check text-muted mb-2 d-block fs-4"></i>
                            <span class="d-block text-muted small" style="font-size: 0.8rem;">Sistem Pengereman</span>
                            <span class="fw-bold text-carbon" style="color: #1e293b;">
                                {{ $motor->specification->sistem_pengereman ?? 'Standard Disc' }}
                            </span>
                        </div>
                    </div>

                    <div class="col-sm-4 col-6">
                        <div class="spec-card p-3 rounded-3 border h-100"
                            style="background-color: #ffffff; border-color: #f1f5f9 !important;">
                            <i class="bi bi-activity text-muted mb-2 d-block fs-4"></i>
                            <span class="d-block text-muted small" style="font-size: 0.8rem;">Tenaga Maksimum</span>
                            <span class="fw-bold text-carbon" style="color: #1e293b;">
                                {{ $motor->specification->tenaga_maksimum ?? '-' }}
                            </span>
                        </div>
                    </div>

                </div>
            </div>

            <div class="mt-5">
                <h5 class="fw-extrabold text-carbon mb-3" style="color: #0f172a;">Deskripsi Kendaraan</h5>
                <p class="text-secondary leading-relaxed" style="color: #475569; font-size: 0.95rem; line-height: 1.7;">
                    {{ $motor->brand }} {{ $motor->model }} menghadirkan pengalaman berkendara yang luar biasa di
                    kelasnya.
                    Unit ini terdaftar dalam kategori manufaktur premium kami dan selalu melalui proses inspeksi ketat
                    multi-titik mekanis sebelum diserahterimakan kepada penyewa untuk menjamin performa yang responsif,
                    aman, dan prima di jalan raya.
                </p>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="sticky-booking-panel p-4 rounded-4 border bg-white shadow-sm"
                style="border-color: #e2e8f0 !important; position: sticky; top: 24px;">
                <span class="badge bg-dark mb-2 px-3 py-2 rounded-pill text-uppercase fw-bold"
                    style="letter-spacing: 1px; font-size: 0.65rem; background-color: #0f172a !important;">
                    {{ $motor->category->name ?? 'Premium Unit' }}
                </span>
                <div class="d-flex align-items-center flex-wrap gap-2 mt-3 mb-1">
                    <h4 class="fw-extrabold text-carbon mb-0" id="modalMotorName" style="color: #0f172a;">
                        {{ $motor->brand }} {{ $motor->model }}
                    </h4>
                    <span class="badge font-monospace text-uppercase"
                        style="background-color: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; font-size: 0.75rem; border-radius: 6px; padding: 4px 8px;">
                        {{ $motor->plate_nomor }}
                    </span>
                </div>
                <p class="text-muted small mb-4 border-bottom pb-3">
                    <i class="bi bi-shield-fill-check text-dark me-1"></i> Telah Lolos Inspeksi 50 Titik Garansi
                    Kendaraan
                </p>

                <div class="d-flex align-items-baseline mb-4 p-3 rounded-3" style="background-color: #f8fafc;">
                    <span class="text-secondary small me-2 fw-medium">Tarif Bersih:</span>
                    <span class="fs-2 fw-black text-carbon" style="color: #0f172a; font-weight: 900;">Rp
                        {{ number_format($motor->harga_per_hari, 0, ',', '.') }}</span>
                    <span class="text-secondary small ms-1">/ hari</span>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold text-carbon small mb-2" style="color: #1e293b;">Fasilitas Complimentary:</h6>
                    <ul class="list-unstyled mb-0 text-secondary small" style="line-height: 1.8; color: #475569;">
                        <li><i class="bi bi-check2 text-dark me-1 fw-bold"></i> 2 Unit Helm Premium SNI (Steril &
                            Bersih)</li>
                        <li><i class="bi bi-check2 text-dark me-1 fw-bold"></i> 2 Pasang Jas Hujan Setelan Kualitas
                            Tinggi</li>
                        <li><i class="bi bi-check2 text-dark me-1 fw-bold"></i> Proteksi Kerusakan Dasar & Asuransi
                            Driver</li>
                        <li><i class="bi bi-check2 text-dark me-1 fw-bold"></i> Phone Holder GPS yang Kokoh terpasang
                            di
                            Stang</li>
                    </ul>
                </div>

                <h6 class="fw-bold text-carbon mb-2 small" style="color: #1e293b;">Ketentuan Dasar:</h6>
                <ul class="text-secondary small ps-3 mb-4" style="line-height: 1.6; color: #475569;">
                    <li>Menunjukkan SIM C aktif saat proses serah terima fisik.</li>
                    <li>Menjaminkan dokumen identitas asli (KTP Elektronik).</li>
                </ul>

                @if (session('error'))
                    <div class="alert alert-danger border-0 rounded-4 shadow-sm p-3 mb-4 d-flex align-items-center gap-2"
                        style="background-color: #fef2f2; color: #991b1b;">
                        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                        <div>
                            <span class="fw-bold d-block" style="font-size: 0.85rem;">Sistem Menolak Akses</span>
                            <span style="font-size: 0.8rem; opacity: 0.9;">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                @if ($motor->status === 'Tersedia')
                    <button type="button"
                        class="btn btn-dark btn-lg w-100 py-3 rounded-pill d-flex align-items-center justify-content-center gap-2 shadow-sm"
                        style="background-color: #0f172a; border-color: #0f172a; font-size: 0.95rem;"
                        data-bs-toggle="modal" data-bs-target="#bookingModal">
                        <i class="bi bi-bag-plus-fill"></i>
                        <span class="fw-bold">Sewa Sekarang</span>
                    </button>
                @else
                    <button
                        class="btn btn-secondary btn-lg w-100 py-3 rounded-pill d-flex align-items-center justify-content-center gap-2"
                        disabled>
                        <i class="bi bi-exclamation-octagon"></i>
                        <span class="fw-bold">Unit Sedang Tidak Tersedia</span>
                    </button>
                @endif
            </div>
        </div>

    </div>
</main>

<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-content-custom border-0 rounded-4 shadow-lg overflow-hidden">

            <div class="modal-header-custom d-flex justify-content-between align-items-center p-3 text-white"
                style="background-color: #0f172a;">
                <div>
                    <h5 class="modal-title fw-bold mb-0" id="bookingModalLabel">Formulir Sewa Kuda Besi</h5>
                    <span class="text-white-50 small">Unit: {{ $motor->brand }} {{ $motor->model }}
                        ({{ $motor->plate_nomor ?? $motor->plat_nomor }})</span>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <form id="bookingForm" action="{{ route('booking.store') }}" method="POST">
                @csrf
                <input type="hidden" name="motor_id" value="{{ $motor->id }}">
                <input type="hidden" id="hargaHarian" value="{{ $motor->harga_per_hari }}">

                <div class="modal-body p-4" style="max-height: 75vh; overflow-y: auto;">
                    <div class="row g-4">

                        <div class="col-md-6 border-end" style="border-color: #f1f5f9 !important;">
                            <h6 class="fw-bold text-dark text-uppercase mb-3"
                                style="letter-spacing: 0.5px; color: #0f172a;">1. Logistik & Waktu Sewa</h6>

                            <div class="mb-3">
                                <label class="form-label text-secondary small fw-bold text-uppercase"><i
                                        class="bi bi-calendar2-week me-1"></i> Tanggal & Jam Ambil</label>
                                <input type="datetime-local" class="form-control rounded-3" id="bookPickupDate"
                                    name="tanggal_mulai" required onchange="calculateTotalCost()"
                                    min="{{ date('Y-m-d\TH:i') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-secondary small fw-bold text-uppercase"><i
                                        class="bi bi-calendar2-check me-1"></i> Tanggal & Jam Kembali</label>
                                <input type="datetime-local" class="form-control rounded-3" id="bookReturnDate"
                                    name="tanggal_rencana_kembali" required onchange="calculateTotalCost()">
                                <span class="text-danger small d-block mt-1 fw-medium" id="durationAlert">Silakan
                                    tentukan rentang waktu sewa</span>
                            </div>

                            <hr class="border-light my-4">

                            <h6 class="fw-bold text-dark text-uppercase mb-3"
                                style="letter-spacing: 0.5px; color: #0f172a;">2. Pengantaran Unit</h6>
                            <div class="mb-3">
                                <label class="form-label text-secondary small fw-bold text-uppercase"><i
                                        class="bi bi-geo-alt-fill me-1"></i> Metode Penyerahan</label>
                                <select class="form-select rounded-3" id="bookDeliveryMethod"
                                    name="metode_pengantaran" onchange="calculateTotalCost()">
                                    <option value="pickup" selected>Ambil Sendiri di Garasi Pusat (Gratis)</option>
                                    <option value="delivery">Antar-Jemput ke Bandara / Hotel (+Rp 75.000)</option>
                                </select>
                            </div>

                            <div class="mb-3 d-none" id="deliveryAddressWrapper">
                                <label class="form-label text-secondary small fw-bold text-uppercase">Alamat Detail
                                    Pengantaran</label>
                                <textarea class="form-control rounded-3" name="alamat_pengantaran" rows="2"
                                    placeholder="Nama Hotel / No. Kamar / Terminal Bandara..."></textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h6 class="fw-bold text-dark text-uppercase mb-3"
                                style="letter-spacing: 0.5px; color: #0f172a;">3. Perlengkapan Opsional</h6>

                            <div class="mb-2">
                                <input type="checkbox" class="btn-check" id="addonGlove" name="addon_glove"
                                    value="20000" autocomplete="off" onchange="calculateTotalCost()">
                                <label class="btn btn-outline-secondary w-100 text-start p-3 rounded-3"
                                    for="addonGlove" style="border-color: #cbd5e1;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="fw-bold d-block text-dark" style="font-size: 0.9rem;">Sarung
                                                Tangan Alpinestars</span>
                                            <span class="text-muted small" style="font-size: 0.75rem;">Proteksi
                                                handgrip premium harian</span>
                                        </div>
                                        <span class="text-dark fw-bold small">+Rp 20rb<small
                                                class="text-muted">/hari</small></span>
                                    </div>
                                </label>
                            </div>

                            <div class="mb-3">
                                <input type="checkbox" class="btn-check" id="addonActionCam" name="addon_cam"
                                    value="50000" autocomplete="off" onchange="calculateTotalCost()">
                                <label class="btn btn-outline-secondary w-100 text-start p-3 rounded-3"
                                    for="addonActionCam" style="border-color: #cbd5e1;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="fw-bold d-block text-dark" style="font-size: 0.9rem;">Action
                                                Camera GoPro Hero</span>
                                            <span class="text-muted small" style="font-size: 0.75rem;">Dokumentasikan
                                                keseruan petualangan</span>
                                        </div>
                                        <span class="text-dark fw-bold small">+Rp 50rb<small
                                                class="text-muted">/hari</small></span>
                                    </div>
                                </label>
                            </div>

                            <div class="rounded-4 p-3 mt-4"
                                style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                                <h6 class="fw-bold text-dark text-uppercase mb-3"
                                    style="letter-spacing: 0.5px; font-size: 0.8rem;">Rencana Anggaran Sewa</h6>

                                <div class="d-flex justify-content-between small text-secondary mb-2">
                                    <span>Tarif Pokok Unit (Rp {{ number_format($motor->harga_per_hari, 0, ',', '.') }}
                                        x <span id="summaryDays">0</span> Hari)</span>
                                    <span id="summaryBaseCost">Rp 0</span>
                                </div>

                                <div class="d-flex justify-content-between small text-secondary mb-2">
                                    <span>Tarif Pengantaran</span>
                                    <span id="summaryDeliveryCost">Rp 0</span>
                                </div>

                                <div class="d-flex justify-content-between small text-secondary mb-3">
                                    <span>Tambahan Perlengkapan</span>
                                    <span id="summaryAddonCost">Rp 0</span>
                                </div>

                                <div class="d-flex justify-content-between text-dark fw-bold border-top pt-2"
                                    style="border-color: #e2e8f0 !important;">
                                    <span>Perkiraan Total</span>
                                    <span class="fs-5 fw-extrabold" id="summaryTotalCost" style="color: #0f172a;">Rp
                                        0</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer p-3 bg-light-soft border-top d-flex gap-2"
                    style="background-color: #f8fafc;">
                    <button type="button" class="btn btn-light rounded-pill px-4"
                        data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" id="submitBtn"
                        class="btn btn-dark rounded-pill px-5 py-2.5 d-flex align-items-center gap-2"
                        style="background-color: #0f172a;" disabled>
                        <i class="bi bi-calendar-check"></i>
                        <span class="fw-bold">Konfirmasi Booking</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="position-fixed bottom-0 inset-end-0 p-3" style="z-index: 1100">
    <div id="bookingToast" class="toast align-items-center text-white bg-dark border-0 rounded-4" role="alert"
        aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill text-white fs-5"></i>
                <span id="toastMessage">Booking Anda berhasil diajukan!</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>
</div>

@vite(['resources/js/app.js'])
