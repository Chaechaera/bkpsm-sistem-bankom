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
        Schema::create('ref_carapelatihans', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('cara_pelatihan');
            $table->string('jumlah_jp');
            $table->longText('pengertian');
            $table->string('pemberlakuan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_carapelatihans');
    }
};
