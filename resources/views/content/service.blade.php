@extends('layouts.app')

@section('title', 'Tambah Service Kendaraan')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            {{-- Alert Pesan Sukses/Error --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-check-all me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-block-helper me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- FORM UTAMA --}}
            <form action="{{ route('services.store') }}" method="POST" id="form-service">
                @csrf
                <div class="row">
                    {{-- KOLOM KIRI: Input Data --}}
                    <div class="col-lg-8">

                        {{-- 1. Informasi Kendaraan & Pelanggan --}}
                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-transparent border-bottom">
                                <h5 class="my-0 text-primary"><i class="mdi mdi-car me-2"></i>Informasi Kendaraan</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    {{-- Pilih Kendaraan --}}
                                    <div class="col-md-6">
                                        <label class="form-label">Kendaraan <span class="text-danger">*</span></label>
                                        <select name="kendaraan_id" class="form-select select2" required>
                                            <option value="">-- Pilih Kendaraan --</option>
                                            @foreach($kendaraans as $kendaraan)
                                                <option value="{{ $kendaraan->id }}" {{ old('kendaraan_id') == $kendaraan->id ? 'selected' : '' }}>
                                                    {{ $kendaraan->plat_nomor }} - {{ $kendaraan->merk }} ({{ $kendaraan->customer->name ?? 'No Owner' }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Jenis Service --}}
                                    <div class="col-md-6">
                                        <label class="form-label">Jenis Service <span class="text-danger">*</span></label>
                                        <select name="jenis_service" class="form-select" required>
                                            <option value="Berkala" {{ old('jenis_service') == 'Berkala' ? 'selected' : '' }}>Service Berkala</option>
                                            <option value="Perbaikan" {{ old('jenis_service') == 'Perbaikan' ? 'selected' : '' }}>Perbaikan Berat</option>
                                            <option value="Lainnya" {{ old('jenis_service') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                    </div>

                                    {{-- Teknisi --}}
                                    <div class="col-md-6">
                                        <label class="form-label">Teknisi <span class="text-danger">*</span></label>
                                        <select name="teknisi_id" class="form-select" required>
                                            <option value="">-- Pilih Teknisi --</option>
                                            @foreach($teknisis as $teknisi)
                                                <option value="{{ $teknisi->id }}" {{ old('teknisi_id') == $teknisi->id ? 'selected' : '' }}>
                                                    {{ $teknisi->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Tanggal --}}
                                    <div class="col-md-6">
                                        <label class="form-label">Tanggal Service <span class="text-danger">*</span></label>
                                        <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                    </div>

                                    {{-- Keluhan --}}
                                    <div class="col-12">
                                        <label class="form-label">Keluhan / Catatan</label>
                                        <textarea name="keluhan" class="form-control" rows="2" placeholder="Tulis keluhan pelanggan...">{{ old('keluhan') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 2. Detail Jasa Service --}}
                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                                <h5 class="my-0 text-primary"><i class="mdi mdi-wrench me-2"></i>Jasa Service</h5>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addRow('service-wrapper','tpl-service')">
                                    <i class="bx bx-plus"></i> Tambah Jasa
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="service-wrapper">
                                    {{-- Baris Default Jasa --}}
                                    <div class="row g-2 mb-2 item-row">
                                        <div class="col-md-7">
                                            <input type="text" name="service_desc[]" class="form-control" placeholder="Contoh: Jasa Ganti Oli" required>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" name="service_price[]" class="form-control price-input service-price" placeholder="0" oninput="calculateTotal()" required>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-soft-danger w-100" onclick="removeRow(this)">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 3. Detail Sparepart --}}
                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                                <h5 class="my-0 text-primary"><i class="mdi mdi-cube-outline me-2"></i>Penggunaan Sparepart</h5>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addRow('sparepart-wrapper','tpl-sparepart')">
                                    <i class="bx bx-plus"></i> Tambah Part
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="sparepart-wrapper">
                                    <p class="text-muted text-center small fst-italic mb-0" id="empty-part-msg">Belum ada sparepart yang dipilih.</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- KOLOM KANAN: Status & Ringkasan --}}
                    <div class="col-lg-4">
                        <div class="card shadow-sm border-0 sticky-top" style="top: 100px; z-index: 99;">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0"><i class="mdi mdi-receipt me-2"></i>Status & Ringkasan</h5>
                            </div>
                            <div class="card-body">
                                
                                {{-- Opsi Status Servis --}}
                                <div class="mb-3">
                                    <label class="form-label">Status Pengerjaan</label>
                                    <div class="d-flex gap-3 mt-1">
                                        <div class="form-check form-radio-outline form-radio-warning">
                                            <input class="form-check-input" type="radio" name="status_servis" id="statusProses" value="Proses" checked>
                                            <label class="form-check-label" for="statusProses">Proses</label>
                                        </div>
                                        <div class="form-check form-radio-outline form-radio-success">
                                            <input class="form-check-input" type="radio" name="status_servis" id="statusSelesai" value="Selesai">
                                            <label class="form-check-label" for="statusSelesai">Selesai</label>
                                        </div>
                                    </div>
                                    <small class="text-muted">Pilih 'Proses' agar muncul di list Service Aktif.</small>
                                </div>

                                {{-- Opsi Status Pembayaran --}}
                                <div class="mb-4">
                                    <label class="form-label">Status Pembayaran</label>
                                    <select name="status_pembayaran" class="form-select border-primary">
                                        <option value="Belum Lunas">Belum Lunas (Kasbon)</option>
                                        <option value="Lunas">Lunas (Lunas)</option>
                                    </select>
                                </div>

                                <hr>

                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Total Jasa</span>
                                    <span class="fw-bold" id="total_service">Rp 0</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-muted">Total Sparepart</span>
                                    <span class="fw-bold" id="total_sparepart">Rp 0</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <span class="h6 mb-0">Total Bayar</span>
                                    <span class="h4 text-primary mb-0" id="grand_total">Rp 0</span>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-success btn-lg waves-effect waves-light">
                                        <i class="bx bx-save me-1"></i> Simpan Transaksi
                                    </button>
                                    <a href="{{ route('services.index') }}" class="btn btn-light waves-effect">Batal</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

