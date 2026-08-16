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
        Schema::create('sppgs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sppg');
            $table->string('nama_yayasan')->nullable();
            $table->string('ketua_sppg')->nullable();
            $table->text('alamat')->nullable();
            $table->string('koordinat_lokasi')->nullable();
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->integer('jumlah_penerima_manfaat')->default(0);
            $table->json('additional_data')->nullable()->comment('Flexible EAV/JSON data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sppgs');
    }
};
