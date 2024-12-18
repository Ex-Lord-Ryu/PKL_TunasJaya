<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Pembelian;
use App\Models\MasterMotor;
use App\Models\MasterWarna;
use Illuminate\Http\Request;
use App\Models\PembelianDetail;

class PembelianDetailController extends Controller
{
    public function index()
    {
        $pembelian_detail = PembelianDetail::with(['pembelian'])
            ->get()
            ->groupBy('pembelian.invoice_pembelian')
            ->map(function ($group) {
                $firstItem = $group->first();
                return [
                    'pembelian_id' => $firstItem->pembelian_id,
                    'invoice_pembelian' => $firstItem->pembelian->invoice_pembelian,
                    'tanggal_pembuatan' => $firstItem->created_at ?? now(),
                    'total_items' => $group->count(),
                    'status' => $firstItem->status,
                ];
            })
            ->values();

        return view('layouts.pembelian_detail.index', compact('pembelian_detail'));
    }

    public function create()
    {
        // Hanya ambil pembelian dengan status Pending
        $pembelian = Pembelian::where('status', 'Pending')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $master_motor = MasterMotor::all();
        $master_warna = MasterWarna::all();
        
        // Jika tidak ada pembelian pending, redirect dengan pesan
        if ($pembelian->isEmpty()) {
            return redirect()->route('pembelian.create')
                ->with('warning', 'Buat pembelian baru terlebih dahulu sebelum menambahkan detail pembelian.');
        }
        
        return view('layouts.pembelian_detail.create', compact('pembelian', 'master_motor', 'master_warna'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pembelian_id' => 'required|exists:pembelians,id',
            'motor_ids' => 'required|array',
            'motor_ids.*' => 'exists:master_motors,id',
            'motor_quantities' => 'required|array',
            'motor_quantities.*' => 'integer|min:1',
            'motor_prices' => 'required|array',
            'motor_prices.*' => 'numeric|min:0',
            'motor_warna_ids' => 'required|array',
            'motor_warna_ids.*' => 'exists:master_warnas,id_warna',
        ]);

        DB::beginTransaction();

        try {
            $pembelianId = $request->pembelian_id;
            $motorIds = $request->motor_ids;
            $quantities = $request->motor_quantities;
            $prices = $request->motor_prices;
            $warnaIds = $request->motor_warna_ids;

            for ($i = 0; $i < count($motorIds); $i++) {
                $total_harga = $quantities[$i] * $prices[$i]; // Hitung total_harga

                PembelianDetail::create([
                    'pembelian_id' => $pembelianId,
                    'motor_id' => $motorIds[$i],
                    'warna_id' => $warnaIds[$i],
                    'jumlah_motor' => $quantities[$i],
                    'harga_motor' => $prices[$i],
                    'total_harga' => $total_harga, // Tambahkan total_harga
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Data pembelian detail berhasil disimpan.',
                'redirect' => route('pembelian_detail.index')
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $pembelian = Pembelian::findOrFail($id);

        // Check if the status is not 'Pending'
        if (strtolower($pembelian->status) !== 'pending') {
            return redirect()->route('pembelian_detail.index')
                ->with('error', 'Cannot edit completed or cancelled purchase.');
        }

        $pembelian_details = PembelianDetail::where('pembelian_id', $id)->with(['motor', 'warna'])->get();
        $master_motor = MasterMotor::all();
        $master_warna = MasterWarna::all();

        return view('layouts.pembelian_detail.edit', compact('pembelian', 'pembelian_details', 'master_motor', 'master_warna'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'detail_ids' => 'required|array',
            'detail_ids.*' => 'exists:pembelian_details,id',
            'motor_ids' => 'required|array',
            'motor_ids.*' => 'exists:master_motors,id',
            'warna_ids' => 'required|array',
            'warna_ids.*' => 'exists:master_warnas,id_warna',
            'jumlah_motors' => 'required|array',
            'jumlah_motors.*' => 'integer|min:1',
            'harga_motors' => 'required|array',
            'harga_motors.*' => 'numeric|min:0',
            'status' => 'required|in:Pending,Completed,Cancelled',
        ]);

        $pembelian = Pembelian::findOrFail($id);

        // Check if the status is not 'Pending'
        if (strtolower($pembelian->status) !== 'pending') {
            return redirect()->route('pembelian_detail.index')
                ->with('error', 'Cannot update completed or cancelled purchase.');
        }

        DB::beginTransaction();

        try {
            $totalHarga = 0;

            // Update existing details and their status
            foreach ($request->detail_ids as $index => $detailId) {
                $detail = PembelianDetail::findOrFail($detailId);
                $detail->update([
                    'motor_id' => $request->motor_ids[$index],
                    'warna_id' => $request->warna_ids[$index],
                    'jumlah_motor' => $request->jumlah_motors[$index],
                    'harga_motor' => $request->harga_motors[$index],
                ]);

                $totalHarga += $request->jumlah_motors[$index] * $request->harga_motors[$index];
            }

            // Update total harga and status in pembelian
            $pembelian->update([
                'total_harga' => $totalHarga
            ]);

            DB::commit();

            return response()->json(['redirect' => route('pembelian_detail.index')]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $pembelian = Pembelian::findOrFail($id);

        // Jika tanggal_pembuatan null, gunakan created_at atau nilai default lainnya
        $pembelian->tanggal_pembuatan = $pembelian->tanggal_pembuatan ?? $pembelian->created_at ?? now();

        $details = PembelianDetail::with(['master_motor', 'warna'])
            ->where('pembelian_id', $id)
            ->get()
            ->map(function ($detail) {
                return [
                    'motor' => $detail->master_motor->nama_motor ?? 'N/A',
                    'warna' => $detail->warna->nama_warna ?? 'N/A',
                    'jumlah_motor' => $detail->jumlah_motor,
                    'harga_motor' => $detail->harga_motor,
                    'total_harga' => $detail->total_harga,
                    'status' => $detail->status,
                ];
            });

        return view('layouts.pembelian_detail.show', compact('pembelian', 'details'));
    }

    public function destroy($id)
    {
        $pembelian = Pembelian::findOrFail($id);

        // Check if the status is not 'Pending'
        if (strtolower($pembelian->status) !== 'pending') {
            return redirect()->route('pembelian_detail.index')
                ->with('error', 'Cannot delete completed or cancelled purchase.');
        }

        DB::beginTransaction();

        try {
            // Delete all related PembelianDetail records
            PembelianDetail::where('pembelian_id', $id)->delete();

            // Delete the Pembelian record
            $pembelian->delete();

            DB::commit();

            return redirect()->route('pembelian_detail.index')
                ->with('success', 'Purchase and all its details deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('pembelian_detail.index')
                ->with('error', 'An error occurred while deleting: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        Log::info('updateStatus called with id: ' . $id . ', status: ' . $request->status);
    
        DB::beginTransaction();
    
        try {
            $pembelian = Pembelian::findOrFail($id);
            $oldStatus = $pembelian->status;
            $newStatus = $request->status;
    
            Log::info('Current pembelian status: ' . $oldStatus);
    
            // Validasi status
            if (strtolower($oldStatus) !== 'pending') {
                throw new \Exception('Hanya pembelian dengan status Pending yang dapat diubah.');
            }
    
            // Update status PembelianDetail terlebih dahulu
            PembelianDetail::where('pembelian_id', $id)->update(['status' => $newStatus]);
    
            // Update status Pembelian
            $pembelian->status = $newStatus;
            $pembelian->save();
    
            // Jika status baru adalah 'Completed', transfer ke stock
            if (strtolower($newStatus) === 'completed') {
                $stockController = new StockController();
                $transferResult = $stockController->TranferToStock($pembelian->invoice_pembelian);
    
                if (!$transferResult['success']) {
                    throw new \Exception('Gagal mentransfer ke stock: ' . ($transferResult['message'] ?? 'Unknown error'));
                }
    
                Log::info("Stock transfer successful for invoice: " . $pembelian->invoice_pembelian);
            }
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => "Status pembelian berhasil diubah dari $oldStatus menjadi $newStatus",
                'transferResult' => isset($transferResult) ? $transferResult : null
            ]);
    
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating status: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
