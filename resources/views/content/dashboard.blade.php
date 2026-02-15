@extends('layouts.app')

@section('title', 'Dashboard Bengkel')

@section('content')

<style>
    /* UI Clean & Modern */
    .stat-card {
        border: none;
        border-radius: 16px;
        transition: all 0.3s ease;
        background: #ffffff;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.06);
    }

    .icon-box-lg {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    /* Warna Konsisten */
    .bg-soft-success { background-color: rgba(52, 195, 143, 0.15); color: #34c38f; }
    .bg-soft-danger { background-color: rgba(244, 106, 106, 0.15); color: #f46a6a; }
    .bg-soft-primary { background-color: rgba(81, 86, 190, 0.15); color: #5156be; }
    .bg-soft-info { background-color: rgba(80, 165, 241, 0.15); color: #50a5f1; }

    .scroll-container {
        max-height: 340px;
        overflow-y: auto;
        scrollbar-width: thin;
    }

    .table-responsive { border-radius: 12px; }
    
    .card-header {
        background-color: transparent !important;
        border-bottom: 1px solid #f1f1f1 !important;
    }
</style>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            {{-- ================= TOP HEADER ================= --}}
            <div class="row align-items-center mb-4">
                <div class="col-md-6">
                    <h4 class="fw-bold text-dark mb-0">Ringkasan Performa</h4>
                    <p class="text-muted mb-0">Statistik untuk tanggal: <strong>{{ \Carbon\Carbon::parse($tanggalTerpilih)->translatedFormat('d F Y') }}</strong></p>
                </div>
                <div class="col-md-6 text-md-end">
                    <form id="filterTanggalForm" action="{{ route('home') }}" method="GET" class="d-inline-flex align-items-center bg-white p-2 rounded-3 shadow-sm">
                        <i class="bx bx-calendar text-primary me-2 fs-5"></i>
                        <input type="date" id="customDate" name="tanggal" class="form-control form-control-sm border-0" 
                               value="{{ $tanggalTerpilih }}">
                        <button type="submit" class="btn btn-sm btn-primary ms-2 px-3">Terapkan</button>
                    </form>
                </div>
            </div>

            {{-- ================= STATISTIC CARDS (DINAMIS) ================= --}}
            <div class="row g-3 mb-4">
                {{-- LABA BERSIH --}}
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-box-lg bg-soft-info me-3">
                                    <i class="bx bx-wallet"></i>
                                </div>
                                <div>
                                    <p class="text-muted mb-1 fw-medium">Laba Bersih Hari Ini</p>
                                    <h4 class="fw-bold mb-0 text-dark">Rp {{ number_format($saldoAkhirBulanIni, 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PENDAPATAN --}}
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-box-lg bg-soft-success me-3">
                                    <i class="bx bx-trending-up"></i>
                                </div>
                                <div>
                                    <p class="text-muted mb-1 fw-medium">Pendapatan Lunas</p>
                                    <h4 class="fw-bold mb-0 text-success">Rp {{ number_format($totalPendapatanBulanIni, 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PENGELUARAN --}}
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-box-lg bg-soft-danger me-3">
                                    <i class="bx bx-trending-down"></i>
                                </div>
                                <div>
                                    <p class="text-muted mb-1 fw-medium">Total Pengeluaran</p>
                                    <h4 class="fw-bold mb-0 text-danger">Rp {{ number_format($totalPengeluaranBulanIni, 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SERVICE HARI INI --}}
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-box-lg bg-soft-primary me-3">
                                    <i class="bx bx-wrench"></i>
                                </div>
                                <div>
                                    <p class="text-muted mb-1 fw-medium">Unit Service Hari Ini</p>
                                    <h4 class="fw-bold mb-0 text-primary">{{ $countServiceHariIni }} Unit</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- ================= CHART KEUNTUNGAN (CUSTOM TREND) ================= --}}
                <div class="col-xl-8 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header py-3">
                            <h5 class="card-title mb-0 fw-bold">
                                Tren Keuntungan S/D {{ \Carbon\Carbon::parse($tanggalTerpilih)->translatedFormat('F Y') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div style="height: 320px;">
                                <canvas id="salesChart" 
                                    data-labels="{{ json_encode($grafikBulanan->pluck('label')) }}" 
                                    data-totals="{{ json_encode($grafikBulanan->pluck('total')) }}">
                                </canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ================= SERVICE AKTIF (ANTREAN) ================= --}}
                <div class="col-xl-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 fw-bold">Antrean Proses</h5>
                            <span class="badge rounded-pill bg-soft-primary">{{ count($serviceAktif) }} Unit</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="scroll-container">
                                <ul class="list-group list-group-flush">
                                    @forelse($serviceAktif as $aktif)
                                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                            <div>
                                                <h6 class="mb-1 fw-bold">{{ $aktif->kendaraan->plat_nomor }}</h6>
                                                <p class="text-muted mb-0 small">{{ $aktif->kendaraan->customer->name ?? '-' }}</p>
                                            </div>
                                            <a href="{{ route('services.show', $aktif->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                Detail
                                            </a>
                                        </li>
                                    @empty
                                        <div class="text-center py-5">
                                            <i class="bx bx-list-check fs-1 text-muted opacity-25"></i>
                                            <p class="text-muted mb-0">Tidak ada antrean aktif.</p>
                                        </div>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= RIWAYAT TRANSAKSI (FILTERED) ================= --}}
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 fw-bold">Transaksi Tanggal Terpilih</h5>
                            <a href="{{ route('transaksi.index') }}" class="btn btn-sm btn-light">Lihat Semua Laporan</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light text-muted">
                                        <tr>
                                            <th class="ps-4">No. Invoice</th>
                                            <th>Tanggal</th>
                                            <th>Customer</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($transaksiTerakhir as $trx)
                                            <tr>
                                                <td class="ps-4 fw-medium">#{{ str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}</td>
                                                <td>{{ \Carbon\Carbon::parse($trx->tanggal)->format('d/m/Y') }}</td>
                                                <td>{{ $trx->kendaraan->customer->name ?? '-' }}</td>
                                                <td class="fw-bold text-dark">Rp {{ number_format($trx->grand_total, 0, ',', '.') }}</td>
                                                <td>
                                                    <span class="badge {{ $trx->status_pembayaran == 'Lunas' ? 'bg-soft-success' : 'bg-soft-danger' }} px-3">
                                                        {{ $trx->status_pembayaran }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('services.show', $trx->id) }}" class="btn btn-sm btn-light">
                                                        <i class="bx bx-show"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-5">
                                                    <p class="text-muted mb-0">Tidak ada transaksi pada tanggal {{ \Carbon\Carbon::parse($tanggalTerpilih)->format('d/m/Y') }}.</p>
                                                </td>
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



@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Chart Keuntungan Dinamis
    const canvas = document.getElementById('salesChart');
    if (!canvas) return;

    const labels = JSON.parse(canvas.getAttribute('data-labels') || '[]');
    const totals = JSON.parse(canvas.getAttribute('data-totals') || '[]');

    const ctx = canvas.getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(81, 86, 190, 0.3)');
    gradient.addColorStop(1, 'rgba(81, 86, 190, 0.0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Laba Bersih (Rp)',
                data: totals,
                borderColor: '#5156be',
                backgroundColor: gradient,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#5156be',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { borderDash: [5, 5], color: '#f1f1f1' },
                    ticks: {
                        callback: (v) => v >= 1000000 ? 'Rp ' + (v/1000000).toFixed(1) + 'jt' : 'Rp ' + v.toLocaleString('id-ID')
                    }
                },
                x: { grid: { display: false } }
            }
        }
    });
});
</script>
@endpush
@endsection