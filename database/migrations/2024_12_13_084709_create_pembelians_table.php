<?php

use App\Models\Vendor;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pembelians', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->string('invoice_pembelian')->unique(); 
            $table->enum('status', ['Pending', 'Completed', 'Cancelled']);
            $table->enum('metode_pembayaran', ['Cash', 'Transfer' , 'Kredit']);
            $table->enum('metode_pengiriman', ['Kapal Kargo', 'Truck', 'Pick-Up', 'Mobil-Box' ]);
            $table->date('tanggal_pembelian')->nullable();
            $table->date('tanggal_pengiriman')->nullable();
            $table->date('tanggal_penerimaan')->nullable();
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('vendors');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelians');
    }
};
