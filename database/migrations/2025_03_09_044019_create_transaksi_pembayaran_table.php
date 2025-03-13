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
        Schema::create('transaksi_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penjualan_id');
            $table->string('duitku_reference')->unique();
            $table->string('payment_method')->nullable();
            $table->double('amount');
            $table->enum('status', ['waiting', 'paid', 'expired', 'failed', 'refund'])->default('waiting');
            $table->timestamps();

            $table->foreign('penjualan_id')->references('id')->on('penjualan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_pembayaran');
    }
};