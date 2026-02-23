@extends('layouts.app')

@section('title', 'Tambah Service Kendaraan')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

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

            <form action="{{ route('services.store') }}" method="POST" id="form-service">
                @csrf
                <div class="row">
                    <div class="col-lg-8">
                        {{-- INFORMASI KENDARAAN --}}
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header bg-transparent border-bottom">
                                <h5 class="mb-0">Informasi Kendaraan</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Kendaraan <span class="text-danger">*</span></label>
                                        <div class="dropdown w-100">
                                            <button type="button" id="btnPilihKendaraan" class="btn btn-outline-secondary w-100 text-start d-flex justify-content-between align-items-center" data-bs-toggle="dropdown">
                                                <span>Pilih Kendaraan</span>
                                                <i class="mdi mdi-chevron-down"></i>
                                            </button>
                                            <div class="dropdown-menu p-3 shadow" style="width:100%; min-width:300px">
                                                <input type="text" class="form-control mb-2" id="searchKendaraan" placeholder="Cari plat nomor atau merk...">
                                                <div id="kendaraanList" style="max-height:250px; overflow-y:auto">
                                                    @foreach($kendaraans as $kendaraan)
                                                        <a href="#" class="dropdown-item border-bottom py-2 kendaraan-item" 
                                                           data-id="{{ $kendaraan->id }}" 
                                                           data-text="{{ $kendaraan->plat_nomor }} - {{ $kendaraan->merk }}">
                                                            <div class="fw-bold">{{ $kendaraan->plat_nomor }}</div>
                                                            <div class="small text-muted">
                                                                {{ $kendaraan->merk }} | {{ $kendaraan->customer->name ?? 'No Owner' }}
                                                            </div>
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="kendaraan_id" id="kendaraan_id" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Teknisi <span class="text-danger">*</span></label>
                                        <select name="teknisi_id" class="form-select" required>
                                            <option value="">Pilih Teknisi</option>
                                            @foreach($teknisis as $teknisi)
                                                <option value="{{ $teknisi->id }}">{{ $teknisi->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Jenis Service</label>
                                        <select name="jenis_service" class="form-select">
                                            <option value="Berkala">Service Berkala</option>
                                            <option value="Perbaikan">Perbaikan</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Tanggal <span class="text-danger">*</span></label>
                                        <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-bold">Keluhan</label>
                                        <textarea name="keluhan" class="form-control" rows="2" placeholder="Tuliskan keluhan pelanggan..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- JASA SERVICE --}}
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Jasa Service</h5>
                                <button type="button" class="btn btn-sm btn-primary" onclick="addServiceRow()">
                                    <i class="mdi mdi-plus"></i> Tambah Jasa
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="service-wrapper">
                                    <div class="row mb-3 service-row align-items-end">
                                        <div class="col-md-7">
                                            <label class="form-label small text-muted">Deskripsi Jasa</label>
                                            <input type="text" name="service_desc[]" class="form-control" placeholder="Contoh: Ganti Oli Mesin" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small text-muted">Harga (Rp)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" name="service_price[]" class="form-control service-price" value="0" required oninput="calculateTotal()">
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-outline-danger w-100" onclick="removeRow(this)">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- SPAREPART --}}
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Sparepart / Suku Cadang</h5>
                                <button type="button" class="btn btn-sm btn-info text-white" onclick="addSparepartRow()">
                                    <i class="mdi mdi-plus"></i> Tambah Sparepart
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="sparepart-wrapper">
                                    {{-- Baris sparepart akan muncul di sini --}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card sticky-top shadow-lg">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Ringkasan Transaksi</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Status Servis</label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status_servis" id="status_proses" value="Proses" checked>
                                            <label class="form-check-label" for="status_proses">Proses</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status_servis" id="status_selesai" value="Selesai">
                                            <label class="form-check-label" for="status_selesai">Selesai</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Status Pembayaran</label>
                                    <select name="status_pembayaran" id="status_pembayaran" class="form-select bg-light">
                                        <option value="Cicilan" selected>Cicilan</option>
                                        <option value="Belum Lunas">Belum Lunas</option>
                                        <option value="Lunas">Lunas</option>
                                    </select>
                                </div>

                                <hr class="my-3">

                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Total Jasa</span>
                                    <span id="total_service" class="fw-bold text-dark">Rp 0</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Total Sparepart</span>
                                    <span id="total_sparepart" class="fw-bold text-dark">Rp 0</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3 pt-2 border-top">
                                    <span class="h6">Total Bayar</span>
                                    <span id="grand_total" class="h6 text-primary">Rp 0</span>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">DP / Bayar Sekarang</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-success text-white">Rp</span>
                                        <input type="number" name="dp" id="dp" class="form-control form-control-lg" value="0" oninput="calculateTotal()">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mb-4 p-2 bg-light rounded">
                                    <span class="fw-bold">Sisa Tagihan</span>
                                    <span id="sisa_tagihan" class="fw-bold text-danger">Rp 0</span>
                                </div>

                                <button type="submit" class="btn btn-success btn-lg w-100 shadow-sm">
                                    <i class="mdi mdi-content-save me-1"></i> Simpan Transaksi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- TEMPLATE HIDDEN --}}
<div id="sparepart-template" class="d-none">
    <div class="row mb-3 sparepart-row align-items-end">
        <div class="col-md-5">
            <label class="form-label small text-muted">Pilih Sparepart</label>
            <select class="form-select sparepart-select" name="sparepart_id[]" onchange="updateSparepartPrice(this)">
                <option value="">-- Pilih Sparepart --</option>
                @foreach($spareparts as $sp)
                    <option value="{{ $sp->id }}" 
                            data-price="{{ $sp->selling_price }}" 
                            data-stock="{{ $sp->stock }}">
                        {{ $sp->name }} (Stok: {{ $sp->stock }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small text-muted">Harga</label>
            <input type="number" name="sparepart_price[]" class="form-control sparepart-price" readonly>
        </div>
        <div class="col-md-2">
            <label class="form-label small text-muted">Qty</label>
            <input type="number" name="sparepart_qty[]" class="form-control sparepart-qty" value="1" min="1" oninput="calculateTotal()">
        </div>
        <div class="col-md-2">
            <label class="form-label small text-muted">Subtotal</label>
            <input type="text" class="form-control sparepart-subtotal" readonly value="Rp 0">
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-outline-danger w-100" onclick="removeSparepartRow(this)">
                <i class="mdi mdi-delete"></i>
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Pemilihan Kendaraan
        $(document).on('click', '.kendaraan-item', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const fullText = $(this).data('text');
            $('#kendaraan_id').val(id);
            $('#btnPilihKendaraan span').text(fullText);
            $('#btnPilihKendaraan').removeClass('btn-outline-secondary').addClass('btn-outline-primary');
        });

        // Pencarian Kendaraan
        $('#searchKendaraan').on('keyup', function() {
            const value = $(this).val().toLowerCase();
            $('#kendaraanList .kendaraan-item').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        // Inisialisasi hitungan saat halaman dimuat
        calculateTotal();
    });

    function addServiceRow() {
        const rowHtml = `
            <div class="row mb-3 service-row align-items-end">
                <div class="col-md-7">
                    <input type="text" name="service_desc[]" class="form-control" placeholder="Deskripsi jasa..." required>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="service_price[]" class="form-control service-price" value="0" required oninput="calculateTotal()">
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-outline-danger w-100" onclick="removeRow(this)">
                        <i class="mdi mdi-delete"></i>
                    </button>
                </div>
            </div>`;
        $('#service-wrapper').append(rowHtml);
    }

    function removeRow(btn) {
        if ($('.service-row').length > 1) {
            $(btn).closest('.service-row').remove();
            calculateTotal();
        }
    }

    function addSparepartRow() {
        const template = $('#sparepart-template').html();
        $('#sparepart-wrapper').append(template);
    }

    function removeSparepartRow(btn) {
        $(btn).closest('.sparepart-row').remove();
        calculateTotal();
    }

    function updateSparepartPrice(selectElement) {
        const selectedOption = $(selectElement).find(':selected');
        const price = selectedOption.data('price') || 0;
        const row = $(selectElement).closest('.sparepart-row');
        row.find('.sparepart-price').val(price);
        calculateTotal();
    }

    function calculateTotal() {
        let totalService = 0;
        let totalSparepart = 0;

        // Hitung Jasa
        $('.service-price').each(function() {
            totalService += Number($(this).val()) || 0;
        });

        // Hitung Sparepart
        $('.sparepart-row').each(function() {
            const price = Number($(this).find('.sparepart-price').val()) || 0;
            const qty = Number($(this).find('.sparepart-qty').val()) || 0;
            const subtotal = price * qty;
            totalSparepart += subtotal;
            $(this).find('.sparepart-subtotal').val(formatCurrency(subtotal));
        });

        const grandTotal = totalService + totalSparepart;
        const dp = Number($('#dp').val()) || 0;
        const sisa = Math.max(0, grandTotal - dp);

        // Update UI
        $('#total_service').text(formatCurrency(totalService));
        $('#total_sparepart').text(formatCurrency(totalSparepart));
        $('#grand_total').text(formatCurrency(grandTotal));
        $('#sisa_tagihan').text(formatCurrency(sisa));

        // LOGIKA STATUS PEMBAYARAN (Default: Cicilan)
        let status = "Cicilan"; 

        if (grandTotal > 0) {
            if (dp >= grandTotal) {
                status = "Lunas";
            } else if (dp === 0) {
                // Kamu bisa ganti ke "Belum Lunas" jika DP benar-benar kosong, 
                // tapi sesuai requestmu, default kita biarkan "Cicilan"
                status = "Cicilan"; 
            } else {
                status = "Cicilan";
            }
        }
        
        $('#status_pembayaran').val(status);
    }

    function formatCurrency(val) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        }).format(val);
    }
</script>
@endpush