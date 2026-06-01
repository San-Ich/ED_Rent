<nav class="navbar navbar-expand-lg navbar-light sticky-top py-3 border-bottom border-light custom-navbar">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand fw-extrabold fs-3 d-flex align-items-center" href="{{ route('home') }}">
            <i class="bi bi-gear-fill text-carbon me-2 animate-gear"></i>
            <span class="fw-black text-carbon" style="letter-spacing: -0.5px;">KUDA</span><span class="text-titanium fw-light">BESI</span><span class="fs-6 text-muted ms-1 fw-bold">RENT</span>
        </a>
        
        <!-- Toggler untuk Mobile -->
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
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

                <li class="nav-item dropdown ms-lg-3 mt-2 mt-lg-0 w-100 w-lg-auto">
                    <a class="nav-link dropdown-toggle fw-semibold text-carbon d-flex align-items-center justify-content-center justify-content-lg-start gap-2 rounded-pill profile-pill"
                        href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle fs-5 text-titanium"></i>
                        <span>Hi, Ridwan</span>
                    </a>
                    
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom mt-2 animate fade-in" aria-labelledby="profileDropdown">
                        <li class="px-3 py-2 border-bottom border-light mb-2 bg-light rounded-3">
                            <span class="d-block small text-muted" style="font-size: 0.75rem;">Akun Pengguna</span>
                            <span class="fw-bold text-carbon small d-block text-truncate">ridwan@example.com</span>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="#profile">
                                <i class="bi bi-person-gear text-secondary"></i> Detail Profil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="#pesanan">
                                <i class="bi bi-receipt text-secondary"></i> Pesanan Saya
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="#bantuan">
                                <i class="bi bi-question-circle text-secondary"></i> Bantuan
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider border-lightmy-1">
                        </li>
                        <li>
                            <a class="dropdown-item dropdown-item-danger d-flex align-items-center gap-2 text-danger" href="#logout">
                                <i class="bi bi-box-arrow-right"></i> Keluar
                            </a>
                        </li>
                    </ul>
                </li>
                
            </ul>
        </div>
    </div>
</nav>