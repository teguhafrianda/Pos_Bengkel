<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kendaraan;
use App\Models\Customer;

class KendaraanController extends Controller
{
    // Tampilkan kendaraan per pelanggan
    public function index()
    {
        $customers = Customer::with('kendaraans')->orderBy('name')->get();
        return view('content.pelanggan', compact('customers'));
    }

    // Cek plat nomor untuk AJAX
    public function checkPlat(Request $request)
    {
        $plat = strtoupper($request->query('plat_nomor'));
        $exists = Kendaraan::where('plat_nomor', $plat)->exists();

        return response()->json([
            'exists' => $exists
        ]);
    }

    // Simpan kendaraan baru
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'plat_nomor'  => 'required|string|max:15|unique:kendaraans,plat_nomor',
            'merk'        => 'required|string|max:50',
            'tipe'        => 'nullable|string|max:50',
        ]);

        Kendaraan::create([
            'customer_id' => $request->customer_id,
            'plat_nomor'  => strtoupper($request->plat_nomor),
            'merk'        => $request->merk,
            'tipe'        => $request->tipe,
        ]);

        return redirect()->back()->with('success', 'Unit kendaraan berhasil ditambahkan.');
    }

    // Update kendaraan
    public function update(Request $request, $id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'plat_nomor'  => 'required|string|max:15|unique:kendaraans,plat_nomor,' . $kendaraan->id,
            'merk'        => 'required|string|max:50',
            'tipe'        => 'nullable|string|max:50',
        ]);

        $kendaraan->update([
            'customer_id' => $request->customer_id,
            'plat_nomor'  => strtoupper($request->plat_nomor),
            'merk'        => $request->merk,
            'tipe'        => $request->tipe,
        ]);

        return redirect()->back()->with('success', 'Kendaraan berhasil diperbarui.');
    }

    // Hapus kendaraan
    public function destroy($id)
    {
        $kendaraan = Kendaraan::findOrFail($id);
        $kendaraan->delete();

        return redirect()->back()->with('success', 'Kendaraan berhasil dihapus.');
    }
}