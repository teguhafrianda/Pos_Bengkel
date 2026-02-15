@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<style>
    /* UI Enhancement */
    .profile-header-cover {
        height: 120px;
        background: linear-gradient(135deg, #5156be 0%, #3d42a5 100%);
        border-radius: 16px 16px 0 0;
    }
    
    .profile-avatar-wrapper {
        margin-top: -60px;
        position: relative;
        display: inline-block;
    }

    .profile-avatar {
        width: 110px;
        height: 110px;
        border: 5px solid #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }

    .info-label {
        color: #adb5bd;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }

    .info-value {
        font-weight: 600;
        color: #343a40;
    }

    .card {
        border-radius: 16px;
    }

    .form-control {
        border-radius: 10px;
        padding: 10px 15px;
        border: 1px solid #e9ecef;
        background-color: #f8f9fa;
    }

    .form-control:focus {
        background-color: #fff;
        border-color: #5156be;
        box-shadow: 0 0 0 0.2rem rgba(81, 86, 190, 0.1);
    }

    .btn-save {
        border-radius: 10px;
        padding: 10px 25px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(81, 86, 190, 0.3);
    }
</style>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <div class="row">
                {{-- ================== LEFT: PROFILE CARD ================== --}}
                <div class="col-xl-4 col-lg-5">
                    <div class="card border-0 shadow-sm overflow-hidden mb-4">
                        <div class="profile-header-cover"></div>
                        <div class="card-body text-center pt-0">
                            <div class="profile-avatar-wrapper">
                                <img src="https://ui-avatars.com/api/?name={{ $user->name }}&size=128&background=5156be&color=fff" 
                                     alt="Profile" class="rounded-circle profile-avatar">
                            </div>
                            
                            <h5 class="mt-3 mb-1 fw-bold text-dark">{{ $user->name }}</h5>
                            <p class="text-muted mb-4">{{ $user->role }}</p>

                            <hr class="my-4 opacity-50">

                            <div class="text-start">
                                <div class="mb-3">
                                    <p class="info-label">Alamat Email</p>
                                    <p class="info-value mb-0">{{ $user->email }}</p>
                                </div>
                                <div class="mb-3">
                                    <p class="info-label">Nama Bengkel</p>
                                    <p class="info-value mb-0 text-primary" id="bengkelNameDisplay">
                                        <i class="bx bx-store-alt me-1"></i>{{ $user->bengkel }}
                                    </p>
                                </div>
                                <div class="mb-0">
                                    <p class="info-label">Tanggal Bergabung</p>
                                    <p class="info-value mb-0">{{ $user->joined }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ================== RIGHT: EDIT FORM ================== --}}
                <div class="col-xl-8 col-lg-7">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-0 fw-bold">Pengaturan Akun</h5>
                                    <p class="text-muted small mb-0">Perbarui informasi profil dan identitas bengkel Anda.</p>
                                </div>
                                <i class="bx bx-user-circle fs-2 text-primary opacity-25"></i>
                            </div>
                        </div>
                        <div class="card-body p-4 pt-2">
                            
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                                    <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger border-0 shadow-sm mb-4" role="alert">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form id="profileForm" action="{{ route('profile.update') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold small">Nama Lengkap</label>
                                        <input type="text" class="form-control" name="name" value="{{ $user->name }}" placeholder="Contoh: Budi Santoso" required>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold small">Alamat Email</label>
                                        <input type="email" class="form-control" name="email" value="{{ $user->email }}" placeholder="budi@email.com" required>
                                    </div>

                                    <div class="col-12 mb-4">
                                        <label class="form-label fw-bold small">Nama Bengkel</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0" style="border-radius: 10px 0 0 10px;">
                                                <i class="bx bx-store text-primary"></i>
                                            </span>
                                            {{-- Name diganti menjadi shop_name agar sesuai dengan Controller & Database --}}
                                            <input type="text" class="form-control border-start-0" name="shop_name" id="bengkelInput" 
                                                   style="border-radius: 0 10px 10px 0;"
                                                   placeholder="Masukkan nama bengkel Anda" value="{{ $user->bengkel }}" required>
                                        </div>
                                        <div class="form-text text-muted">Nama ini akan muncul di struk pembayaran dan laporan penjualan.</div>
                                    </div>

                                    <div class="col-12"><hr class="my-3 opacity-50"></div>

                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold small">Password Baru</label>
                                        <input type="password" class="form-control" name="password" placeholder="Kosongkan jika tidak ganti">
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold small">Konfirmasi Password</label>
                                        <input type="password" class="form-control" name="password_confirmation" placeholder="Ulangi password baru">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-2">
                                    <button type="reset" class="btn btn-light px-4" style="border-radius: 10px;">Batal</button>
                                    {{-- Type diganti menjadi submit --}}
                                    <button type="submit" class="btn btn-primary btn-save px-4">
                                        <i class="bx bx-check-double me-1"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection