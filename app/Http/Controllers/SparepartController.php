<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use Illuminate\Http\Request;

class SparepartController extends Controller
{
    /**
     * Menampilkan daftar sparepart.
     */
    public function index()
    {
        // Ambil data terbaru dulu
        $spareparts = Sparepart::latest()->get(); 
        
        return view('content.sparepart', compact('spareparts'));
    }

    /**
     * Menyimpan data sparepart baru.
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'name'          => 'required|string|max:255',
            'cost_price'    => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock'         => 'required|integer|min:0',
        ]);

        // 2. Simpan ke database
        Sparepart::create($request->all());

        // 3. Kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->route('spareparts.index')
                         ->with('success', 'Sparepart berhasil ditambahkan!');
    }

    public function updateStok(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:spareparts,id',
            'add_stock' => 'required|integer|min:1',
        ]);

        $sparepart = Sparepart::findOrFail($request->id);
        
        // Logika penambahan stok (increment)
        $sparepart->increment('stock', $request->add_stock);

        return redirect()->back()->with('success', "Stok {$sparepart->name} berhasil ditambah sebanyak {$request->add_stock} pcs.");
    }

    /**
     * Menghapus sparepart.
     */
    public function destroy(Sparepart $sparepart)
    {
        $sparepart->delete();

        return redirect()->route('spareparts.index')
                         ->with('success', 'Sparepart berhasil dihapus!');
    }
}