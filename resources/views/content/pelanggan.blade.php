@extends('layouts.app')

@section('title', 'Pelanggan')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            {{-- Header --}}
            <div class="row mb-3">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 font-size-18">Daftar Pelanggan</h4>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#formPelanggan">
                        <i data-feather="plus" class="icon-xs me-1"></i> Tambah Pelanggan
                    </button>
                </div>
            </div>

            {{-- Form Tambah/Edit --}}
            <div class="row mb-3 collapse" id="formPelanggan">
                <div class="col-12">
                    <div class="card shadow-sm" style="border-radius: 12px;">
                        <div class="card-body">
                            <form action="{{ route('customers.store') }}" method="POST">
                                @csrf
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label">Nama <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">No. HP</label>
                                        <input type="text" name="phone" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Alamat</label>
                                        <input type="text" name="address" class="form-control">
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-success">
                                        <i data-feather="save" class="icon-xs me-1"></i> Simpan Pelanggan
                                    </button>
                                    <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target="#formPelanggan">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel Pelanggan --}}
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm" style="border-radius: 12px;">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>No. HP</th>
                                            <th>Alamat</th>
                                            <th>Tanggal Dibuat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($customers as $index => $customer)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $customer->name }}</td>
                                            <td>{{ $customer->email ?? '-' }}</td>
                                            <td>{{ $customer->phone ?? '-' }}</td>
                                            <td>{{ $customer->address ?? '-' }}</td>
                                            <td>{{ $customer->created_at->format('d-m-Y') }}</td>
                                        </tr>
                                        @endforeach
                                        @if($customers->isEmpty())
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Belum ada pelanggan</td>
                                        </tr>
                                        @endif
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
