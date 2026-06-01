<section class="container filter-container">
    <div class="filter-card">
        <div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-2">

            <a href="{{ request()->fullUrlWithQuery(['status' => null, 'page' => null]) }}"
                class="filter-pill text-decoration-none {{ !request('status') ? 'active' : '' }}">
                Semua Pesanan ({{ $counts['all'] }})
            </a>

            <a href="{{ request()->fullUrlWithQuery(['status' => 'Menunggu', 'page' => null]) }}"
                class="filter-pill text-decoration-none {{ request('status') == 'Menunggu' ? 'active' : '' }}">
                Menunggu Pembayaran ({{ $counts['Menunggu'] }})
            </a>

            <a href="{{ request()->fullUrlWithQuery(['status' => 'Disewa', 'page' => null]) }}"
                class="filter-pill text-decoration-none {{ request('status') == 'Disewa' ? 'active' : '' }}">
                Aktif/Berjalan ({{ $counts['Disewa'] }})
            </a>

            <a href="{{ request()->fullUrlWithQuery(['status' => 'Selesai', 'page' => null]) }}"
                class="filter-pill text-decoration-none {{ request('status') == 'Selesai' ? 'active' : '' }}">
                Selesai ({{ $counts['Selesai'] }})
            </a>

            <a href="{{ request()->fullUrlWithQuery(['status' => 'Gagal', 'page' => null]) }}"
                class="filter-pill text-decoration-none {{ request('status') == 'Gagal' ? 'active' : '' }}">
                Gagal ({{ $counts['Gagal'] }})
            </a>

        </div>
    </div>
</section>
