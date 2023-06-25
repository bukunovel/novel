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
        Schema::create('buku_novel', function (Blueprint $table) {
            $table->id();
            $table->string('judul_novel')->nullable();
            $table->string('pengarang_novel')->nullable();
            $table->string('penerbit_novel')->nullable();
            $table->string('novel_terbit')->nullable();
            $table->integer('jumlah_view_novel')->nullable();
            $table->string('image_novel')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_novel');
    }
};
