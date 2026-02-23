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

    public function checkPlat(Request $request)
{
    // Mengambil plat dari query string
    $plat = $request->query('plat_nomor');

    // Cek apakah plat sudah ada di database
    $exists = \App\Models\Kendaraan::where('plat_nomor', $plat)->exists();

    return response()->json([
        'exists' => $exists
    ]);
}

    // Simpan kendaraan baru
public function store(Request $request)
{
    $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'plat_nomor'  => 'required|string|max:15|unique:kendaraans,plat_nomor', // Validasi unik di sini
        'merk'        => 'required|string|max:50',
        'tipe'        => 'nullable|string|max:50',
    ], [
        'plat_nomor.unique' => 'Plat nomor ini sudah terdaftar di sistem.'
    ]);

    // Pastikan plat nomor disimpan dalam huruf besar
    $data = $request->all();
    $data['plat_nomor'] = strtoupper($request->plat_nomor);

    \App\Models\Kendaraan::create($data);

    return redirect()->back()->with('success', 'Unit kendaraan berhasil ditambahkan.');
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

        return redirect()->route('kendaraan.index')
                         ->with('success', 'Kendaraan berhasil diupdate.');
    }

    // Hapus kendaraan
    public function destroy(Kendaraan $kendaraan)
    {
        $kendaraan->delete();
        return redirect()->route('customers.index')
                         ->with('success', 'Kendaraan berhasil dihapus.');
    }

   
}
