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
        Schema::create('umkms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->onDelete('cascade');
            $table->string('nama_umkm');
            $table->string('kategori')->default('Kuliner'); // Kuliner, Kreatif, Fashion
            $table->string('nama_pemilik');
            $table->string('koordinat_lokasi')->nullable();
            $table->string('nomor_nib')->nullable();
            $table->string('izin_halal')->nullable();
            $table->string('bpom')->nullable();
            $table->json('foto_produk')->nullable();
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('link_marketplace')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('umkms');
    }
};
