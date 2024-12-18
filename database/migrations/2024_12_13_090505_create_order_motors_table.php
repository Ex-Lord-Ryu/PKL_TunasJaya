<?php

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
        Schema::create('order_motors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('motor_id');
            $table->string('warna_id');
            $table->integer('jumlah_motor');
            $table->enum('pengiriman', ['Truck', 'Pick-Up', 'Mobil-Box', 'Ambil Ditempat']);
            $table->enum('pembayaran', ['Cash', 'Transfer', 'Kredit']);
            $table->string('no_rangka')->nullable();
            $table->string('no_mesin')->nullable();
            $table->decimal('harga_jual', 15, 2)->nullable();
            $table->decimal('dp', 15, 2)->nullable();
            $table->integer('tenor')->nullable();
            $table->decimal('angsuran', 15, 2)->nullable();
            $table->string('nama_pembeli')->nullable();
            $table->string('alamat_pembeli')->nullable();
            $table->string('no_hp_pembeli')->nullable();
            $table->enum('status', ['active', 'cancelled', 'completed'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_motors');
    }
};
