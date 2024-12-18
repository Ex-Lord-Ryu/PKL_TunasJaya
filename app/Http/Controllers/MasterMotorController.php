<?php

namespace App\Http\Controllers;

use App\Models\MasterMotor;
use Illuminate\Http\Request;

class MasterMotorController extends Controller
{
    public function index()
    {
        $master_motor = MasterMotor::all();
        return view('layouts.master_motor.index', compact('master_motor'));
    }

    public function create()
    {
        return view('layouts.master_motor.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_motor' => 'required|max:255',
            'nomor_rangka' => 'required|max:20',
            'nomor_mesin' => 'required|max:20',
        ]);

        MasterMotor::create($validatedData);

        return redirect()->route('master_motor.index')->with('message', 'Master Motor berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $master_motor = MasterMotor::findOrFail($id);
        return view('layouts.master_motor.edit', compact('master_motor'));
    }

    public function update(Request $request, $id)
    {
        $master_motor = MasterMotor::findOrFail($id);

        $validatedData = $request->validate([
            'nama_motor' => 'required|max:255',
            'nomor_rangka' => 'required|max:20',
            'nomor_mesin' => 'required|max:20',
        ]); 

        $master_motor->update($validatedData);

        return redirect()->route('master_motor.index')->with('message', 'Master Motor berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $master_motor = MasterMotor::findOrFail($id);
        $master_motor->delete();

        return redirect()->route('master_motor.index')->with('message', 'Master Motor berhasil dihapus.');
    }
}
