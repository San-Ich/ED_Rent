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
                                    onclick="showOrderDetail('{{ $order->id }}', '{{ $order->kode_booking }}', '{{ $order->motor->model ?? $order->motor_name }}', '{{ \Carbon\Carbon::parse($order->tanggal_mulai)->translatedFormat('d M Y (H:i)') }}', '{{ \Carbon\Carbon::parse($order->tanggal_rencana_kembali)->translatedFormat('d M Y (H:i)') }}', 'Rp {{ number_format($order->total_harga, 0, ',', '.') }}', '{{ $order->status }}', '{{ $order->motor && $order->motor->image ? asset('storage/' . $order->motor->image) : '' }}', '{{ $order->payment_proof ? 1 : 0 }}')">
                                    <i class="bi bi-info-circle"></i>
                                </button>
                            @elseif($order->status === 'active' || $order->status === 'Disewa')
                                <a href="{{ route('customer.rental.download-struk', $order->id) }}"
                                    class="btn btn-success btn-sm rounded-pill">
                                    <i class="bi bi-download me-1"></i> Struk
                                </a>

                                @if (\Carbon\Carbon::parse($order->tanggal_rencana_kembali)->subHours(2)->isPast())
                                    <form action="{{ route('customer.rental.kembalikan', $order->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm rounded-pill"
                                            onclick="return confirm('Apakah Anda yakin ingin mengonfirmasi pengembalian motor saat ini?')">
                                            <i class="bi bi-arrow-left-right me-1"></i> Kembalikan Motor
                                        </button>
                                    </form>
                                @endif

                                <button type="button" class="btn btn-outline-primary btn-md rounded-pill px-3"
                                    onclick="showOrderDetail('{{ $order->id }}', '{{ $order->kode_booking }}', '{{ $order->motor->model ?? $order->motor_name }}', '{{ \Carbon\Carbon::parse($order->tanggal_mulai)->translatedFormat('d M Y (H:i)') }}', '{{ \Carbon\Carbon::parse($order->tanggal_rencana_kembali)->translatedFormat('d M Y (H:i)') }}', 'Rp {{ number_format($order->total_harga, 0, ',', '.') }}', '{{ $order->status }}', '{{ $order->motor && $order->motor->image ? asset('storage/' . $order->motor->image) : '' }}', '{{ $order->payment_proof ? 1 : 0 }}')">
                                    <i class="bi bi-info-circle"></i>
                                </button>
                            @elseif($order->status === 'Pending Denda')
                                <a href="{{ route('customer.rental.denda', $order->id) }}"
                                    class="btn btn-danger btn-md rounded-pill text-white fw-bold px-4 border-0 shadow-sm animate__animated animate__pulse animate__infinite">
                                    <i class="bi bi-wallet2 me-1"></i> Bayar Denda
                                </a>

                                <button type="button" class="btn btn-outline-secondary btn-md rounded-pill px-3"
                                    onclick="showOrderDetail('{{ $order->id }}', '{{ $order->kode_booking }}', '{{ $order->motor->model ?? $order->motor_name }}', '{{ \Carbon\Carbon::parse($order->tanggal_mulai)->translatedFormat('d M Y (H:i)') }}', '{{ \Carbon\Carbon::parse($order->tanggal_rencana_kembali)->translatedFormat('d M Y (H:i)') }}', 'Rp {{ number_format($order->total_harga, 0, ',', '.') }}', '{{ $order->status }}', '{{ $order->motor && $order->motor->image ? asset('storage/' . $order->motor->image) : '' }}', '{{ $order->payment_proof ? 1 : 0 }}')">
                                    <i class="bi bi-info-circle"></i>
                                </button>
                            @elseif($order->status === 'Menunggu Verifikasi')
                                <button type="button" class="btn btn-outline-warning btn-md rounded-pill px-4"
                                    disabled>
                                    <i class="bi bi-hourglass-split me-1"></i> Sedang Dicek
                                </button>

                                <button type="button" class="btn btn-outline-secondary btn-md rounded-pill px-3"
                                    onclick="showOrderDetail('{{ $order->id }}', '{{ $order->kode_booking }}', '{{ $order->motor->model ?? $order->motor_name }}', '{{ \Carbon\Carbon::parse($order->tanggal_mulai)->translatedFormat('d M Y (H:i)') }}', '{{ \Carbon\Carbon::parse($order->tanggal_rencana_kembali)->translatedFormat('d M Y (H:i)') }}', 'Rp {{ number_format($order->total_harga, 0, ',', '.') }}', '{{ $order->status }}', '{{ $order->motor && $order->motor->image ? asset('storage/' . $order->motor->image) : '' }}', '{{ $order->payment_proof ? 1 : 0 }}')">
                                    <i class="bi bi-info-circle"></i>
                                </button>
                            @elseif($order->status === 'failed' || $order->status === 'Gagal')
                                <a href="{{ route('catalog.show', $order->id) }}"
                                    class="btn btn-danger btn-md rounded-pill text-white px-4 border-0 shadow-sm">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Sewa Ulang
                                </a>
                            @else
                                <button type="button" class="btn btn-outline-secondary btn-md rounded-pill px-4"
                                    onclick="showOrderDetail('{{ $order->id }}', '{{ $order->kode_booking }}', '{{ $order->motor->model ?? $order->motor_name }}', '{{ \Carbon\Carbon::parse($order->tanggal_mulai)->translatedFormat('d M Y (H:i)') }}', '{{ \Carbon\Carbon::parse($order->tanggal_rencana_kembali)->translatedFormat('d M Y (H:i)') }}', 'Rp {{ number_format($order->total_harga, 0, ',', '.') }}', '{{ $order->status }}', '{{ $order->motor && $order->motor->image ? asset('storage/' . $order->motor->image) : '' }}', '{{ $order->payment_proof ? 1 : 0 }}')">
                                    <i class="bi bi-file-earmark-text me-1"></i> Riwayat
                                </button>
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
                <a href="{{ url('/catalog') }}" class="btn btn-carbon rounded-pill px-4 mt-2 text-decoration-none">Sewa
                    Motor Sekarang</a>
            </div>
        @endforelse
        <div class="d-flex justify-content-center custom-pagination mt-5">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    </div>
</main>

<x-modal-orderList />
