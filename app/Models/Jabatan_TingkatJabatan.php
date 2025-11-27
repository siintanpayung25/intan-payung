<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan_TingkatJabatan extends Model
{
    use HasFactory;

    // Menentukan nama tabel
    protected $table = 'jabatan_tingkat_jabatans';

    // Kolom yang dapat diisi secara mass-assignment
    protected $fillable = [
        'tingkat_jabatan_id',         // ID Tingkat Jabatan
        'jenis_jabatan_id',           // ID Jenis Jabatan (foreign key)
        'jenis_tingkat_jabatan_id',   // ID Jenis Tingkat Jabatan (foreign key)
        'kode_tingkat_jabatan',       // Kode Tingkat Jabatan
        'nama',                       // Nama Tingkat Jabatan
        'eselon_id',                  // ID Eselon (foreign key)
    ];

    // Kolom primary key
    protected $primaryKey = 'tingkat_jabatan_id';

    // Mengatur tipe data primary key sebagai string
    protected $keyType = 'string';

    /**
     * Relasi dengan JenisJabatan
     * (Setiap tingkat jabatan memiliki satu jenis jabatan)
     */
    public function jenisJabatan()
    {
        return $this->belongsTo(JabatanJenisJabatan::class, 'jenis_jabatan_id', 'jenis_jabatan_id')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
    }

    /**
     * Relasi dengan JenisTingkatJabatan
     * (Setiap tingkat jabatan memiliki satu jenis tingkat jabatan)
     */
    public function jenisTingkatJabatan()
    {
        return $this->belongsTo(JenisTingkatJabatan::class, 'jenis_tingkat_jabatan_id', 'jenis_tingkat_jabatan_id')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
    }

    /**
     * Relasi dengan Eselon
     * (Setiap tingkat jabatan memiliki satu eselon)
     */
    public function eselon()
    {
        return $this->belongsTo(Eselon::class, 'eselon_id', 'eselon_id')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
    }

    /**
     * Relasi dengan Jabatan
     * (Setiap tingkat jabatan dapat memiliki banyak jabatan)
     */
    public function jabatans()
    {
        return $this->hasMany(Jabatan::class, 'tingkat_jabatan_id', 'tingkat_jabatan_id')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
    }

    /**
     * Menyimpan nama tingkat jabatan
     * sebagai representasi objek.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->nama;
    }
}
