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
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('nama');
            $table->string('nim')->unique();
            $table->string('email')->unique();
            $table->string('whatsapp')->unique()->nullable();
            $table->integer('jurusan')->nullable();
            $table->integer('prodi')->nullable(); 
            $table->integer('ormawa')->nullable(); 
            $table->string('foto_profil')->nullable(); // Menambahkan kolom foto profil
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
