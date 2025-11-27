<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan_JenjangJabatan  extends Model
{
    use HasFactory;

    // Menentukan nama tabel
    protected $table = 'jabatan_jenjang_jabatans';

    // Kolom yang dapat diisi secara mass-assignment
    protected $fillable = [
        'jenjang_jafung_id',               // ID Jenjang Jabatan Fungsional
        'tingkat_jabatan_id',                      // ID Tingkat Jabatan (foreign key)
        'kode_jenjang_jabatan_fungsional',         // Kode Jenjang Jabatan Fungsional
        'nama',                                    // Nama Jenjang Jabatan Fungsional
    ];

    // Kolom primary key
    protected $primaryKey = 'jenjang_jafung_id';

    // Mengatur tipe data primary key sebagai string
    protected $keyType = 'string';

    /**
     * Relasi dengan TingkatJabatan
     * (Setiap jenjang jabatan fungsional memiliki satu tingkat jabatan)
     */
    public function tingkatJabatan()
    {
        return $this->belongsTo(TingkatJabatan::class, 'tingkat_jabatan_id', 'tingkat_jabatan_id')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
    }

    /**
     * Menyimpan nama jenjang jabatan fungsional
     * sebagai representasi objek.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->nama;
    }
}
