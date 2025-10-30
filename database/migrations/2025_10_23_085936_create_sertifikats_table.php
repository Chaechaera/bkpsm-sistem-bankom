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
        Schema::create('sertifikats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subunitkerja_id')->constrained('ref_subunitkerjas');
            $table->string('nomor_sertifikat')->nullable();
            $table->date('tanggalkeluar_sertifikat')->nullable();
            $table->string('file_path')->nullable(); // path ke PDF sertifikat
            $table->timestamps();

            $table->foreignId('balasan_laporan_kegiatan_id')->nullable()->constrained('balasan_laporan_kegiatans');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sertifikats');
    }
};
