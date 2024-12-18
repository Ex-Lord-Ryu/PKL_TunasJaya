<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\OrderMotor;
use App\Models\MasterMotor;
use App\Models\MasterWarna;
use App\Models\SoldMotor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreOrderMotorRequest;
use App\Http\Requests\UpdateOrderMotorRequest;
use Illuminate\Support\Facades\Log;

class OrderMotorController extends Controller
{
    public function index()
    {
        $orders = OrderMotor::with(['user', 'master_motor', 'master_warna'])
                           ->orderBy('created_at', 'desc')
                           ->get();
        return view('layouts.order_motor.index', compact('orders'));
    }

    public function create()
    {
        $motors = MasterMotor::all();
        $warnas = MasterWarna::all();
        $stocks = Stock::whereNull('order_id')->get(); // Ambil stock yang belum diorder
        return view('layouts.order_motor.create', compact('motors', 'warnas', 'stocks'));
    }

    public function store(StoreOrderMotorRequest $request)
    {
        try {
            DB::beginTransaction();

            // Debug logs
            Log::info('Store method called');
            Log::info('Request data:', $request->all());

            foreach ($request->orders as $orderData) {
                // Cek ketersediaan stock
                $stock = Stock::find($orderData['stock_id']);
                Log::info('Stock data:', ['stock' => $stock]);
                
                if (!$stock || $stock->order_id) {
                    throw new \Exception('Stock tidak tersedia atau sudah diorder: ' . $stock->no_mesin);
                }

                $orderData = [
                    'user_id' => Auth::id(),
                    'motor_id' => $orderData['motor_id'],
                    'warna_id' => $orderData['warna_id'],
                    'jumlah_motor' => $orderData['jumlah_motor'],
                    'pengiriman' => $request->pengiriman,
                    'pembayaran' => $request->pembayaran,
                    'no_rangka' => $orderData['no_rangka'],
                    'no_mesin' => $orderData['no_mesin'],
                    'harga_jual' => $orderData['harga_jual'],
                    'nama_pembeli' => $request->nama_pembeli,
                    'alamat_pembeli' => $request->alamat_pembeli,
                    'no_hp_pembeli' => $request->no_hp_pembeli,
                    'status' => 'active'
                ];

                // Tambahkan data kredit jika metode pembayaran adalah Kredit
                if ($request->pembayaran === 'Kredit') {
                    $orderData = array_merge($orderData, [
                        'dp' => str_replace(['Rp', '.', ' '], '', $request->dp),
                        'tenor' => $request->tenor,
                        'angsuran' => str_replace(['Rp', '.', ' '], '', $request->angsuran)
                    ]);
                }

                $order = OrderMotor::create($orderData);

                // Update stock dengan order_id
                $stock->update(['order_id' => $order->id]);
            }

            DB::commit();
            return redirect()->route('order_motor.index')->with('success', 'Order berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function cancel(OrderMotor $orderMotor)
    {
        try {
            DB::beginTransaction();

            // Update status order menjadi cancelled
            $orderMotor->update(['status' => 'cancelled']);

            // Kembalikan stock
            Stock::where('no_mesin', $orderMotor->no_mesin)
                 ->update(['order_id' => null]);

            DB::commit();
            return redirect()->route('order_motor.index')
                           ->with('success', 'Order berhasil dibatalkan dan stock dikembalikan');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(OrderMotor $orderMotor)
    {
        $motors = MasterMotor::all();
        $warnas = MasterWarna::all();
        return view('layouts.order_motor.edit', compact('orderMotor', 'motors', 'warnas'));
    }

    public function update(UpdateOrderMotorRequest $request, OrderMotor $orderMotor)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            // Tambahkan data pembeli
            $data['nama_pembeli'] = $request->nama_pembeli;
            $data['alamat_pembeli'] = $request->alamat_pembeli;
            $data['no_hp_pembeli'] = $request->no_hp_pembeli;

            // Jika pembayaran Kredit, format nilai currency
            if ($request->pembayaran === 'Kredit') {
                $data['dp'] = str_replace(['Rp', '.', ' '], '', $request->dp);
                $data['angsuran'] = str_replace(['Rp', '.', ' '], '', $request->angsuran);
            } else {
                // Jika bukan Kredit, set nilai kredit ke null
                $data['dp'] = null;
                $data['tenor'] = null;
                $data['angsuran'] = null;
            }

            $orderMotor->update($data);

            DB::commit();
            return redirect()->route('order_motor.index')->with('success', 'Order berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(OrderMotor $orderMotor)
    {
        $orderMotor->delete();
        return redirect()->route('order-motor.index')->with('success', 'Order berhasil dihapus');
    }

    public function complete(OrderMotor $orderMotor)
    {
        try {
            DB::beginTransaction();

            // Update status order menjadi completed
            $orderMotor->update(['status' => 'completed']);

            // Buat record baru di sold_motors
            SoldMotor::create([
                'motor_id' => $orderMotor->motor_id,
                'warna_id' => $orderMotor->warna_id,
                'harga' => $orderMotor->harga_jual,
                'total_harga' => $orderMotor->harga_jual * $orderMotor->jumlah_motor,
                'no_rangka' => $orderMotor->no_rangka,
                'no_mesin' => $orderMotor->no_mesin,
                'tanggal_penjualan' => now(),
                'nama_pembeli' => $orderMotor->nama_pembeli,
                'alamat_pembeli' => $orderMotor->alamat_pembeli,
                'no_hp_pembeli' => $orderMotor->no_hp_pembeli,
                'metode_pembayaran' => $orderMotor->pembayaran,
                'dp' => $orderMotor->dp,
                'tenor' => $orderMotor->tenor,
                'angsuran' => $orderMotor->angsuran
            ]);

            // Update stock status
            Stock::where('order_id', $orderMotor->id)
                 ->update(['status' => 'sold']);

            DB::commit();
            return redirect()->route('order_motor.index')
                           ->with('success', 'Order berhasil diselesaikan dan dipindahkan ke Sold Motor');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error completing order: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(OrderMotor $orderMotor)
    {
        $orderMotor->load(['user', 'master_motor', 'master_warna']);
        return view('layouts.order_motor.show', compact('orderMotor'));
    }
}
