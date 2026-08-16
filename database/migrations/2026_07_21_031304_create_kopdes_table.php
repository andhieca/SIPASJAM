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
        Schema::create('kopdes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->onDelete('cascade');
            $table->string('nama_koperasi');
            $table->string('nomor_badan_hukum')->nullable();
            $table->integer('jumlah_anggota')->default(0);
            $table->decimal('aset', 15, 2)->default(0);
            $table->boolean('status_aktif')->default(true);
            $table->json('additional_data')->nullable()->comment('Flexible EAV/JSON data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kopdes');
    }
};
