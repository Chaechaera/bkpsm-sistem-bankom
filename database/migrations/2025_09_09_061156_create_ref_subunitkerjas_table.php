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
        Schema::create('ref_subunitkerjas', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->unsignedBigInteger('unitkerja_id')->index('ref_subunitkerjas_unitkerja_id_foreign');
            $table->string('sub_unitkerja');
            $table->string('singkatan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_subunitkerjas');
    }
};
