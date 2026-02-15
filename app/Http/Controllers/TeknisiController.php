<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teknisi;

class TeknisiController extends Controller
{
    public function index()
    {
        $teknisis = Teknisi::latest()->get();
        return view('content.teknisi', compact('teknisis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'nullable|email|unique:teknisis,email',
            'phone' => 'nullable|string|max:20',
        ]);

        Teknisi::create($request->all());

        return redirect()->route('teknisis.index')->with('success', 'Teknisi berhasil ditambahkan.');
    }

    public function update(Request $request, Teknisi $teknisi)
    {
        $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'nullable|email|unique:teknisis,email,' . $teknisi->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $teknisi->update($request->all());

        return redirect()->route('teknisis.index')->with('success', 'Teknisi berhasil diupdate.');
    }

    public function destroy(Teknisi $teknisi)
    {
        $teknisi->delete();
        return redirect()->route('teknisis.index')->with('success', 'Teknisi berhasil dihapus.');
    }
}
