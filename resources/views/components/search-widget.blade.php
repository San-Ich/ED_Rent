<section class="container search-box-container d-block d-lg-none" data-aos="fade-up" data-aos-delay="200" data-aos-offset="20">
    <div class="search-card p-4 shadow-md border-0">
        <form action="{{ route('catalog') }}" method="GET" class="row g-3">
            
            <div class="col-12">
                <label class="form-label text-secondary small fw-bold text-uppercase">
                    <i class="bi bi-calendar2-range text-carbon me-1"></i> Tanggal Ambil
                </label>
                <input type="date" name="start_date" class="form-control form-control-clean py-2.5 rounded-3">
            </div>
            
            <div class="col-12">
                <label class="form-label text-secondary small fw-bold text-uppercase">
                    <i class="bi bi-calendar2-check text-carbon me-1"></i> Tanggal Kembali
                </label>
                <input type="date" name="end_date" class="form-control form-control-clean py-2.5 rounded-3">
            </div>
            
            <!-- Kategori Motor -->
            <div class="col-12">
                <label class="form-label text-secondary small fw-bold text-uppercase">
                    <i class="bi bi-funnel text-carbon me-1"></i> Kategori Motor
                </label>
                <select name="category" class="form-select form-select-clean py-2.5 rounded-3">
                    <option value="">Semua Jenis Motor</option>
                    <option value="Fairing">Fairing</option>
                    <option value="Matic">Matic</option>
                    <option value="Naked">Naked</option>
                    <option value="Underbone">Underbone/bebek</option>
                    <option value="Trail">Trail</option>
                </select>
            </div>
            
            <div class="col-12 d-grid pt-2">
                <button type="submit" class="btn btn-carbon py-2.5 rounded-3 fw-bold shadow-sm">
                    <i class="bi bi-search me-2"></i>Cari Motor Tersedia
                </button>
            </div>
            
        </form>
    </div>
</section>