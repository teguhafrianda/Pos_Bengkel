@extends('install.layout')

@section('title', 'Buat Akun Admin')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">
        <div class="card installer-card">
            
            <div class="card-header p-4 bg-primary bg-soft border-0" style="border-radius: 15px 15px 0 0;">
                <div class="text-center">
                    <h4 class="text-primary mb-1">Akun Administrator</h4>
                    <p class="text-muted mb-0">Langkah 3: Buat akses utama untuk mengelola sistem</p>
                </div>
            </div>

            <div class="card-body p-4">
                
                <div class="row mb-4">
                    <div class="col-3 step-item text-center">
                        <div class="step-icon bg-success text-white"><i data-feather="check" style="width: 16px;"></i></div>
                        <small class="text-muted">Info</small>
                    </div>
                    <div class="col-3 step-item text-center">
                        <div class="step-icon bg-success text-white"><i data-feather="check" style="width: 16px;"></i></div>
                        <small class="text-muted">Database</small>
                    </div>
                    <div class="col-3 step-item active text-center">
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
                        <div class="d-flex">
                            <i data-feather="alert-circle" class="me-2" style="width: 20px;"></i>
                            <ul class="mb-0 ps-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ url('/install/admin') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i data-feather="user" class="text-muted" style="width: 16px;"></i></span>
                            <input type="text" name="name" class="form-control border-start-0 bg-light" placeholder="Masukkan nama Anda" value="{{ old('name') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Email (Username)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i data-feather="mail" class="text-muted" style="width: 16px;"></i></span>
                            <input type="email" name="email" class="form-control border-start-0 bg-light" placeholder="admin@bengkel.com" value="{{ old('email') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i data-feather="lock" class="text-muted" style="width: 16px;"></i></span>
                            <input type="password" name="password" class="form-control border-start-0 bg-light" placeholder="Min. 8 karakter" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Konfirmasi Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i data-feather="shield" class="text-muted" style="width: 16px;"></i></span>
                            <input type="password" name="password_confirmation" class="form-control border-start-0 bg-light" placeholder="Ulangi password" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between gap-3">
                        <a href="{{ url('/install/database') }}" class="btn btn-light px-4">
                            <i data-feather="arrow-left" class="me-1" style="width: 16px;"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-success px-4 flex-grow-1">
                            <i data-feather="download-cloud" class="me-1" style="width: 16px;"></i> Selesaikan Instalasi
                        </button>
                    </div>

                </form>
            </div>
        </div>

        <div class="mt-4 text-center">
            <p class="text-muted mb-0 small">Data ini akan digunakan untuk login pertama kali setelah instalasi selesai.</p>
        </div>
    </div>
</div>
@endsection