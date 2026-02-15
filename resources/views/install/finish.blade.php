@extends('install.layout')

@section('title', 'Instalasi Berhasil')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">
        <div class="card installer-card py-4">
            <div class="card-body p-5 text-center">
                
                <div class="mb-4">
                    <div class="avatar-md mx-auto">
                        <div class="avatar-title bg-success-subtle text-success display-3 rounded-circle" style="width: 100px; height: 100px; margin: 0 auto; display: flex; align-items: center; justify-content: center; background: #d1f2e1;">
                            <i data-feather="check-circle" style="width: 48px; height: 48px;"></i>
                        </div>
                    </div>
                </div>

                <h3 class="fw-bold text-dark mb-2">Instalasi Berhasil! 🎉</h3>
                <p class="text-muted mb-4">
                    Selamat! Aplikasi bengkel Anda telah dikonfigurasi dengan sempurna dan database telah siap digunakan.
                </p>

                <div class="alert alert-light border-0 bg-light p-3 mb-4 text-start">
                    <div class="d-flex align-items-center mb-2">
                        <i data-feather="info" class="text-primary me-2" style="width: 16px;"></i>
                        <span class="fw-bold small text-uppercase">Tips Keamanan:</span>
                    </div>
                    <small class="text-muted d-block">
                        Jangan lupa untuk menghapus folder <code>/install</code> atau memastikan file <code>installed.txt</code> sudah ada untuk keamanan sistem Anda.
                    </small>
                </div>

                <div class="d-grid shadow-sm">
                    <a href="{{ url('/') }}" class="btn btn-primary btn-lg fw-semibold">
                        Masuk ke Dashboard <i data-feather="arrow-right" class="ms-1" style="width: 18px;"></i>
                    </a>
                </div>

                <div class="mt-4">
                    <a href="{{ url('/') }}" class="text-muted small">
                         Lihat Halaman Depan
                    </a>
                </div>

            </div>
        </div>

        <div class="mt-4 text-center">
            <p class="text-muted mb-0">Butuh bantuan? <a href="#" class="text-primary">Hubungi Support</a></p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
<script>
    window.onload = function() {
        confetti({
            particleCount: 150,
            spread: 70,
            origin: { y: 0.6 },
            colors: ['#5156be', '#2ab57d', '#4ba3ff']
        });
    };
</script>
@endsection