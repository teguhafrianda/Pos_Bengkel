@extends('layouts.app')

@section('title', 'Daftar Sparepart')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            {{-- Alert --}}
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
                        <h4 class="mb-sm-0 font-size-18">Data Sparepart</h4>
                        <button class="btn btn-primary btn-sm waves-effect waves-light" type="button" data-bs-toggle="collapse" data-bs-target="#formTambahSparepart">
                            <i class="bx bx-plus font-size-16 align-middle me-2"></i> Tambah Baru
                        </button>
                    </div>
                </div>
            </div>

            {{-- Form Tambah Baru (Collapse) --}}
            <div class="row collapse mb-4" id="formTambahSparepart">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <form action="{{ route('spareparts.store') }}" method="POST">
                                @csrf
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-3">
                                        <label class="form-label small text-muted fw-bold">Nama Sparepart</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small text-muted fw-bold">Harga Modal (Beli)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" name="cost_price" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small text-muted fw-bold">Harga Jual</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" name="selling_price" class="form-control fw-bold" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small text-muted fw-bold">Stok Awal</label>
                                        <input type="number" name="stock" class="form-control" required>
                                    </div>
                                    <div class="col-md-1 d-grid">
                                        <button type="submit" class="btn btn-success"><i class="bx bx-save"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel Data --}}
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Sparepart</th>
                                            <th>Harga Modal</th>
                                            <th>Harga Jual</th>
                                            <th>Profit</th>
                                            <th>Stok</th>
                                            <th style="width: 150px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($spareparts as $s)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="fw-medium">{{ $s->name }}</td>
                                            <td class="text-muted">Rp {{ number_format($s->cost_price, 0, ',', '.') }}</td>
                                            <td class="fw-bold">Rp {{ number_format($s->selling_price, 0, ',', '.') }}</td>
                                            @php $profit = $s->selling_price - $s->cost_price; @endphp
                                            <td class="{{ $profit < 0 ? 'text-danger' : 'text-success' }} fw-bold">
                                                Rp {{ number_format(abs($profit), 0, ',', '.') }}
                                            </td>
                                            <td>
                                                @if($s->stock <= 5)
                                                    <span class="badge badge-soft-warning font-size-12 text-warning">{{ $s->stock }} pcs</span>
                                                @else
                                                    <span class="badge badge-soft-success font-size-12">{{ $s->stock }} pcs</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    {{-- Tombol Tambah Stok (Pemicu Modal) --}}
                                                    <button type="button" class="btn btn-soft-primary btn-sm waves-effect waves-light" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#modalTambahStok" 
                                                            data-id="{{ $s->id }}" 
                                                            data-name="{{ $s->name }}" 
                                                            data-stock="{{ $s->stock }}">
                                                        <i class="bx bx-plus-circle font-size-16"></i>
                                                    </button>

                                                    {{-- Tombol Hapus --}}
                                                    <form action="{{ route('spareparts.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Hapus data?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-soft-danger btn-sm">
                                                            <i class="bx bx-trash font-size-16"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="7" class="text-center py-5">Data Kosong</td></tr>
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

{{-- MODAL TAMBAH STOK --}}
<div class="modal fade" id="modalTambahStok" tabindex="-1" aria-labelledby="modalTambahStokLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahStokLabel">Update Stok Sparepart</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('spareparts.updateStok') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" id="sparepart_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" class="form-control bg-light" id="sparepart_name" readonly>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Stok Sekarang</label>
                                <input type="text" class="form-control bg-light" id="current_stock" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Jumlah Tambah <span class="text-danger">*</span></label>
                                <input type="number" name="add_stock" class="form-control border-primary" min="1" placeholder="0" required autofocus>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-soft-info small mb-0">
                        <i class="bx bx-info-circle me-1"></i> Stok akan dijumlahkan otomatis dengan stok yang sudah ada.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Stok Baru</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Script untuk memindahkan data dari baris tabel ke dalam Modal saat tombol diklik
    const modalTambahStok = document.getElementById('modalTambahStok');
    modalTambahStok.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        
        // Ambil data dari atribut data-*
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const stock = button.getAttribute('data-stock');

        // Masukkan ke input di dalam modal
        modalTambahStok.querySelector('#sparepart_id').value = id;
        modalTambahStok.querySelector('#sparepart_name').value = name;
        modalTambahStok.querySelector('#current_stock').value = stock + ' pcs';
    });
</script>
@endpush