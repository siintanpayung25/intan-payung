<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    // Menentukan nama tabel
    protected $table = 'jabatans';

    // Kolom yang dapat diisi secara mass-assignment
    protected $fillable = [
        'jabatan_id',               // ID Jabatan
        'jenis_jabatan_id',         // ID Jenis Jabatan (foreign key)
        'jenis_tingkat_jabatan_id', // ID Jenis Tingkat Jabatan (foreign key)
        'tingkat_jabatan_id',       // ID Tingkat Jabatan (foreign key)
        'kode_jabatan',             // Kode Jabatan
        'nama',                     // Nama Jabatan
        'bup',                      // Batas Usia Pensiun (BUP)
        'golongan_maksimal_id',     // ID Golongan Maksimal (foreign key)
        'kd_jab_simpeg',            // Kode Jabatan Simpeg (Optional)
    ];

    // Kolom primary key
    protected $primaryKey = 'jabatan_id';

    // Mengatur tipe data primary key sebagai string
    protected $keyType = 'string';

    /**
     * Relasi dengan JenisJabatan
     * (Setiap jabatan memiliki satu jenis jabatan)
     */
    public function jenisJabatan()
    {
        return $this->belongsTo(JabatanJenisJabatan::class, 'jenis_jabatan_id', 'jenis_jabatan_id')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
    }

    /**
     * Relasi dengan JenisTingkatJabatan
     * (Setiap jabatan memiliki satu jenis tingkat jabatan)
     */
    public function jenisTingkatJabatan()
    {
        return $this->belongsTo(JenisTingkatJabatan::class, 'jenis_tingkat_jabatan_id', 'jenis_tingkat_jabatan_id')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
    }

    /**
     * Relasi dengan TingkatJabatan
     * (Setiap jabatan memiliki satu tingkat jabatan)
     */
    public function tingkatJabatan()
    {
        return $this->belongsTo(TingkatJabatan::class, 'tingkat_jabatan_id', 'tingkat_jabatan_id')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
    }

    /**
     * Relasi dengan Golongan
     * (Setiap jabatan memiliki satu golongan maksimal)
     */
    public function golonganMaksimal()
    {
        return $this->belongsTo(Golongan::class, 'golongan_maksimal_id', 'golongan_id')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
    }

    /**
     * Menyimpan nama jabatan
     * sebagai representasi objek.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->nama;
    }
}
