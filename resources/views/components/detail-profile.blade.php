<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<div class="container py-5 animate__animated animate__fadeIn">
    <div class="row g-4">

        @if (session('error'))
            <div class="alert alert-error border-0 rounded-4 shadow-sm p-3 mb-4 d-flex align-items-center gap-2"
                style="background-color: #fdf0f0; color: #651616;">
                <i class="bi bi-x-circle-fill fs-5"></i>
                <div>
                    <span class="fw-bold d-block" style="font-size: 0.85rem;">Gagal</span>
                    <span style="font-size: 0.8rem; opacity: 0.9;">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <div class="col-lg-4">
            <div class="card profile-card border-0 shadow-sm rounded-4 text-center p-4 h-100">
                <div class="avatar-wrapper my-4">
                    <div class="avatar-icon text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                        style="width: 100px; height: 100px; background-color: #2b2d42;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                </div>

                <h4 class="fw-bold text-dark mb-1">{{ $user->name }}</h4>
                <p class="text-muted small mb-3"><i class="bi bi-envelope me-1"></i> {{ $user->email }}</p>

                <div class="mb-4">
                    @if ($user->is_verified)
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-4 py-2 fw-semibold">
                            <i class="bi bi-patch-check-fill me-1 text-success"></i> Akun Terverifikasi
                        </span>
                    @elseif (!$user->is_verified && $user->catatan_verifikasi)
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-4 py-2 fw-semibold">
                            <i class="bi bi-exclamation-triangle-fill me-1 text-danger"></i> Dokumen Ditolak
                        </span>
                    @else
                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-4 py-2 fw-semibold text-dark">
                            <i class="bi bi-hourglass-split me-1 text-warning"></i> Menunggu Verifikasi
                        </span>
                    @endif
                </div>

                @if (!$user->is_verified && $user->catatan_verifikasi)
                    <div class="alert alert-warning border-0 rounded-4 shadow-sm text-start p-3 mb-3 animate__animated animate__headShake"
                        style="background-color: #fffbeb; color: #713f12;">
                        <small class="fw-bold d-block mb-1"><i class="bi bi-chat-left-dots-fill me-1"></i> Catatan Admin:</small>
                        <span style="font-size: 0.8rem; font-style: italic;">"{{ $user->catatan_verifikasi }}"</span>
                    </div>
                @endif

                @if (!$user->is_verified)
                    <div class="mb-4">
                        <form action="{{ route('customer.profile.request-verification') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm w-100 rounded-pill py-2.5 fw-bold shadow-sm">
                                <i class="bi bi-send-check-fill me-1"></i> Minta Verifikasi Akun
                            </button>
                        </form>
                    </div>
                @endif

                <div class="p-3 bg-light rounded-4 text-start mt-auto shadow-inner">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted d-block text-uppercase fw-bold"
                                style="font-size: 0.7rem; letter-spacing: 0.05em;">Maksimal Rental</small>
                            <span class="fw-extrabold text-dark fs-4">{{ $user->rental_limit }} <small
                                    class="fs-6 text-muted fw-normal">Hari</small></span>
                        </div>
                        <div class="bg-warning-subtle p-3 rounded-3 text-carbon">
                            <i class="bi bi-speedometer2 fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card profile-card border-0 shadow-sm rounded-4 p-4 p-md-5">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-carbon text-white rounded-3 p-2 me-3 shadow-sm" style="background-color: #2b2d42;">
                        <i class="bi bi-sliders fs-4"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold text-dark mb-0">Pengaturan Profil</h3>
                        <p class="text-muted small mb-0">Kelola informasi pribadi dan verifikasi identitas Anda</p>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 p-3 mb-4 animate__animated animate__headShake"
                        role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill fs-4 me-3 text-success"></i>
                            <div>
                                <h6 class="fw-bold mb-0">Berhasil!</h6>
                                <small class="text-muted">{{ session('success') }}</small>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 rounded-start-3 text-muted"><i
                                        class="bi bi-person"></i></span>
                                <input type="text" name="name"
                                    class="form-control border-start-0 rounded-end-3 py-2 @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}" required {{ $user->is_verified ? 'readonly' : '' }}>
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Nomor WhatsApp</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 rounded-start-3 text-muted"><i
                                        class="bi bi-whatsapp"></i></span>
                                <input type="text" name="phone"
                                    class="form-control border-start-0 rounded-end-3 py-2 @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 08123456789" required>
                            </div>
                            @error('phone')
                                <div class="invalid-feedback d-block small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-dark">Alamat Sekarang</label>
                            <textarea name="address" rows="3" class="form-control rounded-3 p-3 @error('address') is-invalid @enderror"
                                placeholder="Tuliskan alamat rumah lengkap Anda saat ini..." {{ $user->is_verified ? 'readonly' : '' }}>{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback d-block small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mt-5">
                            <div class="border-bottom pb-2 mb-4">
                                <h5 class="fw-bold text-dark mb-1"><i
                                        class="bi text-carbon bi-shield-check text-primary me-2"></i> Dokumen Identitas
                                </h5>
                                <p class="text-muted small mb-0">Dokumen akan dikunci otomatis oleh sistem setelah
                                    berhasil diverifikasi.</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Kartu Tanda Penduduk (KTP)</label>
                            <div class="p-3 rounded-4 doc-preview-box text-center" style="background-color: #f8f9fa; border: 2px dashed #dee2e6;">
                                @if ($user->ktp_path)
                                    <div class="mb-3">
                                        <a href="{{ asset('storage/' . $user->ktp_path) }}" target="_blank"
                                            class="btn btn-sm btn-light border rounded-pill px-3 shadow-sm">
                                            <i class="bi bi-eye text-primary me-1"></i> Lihat KTP Anda
                                        </a>
                                    </div>
                                @else
                                    <i class="bi bi-card-image text-muted fs-2 d-block mb-2"></i>
                                @endif
                                
                                @if(!$user->is_verified)
                                    <input type="file" name="ktp" class="form-control form-control-sm rounded-3">
                                @else
                                    <span class="text-success small d-block py-1"><i class="bi bi-lock-fill"></i> KTP Terkunci Keamanan</span>
                                @endif
                            </div>
                            @error('ktp')
                                <div class="invalid-feedback d-block small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Surat Izin Mengemudi (SIM)</label>
                            <div class="p-3 rounded-4 doc-preview-box text-center" style="background-color: #f8f9fa; border: 2px dashed #dee2e6;">
                                @if ($user->sim_path)
                                    <div class="mb-3">
                                        <a href="{{ asset('storage/' . $user->sim_path) }}" target="_blank"
                                            class="btn btn-sm btn-light border rounded-pill px-3 shadow-sm">
                                            <i class="bi bi-eye text-primary me-1"></i> Lihat SIM Anda
                                        </a>
                                    </div>
                                @else
                                    <i class="bi bi-card-image text-muted fs-2 d-block mb-2"></i>
                                @endif

                                @if(!$user->is_verified)
                                    <input type="file" name="sim" class="form-control form-control-sm rounded-3">
                                @else
                                    <span class="text-success small d-block py-1"><i class="bi bi-lock-fill"></i> SIM Terkunci Keamanan</span>
                                @endif
                            </div>
                            @error('sim')
                                <div class="invalid-feedback d-block small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mt-5">
                            <div class="border-bottom pb-2 mb-4">
                                <h5 class="fw-bold text-dark mb-1"><i
                                        class="bi text-carbon bi-key text-primary me-2"></i> Kredensial Keamanan</h5>
                                <p class="text-muted small mb-0">Biarkan kosong jika Anda tidak berniat mengganti
                                    password lama</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Password Baru</label>
                            <input type="password" name="password" class="form-control rounded-3 py-2"
                                placeholder="Minimal 8 karakter" {{ $user->is_verified ? 'disabled' : '' }}>
                            @error('password')
                                <div class="invalid-feedback d-block small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control rounded-3 py-2"
                                placeholder="Ulangi password baru" {{ $user->is_verified ? 'disabled' : '' }}>
                        </div>

                        <div class="col-12 text-end mt-5">
                            @if(!$user->is_verified)
                                <button type="submit"
                                    class="btn rounded-pill px-5 py-2.5 fw-bold shadow-sm transition-all text-white" style="background-color: #2b2d42;">
                                    <i class="bi bi-cloud-arrow-up-fill me-2"></i> Perbarui Data Profil
                                </button>
                            @else
                                <button type="button" class="btn btn-secondary rounded-pill px-5 py-2.5 fw-bold shadow-sm" disabled>
                                    <i class="bi bi-check-all me-2"></i> Data Sudah Valid & Terkunci
                                </button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>