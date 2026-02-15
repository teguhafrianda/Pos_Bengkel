@extends('layouts.app')

@section('title', 'Data Pengeluaran')

@section('content')
<style>
    .card-modern { border: none; border-radius: 14px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    .btn-primary-soft { background-color: rgba(13,110,253,0.1); color: #0d6efd; border: none; }
    .btn-primary-soft:hover { background-color: #0d6efd; color: white; }
</style>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            {{-- HEADER --}}
            <div class="row mb-4">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-1">Manajemen Pengeluaran</h4>
                        <small class="text-muted">Kelola semua pengeluaran operasional bengkel</small>
                    </div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <i data-feather="plus"></i> Tambah Pengeluaran
                    </button>
                </div>
            </div>

            {{-- FILTER --}}
            <form action="{{ route('pengeluaran.index') }}" method="GET">
                <div class="card card-modern mb-4 p-3">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Dari Tanggal</label>
                            <input type="date" name="dari" class="form-control" value="{{ request('dari') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Sampai Tanggal</label>
                            <input type="date" name="sampai" class="form-control" value="{{ request('sampai') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Kategori</label>
                            <select name="kategori" class="form-select">
                                <option value="Semua">Semua</option>
                                @foreach(['Operasional', 'Gaji', 'Pembelian Barang', 'Listrik & Air'] as $cat)
                                    <option value="{{ $cat }}" {{ request('kategori') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i data-feather="search"></i> Filter
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            {{-- TABLE --}}
            <div class="card card-modern">
                <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                    <span>Daftar Pengeluaran</span>
                    <span class="badge bg-danger">Total Bulan Ini: Rp {{ number_format($totalBulanIni, 0, ',', '.') }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kategori</th>
                                    <th>Deskripsi</th>
                                    <th>Jumlah</th>
                                    <th>Metode</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pengeluaran as $item)
                                <tr>
                                    <td>{{ $item->tanggal->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge bg-soft-info text-info">{{ $item->kategori }}</span>
                                    </td>
                                    <td>{{ $item->deskripsi }}</td>
                                    <td class="fw-bold text-danger">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                    <td>{{ $item->metode_pembayaran }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('pengeluaran.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i data-feather="trash-2" style="width: 16px;"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada data pengeluaran</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">
                        {{ $pengeluaran->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('pengeluaran.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Pengeluaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-select" required>
                            <option value="Operasional">Operasional</option>
                            <option value="Gaji">Gaji</option>
                            <option value="Pembelian Barang">Pembelian Barang</option>
                            <option value="Listrik & Air">Listrik & Air</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="2" placeholder="Contoh: Bayar Listrik" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah (Rp)</label>
                        <input type="number" name="jumlah" class="form-control" placeholder="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <select name="metode_pembayaran" class="form-select" required>
                            <option value="Cash">Cash</option>
                            <option value="Transfer">Transfer</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Pengeluaran</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection