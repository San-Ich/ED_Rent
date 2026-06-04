<section id="terpopuler" class="container py-5">
    <div class="text-center mb-5">
        <h2 class="text-carbon section-title">Pilihan <span class="text-titanium">Motor</span> Terpopuler</h2>
        <p class="text-secondary mt-3">Daftar motor dengan performa tinggi yang paling sering disewa oleh para pengendara
            andal.</p>
    </div>

    <div class="row g-4 py-3">
        @foreach ($motors as $index => $motor)
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 4) * 150 }}">
                <div
                    class="card card-motor h-100 border border-light-subtle shadow-sm rounded-4 overflow-hidden position-relative hover-animate bg-white">

                    <div class="position-absolute top-0 inset-s-0 m-3" style="z-index: 10;">
                        <span
                            class="badge bg-dark bg-gradient text-white px-3 py-2 rounded-pill fw-bold small shadow-sm d-flex align-items-center gap-1">
                            <i class="bi bi-fire text-warning animate-pulse"></i> RANK #{{ $index + 1 }}
                        </span>
                    </div>

                    <div class="bg-light d-flex align-items-center justify-content-center position-relative border-bottom border-light"
                        style="height: 205px; overflow: hidden;">
                        <img src="{{ asset('storage/' . $motor->image) }}" alt="{{ $motor->brand }} {{ $motor->model }}"
                            class="w-100 h-100 object-cover img-zoom">

                        <div class="position-absolute bottom-0 inset-s-0 w-100 h-25"
                            style="background: linear-gradient(to top, rgba(255,255,255,1), rgba(255,255,255,0));">
                        </div>
                    </div>

                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-muted text-uppercase tracking-wider font-sans-serif"
                                    style="font-size: 0.75rem; letter-spacing: 1px;">
                                    {{ $motor->brand }}
                                </span>
                            </div>

                            <h5 class="card-title fw-extrabold text-dark mb-2 text-truncate" title="{{ $motor->model }}"
                                style="letter-spacing: -0.5px;">
                                {{ $motor->model }}
                            </h5>

                            <span
                                class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle rounded-2 px-2 py-1 mb-3"
                                style="font-size: 0.75rem;">
                                <i class="bi bi-tag-fill me-1"></i>{{ $motor->category->name ?? 'Sport & Matic' }}
                            </span>

                            <div
                                class="row g-2 text-secondary small mb-3 border-top border-bottom py-3 my-2 bg-light bg-opacity-50 rounded-3 px-1">
                                <div class="col-6 d-flex align-items-center gap-2">
                                    <i class="bi bi-lightning-charge-fill text-carbon fs-6"></i>
                                    <span
                                        class="text-dark-emphasis fw-medium">{{ $motor->specification->kapasitas_mesin ?? '150 cc' }}</span>
                                </div>
                                <div class="col-6 d-flex align-items-center gap-2">
                                    <i class="bi bi-gear-fill text-carbon fs-6"></i>
                                    <span
                                        class="text-dark-emphasis fw-medium text-truncate">{{ $motor->specification->transmisi ?? 'Manual' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-2">
                            <div class="d-flex justify-content-between align-items-baseline mb-3">
                                <span class="text-muted small">Harga Sewa</span>
                                <div class="text-end">
                                    <span class="text-dark fw-black fs-4 font-sans-serif">
                                        Rp{{ number_format($motor->harga_per_hari, 0, ',', '.') }}
                                    </span>
                                    <span class="text-muted small fw-normal">/hari</span>
                                </div>
                            </div>

                            <a href="{{ route('catalog', ['category' => $motor->category->name ?? '', 'search' => $motor->model]) }}"
                                class="btn btn-dark btn-hover-effect w-100 py-25 rounded-pill fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-calendar-check-fill small"></i> Sewa Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</section>
