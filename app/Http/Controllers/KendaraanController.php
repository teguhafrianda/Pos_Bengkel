<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kendaraan;
use App\Models\Customer;

class KendaraanController extends Controller
{
    // Tampilkan daftar kendaraan + form tambah
    public function index()
    {
        $kendaraans = Kendaraan::with('customer')->latest()->get();
        $customers = Customer::orderBy('name')->get(); // untuk dropdown customer
        return view('content.kendaraan', compact('kendaraans', 'customers'));
    }

    // Simpan kendaraan baru
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'plat_nomor'  => 'required|string|max:20',
            'merk'        => 'required|string|max:50',
            'tipe'        => 'nullable|string|max:50',
            'warna'       => 'nullable|string|max:30',
        ]);

        Kendaraan::create($request->all());

        return redirect()->route('kendaraans.index')
                         ->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    // Update kendaraan
    public function update(Request $request, Kendaraan $kendaraan)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'plat_nomor'  => 'required|string|max:20',
            'merk'        => 'required|string|max:50',
            'tipe'        => 'nullable|string|max:50',
            'warna'       => 'nullable|string|max:30',
        ]);

        $kendaraan->update($request->all());

        return redirect()->route('kendaraans.index')
                         ->with('success', 'Kendaraan berhasil diupdate.');
    }

    // Hapus kendaraan
    public function destroy(Kendaraan $kendaraan)
    {
        $kendaraan->delete();
        return redirect()->route('kendaraans.index')
                         ->with('success', 'Kendaraan berhasil dihapus.');
    }
}
