<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelatihan_rekap_capaian', function (Blueprint $table) {
            // Primary Key Gabungan (NIP 18 digit + Tahun 4 digit)
            $table->string('pelatihan_rekap_capaian_id', 23)->primary();

            // Kolom Relasional dan Filtering (TANPA FOREIGN KEY CONSTRAINT)
            $table->string('nip', 18);
            $table->year('tahun');
            $table->string('unor_id', 10)->nullable();

            // Ganti kabupaten_id dengan satker_id dan relasi ke tabel satkers
            $table->string('satker_id', 10)->nullable();

            // Data Agregat
            $table->decimal('target_jam_setahun', 4, 2);
            $table->decimal('durasi_tna', 5, 2)->default(0.00);
            $table->decimal('durasi_non_tna', 5, 2)->default(0.00);
            $table->decimal('durasi_total', 5, 2)->default(0.00);
            $table->decimal('persentase_capaian', 5, 2)->default(0.00);

            $table->timestamps();

            // Index untuk kecepatan Lookup dan Filtering
            $table->index(['nip', 'tahun']); // Index gabungan untuk pencarian cepat
            $table->index(['unor_id']);
            $table->index(['satker_id']); // Menambahkan index untuk satker_id

            // Constraints unik (menjaga data tidak duplikat per NIP per Tahun)
            $table->unique(['nip', 'tahun']);

            // Menambahkan foreign key constraint untuk satker_id
            $table->foreign('satker_id')
                ->references('id')
                ->on('satkers')
                ->onDelete('cascade')  // Cascade delete
                ->onUpdate('cascade'); // Cascade update
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelatihan_rekap_capaian');
    }
};
