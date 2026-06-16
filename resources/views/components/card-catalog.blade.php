<div class="container my-5">
    <div class="row g-4">

        @foreach ($motors as $motor)
            <div class="col-lg-3 col-md-6 col-sm-12 motor-item" data-aos="fade-up"
                data-aos-delay="{{ ($loop->index % 4) * 150 }}" data-aos-offset="20"
                data-category="{{ Str::slug($motor->category->name) }}">

                <div
                    class="card-motor-clean h-100 d-flex flex-column position-relative bg-white rounded-4 overflow-hidden border border-light-subtle shadow-sm transition-all">


                    <div class="motor-img-container position-relative overflow-hidden"
                        style="height: 180px; width: 100%;">

                        @if ($motor->status === 'Tersedia')
                            <span class="position-absolute top-0 start-0 m-3 z-3 border shadow-sm"
                                style="padding: 4px 12px; border-radius: 50px; font-size: 0.7rem; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; background-color: rgba(255, 255, 255, 0.95); color: #0f172a; border-color: rgba(15, 23, 42, 0.1) !important;">
                                <i class="bi bi-circle-fill me-1 text-success"
                                    style="font-size: 0.45rem; vertical-align: middle;"></i> Tersedia
                            </span>
                        @elseif ($motor->status === 'Disewa')
                            <span class="position-absolute top-0 start-0 m-3 z-3 shadow-sm"
                                style="padding: 4px 12px; border-radius: 50px; font-size: 0.7rem; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; background-color: #0f172a; color: #ffffff;">
                                <i class="bi bi-circle-fill me-1 text-danger"
                                    style="font-size: 0.45rem; vertical-align: middle;"></i> Disewa
                            </span>
                        @else
                            <span class="position-absolute top-0 start-0 m-3 z-3 border shadow-sm"
                                style="padding: 4px 12px; border-radius: 50px; font-size: 0.7rem; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; background-color: #f8fafc; color: #64748b; border-color: #e2e8f0 !important;">
                                {{ $motor->status }}
                            </span>
                        @endif

                        <img src="{{ $motor->image ? asset('storage/' . $motor->image) : 'https://placehold.co/400x300/f8fafc/0f172a?text=' . urlencode($motor->model) }}"
                            class="w-100 h-100 object-fit-cover transition-transform duration-500 motor-img"
                            alt="{{ $motor->model }}">
                    </div>

                    <div class="p-3 grow d-flex flex-column justify-content-between bg-white">
                        <div>
                            <h6 class="fw-bold text-carbon mb-1 text-truncate-2"
                                style="font-size: 0.95rem; line-height: 1.4;">
                                {{ $motor->model }}
                            </h6>
                            <span
                                class="badge bg-light text-secondary border border-light-subtle rounded-3 mb-2 d-inline-block"
                                style="font-size: 0.7rem; padding: 4px 8px;">
                                {{ $motor->category_id ? $motor->category->name : 'Kategori Tidak Diketahui' }}
                            </span>

                            <div class="d-flex gap-2 text-secondary mb-3 border-top border-bottom py-2"
                                style="font-size: 0.75rem;">
                                <span class="d-flex align-items-center text-truncate"><i
                                        class="bi bi-lightning-charge-fill text-carbon me-1"></i>
                                    {{ $motor->specification->kapasitas_mesin ?? 'Kapasitas Tidak Diketahui' }}
                                </span>
                                <span class="d-flex align-items-center text-truncate" style="max-width: 200px;"
                                    title="{{ $motor->specification->transmisi }}">
                                    <i class="bi bi-gear-fill text-carbon me-1"></i>
                                    <span class="text-truncate">
                                        {{ $motor->specification->transmisi ?? 'Transmisi Tidak Diketahui' }}
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
                                        Rp {{ number_format($motor->harga_per_hari, 0, ',', '.') }}<span
                                            class="text-secondary fw-normal" style="font-size: 0.75rem;">/hari</span>
                                    </span>
                                </div>

                                <div class="text-end">
                                    <span class="text-muted d-block"
                                        style="font-size: 0.65rem; text-transform: uppercase;">Plat Nomor</span>
                                    <span
                                        class="badge bg-light text-dark border border-secondary-subtle px-2 py-1 fw-bold font-monospace rounded"
                                        style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                        {{ $motor->plate_nomor ?? 'B 1234 ABC' }}
                                    </span>
                                </div>
                            </div>

                            <a href="{{ route('catalog.show', $motor->id) }}"
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


    <div class="d-flex justify-content-center custom-pagination mt-5" data-aos="fade-up">
        {{ $motors->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>
