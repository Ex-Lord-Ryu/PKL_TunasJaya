<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Vendor;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use App\Models\PembelianDetail;
use App\Http\Controllers\StockController;

class PembelianController extends Controller
{
    public function index()
    {
        $pembelian = Pembelian::with('vendor')->get();
        return view('layouts.pembelian.index', compact('pembelian'));
    }

    public function create()
    {
        $vendors = Vendor::all();
        $availableInvoices = $this->getAvailableInvoices();
        
        // Hanya tampilkan pembelian dengan status Pending
        $pendingPembelian = Pembelian::where('status', 'Pending')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('layouts.pembelian.create', compact('vendors', 'availableInvoices', 'pendingPembelian'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        
        try {
            $request->validate([
                'vendor_id' => 'required|exists:vendors,id',
                'metode_pembayaran' => 'required|in:Cash,Transfer,Kredit',
                'metode_pengiriman' => 'required|in:Kapal Kargo,Truck,Pick-Up,Mobil-Box',
            ]);

            // Generate invoice number
            $invoice = $this->generateUniqueInvoice();

            // Cek apakah invoice sudah ada
            $existingPembelian = Pembelian::where('invoice_pembelian', $invoice)->first();
            
            if ($existingPembelian) {
                throw new \Exception('Invoice sudah ada dalam sistem. Silakan coba lagi.');
            }

            // Buat pembelian baru
            $pembelian = new Pembelian();
            $pembelian->vendor_id = $request->vendor_id;
            $pembelian->invoice_pembelian = $invoice;
            $pembelian->metode_pembayaran = $request->metode_pembayaran;
            $pembelian->metode_pengiriman = $request->metode_pengiriman;
            $pembelian->tanggal_pembelian = now();
            $pembelian->status = 'Pending';
            
            if (!$pembelian->save()) {
                throw new \Exception('Gagal menyimpan data pembelian.');
            }

            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Pembelian berhasil ditambahkan.',
                'redirect' => route('pembelian.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate unique invoice number
     * @return string
     */
    private function generateUniqueInvoice()
    {
        $prefix = 'INV';
        $year = date('Y');
        $month = date('m');
        $day = date('d');
        
        // Get count of invoices for today that are not completed
        $count = Pembelian::whereDate('created_at', today())
            ->where('status', '!=', 'Completed')
            ->count() + 1;
        
        // Format: INV-YYYYMMDD-XXXX
        return sprintf("%s-%s%s%s-%04d", $prefix, $year, $month, $day, $count);
    }

    /**
     * Get available invoices (not completed)
     * @return Collection
     */
    public function getAvailableInvoices()
    {
        return Pembelian::where('status', '!=', 'Completed')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function show($id)
    {
        $pembelian = Pembelian::with('vendor')->findOrFail($id);
        return response()->json($pembelian);
    }

    public function edit($id)
    {
        $pembelian = Pembelian::findOrFail($id);
        if (strtolower($pembelian->status) !== 'pending') {
            return redirect()->route('pembelian.index')->with('error', 'Tidak dapat mengedit pembelian yang sudah selesai atau dibatalkan.');
        }
        $vendors = Vendor::all();
        return view('layouts.pembelian.edit', compact('pembelian', 'vendors'));
    }

    public function update(Request $request, $id)
    {
        $pembelian = Pembelian::findOrFail($id);
        if (strtolower($pembelian->status) !== 'pending') {
            return redirect()->route('pembelian.index')->with('error', 'Tidak dapat memperbarui pembelian yang sudah selesai atau dibatalkan.');
        }

        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'status' => 'required|in:Pending,Completed,Cancelled',
            'metode_pembayaran' => 'required',
            'metode_pengiriman' => 'required',
            'tanggal_pembelian' => 'required|date',
            'tanggal_pengiriman' => 'nullable|date',
            'tanggal_penerimaan' => 'nullable|date',
        ]);

        DB::beginTransaction();

        try {
            $pembelian->update($request->all());

            // Update status of all related PembelianDetail
            if ($request->status == 'Completed' || $request->status == 'Cancelled') {
                PembelianDetail::where('pembelian_id', $id)->update(['status' => $request->status]);
            }

            DB::commit();

            return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateTanggal(Request $request, $id)
    {
        $pembelian = Pembelian::findOrFail($id);
        $field = $request->input('type') === 'pengiriman' ? 'tanggal_pengiriman' : 'tanggal_penerimaan';
        $pembelian->$field = now(); // Menggunakan waktu server saat ini

        if ($pembelian->save()) {
            return response()->json([
                'success' => true,
                'newDate' => $pembelian->$field->format('Y-m-d')
            ]);
        } else {
            return response()->json(['success' => false], 500);
        }
    }

    public function destroy($id)
    {
        $pembelian = Pembelian::findOrFail($id);
        if (strtolower($pembelian->status) !== 'pending') {
            return redirect()->route('pembelian.index')->with('error', 'Tidak dapat menghapus pembelian yang sudah selesai atau dibatalkan.');
        }

        DB::beginTransaction();
        try {
            // Delete all related PembelianDetail records
            PembelianDetail::where('pembelian_id', $id)->delete();

            // Delete the Pembelian record
            $pembelian->delete();

            DB::commit();
            return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('pembelian.index')->with('error', 'Terjadi kesalahan saat menghapus: ' . $e->getMessage());
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
