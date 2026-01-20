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
        Schema::create('violations', function (Blueprint $table) {
            $table->id();
            $table->string('nis');
            $table->string('nama_siswa');
            $table->string('kelas');
            $table->date('tgl_pelanggaran');
            $table->enum('kategori_pelanggaran', ['Ringan', 'Sedang', 'Berat'])->default('Ringan');
            $table->integer('point_pelanggaran');
            $table->integer('total_point')->nullable();
            $table->string('deskripsi_pelanggaran');
            $table->string('slug');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('violations');
    }
};
