<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detailkegiatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usulankegiatan_id')->constrained('usulankegiatans');
            $table->text('latarbelakang_kegiatan')->nullable();;
            $table->text('dasarhukum_kegiatan')->nullable();;
            $table->text('uraian_kegiatan')->nullable();
            $table->text('maksud_kegiatan')->nullable();;
            $table->text('tujuan_kegiatan')->nullable();
            $table->text('hasil_kegiatan')->nullable();;
            $table->string('narasumber_kegiatan')->nullable();
            $table->string('peserta_kegiatan')->nullable();
            $table->decimal('alokasianggaran_kegiatan', 12, 2)->nullable();
            $table->foreignId('metodepelatihan_id')->constrained('ref_metodepelatihans');
            $table->string('dokumenpendukung_kegiatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detailkegiatans');
    }
};
