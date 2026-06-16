<nav class="navbar navbar-expand-lg navbar-light sticky-top py-3 border-bottom border-light custom-navbar">
    <div class="container">
    
        <a class="navbar-brand fw-extrabold fs-3 d-flex align-items-center position-relative" href="{{ route('home') }}"
            style="width: 250px; height: 40px;">
            <img src="{{ asset('storage/images/logo.webp') }}" alt="Kuda Besi Rent Logo"
                class="position-absolute start-0 top-50 translate-middle-y"
                style="height: 80px; width: auto; max-width: none;">
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu Navigasi -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center gap-1 mt-3 mt-lg-0">

                <li class="nav-item">
                    <a class="nav-link fw-semibold px-3 {{ request()->routeIs('home') ? 'text-carbon active-menu' : 'text-secondary' }}"
                        href="{{ route('home') }}">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fw-semibold px-3 {{ request()->routeIs('catalog') || request()->routeIs('motor.detail') ? 'text-carbon active-menu' : 'text-secondary' }}"
                        href="{{ route('catalog') }}">Pilih Kendaraan</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fw-semibold px-3 {{ request()->routeIs('customer.orders') || request()->routeIs('customer.orders.detail') ? 'text-carbon active-menu' : 'text-secondary' }}"
                        href="{{ route('customer.orders') }}">Daftar Pesanan</a>
                </li>

                @auth
                    <li class="nav-item dropdown ms-lg-3 mt-2 mt-lg-0 w-70 w-lg-auto">
                        <a class="nav-link dropdown-toggle fw-semibold text-carbon d-flex align-items-center justify-content-center justify-content-lg-start gap-2 rounded-pill profile-pill"
                            href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-person-circle fs-5 text-titanium"></i>
                            <span>Hi, {{ Auth::user()->name }}</span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom mt-2 animate fade-in"
                            aria-labelledby="profileDropdown">
                            <li class="px-3 py-2 border-bottom border-light mb-2 bg-light rounded-3">
                                <span class="d-block small text-muted" style="font-size: 0.75rem;">Akun Pengguna</span>
                                <span
                                    class="fw-bold text-carbon small d-block text-truncate">{{ Auth::user()->email }}</span>
                            </li>
                            @if (Auth::user()->role === 'admin')
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 text-primary fw-bold"
                                        href="/admin">
                                        <i class="bi bi-speedometer2"></i> Panel Admin
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider border-light my-1">
                                </li>
                            @endif
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2"
                                    href="{{ route('customer.profile') }}">
                                    <i class="bi bi-person-gear text-secondary"></i> Detail Profil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2"
                                    href="{{ route('customer.orders') }}">
                                    <i class="bi bi-receipt text-secondary"></i> Pesanan Saya
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider border-light my-1">
                            </li>
                            <li>
                                <a class="dropdown-item dropdown-item-danger d-flex align-items-center gap-2 text-danger"
                                    href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right"></i> Keluar
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <div class="d-flex gap-2 ms-lg-3 mt-2 mt-lg-0">
                        <a href="{{ route('login') }}" class="btn btn-outline-carbon rounded-pill px-4">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-carbon rounded-pill px-4">Daftar</a>
                    </div>
                @endauth

            </ul>
        </div>
    </div>
</nav>
