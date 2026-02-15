@extends('install.layout')

@section('title', 'Informasi Aplikasi')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">
        <div class="card installer-card">
            
            <div class="card-header p-4 bg-primary bg-soft border-0" style="border-radius: 15px 15px 0 0;">
                <div class="text-center">
                    <h4 class="text-primary mb-1">Konfigurasi Awal</h4>
                    <p class="text-muted mb-0">Langkah 1: Atur identitas aplikasi Anda</p>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="row mb-4">
                    <div class="col-3 step-item active">
                        <div class="step-icon"><i data-feather="settings" style="width: 18px;"></i></div>
                        <div class="text-center"><small>Info</small></div>
                    </div>
                    <div class="col-3 step-item">
                        <div class="step-icon"><i data-feather="database" style="width: 18px;"></i></div>
                        <div class="text-center"><small>Database</small></div>
                    </div>
                    <div class="col-3 step-item">
                        <div class="step-icon"><i data-feather="user" style="width: 18px;"></i></div>
                        <div class="text-center"><small>Admin</small></div>
                    </div>
                    <div class="col-3 step-item">
                        <div class="step-icon"><i data-feather="check-circle" style="width: 18px;"></i></div>
                        <div class="text-center"><small>Selesai</small></div>
                    </div>
                </div>

                <hr class="my-4 opacity-50">

                <form method="POST" action="{{ url('/install/step1') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">Nama Aplikasi</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i data-feather="monitor" class="text-muted" style="width: 16px;"></i></span>
                            <input type="text" name="app_name" class="form-control border-start-0 bg-light" placeholder="Sistem Bengkel Pro" required>
                        </div>
                        <div class="form-text">Muncul di tab browser dan judul sistem.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark">Nama Bengkel / Bisnis</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i data-feather="briefcase" class="text-muted" style="width: 16px;"></i></span>
                            <input type="text" name="shop_name" class="form-control border-start-0 bg-light" placeholder="Bengkel Jaya Motor" required>
                        </div>
                        <div class="form-text">Nama ini akan dicetak pada nota/invoice.</div>
                    </div>

                    <div class="d-grid shadow-sm">
                        <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                            Lanjutkan Ke Database <i class="bx bx-right-arrow-alt ms-1"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-4 text-center">
            <p class="text-muted mb-0">© {{ date('Y') }} Bengkel App. All rights reserved.</p>
        </div>
    </div>
</div>
@endsection