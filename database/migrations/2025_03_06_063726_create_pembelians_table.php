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
        Schema::create('pembelian', function (Blueprint $table) {
            $table->id();
            $table->string('kode_masuk', 50)->unique();
            $table->date('tanggal_masuk');
            $table->double('total');
            $table->enum('status_pembelian', ['pending', 'selesai', 'dibatalkan'])->default('pending');
            $table->unsignedBigInteger('pemasok_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('pemasok_id')->references('id')->on('pemasok')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian');
    }
};
