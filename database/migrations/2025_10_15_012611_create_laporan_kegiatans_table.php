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
        Schema::create('laporan_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usulankegiatan_id')->constrained()->onDelete('cascade');
            $table->foreignId('unitkerja_id')->nullable()->constrained('ref_unitkerjas');
            $table->foreignId('subunitkerja_id')->nullable()->constrained('ref_subunitkerjas');
            $table->string('dokumenpendukung_kegiatan')->nullable();
            $table->string('dokumenPK_kegiatan')->nullable();
            $table->enum('statuslaporan_kegiatan', ['pending', 'accepted', 'rejected']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_kegiatans');
    }
};
