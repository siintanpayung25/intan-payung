<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePegawaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pegawais', function (Blueprint $table) {
            $table->string('nip', 18)->primary();
            $table->string('nip_bps', 9)->unique();
            $table->string('nama', 100);
            $table->string('unor_id', 5)->nullable();
            $table->string('provinsi_id', 4)->nullable();
            $table->string('kabupaten_id', 6)->nullable();
            $table->string('jenis_jabatan_id', 2)->nullable();
            $table->string('jenis_tingkat_jabatan_id', 4)->nullable();
            $table->string('tingkat_jabatan_id', 8)->nullable();
            $table->string('jabatan_id', 12)->nullable();
            $table->string('status_fungsional_id', 1)->nullable();
            $table->string('tingkat_pendidikan_id', 2)->nullable();
            $table->string('pendidikan_id', 6)->nullable();
            $table->string('status_ais_id', 1)->nullable();
            $table->date('tmt_cpns')->nullable();
            $table->string('golongan_id', 2)->nullable();
            $table->date('tmt_golongan');
            $table->string('status_pegawai_id', 2)->nullable();
            $table->date('perkiraan_pensiun');
            $table->date('tmt_bmp')->nullable();
            $table->string('nomor_sk_bmp', 27);
            $table->string('agama_id', 1)->nullable();
            $table->string('jenis_kelamin_id', 1)->nullable();
            $table->string('nik', 16);
            $table->string('wilayah_domisili', 12);
            $table->string('provinsi_domisili_id', 4)->nullable();
            $table->string('kabupaten_domisili_id', 6)->nullable();
            $table->string('kecamatan_domisili_id', 9)->nullable();
            $table->string('desa_domisili_id', 12)->nullable();
            $table->string('alamat_domisili', 100);
            $table->string('rt_domisili', 3);
            $table->string('rw_domisili', 3);
            $table->string('kode_pos_domisili', 5);
            $table->string('wilayah_ktp', 12);
            $table->string('provinsi_ktp_id', 4)->nullable();
            $table->string('kabupaten_ktp_id', 6)->nullable();
            $table->string('kecamatan_ktp_id', 9)->nullable();
            $table->string('desa_ktp_id', 12)->nullable();
            $table->string('alamat_ktp', 100);
            $table->string('rt_ktp', 3);
            $table->string('rw_ktp', 3);
            $table->string('kode_pos_ktp', 5);
            $table->string('foto_profile')->default('gambar_profile/default.jpg');
            $table->string('level_user_id'); // Mengubah tipe menjadi string
            $table->timestamps();

            // Foreign Key Constraints with Cascade
            $table->foreign('unor_id')->references('unor_id')->on('unors')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('provinsi_id')->references('provinsi_id')->on('wilayah_provinsis')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('kabupaten_id')->references('kabupaten_id')->on('wilayah_kabupatens')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('jenis_jabatan_id')->references('jenis_jabatan_id')->on('jabatan_jenis_jabatans')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('jenis_tingkat_jabatan_id')->references('jenis_tingkat_jabatan_id')->on('jabatan_jenis_tingkat_jabatans')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('tingkat_jabatan_id')->references('tingkat_jabatan_id')->on('jabatan_tingkat_jabatans')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('jabatan_id')->references('jabatan_id')->on('jabatans')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('status_fungsional_id')->references('status_fungsional_id')->on('jabatan_status_fungsionals')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('tingkat_pendidikan_id')->references('tingkat_pendidikan_id')->on('pendidikan_tingkat_pendidikans')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('pendidikan_id')->references('pendidikan_id')->on('pendidikans')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('status_ais_id')->references('status_ais_id')->on('pendidikan_status_ais')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('golongan_id')->references('golongan_id')->on('pangkat_golongans')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('status_pegawai_id')->references('status_pegawai_id')->on('status_pegawais')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('agama_id')->references('agama_id')->on('agamas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('jenis_kelamin_id')->references('jenis_kelamin_id')->on('jenis_kelamins')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('provinsi_domisili_id')->references('provinsi_id')->on('wilayah_provinsis')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('kabupaten_domisili_id')->references('kabupaten_id')->on('wilayah_kabupatens')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('kecamatan_domisili_id')->references('kecamatan_id')->on('wilayah_kecamatans')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('desa_domisili_id')->references('desa_id')->on('wilayah_desas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('provinsi_ktp_id')->references('provinsi_id')->on('wilayah_provinsis')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('kabupaten_ktp_id')->references('kabupaten_id')->on('wilayah_kabupatens')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('kecamatan_ktp_id')->references('kecamatan_id')->on('wilayah_kecamatans')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('desa_ktp_id')->references('desa_id')->on('wilayah_desas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('level_user_id')->references('level_user_id')->on('level_users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pegawais');
    }
}
