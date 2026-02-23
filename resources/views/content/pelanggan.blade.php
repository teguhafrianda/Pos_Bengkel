@extends('layouts.app')

@section('title', 'Daftar Pelanggan')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            {{-- HEADER --}}
            <div class="row mb-4">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 font-size-18">Daftar Pelanggan</h4>
                        <p class="text-muted mb-0">Kelola data pelanggan, kendaraan, dan pantau aktivitas layanan mereka.</p>
                    </div>
                    <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#formPelanggan">
                        <i data-feather="plus-circle" class="icon-sm me-1"></i> Tambah Pelanggan
                    </button>
                </div>
            </div>

            {{-- NOTIFIKASI --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-check-all me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- FORM TAMBAH PELANGGAN --}}
            <div class="row collapse mb-4 @if($errors->any()) show @endif" id="formPelanggan">
                <div class="col-12">
                    <div class="card shadow-sm" style="border-radius: 12px;">
                        <div class="card-body">
                            <form action="{{ route('customers.store') }}" method="POST">
                                @csrf
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-4">
                                        <label class="form-label small text-muted fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan nama lengkap" value="{{ old('name') }}" required>
                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small text-muted fw-bold">No. HP / WhatsApp</label>
                                        <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="0812xxxx" value="{{ old('phone') }}">
                                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small text-muted fw-bold">Alamat</label>
                                        <input type="text" name="address" class="form-control" placeholder="Masukkan alamat singkat" value="{{ old('address') }}">
                                    </div>
                                    <div class="col-md-1 d-grid">
                                        <button type="submit" class="btn btn-success">
                                            <i class="bx bx-save"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TABEL PELANGGAN --}}
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="50">#</th>
                                            <th>Nama Pelanggan</th>
                                            <th>Kontak</th>
                                            <th>Alamat</th>
                                            <th class="text-center">Kendaraan</th>
                                            <th class="text-center">Layanan</th>
                                            <th>Tgl Bergabung</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($customers as $index => $customer)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><span class="fw-bold d-block text-dark">{{ $customer->name }}</span></td>
                                            <td>{{ $customer->phone ?? '-' }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($customer->address, 30) ?? '-' }}</td>
                                            <td class="text-center">
                                                <button class="btn btn-soft-info btn-sm" data-bs-toggle="modal" data-bs-target="#kendaraanModal{{ $customer->id }}">
                                                    <i data-feather="truck" class="icon-xs me-1"></i> 
                                                    {{ $customer->kendaraans ? $customer->kendaraans->count() : 0 }} Unit
                                                </button>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('services.index', ['customer_id' => $customer->id]) }}" class="btn btn-soft-primary btn-sm">
                                                    <i class="bx bx-history me-1"></i>
                                                    @php 
                                                        $totalLayanan = $customer->kendaraans ? $customer->kendaraans->sum(fn($k) => $k->services ? $k->services->count() : 0) : 0;
                                                    @endphp
                                                    {{ $totalLayanan }} Layanan
                                                </a>
                                            </td>
                                            <td>{{ $customer->created_at->format('d/m/Y') }}</td>
                                            <td class="text-center">
                                                <div class="dropdown">
                                                    <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                                        <i data-feather="more-vertical" class="icon-xs"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editCustomerModal{{ $customer->id }}">
                                                                <i class="bx bx-edit-alt me-2 text-warning"></i> Edit Profil
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('Hapus pelanggan?')">
                                                                @csrf @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <i class="bx bx-trash me-2"></i> Hapus
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="8" class="text-center py-5 text-muted">Belum ada data pelanggan.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MODAL SECTION --}}
            @foreach($customers as $customer)
                {{-- MODAL EDIT CUSTOMER --}}
                <div class="modal fade" id="editCustomerModal{{ $customer->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Profil Pelanggan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                                @csrf @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Nama Lengkap</label>
                                        <input type="text" name="name" class="form-control" value="{{ $customer->name }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">No. HP / WhatsApp</label>
                                        <input type="text" name="phone" class="form-control" value="{{ $customer->phone }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Alamat</label>
                                        <textarea name="address" class="form-control" rows="2">{{ $customer->address }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- MODAL KENDARAAN --}}
                <div class="modal fade" id="kendaraanModal{{ $customer->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-light">
                                <h5 class="modal-title">Unit Kendaraan: {{ $customer->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                {{-- Form Tambah Unit --}}
                                <div class="bg-light p-3 rounded mb-4 shadow-sm border">
                                    <form action="{{ route('kendaraans.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                        <div class="row g-2 align-items-start">
                                            <div class="col-md-3">
                                                <input type="text" name="plat_nomor" class="form-control form-control-sm" placeholder="Plat Nomor" required onkeyup="validatePlat(this)">
                                                {{-- FEEDBACK VISUAL --}}
                                                <div class="invalid-feedback feedback-plat" style="font-size: 10px;">Plat nomor sudah terdaftar!</div>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" name="merk" class="form-control form-control-sm" placeholder="Merk" required>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" name="tipe" class="form-control form-control-sm" placeholder="Tipe">
                                            </div>
                                            <div class="col-md-3">
                                                <button type="submit" class="btn btn-success btn-sm w-100 btn-submit-unit">Tambah Unit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <table class="table table-sm align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>PLAT</th>
                                            <th>UNIT</th>
                                            <th class="text-center">RIWAYAT</th>
                                            <th class="text-center">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($customer->kendaraans)
                                            @forelse($customer->kendaraans as $kendaraan)
                                            <tr>
                                                <td><span class="badge bg-dark">{{ $kendaraan->plat_nomor }}</span></td>
                                                <td>{{ $kendaraan->merk }} {{ $kendaraan->tipe }}</td>
                                                <td class="text-center">
                                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#historyModal{{ $kendaraan->id }}">
                                                        <i class="bx bx-list-ul"></i> Log
                                                    </button>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center gap-1">
                                                        <button class="btn btn-soft-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editKendaraanModal{{ $kendaraan->id }}">
                                                            <i class="bx bx-edit-alt"></i>
                                                        </button>
                                                        <form action="{{ route('kendaraans.destroy', $kendaraan->id) }}" method="POST" onsubmit="return confirm('Hapus kendaraan ini?')">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-soft-danger btn-sm"><i class="bx bx-trash"></i></button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr><td colspan="4" class="text-center py-3 text-muted">Belum ada unit.</td></tr>
                                            @endforelse
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- MODAL EDIT KENDARAAN --}}
                @if($customer->kendaraans)
                    @foreach($customer->kendaraans as $kendaraan)
                    <div class="modal fade" id="editKendaraanModal{{ $kendaraan->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Unit: {{ $kendaraan->plat_nomor }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('kendaraans.update', $kendaraan->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Plat Nomor</label>
                                            <input type="text" name="plat_nomor" class="form-control" value="{{ $kendaraan->plat_nomor }}" required onkeyup="this.value = this.value.toUpperCase()">
                                        </div>
                                        <div class="mb-3"><label class="form-label">Merk</label><input type="text" name="merk" class="form-control" value="{{ $kendaraan->merk }}" required></div>
                                        <div class="mb-3"><label class="form-label">Tipe</label><input type="text" name="tipe" class="form-control" value="{{ $kendaraan->tipe }}"></div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- MODAL HISTORY --}}
                    <div class="modal fade" id="historyModal{{ $kendaraan->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-dark text-white">
                                    <h5 class="modal-title text-white">Log Servis: {{ $kendaraan->plat_nomor }}</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <div class="card border p-3 text-center shadow-sm">
                                                <h6 class="text-muted small uppercase mb-1">Total Kedatangan</h6>
                                                <h4 class="mb-0 fw-bold">{{ $kendaraan->services ? $kendaraan->services->count() : 0 }}x</h4>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card border p-3 text-center shadow-sm">
                                                <h6 class="text-muted small uppercase mb-1">Total Biaya Akumulasi</h6>
                                                <h4 class="mb-0 text-primary fw-bold">Rp {{ number_format($kendaraan->services ? $kendaraan->services->sum('grand_total') : 0, 0, ',', '.') }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <table class="table table-bordered align-middle">
                                        <thead class="table-light">
                                            <tr><th>Tanggal</th><th>Jenis & Keluhan</th><th class="text-end">Biaya</th></tr>
                                        </thead>
                                        <tbody>
                                            @if($kendaraan->services)
                                                @forelse($kendaraan->services->sortByDesc('created_at') as $service)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($service->created_at)->format('d/m/Y') }}</td>
                                                    <td><span class="fw-bold d-block">{{ $service->jenis_service ?? 'Servis Umum' }}</span><small>{{ $service->keluhan ?? '-' }}</small></td>
                                                    <td class="text-end fw-bold text-success">Rp {{ number_format($service->grand_total, 0, ',', '.') }}</td>
                                                </tr>
                                                @empty
                                                <tr><td colspan="3" class="text-center py-4">Belum ada riwayat servis.</td></tr>
                                                @endforelse
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer bg-light">
                                    <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#kendaraanModal{{ $customer->id }}">Kembali</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            @endforeach

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if(typeof feather !== 'undefined') { feather.replace(); }
    });

    function validatePlat(input) {
        let plat = input.value.toUpperCase();
        input.value = plat;

        let form = input.closest('form');
        let btnSubmit = form.querySelector('.btn-submit-unit');
        let feedback = form.querySelector('.feedback-plat');

        if (plat.length > 3) {
            fetch(`{{ route('kendaraans.checkPlat') }}?plat_nomor=${plat}`)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        input.classList.add('is-invalid');
                        btnSubmit.disabled = true;
                        if(feedback) feedback.style.display = 'block';
                    } else {
                        input.classList.remove('is-invalid');
                        btnSubmit.disabled = false;
                        if(feedback) feedback.style.display = 'none';
                    }
                })
                .catch(error => console.error('Error:', error));
        } else {
            input.classList.remove('is-invalid');
            btnSubmit.disabled = false;
            if(feedback) feedback.style.display = 'none';
        }
    }
</script>
@endpush