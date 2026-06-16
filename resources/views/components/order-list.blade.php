<main class="container py-5 mt-4">
    @if (session('success'))
        <div class="alert alert-success border-0 rounded-4 shadow-sm p-3 mb-4 d-flex align-items-center gap-2"
            style="background-color: #f0fdf4; color: #166534;">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div>
                <span class="fw-bold d-block" style="font-size: 0.85rem;">Berhasil</span>
                <span style="font-size: 0.8rem; opacity: 0.9;">{{ session('success') }}</span>
            </div>
        </div>
    @endif
    <div class="col-xl-10 mx-auto" id="ordersGrid">

        @forelse ($orders as $order)
            <div class="order-card-clean p-4 order-item" data-status="{{ $order->status }}">
                <div class="row align-items-center g-4">

                    <div class="col-md-auto text-center text-md-start">
                        <div class="order-img-wrapper mx-auto">
                            <img src="{{ $order->motor && $order->motor->image ? asset('storage/' . $order->motor->image) : 'https://placehold.co/400x300/f8fafc/0f172a?text=' . urlencode($order->motor_name ?? 'Motor') }}"
                                alt="{{ $order->motor_name }}">
                        </div>
                    </div>

                    <div class="col-md col-12 text-center text-md-start">
                        <div
                            class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-2 gap-2">
                            <div>
                                <span class="badge bg-light-soft text-muted border small px-2 py-1 mb-1 d-inline-block">
                                    Kode: {{ $order->kode_booking }}
                                </span>
                                <h4 class="fw-bold text-carbon mb-0 mt-1">
                                    {{ $order->motor->model ?? $order->motor_name }}</h4>
                                <div class="mt-2">
                                    @if ($order->metode_pengantaran === 'delivery')
                                        <span class="badge bg-info text-dark small px-2 py-1">
                                            <i class="bi bi-truck me-1"></i> Antar-Jemput (Delivery)
                                        </span>
                                        <small class="text-muted d-block mt-1 bg-light p-2 rounded border"
                                            style="font-size: 0.8rem;">
                                            <i class="bi bi-geo-alt me-1"></i> <strong>Alamat:</strong>
                                            {{ $order->alamat_pengantaran }}
                                        </small>
                                    @else
                                        <span class="badge bg-secondary text-white small px-2 py-1">
                                            <i class="bi bi-geo me-1"></i> Ambil Sendiri di Garasi Pusat
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                @if ($order->status == 'Menunggu')
                                    <span class="badge-status-waiting"><i class="bi bi-clock-history me-1"></i> Menunggu
                                        Bayar</span>
                                @elseif($order->status == 'Disewa')
                                    <span class="badge-status-active"><i class="bi bi-play-circle me-1"></i>
                                        Aktif/Berjalan</span>
                                @elseif($order->status == 'Selesai')
                                    <span class="badge-status-completed"><i class="bi bi-check-circle-fill me-1"></i>
                                        Selesai</span>
                                @elseif($order->status === 'Pending Denda')
                                    @php
                                        $waktuSekarang = \Carbon\Carbon::now('Asia/Jakarta')->startOfDay();
                                        $waktuRencanaKembali = \Carbon\Carbon::parse(
                                            $order->tanggal_rencana_kembali,
                                            'Asia/Jakarta',
                                        )->startOfDay();

                                        $selisihHari = $waktuSekarang->gt($waktuRencanaKembali)
                                            ? $waktuRencanaKembali->diffInDays($waktuSekarang)
                                            : 0;
                                        $tarifDenda = 50000;
                                        $totalDendaMurni = $selisihHari * $tarifDenda;
                                    @endphp

                                    <div
                                        class="alert alert-danger border-0 rounded-4 p-3 mt-3 d-flex align-items-start gap-3 shadow-sm">
                                        <i class="bi bi-exclamation-triangle-fill fs-4 text-danger"></i>
                                        <div>
                                            <span class="fw-bold d-block text-danger mb-1" style="font-size: 0.9rem;">
                                                Terlambat Mengembalikan Motor! (Telat {{ $selisihHari }} Hari)
                                            </span>
                                            <p class="text-secondary mb-2" style="font-size: 0.85rem;">
                                                Anda dikenakan denda keterlambatan karena mengembalikan motor melewati
                                                batas waktu rencana kembali.
                                            </p>
                                            <span class="badge bg-danger fs-6 px-3 py-1.5 rounded-pill fw-bold">
                                                Total Denda: Rp {{ number_format($totalDendaMurni, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                @elseif($order->status == 'Menunggu Verifikasi')
                                    <span class="badge bg-primary text-white px-2 py-1 small rounded">
                                        <i class="bi bi-shield-fill-exclamation me-1"></i> Menunggu Verifikasi Admin
                                    </span>
                                @elseif($order->status == 'Gagal')
                                    <span class="badge-status-danger"><i
                                            class="bi bi-exclamation-octagon-fill me-1"></i>
                                        Gagal</span>
                                @endif
                            </div>
                        </div>

                        <div class="row g-2 text-secondary small pt-2 border-top border-light mt-1">
                            <div class="col-6 col-sm-4">
                                <i class="bi bi-calendar2-week me-1"></i>
                                <strong>Sewa:</strong>
                                {{ \Carbon\Carbon::parse($order->tanggal_mulai)->translatedFormat('d M Y') }}
                            </div>
                            <div class="col-6 col-sm-4">
                                <i class="bi bi-hourglass-split me-1"></i>
                                <strong>Durasi:</strong>
                                {{ \Carbon\Carbon::parse($order->tanggal_rencana_kembali)->diffInDays(\Carbon\Carbon::parse($order->tanggal_mulai)) }}
                                Hari
                            </div>
                            <div class="col-12 col-sm-4 text-sm-end">
                                <span class="text-secondary">Total:</span>
                                <span class="text-carbon fw-bold fs-6">Rp
                                    {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-auto col-12 d-grid gap-2 text-center align-self-center">
                        <div class="d-flex align-items-center justify-content-center justify-content-md-end gap-2"
                            style="background: transparent !important; box-shadow: none !important; border: none !important; white-space: nowrap;">

                            @if ($order->status === 'waiting' || $order->status === 'Menunggu')
                                <a href="{{ route('customer.orders.payment', $order->id) }}"
                                    class="btn btn-warning btn-md rounded-pill text-dark fw-bold px-4 border-0 shadow-sm">
                                    <i class="bi bi-credit-card-2-front me-1"></i> Bayar
                                </a>

                                <button type="button" class="btn btn-outline-secondary btn-md rounded-pill px-3"
                                    onclick="window.tampilkanDetailBooking({{ json_encode($order->load('motor', 'perlengkapan')) }})">
                                    <i class="bi bi-info-circle"></i>
                                </button>
                            @elseif($order->status === 'active' || $order->status === 'Disewa')
                                <a href="{{ route('customer.rental.download-struk', $order->id) }}"
                                    class="btn btn-success btn-sm rounded-pill">
                                    <i class="bi bi-download me-1"></i> Struk
                                </a>

                                @php
                                    $hariIni = \Carbon\Carbon::now('Asia/Jakarta')->startOfDay();
                                    $hariTerakhirSewa = \Carbon\Carbon::parse(
                                        $order->tanggal_rencana_kembali,
                                        'Asia/Jakarta',
                                    )->startOfDay();
                                @endphp

                                @if ($hariIni->greaterThanOrEqualTo($hariTerakhirSewa))
                                    <button type="button" class="btn btn-primary btn-sm rounded-pill"
                                        data-bs-toggle="modal" data-bs-target="#modalKembalikan{{ $order->id }}">
                                        <i class="bi bi-arrow-left-right me-1"></i> Kembalikan Motor
                                    </button>
                                @endif

                                <button type="button" class="btn btn-outline-primary btn-md rounded-pill px-3"
                                    onclick="window.tampilkanDetailBooking({{ json_encode($order->load('motor', 'perlengkapan')) }})">
                                    <i class="bi bi-info-circle"></i>
                                </button>
                            @elseif($order->status === 'Pending Denda')
                                <a href="{{ route('customer.rental.denda', $order->id) }}"
                                    class="btn btn-danger btn-md rounded-pill text-white fw-bold px-4 border-0 shadow-sm animate__animated animate__pulse animate__infinite">
                                    <i class="bi bi-wallet2 me-1"></i> Bayar Denda
                                </a>

                                <button type="button" class="btn btn-outline-secondary btn-md rounded-pill px-3"
                                    onclick="window.tampilkanDetailBooking({{ json_encode($order->load('motor', 'perlengkapan')) }})">
                                    <i class="bi bi-info-circle"></i>
                                </button>
                            @elseif($order->status === 'Menunggu Verifikasi')
                                <button type="button" class="btn btn-outline-warning btn-md rounded-pill px-4"
                                    disabled>
                                    <i class="bi bi-hourglass-split me-1"></i> Sedang Dicek
                                </button>

                                <button type="button" class="btn btn-outline-secondary btn-md rounded-pill px-3"
                                    onclick="window.tampilkanDetailBooking({{ json_encode($order->load('motor', 'perlengkapan')) }})">
                                    <i class="bi bi-info-circle"></i>
                                </button>
                            @elseif($order->status === 'failed' || $order->status === 'Gagal')
                                <div class="d-flex flex-column align-items-center align-items-md-end gap-2">
                                    <span class="text-danger mb-1 d-block" style="font-size: 0.75rem; opacity: 0.85;">
                                        <i class="bi bi-exclamation-circle me-1"></i> Motor sudah disewa
                                    </span>

                                    <div class="d-flex gap-2">
                                        @if ($order->motor_id)
                                            <a href="{{ route('catalog.show', $order->motor_id) }}"
                                                class="btn btn-danger btn-md rounded-pill text-white px-4 border-0 shadow-sm transition-all fw-bold"
                                                style="font-size: 0.85rem;">
                                                <i class="bi bi-arrow-counterclockwise me-1"></i> Sewa Ulang Unit Ini
                                            </a>
                                        @else
                                            <a href="{{ url('/catalog') }}"
                                                class="btn btn-secondary btn-md rounded-pill text-white px-4 border-0 shadow-sm"
                                                style="font-size: 0.85rem;">
                                                <i class="bi bi-search me-1"></i> Cari Motor Lain
                                            </a>
                                        @endif

                                        <button type="button"
                                            class="btn btn-outline-secondary btn-md rounded-pill px-3"
                                            onclick="window.tampilkanDetailBooking({{ json_encode($order->load('motor', 'perlengkapan')) }})">
                                            <i class="bi bi-info-circle"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div id="emptyState" class="text-center py-5">
                <i class="bi bi-receipt display-2 text-muted"></i>
                <h4 class="fw-bold text-carbon mt-3">Tidak Ada Pesanan</h4>
                <p class="text-secondary">Saat ini tidak ada riwayat pesanan sewa motor.</p>
                <a href="{{ url('/catalog') }}"
                    class="btn btn-carbon rounded-pill px-4 mt-2 text-decoration-none">Sewa
                    Motor Sekarang</a>
            </div>
        @endforelse

        @foreach ($orders as $order)
            @if ($order->status === 'active' || $order->status === 'Disewa')
                <div class="modal fade" id="modalKembalikan{{ $order->id }}" tabindex="-1" aria-hidden="true"
                    style="white-space: normal;">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded-4 border-0 shadow text-start">
                            <div class="modal-header bg-dark text-white p-3">
                                <h5 class="modal-title fw-bold fs-6"><i
                                        class="bi bi-box-arrow-in-left me-2"></i>Konfirmasi Pengembalian</h5>
                                <button type="button" class="btn-close btn-close-white"
                                    data-bs-dismiss="modal"></button>
                            </div>

                            <form action="{{ route('customer.rental.kembalikan', $order->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body p-4 text-secondary" style="font-size: 0.9rem;">

                                    @if ($order->metode_pengantaran === 'delivery')
                                        <div class="alert alert-info border-0 rounded-3 p-3 mb-3 d-flex gap-2">
                                            <i class="bi bi-info-circle-fill fs-5 text-primary"></i>
                                            <div>
                                                <span class="fw-bold text-dark d-block mb-1">Layanan Antar-Jemput
                                                    Aktif</span>
                                                Staf kami akan menjemput motor ke lokasi Anda. Silakan tentukan opsi
                                                kehadiran Anda di bawah ini.
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold small text-dark">Bagaimana Anda Ingin
                                                Mengembalikan Motor?</label>
                                            <select class="form-select rounded-3" name="opsi_kehadiran"
                                                onchange="toggleSkenarioKembali(this, '{{ $order->id }}')">
                                                <option value="menunggu">🤝 Serah Terima Langsung (Saya akan menunggu
                                                    staf di lokasi)</option>
                                                <option value="titip">⚡ Contactless / Buru-buru (Motor akan saya
                                                    titipkan / parkir)</option>
                                            </select>
                                        </div>

                                        <div id="formContactless{{ $order->id }}"
                                            class="d-none border rounded-3 p-3 bg-light mb-3">
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold text-dark">Di Kita Kunci & STNK
                                                    Ditinggalkan?</label>
                                                <input type="text" class="form-control rounded-3"
                                                    name="posisi_kunci"
                                                    placeholder="Contoh: Dititip ke Resepsionis Hotel / Di dalam dasbor motor">
                                            </div>
                                            <div class="mb-0">
                                                <label class="form-label small fw-bold text-dark">Foto Posisi Kendaraan
                                                    Parkir</label>
                                                <input type="file" class="form-control rounded-3"
                                                    name="foto_bukti" accept="image/*">
                                                <small class="text-muted d-block mt-1">Unggah foto parkir unit untuk
                                                    mempercepat staf kami mengidentifikasi lokasi.</small>
                                            </div>
                                        </div>

                                        <div class="mb-0">
                                            <label class="form-label fw-bold small text-dark">Konfirmasi / Perubahan
                                                Alamat Jemput</label>
                                            <textarea class="form-control rounded-3" name="alamat_jemput_final" rows="2"
                                                placeholder="Tulis alamat detail jika ada pergeseran lokasi penjemputan...">{{ $order->alamat_pengantaran }}</textarea>
                                        </div>
                                    @else
                                        <div class="text-center py-2">
                                            <i class="bi bi-shop display-6 text-muted mb-2 d-block"></i>
                                            <p class="mb-0">
                                                Anda memilih metode <strong>Ambil Sendiri</strong> pada awal sewa.
                                                Silakan bawa kembali unit motor beserta helm dan STNK langsung ke
                                                <strong>Garasi Utama Kuda Besi</strong>.
                                            </p>
                                        </div>
                                    @endif

                                </div>
                                <div class="modal-footer bg-light p-3 border-top d-flex gap-2 justify-content-end">
                                    <button type="button"
                                        class="btn btn-light rounded-pill px-4 text-secondary small border"
                                        data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-dark rounded-pill px-4 small">Konfirmasi
                                        Selesai</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

        <div class="d-flex justify-content-center custom-pagination mt-5">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    </div>



</main>

<x-modal-orderList />



<script>
    function toggleSkenarioKembali(selectElement, orderId) {
        const formContactless = document.getElementById('formContactless' + orderId);
        if (selectElement.value === 'titip') {
            formContactless.classList.remove('d-none');
        } else {
            formContactless.classList.add('d-none');
        }
    }
</script>
