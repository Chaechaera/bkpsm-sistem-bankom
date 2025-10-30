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
        Schema::create('usulankegiatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subunitkerja_id')->constrained('ref_subunitkerjas');
            $table->foreignId('identitassurat_id')->nullable()->constrained('identitassurats');
            $table->string('nama_kegiatan');
            $table->string('lokasi_kegiatan')->nullable();
            $table->foreignId('carapelatihan_id')->constrained('ref_carapelatihans');
            $table->date('tanggal_pelaksanaan')->nullable();
            $table->enum('statususulan_kegiatan', ['draft', 'pending', 'approved', 'rejected', 'in_progress', 'completed', 'finish'])->default('draft');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usulankegiatans');
    }
};
