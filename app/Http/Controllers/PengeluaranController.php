<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PengeluaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengeluaran::query();

        // Filter Tanggal
        if ($request->dari && $request->sampai) {
            $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
        }

        // Filter Kategori
        if ($request->kategori && $request->kategori != 'Semua') {
            $query->where('kategori', $request->kategori);
        }

        $pengeluaran = $query->orderBy('tanggal', 'desc')->paginate(15);
        
        // Total bulan ini
        $totalBulanIni = Pengeluaran::whereMonth('tanggal', Carbon::now()->month)
                            ->whereYear('tanggal', Carbon::now()->year)
                            ->sum('jumlah');

        return view('content.pengeluaran', compact('pengeluaran', 'totalBulanIni'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required',
            'deskripsi' => 'required',
            'jumlah' => 'required|numeric',
            'metode_pembayaran' => 'required',
        ]);

        Pengeluaran::create($request->all());

        return redirect()->back()->with('success', 'Pengeluaran berhasil dicatat!');
    }

    public function destroy($id)
    {
        Pengeluaran::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }
}