<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Customer;
use App\Models\Sparepart;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
public function dashboard(Request $request)
{
    // 1. Ambil tanggal dari request, jika kosong default ke hari ini
    $tanggalTerpilih = $request->get('tanggal', now()->format('Y-m-d'));
    $date = Carbon::parse($tanggalTerpilih); //

    // ================= STATISTIC (BERDASARKAN TANGGAL FILTER) =================
    $countServiceHariIni = Service::whereDate('tanggal', $tanggalTerpilih)->count(); //
    $totalPendapatanBulanIni = Service::whereDate('tanggal', $tanggalTerpilih)
        ->where('status_pembayaran', 'Lunas')
        ->sum('grand_total'); //
    $totalPengeluaranBulanIni = Pengeluaran::whereDate('tanggal', $tanggalTerpilih)
        ->sum('jumlah'); //
    $saldoAkhirBulanIni = $totalPendapatanBulanIni - $totalPengeluaranBulanIni; //

    // Statistik Umum
    $totalCustomer = Customer::count(); //
    $stokKritis = Sparepart::where('stock', '<=', 5)->count(); //

    // ================= GRAFIK 6 BULAN (DINAMIS BERDASARKAN FILTER) =================
    $months = collect();
    // Titik awal grafik adalah 5 bulan sebelum bulan yang dipilih
    $start = $date->copy()->subMonths(5)->startOfMonth(); 
    $end = $date->copy()->endOfMonth();

    for ($i = 0; $i < 6; $i++) {
        $month = $start->copy()->addMonths($i);
        $months->put($month->format('Y-m'), [
            'label' => $month->translatedFormat('M Y'),
            'total' => 0
        ]);
    }

    // Ambil data pendapatan dalam rentang 6 bulan tersebut
    $pendapatanData = Service::selectRaw('DATE_FORMAT(tanggal, "%Y-%m") as bulan, SUM(grand_total) as total')
        ->where('status_pembayaran', 'Lunas')
        ->whereBetween('tanggal', [$start, $end])
        ->groupBy('bulan')
        ->pluck('total', 'bulan'); //

    // Ambil data pengeluaran dalam rentang 6 bulan tersebut
    $pengeluaranData = Pengeluaran::selectRaw('DATE_FORMAT(tanggal, "%Y-%m") as bulan, SUM(jumlah) as total')
        ->whereBetween('tanggal', [$start, $end])
        ->groupBy('bulan')
        ->pluck('total', 'bulan'); //

    foreach ($months as $key => $val) {
        $pemasukan = $pendapatanData->get($key, 0);
        $pengeluaran = $pengeluaranData->get($key, 0);
        $months->put($key, [
            'label' => $val['label'],
            'total' => $pemasukan - $pengeluaran 
        ]);
    }
    $grafikBulanan = $months->values();

    // ================= DATA TABEL & LIST =================
    $serviceAktif = Service::with(['kendaraan.customer'])
        ->where('status_servis', 'Proses')
        ->latest()->take(5)->get(); //

    $transaksiTerakhir = Service::with(['kendaraan.customer'])
        ->whereDate('tanggal', $tanggalTerpilih)
        ->latest()->take(10)->get(); //

    return view('content.dashboard', compact(
        'countServiceHariIni',
        'totalPendapatanBulanIni',
        'totalPengeluaranBulanIni',
        'saldoAkhirBulanIni',
        'totalCustomer',
        'stokKritis',
        'grafikBulanan',
        'serviceAktif',
        'transaksiTerakhir',
        'tanggalTerpilih'
    ));
}

    public function service()
    {
        return view('content.service');
    }
}