@extends('layouts.app')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            {{-- ================= ACTIONS (Monitor Only) ================= --}}
            <div class="row mb-3 no-print">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 font-size-18">Detail Transaksi Service</h4>
                    <div>
                        <a href="{{ route('services.index') }}" class="btn btn-secondary waves-effect me-1">
                            <i class="bx bx-arrow-back me-1"></i> Kembali
                        </a>
                        <button onclick="window.print()" class="btn btn-primary waves-effect">
                            <i class="bx bx-printer me-1"></i> Cetak Struk Thermal
                        </button>
                    </div>
                </div>
            </div>

            {{-- ================= QUICK ACTION PANEL ================= --}}
            @if($service->status_servis == 'Proses' || $service->status_pembayaran == 'Belum Lunas')
            <div class="card border-warning shadow-sm no-print mb-4" style="border-left: 5px solid; background-color: #fff9eb;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="text-warning mb-1"><i class="bx bx-info-circle me-1"></i> Konfirmasi Transaksi</h5>
                        <p class="text-muted mb-0 small">Klik tombol jika pengerjaan selesai & pembayaran diterima.</p>
                    </div>
                    <form id="update-status-form" action="{{ route('services.updateStatus', $service->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('PATCH')
                    </form>
                    <button type="button" class="btn btn-success btn-lg" onclick="confirmUpdate()">
                        <i class="bx bx-check-double me-1"></i> Selesaikan & Lunaskan
                    </button>
                </div>
            </div>
            @endif

            {{-- ================= INVOICE ================= --}}
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    
                    {{-- Header --}}
                    <div class="row mb-4 header-container">
                        <div class="col-sm-6">
                            {{-- Nama Bengkel Dinamis dari Tabel Users --}}
                            <h4 class="text-primary mb-1 fw-bold text-uppercase">
                                {{ auth()->user()->shop_name ?? 'BENGKEL POS' }}
                            </h4>
                            <p class="text-muted mb-0">Spesialis Perbaikan & Perawatan Kendaraan</p>
                            <p class="text-muted">Dicetak oleh: {{ auth()->user()->name }}</p>
                        </div>
                        <div class="col-sm-6 text-sm-end invoice-id-section">
                            <h3 class="mb-1 text-dark">#INV-{{ str_pad($service->id, 5, '0', STR_PAD_LEFT) }}</h3>
                            <p class="text-muted mb-2">
                                {{ \Carbon\Carbon::parse($service->tanggal)->format('d M Y H:i') }}
                            </p>

                            <div class="no-print">
                                <span class="badge {{ $service->status_servis == 'Proses' ? 'badge-soft-warning' : 'badge-soft-success' }}">
                                    {{ $service->status_servis }}
                                </span>
                                <span class="badge {{ $service->status_pembayaran == 'Lunas' ? 'bg-success' : 'bg-danger' }} text-white ms-1">
                                    {{ $service->status_pembayaran }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4 divider-line">

                    {{-- Info Pelanggan & Kendaraan --}}
                    <div class="row mb-4 info-grid">
                        <div class="col-md-4">
                            <h6 class="text-muted text-uppercase font-size-11 mb-2">Pelanggan</h6>
                            <h5 class="font-size-14 mb-1 fw-bold">
                                {{ $service->kendaraan->customer->name ?? '-' }}
                            </h5>
                            <p class="text-muted mb-0">
                                {{ $service->kendaraan->customer->phone ?? '-' }}
                            </p>
                        </div>
                        <div class="col-md-4 mt-3 mt-md-0">
                            <h6 class="text-muted text-uppercase font-size-11 mb-2">Kendaraan</h6>
                            <h5 class="font-size-14 mb-1 fw-bold">
                                {{ $service->kendaraan->plat_nomor ?? '-' }}
                            </h5>
                            <p class="text-muted mb-0">
                                {{ $service->kendaraan->merk ?? '-' }} {{ $service->kendaraan->tipe ?? '' }}
                            </p>
                        </div>
                        <div class="col-md-4 mt-3 mt-md-0 text-md-end tech-info">
                            <h6 class="text-muted text-uppercase font-size-11 mb-2">Teknisi</h6>
                            <h5 class="font-size-14 mb-1 fw-bold">
                                {{ $service->teknisi->name ?? '-' }}
                            </h5>
                            <p class="text-muted mb-0">
                                Service: {{ $service->jenis_service ?? '-' }}
                            </p>
                        </div>
                    </div>

                    {{-- Tabel Item Jasa & Sparepart --}}
                    <div class="table-responsive mb-4">
                        <table class="table table-nowrap align-middle table-bordered table-custom">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Item</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($service->items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nama_jasa }}</td>
                                    <td class="text-center">1</td>
                                    <td class="text-end">Rp {{ number_format($item->harga,0,',','.') }}</td>
                                </tr>
                                @endforeach

                                @foreach($service->spareparts as $sp)
                                <tr>
                                    <td>{{ $service->items->count() + $loop->iteration }}</td>
                                    <td>{{ $sp->sparepart->name ?? 'Part' }}</td>
                                    <td class="text-center">{{ $sp->qty }}</td>
                                    <td class="text-end">Rp {{ number_format($sp->harga,0,',','.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Ringkasan Biaya --}}
                    <div class="row mt-4">
                        <div class="col-md-7">
                            <div class="p-3 border rounded border-dashed bg-light notes-box">
                                <h6 class="text-muted text-uppercase mb-2 fw-bold">Catatan</h6>
                                <p class="mb-0 fst-italic">
                                    {{ $service->keluhan ?? 'Tidak ada catatan.' }}
                                </p>
                            </div>
                        </div>

                        <div class="col-md-5 text-md-end total-section">
                            <div>Subtotal Jasa: Rp {{ number_format($service->total_jasa,0,',','.') }}</div>
                            <div>Subtotal Sparepart: Rp {{ number_format($service->total_sparepart,0,',','.') }}</div>
                            <div class="border-top pt-2 mt-2">
                                <h5 class="fw-bold">TOTAL BAYAR</h5>
                                <h2 class="fw-bold text-primary">
                                    Rp {{ number_format($service->grand_total,0,',','.') }}
                                </h2>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>



{{-- ================= PRINT OPTIMIZED THERMAL ================= --}}
<style>
@media print {
    @page { size: 80mm auto; margin: 0; }
    body { width: 80mm; margin: 0 auto; padding: 5px; font-family: 'Courier New', monospace; font-size: 10pt; color: #000; background: #fff; }
    .no-print, .main-header, .vertical-menu, .footer, .navbar-header { display: none !important; }
    .main-content, .page-content, .container-fluid { margin: 0 !important; padding: 0 !important; width: 100% !important; }
    .card, .card-body { border: none !important; box-shadow: none !important; padding: 0 !important; margin: 0 !important; }

    .header-container { display: block !important; text-align: center !important; margin-bottom: 5px !important; }
    .header-container .col-sm-6 { width: 100% !important; flex: 0 0 100% !important; max-width: 100% !important; text-align: center !important; }
    .header-container h4 { font-size: 14pt; font-weight: bold; margin-bottom: 2px; letter-spacing: 1px; }

    .divider-line { border-top: 1px dashed #000 !important; margin: 8px 0; }
    .info-grid { border-bottom: 1px dashed #000; padding-bottom: 5px; margin-bottom: 5px; }
    .table-custom th, .table-custom td { border: none !important; padding: 3px 0 !important; font-size: 9pt; }
    .table-custom th:first-child, .table-custom td:first-child { display: none; }
    .notes-box { border: 1px dashed #000 !important; background: none !important; margin-top: 8px; }
    .total-section { text-align: right !important; margin-top: 10px; }
    .total-section h2 { font-size: 16pt; color: #000 !important; }
    .total-section::after {
        content: "\A------------------------------\ATERIMA KASIH\A{{ strtoupper(auth()->user()->shop_name) }}\A";
        white-space: pre; display: block; text-align: center; margin-top: 15px; font-size: 8pt;
    }
}
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmUpdate() {
    Swal.fire({
        title: 'Selesaikan & Bayar?',
        text: "Status akan menjadi Selesai & Lunas!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#34c38f',
        cancelButtonColor: '#f46a6a',
        confirmButtonText: 'Ya, Selesaikan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('update-status-form').submit();
        }
    });
}
</script>
@endpush
@endsection