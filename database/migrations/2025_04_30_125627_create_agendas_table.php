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
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_agenda')->unique();
            $table->foreignId('surat_id')->constrained('surats')->onDelete('cascade');
            $table->date('tanggal_agenda');
            $table->string('pengirim')->nullable();  // untuk surat masuk
            $table->string('penerima')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendas');
    }
};
