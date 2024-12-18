<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Pembelian;
use App\Models\MasterMotor;
use App\Models\MasterWarna;
use Illuminate\Http\Request;
use App\Models\PembelianDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::with(['pembelian_detail.pembelian', 'pembelian_detail.master_motor', 'master_warna', 'order'])
            ->where(function($query) {
                $query->whereNull('order_id')
                    ->orWhereHas('order', function($q) {
                        $q->where('status', 'cancelled');
                    });
            })
            ->get()
            ->groupBy(function($stock) {
                return $stock->pembelian_detail->master_motor->nama_motor;
            })
            ->map(function($motorStocks) {
                return $motorStocks->groupBy('pembelian_detail.pembelian.invoice_pembelian');
            });

        return view('layouts.stock.index', compact('stocks'));
    }

    public function create()
    {
        $pembelianDetails = PembelianDetail::whereHas('pembelian', function ($query) {
            $query->where('status', 'completed');
        })->with(['pembelian', 'master_motor'])->get();

        return view('layouts.stock.create', compact('pembelianDetails'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pembelian_detail_id' => 'required|exists:pembelian_details,id',
            'no_rangka' => 'required|string|max:255|unique:stocks',
            'no_mesin' => 'required|string|max:255|unique:stocks',
        ]);

        $pembelianDetail = PembelianDetail::findOrFail($request->pembelian_detail_id);

        $stock = new Stock();
        $stock->pembelian_detail_id = $pembelianDetail->id;
        $stock->master_motor_id = $pembelianDetail->master_motor_id;
        $stock->no_rangka = $request->no_rangka;
        $stock->no_mesin = $request->no_mesin;
        $stock->harga_beli = $pembelianDetail->harga_beli;
        $stock->harga_jual = $pembelianDetail->harga_jual;
        $stock->save();

        return redirect()->route('stock.index')->with('message', 'Stock berhasil ditambahkan.');
    }

    public function show($id)
    {
        $stock = Stock::findOrFail($id);
        $pembelian = $stock->pembelian_detail->pembelian;
        $relatedStocks = Stock::where('pembelian_detail_id', $stock->pembelian_detail_id)
            ->where(function($query) {
                $query->whereNull('order_id')
                    ->orWhereHas('order', function($q) {
                        $q->where('status', 'cancelled');
                    });
            })
            ->with(['master_motor', 'master_warna'])
            ->get();

        return view('layouts.stock.show', compact('stock', 'pembelian', 'relatedStocks'));
    }

    public function TranferToStock($invoice_pembelian)
    {
        try {
            DB::beginTransaction();

            $pembelian = Pembelian::where('invoice_pembelian', $invoice_pembelian)
                ->where('status', 'Completed')
                ->firstOrFail();

            $pembelianDetails = $pembelian->pembelianDetails;

            if ($pembelianDetails->isEmpty()) {
                throw new \Exception("Pembelian detail not found for invoice pembelian: {$invoice_pembelian}");
            }

            $stockAdded = 0;

            foreach ($pembelianDetails as $detail) {
                $existingStockCount = Stock::where('pembelian_detail_id', $detail->id)->count();
                $remainingStock = $detail->jumlah_motor - $existingStockCount;

                if ($detail->motor_id && $remainingStock > 0) {
                    for ($i = 0; $i < $remainingStock; $i++) {
                        $stock = new Stock();
                        $stock->pembelian_detail_id = $detail->id;
                        $stock->motor_id = $detail->motor_id;
                        $stock->warna_id = $detail->warna_id;
                        $stock->harga_beli = $detail->harga_motor;
                        $stock->harga_jual = null;

                        Log::info("Attempting to save stock: " . json_encode($stock->toArray()));
                        $stock->save();
                        Log::info("Stock saved successfully with ID: " . $stock->id);

                        $stockAdded++;
                    }
                }
            }

            DB::commit();
            
            return [
                'success' => true,
                'message' => "Stock berhasil ditambahkan untuk Invoice Pembelian: {$invoice_pembelian}",
                'stockAdded' => $stockAdded
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error adding stock for Invoice Pembelian {$invoice_pembelian}: " . $e->getMessage());
            Log::error($e->getTraceAsString());

            return [
                'success' => false,
                'message' => "Gagal menambahkan stock: " . $e->getMessage()
            ];
        }
    }

    public function inputNomor($invoice)
    {
        $stocks = Stock::whereHas('pembelian_detail.pembelian', function ($query) use ($invoice) {
            $query->where('invoice_pembelian', $invoice);
        })
        ->where(function($query) {
            $query->whereNull('order_id')
                ->orWhereHas('order', function($q) {
                    $q->where('status', 'cancelled');
                });
        })
        ->with(['pembelian_detail.master_motor', 'master_warna'])
        ->distinct()
        ->get();

        Log::info("Stocks for invoice {$invoice}:", [
            'count' => $stocks->count(),
            'stocks' => $stocks->map(function($stock) {
                return [
                    'id' => $stock->id,
                    'pembelian_detail_id' => $stock->pembelian_detail_id,
                    'no_rangka' => $stock->no_rangka,
                    'no_mesin' => $stock->no_mesin
                ];
            })
        ]);

        return view('layouts.stock.input_nomor', compact('stocks', 'invoice'));
    }

    public function saveNomor(Request $request)
    {
        try {
            $filteredStocks = collect($request->stocks)->filter(function ($stock) {
                return !empty($stock['no_rangka']) && !empty($stock['no_mesin']);
            })->values()->all();

            $request->merge(['stocks' => $filteredStocks]);

            $request->validate([
                'stocks' => 'required|array',
                'stocks.*.id' => 'required|exists:stocks,id',
                'stocks.*.no_rangka' => 'required|string|unique:stocks,no_rangka',
                'stocks.*.no_mesin' => 'required|string|unique:stocks,no_mesin',
                'stocks.*.custom_awalan_rangka' => 'nullable|string',
                'stocks.*.custom_awalan_mesin' => 'nullable|string',
            ]);

            DB::beginTransaction();

            foreach ($request->stocks as $stockData) {
                $stock = Stock::findOrFail($stockData['id']);
                $masterMotor = $stock->pembelian_detail->master_motor;

                $awalanRangka = $stockData['custom_awalan_rangka'] ?? $masterMotor->nomor_rangka;
                $awalanMesin = $stockData['custom_awalan_mesin'] ?? $masterMotor->nomor_mesin;

                $maxLength = 15;

                $noRangka = substr($awalanRangka . $stockData['no_rangka'], 0, $maxLength);
                $noMesin = substr($awalanMesin . $stockData['no_mesin'], 0, $maxLength);

                $stock->update([
                    'no_rangka' => $noRangka,
                    'no_mesin' => $noMesin,
                ]);
            }

            DB::commit();
            return redirect()->route('stock.index')->with('message', 'Nomor rangka dan mesin berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in saveNomor: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data.'])->withInput();
        }
    }

    public function editPricing($id)
    {
        $stock = Stock::findOrFail($id);
        
        // Only allow editing pricing for available stocks or cancelled orders
        if ($stock->order_id && $stock->order->status !== 'cancelled') {
            return redirect()->route('stock.index')
                ->with('error', 'Tidak dapat mengubah harga untuk motor yang sudah dalam pesanan aktif.');
        }
        
        $colors = Stock::where('motor_id', $stock->motor_id)
            ->where(function($query) {
                $query->whereNull('order_id')
                    ->orWhereHas('order', function($q) {
                        $q->where('status', 'cancelled');
                    });
            })
            ->join('master_warnas', 'stocks.warna_id', '=', 'master_warnas.id_warna')
            ->select('master_warnas.id_warna', 'master_warnas.nama_warna')
            ->distinct()
            ->pluck('nama_warna', 'id_warna');

        return view('layouts.stock.edit-pricing', compact('stock', 'colors'));
    }

    public function updatePricing(Request $request, $id)
    {
        $stock = Stock::findOrFail($id);

        // Check if stock is in active order
        if ($stock->order_id && $stock->order->status !== 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat mengubah harga untuk motor yang sudah dalam pesanan aktif.'
            ], 403);
        }

        $request->validate([
            'harga_jual' => 'required|numeric|min:0',
            'apply_to' => 'required|in:single,all,color',
            'warna_id' => 'required_if:apply_to,color'
        ]);

        $harga_jual = $request->harga_jual;

        switch ($request->apply_to) {
            case 'single':
                $stock->harga_jual = $harga_jual;
                $stock->save();
                break;
            case 'all':
                Stock::where('motor_id', $stock->motor_id)
                    ->where(function($query) {
                        $query->whereNull('order_id')
                            ->orWhereHas('order', function($q) {
                                $q->where('status', 'cancelled');
                            });
                    })
                    ->update(['harga_jual' => $harga_jual]);
                break;
            case 'color':
                Stock::where('motor_id', $stock->motor_id)
                    ->where('warna_id', $request->warna_id)
                    ->where(function($query) {
                        $query->whereNull('order_id')
                            ->orWhereHas('order', function($q) {
                                $q->where('status', 'cancelled');
                            });
                    })
                    ->update(['harga_jual' => $harga_jual]);
                break;
        }

        return response()->json(['message' => 'Harga jual berhasil diupdate.']);
    }
}
