<div class="modal fade" id="orderDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow" style="border-radius: 16px; background-color: #ffffff;">

            <div class="modal-header d-flex justify-content-between align-items-center p-4 text-white"
                style="background: #0f172a; border-bottom: 1px solid #1e293b; border-top-left-radius: 16px; border-top-right-radius: 16px;"
                id="modalHeaderBg">
                <div>
                    <h5 class="modal-title fw-bold mb-0" id="modalTitleMotor" style="font-size: 1.1rem;">Detail
                        Transaksi</h5>
                    <span class="font-monospace small opacity-75 d-block mt-1" id="modalBookingCode"
                        style="letter-spacing: 0.5px; font-size: 0.85rem;">KBR-XXXX</span>
                </div>
                <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-4">

                    <div class="col-md-5 text-center">
                        <div class="rounded-3 p-3 d-flex align-items-center justify-content-center bg-light border"
                            style="height: 200px;">
                            <img id="modalMotorImg" src="" alt="Motor" class="img-fluid"
                                style="max-height: 100%; object-fit: contain;">
                        </div>

                        <h4 class="fw-bold mt-3 mb-1 text-dark" id="modalMotorName" style="font-size: 1.3rem;">Nama
                            Motor</h4>
                        <p class="text-muted small mb-0"><i class="bi bi-shield-check text-dark me-1"></i> Terinspeksi
                            50 Titik Mekanis</p>

                        <div class="d-grid mt-4" id="modalMainAction"></div>
                    </div>

                    <div class="col-md-7 border-start-md ps-md-4">
                        <span class="text-uppercase fw-bold text-muted d-block mb-3"
                            style="font-size: 0.75rem; letter-spacing: 0.5px;">Logistik Distribusi</span>

                        <div class="mb-3">
                            <span class="text-secondary small d-block mb-1">Penyerahan Unit:</span>
                            <div class="p-2.5 rounded-2 bg-light border text-dark fw-bold small" id="modalPickupTime">
                                <i class="bi bi-clock me-1 text-dark"></i> Loading...
                            </div>
                            <span class="text-muted d-block mt-1" style="font-size: 0.75rem;">Lokasi: Hub KudaBesiRent
                                Pusat (No. 24)</span>
                        </div>

                        <div class="mb-4">
                            <span class="text-secondary small d-block mb-1">Batas Pengembalian:</span>
                            <div class="p-2.5 rounded-2 bg-light border text-dark fw-bold small" id="modalReturnTime">
                                <i class="bi bi-clock me-1 text-dark"></i> Loading...
                            </div>
                            <span class="text-muted d-block mt-1" style="font-size: 0.75rem;">S&K: Wajib dikembalikan
                                dengan tangki penuh.</span>
                        </div>

                        <div class="my-3" style="border-top: 1px dashed #e2e8f0;"></div>

                        <span class="text-uppercase fw-bold text-muted d-block mb-3"
                            style="font-size: 0.75rem; letter-spacing: 0.5px;">Ikhtisar Finansial</span>

                        <div class="d-flex justify-content-between small mb-2 text-secondary">
                            <span>Sewa Dasar Unit</span>
                            <span id="modalBreakdownBase" class="fw-bold text-dark">Rp -</span>
                        </div>
                        <div class="d-flex justify-content-between small mb-2 text-secondary align-items-center">
                            <span>Fasilitas Tambahan</span>
                            <span class="badge text-dark border px-2 py-0.5"
                                style="font-size: 0.65rem; background-color: #f8fafc;">FREE HELM & JAS HUJAN</span>
                        </div>
                        <div class="d-flex justify-content-between small mb-3 text-secondary">
                            <span>Proteksi Kerusakan</span>
                            <span class="text-dark fw-medium" style="font-size: 0.8rem;"><i
                                    class="bi bi-check2 text-dark me-0.5"></i> Included</span>
                        </div>

                        <div class="d-flex justify-content-between small mb-3 text-secondary">
                            <span>Perlengkapan Tambahan</span>
                            <span id="modalPerlengkapanTambahan" class="fw-bold text-dark text-end">Tidak Ada</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center p-3 rounded-3"
                            style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                            <span class="fw-bold text-secondary small">Total Tagihan</span>
                            <span class="fs-4 fw-bold text-dark" id="modalTotalPay">Rp -</span>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
