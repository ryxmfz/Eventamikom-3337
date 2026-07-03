<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Proteksi aman: buat tabel hanya jika belum tersedia di database
        if (!Schema::hasTable('partners')) {
            Schema::create('partners', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // Nama Partner/Perusahaan
                $table->string('logo_url'); // URL Logo Partner
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
