@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            {{-- ================= HEADER ================= --}}
            <div class="row mb-3 no-print">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 font-size-18">Monitoring Transaksi & Penjualan</h4>
                </div>
            </div>

            {{-- ================= FILTER ================= --}}
            <div class="card shadow-sm mb-4 no-print">
                <div class="card-body">
                    <form method="GET" action="{{ route('transaksi.index') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Tanggal Dari</label>
                                <input type="date" name="from" value="{{ request('from') }}" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Sampai</label>
                                <input type="date" name="to" value="{{ request('to') }}" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Status Kerja</label>
                                <select name="status_servis" class="form-select">
                                    <option value="">Semua</option>
                                    <option value="Proses" {{ request('status_servis') == 'Proses' ? 'selected' : '' }}>Proses</option>
                                    <option value="Selesai" {{ request('status_servis') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Pembayaran</label>
                                <select name="status_pembayaran" class="form-select">
                                    <option value="">Semua</option>
                                    <option value="Lunas" {{ request('status_pembayaran') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                                    <option value="Belum Lunas" {{ request('status_pembayaran') == 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end gap-2">
                                <button class="btn btn-primary w-100">Filter</button>
                                <a href="{{ route('transaksi.index') }}" class="btn btn-secondary"><i class="bx bx-refresh"></i></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ================= RINGKASAN DATA ================= --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white shadow-sm">
                        <div class="card-body">
                            <h6 class="text-white-50">Total Pendapatan (Lunas)</h6>
                            <h3>Rp {{ number_format($services->where('status_pembayaran', 'Lunas')->sum('grand_total'), 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white shadow-sm">
                        <div class="card-body">
                            <h6 class="text-white-50">Total Piutang (Belum Lunas)</h6>
                            <h3>Rp {{ number_format($services->where('status_pembayaran', 'Belum Lunas')->sum('grand_total'), 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white shadow-sm">
                        <div class="card-body">
                            <h6 class="text-white-50">Sedang Dikerjakan</h6>
                            <h3>{{ $services->where('status_servis', 'Proses')->count() }} Unit</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white shadow-sm">
                        <div class="card-body">
                            <h6 class="text-white-50">Service Selesai</h6>
                            <h3>{{ $services->where('status_servis', 'Selesai')->count() }} Unit</h3>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= TABEL MONITORING ================= --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Transaksi</h5>
                    <button type="button" onclick="window.print()" class="btn btn-soft-success btn-sm no-print">
                        <i class="bx bx-printer"></i> Cetak Laporan
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Pelanggan / Unit</th>
                                    <th>Teknisi</th>
                                    <th class="text-end">Total Biaya</th>
                                    <th class="text-center">Pengerjaan</th>
                                    <th class="text-center">Pembayaran</th>
                                    <th class="text-center no-print">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($services as $service)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ \Carbon\Carbon::parse($service->tanggal)->format('d/m/y') }}</td>
                                    <td>
                                        <span class="fw-bold">{{ $service->kendaraan->customer->name ?? '-' }}</span><br>
                                        <small class="text-muted">{{ $service->kendaraan->plat_nomor ?? '-' }} ({{ $service->kendaraan->merk }})</small>
                                    </td>
                                    <td>{{ $service->teknisi->name ?? '-' }}</td>
                                    <td class="text-end fw-bold">Rp {{ number_format($service->grand_total, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        @if($service->status_servis == 'Proses')
                                            <span class="badge badge-soft-warning px-3 py-2">Proses</span>
                                        @else
                                            <span class="badge badge-soft-success px-3 py-2">Selesai</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($service->status_pembayaran == 'Lunas')
                                            <span class="badge bg-success text-white px-3 py-2 w-100">Lunas</span>
                                        @else
                                            <span class="badge bg-danger text-white px-3 py-2 w-100">Belum Lunas</span>
                                        @endif
                                    </td>
                                    <td class="text-center no-print">
                                        <a href="{{ route('services.show', $service->id) }}" class="btn btn-soft-primary btn-sm">
                                            <i class="bx bx-show"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted fst-italic">Data tidak ditemukan.</td>
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

<style>
@media print {
    .no-print, .vertical-menu, .navbar-header, .footer { display: none !important; }
    .main-content { margin-left: 0 !important; padding: 0 !important; }
    .card { border: 1px solid #ddd !important; box-shadow: none !important; }
    body { background: white !important; }
    .table thead th { background-color: #f8f9fa !important; -webkit-print-color-adjust: exact; }
}
</style>
@endsection