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
        Schema::create('balasan_laporan_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->text('detail')->nullable();
            $table->integer('jumlah_jp')->nullable();
            $table->string('file_surat')->nullable(); // path ke PDF balasan
            $table->timestamps();

            $table->foreignId('laporankegiatan_id')->nullable()->constrained('laporan_kegiatans');
            $table->foreignId('identitassurat_id')->nullable()->constrained('identitassurats');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balasan_laporan_kegiatans');
    }
};
