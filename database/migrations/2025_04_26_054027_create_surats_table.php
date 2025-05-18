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
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique();
            $table->enum('tipe', ['masuk', 'keluar']);
            $table->enum('kategori', ['internal', 'eksternal']);
            $table->string('asal_surat');
            $table->string('tujuan_surat')->nullable(); /* Hanya untuk surat keluar */
            $table->date('tanggal_surat');
            $table->string('perihal');
            $table->text('isi');
            $table->string('file')->nullable(); /* Upload PDF */
            $table->enum('status', ['draft', 'dikirim', 'diverifikasi', 'ditolak'])->default('draft');
            $table->timestamps();

            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};
