@extends('layouts.app')

@section('title', 'Daftar Kendaraan')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            {{-- Header --}}
            <div class="row mb-3">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 font-size-18">Daftar Kendaraan</h4>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#formKendaraan">
                        <i data-feather="plus" class="icon-xs me-1"></i> Tambah Kendaraan
                    </button>
                </div>
            </div>

            {{-- Form Tambah Kendaraan --}}
            <div class="row mb-3 collapse" id="formKendaraan">
                <div class="col-12">
                    <div class="card shadow-sm" style="border-radius: 12px;">
                        <div class="card-body">
                            <form action="{{ route('kendaraans.store') }}" method="POST">
                                @csrf
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <label class="form-label">Plat Nomor <span class="text-danger">*</span></label>
                                        <input type="text" name="plat_nomor" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Merk <span class="text-danger">*</span></label>
                                        <input type="text" name="merk" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Tipe</label>
                                        <input type="text" name="tipe" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Warna</label>
                                        <input type="text" name="warna" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Pelanggan <span class="text-danger">*</span></label>
                                        <select name="customer_id" class="form-select" required>
                                            <option value="">Pilih Pelanggan</option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-success">
                                        <i data-feather="save" class="icon-xs me-1"></i> Simpan Kendaraan
                                    </button>
                                    <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target="#formKendaraan">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel Kendaraan --}}
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm" style="border-radius: 12px;">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Plat Nomor</th>
                                            <th>Merk</th>
                                            <th>Tipe</th>
                                            <th>Warna</th>
                                            <th>Pelanggan</th>
                                            <th>Tanggal Dibuat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($kendaraans as $index => $kendaraan)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $kendaraan->plat_nomor }}</td>
                                            <td>{{ $kendaraan->merk }}</td>
                                            <td>{{ $kendaraan->tipe ?? '-' }}</td>
                                            <td>{{ $kendaraan->warna ?? '-' }}</td>
                                            <td>{{ $kendaraan->customer->name ?? '-' }}</td>
                                            <td>{{ $kendaraan->created_at->format('d-m-Y') }}</td>
                                        </tr>
                                        @endforeach
                                        @if($kendaraans->isEmpty())
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">Belum ada kendaraan</td>
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
