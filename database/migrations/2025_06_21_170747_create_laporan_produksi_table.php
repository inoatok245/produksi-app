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
    Schema::create('laporan_produksi', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->date('tanggal');
        $table->string('jenis_pekerjaan');
        $table->integer('jumlah');
        $table->text('keterangan')->nullable();
        $table->string('foto')->nullable(); // path ke file
        $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
        $table->text('alasan_penolakan')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_produksi');
    }
};
