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

                    <div class="col-sm-4 col-6" >
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

                @if ($motor->status === 'Tersedia')
                    <button
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
