@extends('layouts.app')

@section('title', 'Daftar Teknisi')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            {{-- Alert Success --}}
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="mdi mdi-check-all me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            {{-- Header --}}
            <div class="row mb-3">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Data Teknisi</h4>
                        <button class="btn btn-primary btn-sm waves-effect waves-light" type="button" data-bs-toggle="collapse" data-bs-target="#formTambahTeknisi">
                            <i class="bx bx-plus font-size-16 align-middle me-2"></i> Tambah Baru
                        </button>
                    </div>
                </div>
            </div>

            {{-- Form Tambah Teknisi (Collapse) --}}
            <div class="row collapse mb-4" id="formTambahTeknisi">
                <div class="col-12">
                    <div class="card shadow-sm" style="border-radius: 12px;">
                        <div class="card-body">
                            <form action="{{ route('teknisis.store') }}" method="POST">
                                @csrf
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-4">
                                        <label class="form-label small text-muted fw-bold">Nama Teknisi</label>
                                        <input type="text" name="name" class="form-control" placeholder="Masukkan nama lengkap" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small text-muted fw-bold">Email (Opsional)</label>
                                        <input type="email" name="email" class="form-control" placeholder="contoh@mail.com">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small text-muted fw-bold">No. HP (Opsional)</label>
                                        <input type="text" name="phone" class="form-control" placeholder="0812xxxx">
                                    </div>
                                    <div class="col-md-1 d-grid">
                                        {{-- Tombol Save disamakan dengan Sparepart --}}
                                        <button type="submit" class="btn btn-success waves-effect waves-light">
                                            <i class="bx bx-save font-size-16"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table Data --}}
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm" style="border-radius: 12px;">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50px;">#</th>
                                            <th>Nama Teknisi</th>
                                            <th>Email</th>
                                            <th>No. Telepon</th>
                                            <th style="width: 100px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($teknisis as $t)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="fw-medium">{{ $t->name }}</td>
                                            <td>{{ $t->email ?? '-' }}</td>
                                            <td>{{ $t->phone ?? '-' }}</td>
                                            <td>
                                                {{-- Tombol Hapus disamakan dengan Sparepart --}}
                                                <form action="{{ route('teknisis.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus teknisi ini?')">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-soft-danger btn-sm waves-effect waves-light">
                                                        <i class="bx bx-trash font-size-16"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5 text-muted">Belum ada data teknisi</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection