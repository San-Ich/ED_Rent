<section class="container py-5 border-top" style="border-color: #f1f5f9 !important;">
        <div class="mb-4 d-flex justify-content-between align-items-end">
            <div>
                <h4 class="fw-extrabold text-carbon mb-1" style="color: #0f172a; letter-spacing: -0.5px;">
                    Rekomendasi Kendaraan Lainnya
                </h4>
                <p class="text-secondary small mb-0">Pilihan unit premium lainnya yang siap melesat bersama Anda</p>
            </div>
            <a href="/catalog" class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-bold small"
                style="border-color: #cbd5e1; color: #334155;">
                Lihat Semua
            </a>
        </div>

        <div class="row g-4">
            @foreach ($rekomendasiMotors as $item)
                <div class="col-lg-3 col-md-6 col-sm-12 motor-item" data-aos="fade-up"
                    data-aos-delay="{{ ($loop->index % 4) * 150 }}" data-aos-offset="20"
                    data-category="{{ Str::slug($item->category->name ?? 'unknown') }}">

                    <div
                        class="card-motor-clean h-100 d-flex flex-column position-relative bg-white rounded-4 overflow-hidden border border-light-subtle shadow-sm transition-all">

                        <div class="motor-img-container position-relative overflow-hidden"
                            style="height: 180px; width: 100%;">
                            @if ($item->status === 'Tersedia' || $item->status === 'tersedia')
                                <span
                                    class="badge-status position-absolute top-3 inset-s-3 z-3 bg-success text-white px-2.5 py-1 rounded-pill small fw-bold tracking-wide shadow-sm"
                                    style="font-size: 0.75rem;">
                                    Tersedia
                                </span>
                            @else
                                <span
                                    class="badge-status position-absolute top-3 inset-s-3 z-3 bg-danger text-white px-2.5 py-1 rounded-pill small fw-bold tracking-wide shadow-sm"
                                    style="font-size: 0.75rem;">
                                    Disewa
                                </span>
                            @endif

                            <img src="{{ $item->image ? asset('storage/' . $item->image) : 'https://placehold.co/400x300/f8fafc/0f172a?text=' . urlencode($item->model) }}"
                                class="w-100 h-100 object-fit-cover transition-transform duration-500 motor-img"
                                alt="{{ $item->model }}">
                        </div>

                        <div class="p-3 grow d-flex flex-column justify-content-between bg-white">
                            <div>
                                <h6 class="fw-bold text-carbon mb-1 text-truncate-2"
                                    style="font-size: 0.95rem; line-height: 1.4;">
                                    {{ $item->brand }} {{ $item->model }}
                                </h6>
                                <span
                                    class="badge bg-light text-secondary border border-light-subtle rounded-3 mb-2 d-inline-block"
                                    style="font-size: 0.7rem; padding: 4px 8px;">
                                    {{ $item->category_id ? $item->category->name : 'Kategori Tidak Diketahui' }}
                                </span>

                                <div class="d-flex gap-2 text-secondary mb-3 border-top border-bottom py-2"
                                    style="font-size: 0.75rem;">
                                    <span class="d-flex align-items-center text-truncate">
                                        <i class="bi bi-lightning-charge-fill text-carbon me-1"></i>
                                        {{ $item->specification->cc ?? ($item->specification->kapasitas_mesin ?? 'Tidak Diketahui') }}
                                        CC
                                    </span>
                                    <span class="d-flex align-items-center text-truncate" style="max-width: 200px;"
                                        title="{{ $item->specification->transmisi ?? '' }}">
                                        <i class="bi bi-gear-fill text-carbon me-1"></i>
                                        <span class="text-truncate">
                                            {{ $item->specification->transmisi ?? 'Tidak Diketahui' }}
                                        </span>
                                    </span>
                                </div>
                            </div>

                            <div class="d-flex flex-column gap-2 pt-1">
                                <div class="d-flex justify-content-between align-items-end">
                                    <div>
                                        <span class="text-muted d-block"
                                            style="font-size: 0.65rem; text-transform: uppercase;">Harga Sewa</span>
                                        <span class="text-carbon fw-extrabold" style="font-size: 1rem;">
                                            Rp {{ number_format($item->harga_per_hari, 0, ',', '.') }}
                                            <span class="text-secondary fw-normal" style="font-size: 0.75rem;">/hari</span>
                                        </span>
                                    </div>

                                    <div class="text-end">
                                        <span class="text-muted d-block"
                                            style="font-size: 0.65rem; text-transform: uppercase;">Plat Nomor</span>
                                        <span
                                            class="badge bg-light text-dark border border-secondary-subtle px-2 py-1 fw-bold font-monospace rounded"
                                            style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                            {{ $item->plate_nomor ?? ($item->plat_nomor ?? 'B 1234 ABC') }}
                                        </span>
                                    </div>
                                </div>

                                <a href="{{ route('catalog.show', $item->slug) }}"
                                    class="btn btn-carbon btn-sm w-100 py-2 rounded-pill fw-bold dynamic-btn"
                                    style="font-size: 0.8rem;">
                                    <i class="bi bi-bag-plus-fill me-1"></i> Sewa
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    </section>