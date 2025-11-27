<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan_JenisTingkatJabatan extends Model
{
    use HasFactory;

    // Menentukan nama tabel
    protected $table = 'jabatan_jenis_tingkat_jabatans';

    // Kolom yang dapat diisi secara mass-assignment
    protected $fillable = [
        'jenis_tingkat_jabatan_id',                      // ID Pendidikan
        'tingkat_pendidikan_id',    // Foreign Key ke TingkatPendidikan
        'kode_fakultas',            // Kode Fakultas
        'kode_bidang',              // Kode Bidang
        'nama',                     // Nama Pendidikan
        'golongan_maksimal_id',     // Foreign Key ke Golongan
    ];

    // Kolom primary key
    protected $primaryKey = 'jenis_tingkat_jabatan_id';

    // Menentukan tipe data primary key sebagai string
    protected $keyType = 'string';

    /**
     * Relasi dengan TingkatPendidikan
     * (Setiap pendidikan berhubungan dengan satu tingkat pendidikan)
     */
    public function tingkatPendidikan()
    {
        return $this->belongsTo(TingkatPendidikan::class, 'tingkat_pendidikan_id', 'tingkat_pendidikan_id')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
    }

    /**
     * Relasi dengan Golongan
     * (Setiap pendidikan berhubungan dengan satu golongan maksimal)
     */
    public function golonganMaksimal()
    {
        return $this->belongsTo(Golongan::class, 'golongan_maksimal_id', 'golongan_id')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
    }

    /**
     * Menyimpan nama pendidikan sebagai representasi objek.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->nama;
    }
}
