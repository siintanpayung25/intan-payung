<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePelatihansTable extends Migration
{
    public function up()
    {
        Schema::create('pelatihans', function (Blueprint $table) {
            // Kolom-kolom utama
            $table->string('nip', 18); // NIP Pegawai (Relasi ke tabel pegawais)
            $table->string('pelatihan_id', 36)->primary(); // ID Pelatihan (gabungan kode)
            $table->string('skala_id', 1); // Foreign Key ke PelatihanSkala
            $table->string('bentuk_id', 1); // Foreign Key ke PelatihanBentuk
            $table->string('kategori_id', 2); // Foreign Key ke PelatihanKategori
            $table->string('jenis_id', 2); // Foreign Key ke PelatihanJenis
            $table->string('nama', 50); // Nama Pelatihan (maksimal 50 karakter)
            $table->date('tgl_mulai')->nullable(); // Tanggal Mulai (nullable)
            $table->date('tgl_selesai')->nullable(); // Tanggal Selesai (nullable)
            $table->time('durasi'); // Durasi dalam waktu (tipe data time)
            $table->integer('jumlah_peserta')->nullable(); // Jumlah Peserta (nullable)
            $table->integer('rangking')->nullable(); // Rangking (nullable)
            $table->string('link_bukti_dukung', 200); // Link Bukti Dukung (maksimal 200 karakter)
            $table->string('instansi_id', 3); // Foreign Key ke Instansi
            $table->timestamps(); // Timestamps untuk created_at dan updated_at

            // Menambahkan index pada kolom id untuk query lebih cepat
            $table->index('pelatihan_id'); // Menambahkan index pada kolom 'pelatihan_id'

            // Menambahkan relasi foreign key secara manual
            // Menambahkan relasi ke tabel pegawais dengan kolom nip
            $table->foreign('nip')
                ->references('nip')->on('pegawais')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('skala_id')
                ->references('skala_id')->on('pelatihan_skalas')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('bentuk_id')
                ->references('bentuk_id')->on('pelatihan_bentuks')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('kategori_id')
                ->references('kategori_id')->on('pelatihan_kategoris')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('jenis_id')
                ->references('jenis_id')->on('pelatihan_jeniss')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('instansi_id')
                ->references('instansi_id')->on('instansis')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pelatihans');
    }
}
