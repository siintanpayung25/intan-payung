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
        Schema::create('pelatihan_jam_pelajarans', function (Blueprint $table) {
            $table->id(); // Kolom id sebagai primary key

            // Kolom untuk waktu asynchronous dan synchronous
            $table->decimal('asynchronous', 4, 2); // Tipe desimal untuk jam asynchronous
            $table->decimal('synchronous', 4, 2);   // Tipe desimal untuk jam synchronous
            $table->time('jam_pelajaran');   // Tipe data time untuk jam_pelajaran

            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelatihan_jam_pelajarans');
    }
};
