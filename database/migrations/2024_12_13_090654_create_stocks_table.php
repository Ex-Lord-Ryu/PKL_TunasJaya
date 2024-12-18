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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembelian_detail_id')->constrained('pembelian_details');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->enum('status', ['available', 'ordered', 'sold'])->default('available');
            $table->foreignId('motor_id')->constrained('master_motors');
            $table->string('warna_id');
            $table->string('no_rangka')->nullable()->unique();
            $table->string('no_mesin')->nullable()->unique();
            $table->decimal('harga_beli', 15, 2);
            $table->decimal('harga_jual', 15, 2)->nullable();
            $table->timestamps();

            $table->foreign('order_id')
                ->references('id')
                ->on('order_motors')
                ->onDelete('set null');

            $table->foreign('warna_id')
                ->references('id_warna')
                ->on('master_warnas')
                ->onDelete('cascade');

            $table->unique(['no_rangka', 'no_mesin'], 'unique_rangka_mesin')
                ->where([
                    ['no_rangka', 'IS NOT NULL'],
                    ['no_mesin', 'IS NOT NULL']
                ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
