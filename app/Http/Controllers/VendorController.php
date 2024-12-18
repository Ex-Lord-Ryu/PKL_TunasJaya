<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{

    public function index(Request $request)
    {
        $vendor = Vendor::all();
        return view('layouts.vendor.index', compact('vendor'));
    }


    public function create()
    {
        return view('layouts.vendor.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_vendor' => 'required|max:255',
            'alamat' => 'required',
        ]);

        Vendor::create($validatedData);

        return redirect()->route('vendor.index')->with('message', 'Vendor berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $vendor = Vendor::findOrFail($id);
        return view('layouts.vendor.edit', compact('vendor'));
    }

    public function update(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);

        $validatedData = $request->validate([
            'nama_vendor' => 'required|max:255',
            'alamat' => 'required',
        ]);

        $vendor->update($validatedData);

        return redirect()->route('vendor.index')->with('message', 'Vendor berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();

        return redirect()->route('vendor.index')->with('message', 'Vendor berhasil dihapus.');
    }
}