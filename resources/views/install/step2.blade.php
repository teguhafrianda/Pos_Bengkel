@extends('install.layout')

@section('title', 'Konfigurasi Database')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">
        <div class="card installer-card">
            
            <div class="card-header p-4 bg-primary bg-soft border-0" style="border-radius: 15px 15px 0 0;">
                <div class="text-center">
                    <h4 class="text-primary mb-1">Koneksi Database</h4>
                    <p class="text-muted mb-0">Langkah 2: Hubungkan aplikasi ke database Anda</p>
                </div>
            </div>

            <div class="card-body p-4">
                
                <div class="row mb-4">
                    <div class="col-3 step-item text-center">
                        <div class="step-icon active bg-success text-white"><i data-feather="check" style="width: 16px;"></i></div>
                        <small class="text-muted">Info</small>
                    </div>
                    <div class="col-3 step-item active text-center">
                        <div class="step-icon"><i data-feather="database" style="width: 18px;"></i></div>
                        <small>Database</small>
                    </div>
                    <div class="col-3 step-item text-center text-muted">
                        <div class="step-icon"><i data-feather="user" style="width: 18px;"></i></div>
                        <small>Admin</small>
                    </div>
                    <div class="col-3 step-item text-center text-muted">
                        <div class="step-icon"><i data-feather="check-circle" style="width: 18px;"></i></div>
                        <small>Selesai</small>
                    </div>
                </div>

                <hr class="my-4 opacity-50">

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ url('/install/database') }}">
                    @csrf

                    <div class="row">
                        <div class="col-8 mb-3">
                            <label class="form-label fw-bold">DB Host</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i data-feather="server" class="text-muted" style="width: 16px;"></i></span>
                                <input type="text" name="db_host" class="form-control border-start-0 bg-light" value="127.0.0.1" required>
                            </div>
                        </div>

                        <div class="col-4 mb-3">
                            <label class="form-label fw-bold">Port</label>
                            <input type="text" name="db_port" class="form-control bg-light" value="3306" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Database</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i data-feather="database" class="text-muted" style="width: 16px;"></i></span>
                            <input type="text" name="db_name" class="form-control border-start-0 bg-light" placeholder="bengkel_db" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Username Database</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i data-feather="user" class="text-muted" style="width: 16px;"></i></span>
                            <input type="text" name="db_user" class="form-control border-start-0 bg-light" value="root" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Password Database</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i data-feather="lock" class="text-muted" style="width: 16px;"></i></span>
                            <input type="password" name="db_pass" class="form-control border-start-0 bg-light" placeholder="Kosongkan jika tidak ada">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between gap-3">
                        <a href="{{ url('/install') }}" class="btn btn-light px-4">
                            <i data-feather="arrow-left" class="me-1" style="width: 16px;"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary px-4 flex-grow-1">
                            Tes & Lanjutkan <i data-feather="chevron-right" class="ms-1" style="width: 16px;"></i>
                        </button>
                    </div>

                </form>
            </div>
        </div>
        
        <div class="mt-4 text-center">
            <p class="text-muted mb-0">Pastikan database sudah dibuat di phpMyAdmin sebelum melanjutkan.</p>
        </div>
    </div>
</div>
@endsection