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
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table->string('no_faktur', 50)->unique();
            $table->datetime('tgl_faktur');
            $table->double('total_bayar');
            $table->unsignedBigInteger('pelanggan_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->string('duitku_reference')->nullable()->unique();
            $table->enum('metode_pembayaran', ['cash', 'duitku'])->default('cash');
            $table->enum('status_pembayaran', ['pending', 'lunas', 'batal'])->default('pending');
            $table->string('duitku_payment_url')->nullable();
            $table->timestamps();

            $table->foreign('pelanggan_id')->references('id')->on('pelanggan')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};