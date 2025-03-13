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
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang', 50)->unique();
            $table->unsignedBigInteger('kategori_id');
            $table->string('nama_barang', 100);
            $table->enum('satuan', ['pcs', 'pack', 'dus', 'karung', 'liter', 'gram']);
            $table->decimal('harga_beli', 10, 2);
            $table->decimal('harga_jual', 10, 2);
            $table->unsignedInteger('stok')->default(0);
            $table->unsignedInteger('stok_minimal')->default(1);
            $table->string('gambar')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('kategori_id')->references('id')->on('kategori')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};