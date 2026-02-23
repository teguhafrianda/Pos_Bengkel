<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index()
    {
        // Menggunakan Eager Loading agar data Kendaraan & Service muncul (mencegah error sum on null)
        $customers = Customer::with(['kendaraans.services'])->latest()->get();
        return view('content.pelanggan', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        Customer::create($request->only(['name', 'phone', 'address']));

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    // Gunakan $id daripada Customer $customer untuk memastikan refresh data di View
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->update($request->only(['name', 'phone', 'address']));

        // Redirect ke route index untuk memaksa view memuat ulang data terbaru
        return redirect()->route('customers.index')->with('success', 'Profil pelanggan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $customer = Customer::findOrFail($id);

            // Jika tidak menggunakan onDelete('cascade') di database, hapus manual relasinya
            if ($customer->kendaraans()->exists()) {
                foreach ($customer->kendaraans as $kendaraan) {
                    $kendaraan->services()->delete(); // Hapus servis kendaraan
                }
                $customer->kendaraans()->delete(); // Hapus kendaraan
            }

            $customer->delete();
            DB::commit();

            return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('customers.index')->with('error', 'Gagal menghapus pelanggan.');
        }
    }
}