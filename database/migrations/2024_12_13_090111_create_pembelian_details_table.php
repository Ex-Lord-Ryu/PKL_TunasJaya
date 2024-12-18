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
        Schema::create('pembelian_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pembelian_id');
            $table->unsignedBigInteger('motor_id')->nullable();
            $table->string('warna_id')->nullable();
            $table->integer('jumlah_motor');
            $table->integer('harga_motor');
            $table->decimal('total_harga', 15, 2);
            $table->enum('status', ['Pending', 'Completed', 'Cancelled']);
            $table->timestamps();

            $table->foreign('pembelian_id')->references('id')->on('pembelians')->onDelete('cascade');
            $table->foreign('motor_id')->references('id')->on('master_motors')->onDelete('set null');
            $table->foreign('warna_id')->references('id_warna')->on('master_warnas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_details');
    }
};