{{-- ================= TEMPLATE JAVASCRIPT ================= --}}

{{-- Template Baris Jasa --}}
<template id="tpl-service">
    <div class="row g-2 mb-2 item-row fade-in">
        <div class="col-md-7">
            <input type="text" name="service_desc[]" class="form-control" placeholder="Deskripsi Jasa" required>
        </div>
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="number" name="service_price[]" class="form-control price-input service-price" placeholder="0" oninput="calculateTotal()" required>
            </div>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-soft-danger w-100" onclick="removeRow(this)">
                <i class="bx bx-trash"></i>
            </button>
        </div>
    </div>
</template>

{{-- Template Baris Sparepart --}}
<template id="tpl-sparepart">
    <div class="row g-2 mb-2 item-row fade-in align-items-center">
        <div class="col-md-5">
            <select name="sparepart_id[]" class="form-select sparepart-select" onchange="updatePartPrice(this)" required>
                <option value="">-- Pilih Barang --</option>
                @foreach($spareparts as $part)
                    <option value="{{ $part->id }}" 
                            data-price="{{ $part->selling_price }}"
                            data-stock="{{ $part->stock }}">
                        {{ $part->name }} (Sisa: {{ $part->stock }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <div class="input-group">
                <input type="number" name="sparepart_qty[]" class="form-control qty-input text-center" value="1" min="1" oninput="calculateRowTotal(this)" placeholder="Qty" required>
            </div>
            <div class="form-text stock-info text-muted small" style="font-size: 10px;">Stok: -</div>
        </div>
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-light">Rp</span>
                <input type="number" name="sparepart_price[]" class="form-control price-input sparepart-price fw-bold" placeholder="0" readonly>
            </div>
            <input type="hidden" class="unit-price">
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-soft-danger w-100" onclick="removeRow(this)">
                <i class="bx bx-trash"></i>
            </button>
        </div>
    </div>
</template>

@endsection

@push('styles')
<style>
    .fade-in { animation: fadeIn 0.3s; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    .form-radio-outline .form-check-input:checked { border-width: 2px; }
</style>
@endpush

@push('scripts')
<script>
    function addRow(wrapperId, templateId) {
        const wrapper = document.getElementById(wrapperId);
        const template = document.getElementById(templateId);
        const clone = template.content.cloneNode(true);
        const emptyMsg = document.getElementById('empty-part-msg');
        if(emptyMsg && wrapperId === 'sparepart-wrapper') emptyMsg.style.display = 'none';
        wrapper.appendChild(clone);
    }

    function removeRow(btn) {
        const row = btn.closest('.item-row');
        row.remove();
        calculateTotal();
        const wrapper = document.getElementById('sparepart-wrapper');
        if(wrapper.querySelectorAll('.item-row').length === 0) {
             const emptyMsg = document.getElementById('empty-part-msg');
             if(emptyMsg) emptyMsg.style.display = 'block';
        }
    }

    function updatePartPrice(select) {
        const row = select.closest('.item-row');
        const selectedOption = select.selectedOptions[0];
        const price = Number(selectedOption.dataset.price || 0);
        const stock = Number(selectedOption.dataset.stock || 0);

        row.querySelector('.unit-price').value = price;
        const qtyInput = row.querySelector('.qty-input');
        qtyInput.max = stock;
        qtyInput.value = 1;

        row.querySelector('.sparepart-price').value = price;
        row.querySelector('.stock-info').innerText = `Stok: ${stock}`;

        if (stock <= 0) {
            qtyInput.disabled = true;
            alert('Stok habis!');
        } else {
            qtyInput.disabled = false;
        }
        calculateTotal();
    }

    function calculateRowTotal(element) {
        const row = element.closest('.item-row');
        const qtyInput = row.querySelector('.qty-input');
        let qty = Number(qtyInput.value || 0);
        const maxStock = Number(qtyInput.max);
        
        if (qty > maxStock && maxStock > 0) {
            alert(`Stok hanya ${maxStock}!`);
            qty = maxStock;
            qtyInput.value = maxStock;
        }
        const price = Number(row.querySelector('.unit-price').value || 0);
        row.querySelector('.sparepart-price').value = qty * price;
        calculateTotal();
    }

    function calculateTotal() {
        let svcTotal = 0, partTotal = 0;
        document.querySelectorAll('.service-price').forEach(input => svcTotal += Number(input.value) || 0);
        document.querySelectorAll('.sparepart-price').forEach(input => partTotal += Number(input.value) || 0);

        const formatIDR = (num) => 'Rp ' + num.toLocaleString('id-ID');
        document.getElementById('total_service').innerText = formatIDR(svcTotal);
        document.getElementById('total_sparepart').innerText = formatIDR(partTotal);
        document.getElementById('grand_total').innerText = formatIDR(svcTotal + partTotal);
    }

    document.addEventListener('DOMContentLoaded', calculateTotal);
</script>
@endpush