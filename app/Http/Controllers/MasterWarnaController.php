<?php

namespace App\Http\Controllers;

use App\Models\MasterWarna;
use Illuminate\Http\Request;

class MasterWarnaController extends Controller
{
    public function index()
    {
        $master_warna = MasterWarna::all();
        return view('layouts.master_warna.index', compact('master_warna'));
    }

    public function create()
    {
        return view('layouts.master_warna.create');
    }

    public  function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_warna' => 'required|string|max:255',
        ]);

        $id_warna = "clr_" . str_replace('', '_', strtolower($validatedData['nama_warna']));
        $nama_warna = $request->input('nama_warna');

        $master_warna = new MasterWarna();
        $master_warna->id_warna = $id_warna;
        $master_warna->nama_warna = $nama_warna;
        $master_warna->save();

        return redirect()->route('master_warna.index')->with('success', 'Data warna berhasil ditambahkan');
    }

    public function edit($id_warna)
    {
        $master_warna = MasterWarna::find($id_warna);
        return view('layouts.master_warna.edit', compact('master_warna'));
    }

    public function update(Request $request, $id_warna)
    {
        $master_warna = MasterWarna::findOrFail($id_warna);

        $validatedData = $request->validate([
            'nama_warna' => 'required|string|max:255',
        ]);

        $old_nama_warna = $master_warna->nama_warna;
        $new_nama_warna = $validatedData['nama_warna'];

        if ($old_nama_warna !== $new_nama_warna) {
            $new_id_warna = "clr_" . str_replace(' ', '_', strtolower($new_nama_warna));
            $master_warna->id_warna = $new_id_warna;
        }

        $master_warna->nama_warna = $new_nama_warna;
        $master_warna->save();

        return redirect()->route('master_warna.index');
    }

    public function destroy($id_warna)
    {

        $master_warna = MasterWarna::findOrFail($id_warna);
        $master_warna->delete();
        return response()->json(['message' => 'Data berhasil dihapus'], 200);
    }
}
