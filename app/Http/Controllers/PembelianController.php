<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Vendor;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use App\Models\PembelianDetail;
use App\Http\Controllers\StockController;
use App\Http\Requests\StorePembelianRequest;

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

    public function store(StorePembelianRequest $request)
    {
        DB::beginTransaction();
        
        try {
            Log::info('Attempting to store pembelian with data:', $request->validated());
            
            // Generate invoice number
            $invoice = $this->generateUniqueInvoice();
            Log::info('Generated invoice:', ['invoice' => $invoice]);

            // Create new pembelian using validated data
            $pembelian = Pembelian::create([
                'vendor_id' => $request->vendor_id,
                'invoice_pembelian' => $invoice,
                'metode_pembayaran' => $request->metode_pembayaran,
                'metode_pengiriman' => $request->metode_pengiriman,
                'tanggal_pembelian' => now(),
                'status' => 'Pending'
            ]);

            if (!$pembelian) {
                throw new \Exception('Gagal menyimpan data pembelian.');
            }

            Log::info('Pembelian saved successfully', ['pembelian_id' => $pembelian->id]);
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Pembelian berhasil ditambahkan.',
                'redirect' => route('pembelian.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in store pembelian:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
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
        
        // Get the latest invoice number for today
        $latestInvoice = Pembelian::whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->first();
        
        if ($latestInvoice) {
            // Extract the counter from the latest invoice
            $parts = explode('-', $latestInvoice->invoice_pembelian);
            $lastCounter = intval(end($parts));
            $count = $lastCounter + 1;
        } else {
            $count = 1;
        }
        
        // Format: INV-YYYYMMDD-XXXX
        $invoice = sprintf("%s-%s%s%s-%04d", $prefix, $year, $month, $day, $count);
        
        // Check if generated invoice already exists
        while (Pembelian::where('invoice_pembelian', $invoice)->exists()) {
            $count++;
            $invoice = sprintf("%s-%s%s%s-%04d", $prefix, $year, $month, $day, $count);
        }
        
        return $invoice;
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
