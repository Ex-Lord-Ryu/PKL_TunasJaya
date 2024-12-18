<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sold_motors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('motor_id');
            $table->string('warna_id');
            $table->decimal('harga', 15, 2);
            $table->decimal('total_harga', 15, 2);
            $table->string('no_rangka');
            $table->string('no_mesin');
            $table->date('tanggal_penjualan');
            $table->string('nama_pembeli');
            $table->string('alamat_pembeli');
            $table->string('no_hp_pembeli');
            $table->enum('metode_pembayaran', ['Cash', 'Kredit']);
            $table->decimal('dp', 15, 2)->nullable();
            $table->integer('tenor')->nullable();
            $table->decimal('angsuran', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sold_motors');
    }
};
