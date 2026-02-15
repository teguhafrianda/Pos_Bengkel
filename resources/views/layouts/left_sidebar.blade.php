<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">

                {{-- ================= MENU UTAMA ================= --}}
                <li class="menu-title" data-key="t-menu">Menu Utama</li>
                <li>
                    <a href="{{ route('home') }}">
                        <i data-feather="home"></i>
                        <span data-key="t-dashboard">Dashboard</span>
                    </a>
                </li>

                {{-- ================= MANAJEMEN DATA ================= --}}
                <li class="menu-title mt-2" data-key="t-management">Manajemen Data</li>
                <li>
                    <a href="{{ route('customers.index') }}">
                        <i data-feather="users"></i>
                        <span data-key="t-pelanggan">Pelanggan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('kendaraans.index') }}">
                        <i data-feather="truck"></i>
                        <span data-key="t-kendaraan">Kendaraan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('spareparts.index') }}">
                        <i data-feather="package"></i>
                        <span data-key="t-sparepart">Sparepart</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('services.index') }}">
                        <i data-feather="settings"></i>
                        <span data-key="t-layanan">Layanan / Jasa</span>
                    </a>
                </li>

                {{-- ================= TRANSAKSI ================= --}}
                <li class="menu-title mt-2" data-key="t-transaction">Transaksi</li>
                <li>
                    <a href="{{ route('transaksi.index') }}">
                        <i data-feather="shopping-cart"></i>
                        <span data-key="t-penjualan">Penjualan</span>
                    </a>
                </li>

                {{-- ================= SDM & OPERASIONAL ================= --}}
                <li class="menu-title mt-2" data-key="t-staff">SDM & Operasional</li>
                <li>
                    <a href="{{ route('teknisis.index') }}">
                        <i data-feather="tool"></i>
                        <span data-key="t-teknisi">Teknisi</span>
                    </a>
                </li>

                {{-- ================= LAPORAN ================= --}}
                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="file-text"></i>
                        <span data-key="t-laporan">Laporan</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="{{ route('transaksi.index') }}" data-key="t-lapor-jual">
                                Laporan Penjualan
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pengeluaran.index') }}" data-key="t-lapor-duit">
                                Pengeluaran
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>

            {{-- ================= SIDEBAR INFO ================= --}}
            <div class="sidebar-info text-center mx-4 mb-4 mt-5 p-3 rounded bg-soft-primary">
                <h6 class="text-primary mb-1">Status Bengkel</h6>
                <span class="badge bg-success">Buka</span>
            </div>

        </div>
    </div>
</div>
