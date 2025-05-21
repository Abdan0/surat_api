<?php
// database/migrations/xxxx_xx_xx_create_notifikasis_table.ph

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('judul');
            $table->text('pesan');
            $table->string('tipe')->nullable(); // disposisi, surat, dll
            $table->unsignedBigInteger('reference_id')->nullable(); // ID disposisi atau surat
            $table->boolean('dibaca')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifikasis');
    }
};
