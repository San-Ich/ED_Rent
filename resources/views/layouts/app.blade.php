<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'KudaBesiRent - Rental Motor Modern')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- Google Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    {{-- AOS Animation --}}
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />


    <style>
        /* BASE STYLE: MONOCHROME PLATINUM */
        :root {
            --carbon-solid: #0f172a;
            --carbon-hover: #1e293b;
            --titanium-gray: #475569;
            --slate-text: #334155;
            --clean-bg: #f8fafc;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--clean-bg);
            color: var(--slate-text);
            overflow-x: hidden;
        }

        /* PALETTE & ACCENTS */
        .text-carbon {
            color: var(--carbon-solid) !important;
        }

        .text-titanium {
            color: var(--titanium-gray) !important;
        }

        .bg-carbon {
            background-color: var(--carbon-solid) !important;
        }

        .bg-titanium {
            background-color: var(--titanium-gray) !important;
        }

        .bg-light-soft {
            background-color: #f1f5f9 !important;
        }

        /* BUTTONS PREMIUM SOLID TRANSITIONS */
        .btn-carbon {
            background-color: #1a1a1a;
            color: #ffffff;
            border: 1px solid #1a1a1a;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-carbon:hover {
            background-color: rgba(255, 255, 255, 0.9);
            color: #1a1a1a;
            border-color: #1a1a1a;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-outline-carbon {
            background-color: transparent;
            border: 2px solid #1a1a1a;
            color: #1a1a1a;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-carbon:hover {
            background-color: #1a1a1a;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.05);
        }

        /* CUSTOM NAVBAR */
        .custom-navbar {
            background-color: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            transition: all 0.3s ease;
        }

        .custom-navbar .nav-link {
            position: relative;
            padding-bottom: 6px !important;
            transition: color 0.2s ease;
        }

        .custom-navbar .nav-link.active-menu::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 1rem;
            right: 1rem;
            height: 2px;
            background-color: #1a1a1a;
            border-radius: 2px;
        }

        .custom-navbar .nav-link:hover {
            color: #1a1a1a !important;
        }

        .profile-pill {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef !important;
            padding: 0.375rem 1rem !important;
            transition: all 0.2s ease;
        }

        .profile-pill:hover,
        .profile-pill[aria-expanded="true"] {
            background-color: #ffffff;
            border-color: #1a1a1a !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        /* PROFILE DROPDOWN STYLING (Sudah digabung & dirapikan) */
        .dropdown-menu-custom {
            border: 1px solid rgba(0, 0, 0, 0.08) !important;
            border-radius: 16px !important;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08) !important;
            padding: 0.5rem !important;
            overflow: hidden;
            min-width: 220px;
        }

        .dropdown-menu-custom .dropdown-item {
            padding: 0.625rem 1rem;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--titanium-gray);
            transition: all 0.2s ease;
        }

        .dropdown-menu-custom .dropdown-item:hover {
            background-color: #f8f9fa;
            color: var(--carbon-solid);
            transform: translateX(3px);
        }

        .dropdown-menu-custom .dropdown-item-danger:hover {
            background-color: #fff5f5;
            color: #e53e3e !important;
        }

        /* HERO SECTION */
        .hero-section {
            background-image: linear-gradient(rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.25)), url("{{ asset('storage/images/bg-kudabesirent.jpeg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: scroll;
            min-height: 85vh;
            display: flex;
            align-items: center;
            position: relative;
        }

        .hero-text-card {
            background-color: rgba(255, 255, 255, 0.35);
            backdrop-filter: blur(20px) saturate(160%);
            -webkit-backdrop-filter: blur(20px) saturate(160%);
            border: 1px solid rgba(255, 255, 255, 0.45);
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.25);
        }

        .hero-text-card h1 {
            color: #111111 !important;
        }

        .hero-text-card .text-muted-dark {
            color: #2c3e50;
            font-weight: 500;
        }

        /* FLOATING SEARCH BOX */
        .search-box-container {
            position: relative;
            z-index: 105;
            margin-top: -50px;
        }

        .search-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
        }

        .form-control-clean,
        .form-select-clean {
            background-color: var(--clean-bg);
            border: 1px solid #e2e8f0;
            color: var(--carbon-solid);
            font-weight: 500;
        }

        .form-control-clean:focus,
        .form-select-clean:focus {
            background-color: #ffffff;
            border-color: var(--carbon-solid);
            color: var(--carbon-solid);
            box-shadow: 0 0 0 4px rgba(15, 23, 42, 0.05);
        }

        /* CAROUSEL SETUP */
        .carousel-item-img {
            height: 480px;
            object-fit: cover;
        }

        @media (max-width: 768px) {
            .carousel-item-img {
                height: 320px;
            }
        }

        .carousel-image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, rgba(20, 20, 20, 0.85) 20%, rgba(20, 20, 20, 0.4) 60%, rgba(20, 20, 20, 0.1) 100%);
            z-index: 1;
        }

        .carousel-caption-custom {
            z-index: 2;
            bottom: 12% !important;
            left: 8% !important;
            right: 8% !important;
        }

        .carousel-control-custom-icon {
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .carousel-control-prev:hover .carousel-control-custom-icon,
        .carousel-control-next:hover .carousel-control-custom-icon {
            background: #ffffff;
            color: #111111;
            transform: scale(1.1);
        }

        .carousel-indicators [data-bs-target] {
            width: 35px;
            height: 4px;
            border-radius: 2px;
            background-color: rgba(255, 255, 255, 0.3);
        }

        .carousel-indicators .active {
            background-color: #ffffff;
        }

        .badge-platinum-glass {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #ffffff;
            letter-spacing: 1.5px;
        }

        /* MOTOR CARD */
        .hover-animate {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .card-motor:hover {
            transform: translateY(-10px);
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, .175) !important;
            /* Perbaikan dari shadow: ke box-shadow: */
        }

        .img-zoom {
            transition: transform 0.5s ease;
        }

        .card-motor:hover .img-zoom {
            transform: scale(1.08);
        }

        .btn-hover-effect {
            transition: all 0.2s ease;
        }

        .btn-hover-effect:hover {
            transform: scale(1.03);
            background-color: #1a1a1a !important;
        }

        /* FEATURE CARDS */
        .feature-card {
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            background-color: #ffffff !important;
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.08) !important;
            border-color: #dee2e6 !important;
        }

        .icon-wrapper {
            transition: transform 0.4s ease;
        }

        .feature-card:hover .icon-wrapper {
            transform: scale(1.15) rotate(5deg);
        }

        /* TERMS & CONDITIONS (S&K) */
        .sk-card {
            transition: all 0.3s ease;
        }

        .sk-card:hover {
            background-color: #f8f9fa !important;
            /* Mengubah var bootstrap ke solid hex agar aman */
            transform: translateX(5px);
        }

        .icon-circle {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        /* FOOTER ELEMENTS */
        .footer-link-platinum {
            transition: color 0.2s ease, transform 0.2s ease;
            display: inline-block;
        }

        .footer-link-platinum:hover {
            color: #e5e5e5 !important;
            transform: translateX(3px);
        }

        .social-icon-platinum {
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(229, 229, 229, 0.05);
            border: 1px solid rgba(229, 229, 229, 0.1);
            color: rgba(229, 229, 229, 0.55);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .social-icon-platinum:hover {
            color: #ffffff;
            background: rgba(229, 229, 229, 0.15);
            transform: translateY(-4px);
            border-color: rgba(229, 229, 229, 0.3);
            box-shadow: 0 4px 12px rgba(229, 229, 229, 0.1);
        }
    </style>
</head>

<body>

    <x-navbar />
    <main>
        @yield('content')
    </main>
    <x-footer />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>

    <script>
        AOS.init({
            duration: 700,
            easing: 'ease-out-cubic',

            disable: function() {
                return window.innerWidth < 768;
            },

            offset: window.innerWidth < 1025 ? 20 : 120,

            once: true,
            delay: 50
        });
    </script>

</body>

</html>
