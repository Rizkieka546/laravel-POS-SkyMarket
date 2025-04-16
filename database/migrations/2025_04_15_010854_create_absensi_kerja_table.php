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
        Schema::create('absensi_kerja', function (Blueprint $table) {
            $table->id();
            $table->string('nama_karyawan');
            $table->unsignedBigInteger('user_id');
            $table->date('tanggal_masuk');
            $table->time('waktu_masuk')->default('00:00:00');
            $table->enum('status', ['masuk', 'sakit', 'cuti']);
            $table->time('waktu_kerja_selesai')->default('00:00:00');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_kerja');
    }
};