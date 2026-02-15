<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Kendaraan;
use App\Models\Teknisi;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    /**
     * Tampilkan halaman service (form + list)
     */
    public function index()
    {
        $kendaraans = Kendaraan::with('customer')->get();
        $teknisis   = Teknisi::all();
        $spareparts = Sparepart::where('stock', '>', 0)->get();

        $services = Service::with([
            'kendaraan.customer',
            'teknisi'
        ])->latest()->get();

        return view('content.service', compact(
            'kendaraans',
            'teknisis',
            'spareparts',
            'services'
        ));
    }

    /**
     * Simpan transaksi service
     */
    public function store(Request $request)
    {
        $request->validate([
            'kendaraan_id'      => 'required|exists:kendaraans,id',
            'teknisi_id'        => 'required|exists:teknisis,id',
            'jenis_service'     => 'required|string',
            'tanggal'           => 'required|date',
            
            // TAMBAHKAN VALIDASI INI
            'status_servis'     => 'required|in:Proses,Selesai',
            'status_pembayaran' => 'required|in:Lunas,Belum Lunas',

            'service_desc'      => 'required|array|min:1',
            'service_desc.*'    => 'required|string',
            'service_price'     => 'required|array|min:1',
            'service_price.*'   => 'required|numeric|min:0',

            'sparepart_id.*'    => 'nullable|exists:spareparts,id',
            'sparepart_qty.*'   => 'nullable|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $totalService   = array_sum($request->service_price);
            $totalSparepart = 0;

            // Simpan header service
            $service = Service::create([
                'kendaraan_id'    => $request->kendaraan_id,
                'teknisi_id'      => $request->teknisi_id,
                'jenis_service'   => $request->jenis_service,
                'tanggal'         => $request->tanggal,
                'keluhan'         => $request->keluhan,
                
                // TAMBAHKAN DUA BARIS INI UNTUK MENANGKAP INPUT DARI FORM
                'status_servis'     => $request->status_servis,
                'status_pembayaran' => $request->status_pembayaran,

                'total_jasa'      => $totalService,
                'total_sparepart' => 0,
                'grand_total'     => 0,
            ]);

            // ======================
            // SIMPAN JASA (Items)
            // ======================
            foreach ($request->service_desc as $key => $desc) {
                $service->items()->create([
                    'nama_jasa' => $desc,
                    'harga'     => $request->service_price[$key]
                ]);
            }

            // ======================
            // SIMPAN SPAREPART
            // ======================
            if ($request->sparepart_id) {
                foreach ($request->sparepart_id as $key => $partId) {
                    if (!$partId) continue;

                    $qty = $request->sparepart_qty[$key] ?? 1;
                    $part = Sparepart::findOrFail($partId);

                    if ($part->stock < $qty) {
                        throw new \Exception("Stok {$part->name} tidak cukup!");
                    }

                    $subtotal = $part->selling_price * $qty;
                    $totalSparepart += $subtotal;

                    $part->decrement('stock', $qty);

                    $service->spareparts()->create([
                        'sparepart_id' => $partId,
                        'qty'          => $qty,
                        'harga_satuan' => $part->selling_price, // Pastikan kolom ini ada di migrasi
                        'harga'        => $subtotal
                    ]);
                }
            }

            // Update total akhir
            $service->update([
                'total_sparepart' => $totalSparepart,
                'grand_total'     => $totalService + $totalSparepart
            ]);

            DB::commit();

            return redirect()
                ->route('services.show', $service->id)
                ->with('success', 'Transaksi berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Tampilkan Detail Transaksi Service
     */
    public function show($id)
    {
        // Load service dengan semua relasi yang dibutuhkan untuk invoice/detail
        $service = Service::with([
            'kendaraan.customer',
            'teknisi',
            'items',            // Relasi ke tabel jasa/item service
            'spareparts.sparepart' // Relasi ke detail sparepart yang digunakan
        ])->findOrFail($id);

        return view('content.service_detail', compact('service'));
    }

        public function updateStatus(Request $request, Service $service)
    {
        $service->update([
            'status_servis' => 'Selesai',
            'status_pembayaran' => 'Lunas'
        ]);

        return back()->with('success', 'Status transaksi telah diperbarui menjadi Selesai & Lunas!');
    }

    public function transaksi(Request $request)
    {
        $query = Service::with(['kendaraan.customer', 'teknisi']);

        // Filter Tanggal
        if ($request->from && $request->to) {
            $query->whereBetween('tanggal', [$request->from, $request->to]);
        }

        // Filter Status Pengerjaan
        if ($request->status_servis) {
            $query->where('status_servis', $request->status_servis);
        }

        // Filter Status Pembayaran
        if ($request->status_pembayaran) {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }

        $services = $query->orderBy('tanggal', 'desc')->get();

        return view('content.transaksi', compact('services'));
    }

}