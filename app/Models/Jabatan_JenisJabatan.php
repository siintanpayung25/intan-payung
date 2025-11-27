<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan_JenisJabatan extends Model
{
    use HasFactory;

    // Tentukan nama tabel yang sesuai
    protected $table = 'jabatan_jenis_jabatans';

    // Kolom yang dapat diisi secara mass-assignment
    protected $fillable = [
        'jenis_jabatan_id', // ID jenis jabatan
        'nama', // Nama jenis jabatan
    ];

    // Kolom primary key
    protected $primaryKey = 'jenis_jabatan_id';

    // Mengatur tipe data primary key sebagai string
    protected $keyType = 'string';

    // Menentukan apakah primary key adalah auto-increment
    public $incrementing = false;

    /**
     * Relasi dengan JenisTingkatJabatan
     * (Setiap jenis jabatan dapat memiliki banyak jenis tingkat jabatan)
     */
    public function jenisTingkatJabatans()
    {
        return $this->hasMany(JenisTingkatJabatan::class, 'jenis_jabatan_id', 'jenis_jabatan_id')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
    }

    /**
     * Relasi dengan TingkatJabatan
     * (Setiap jenis jabatan dapat memiliki banyak tingkat jabatan melalui jenis tingkat jabatan)
     */
    public function tingkatJabatans()
    {
        return $this->hasManyThrough(TingkatJabatan::class, JenisTingkatJabatan::class, 'jenis_jabatan_id', 'jenis_tingkat_jabatan_id', 'jenis_jabatan_id', 'jenis_tingkat_jabatan_id')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
    }

    /**
     * Relasi dengan Jabatan
     * (Setiap jenis jabatan dapat memiliki banyak jabatan melalui tingkat jabatan)
     */
    public function jabatans()
    {
        return $this->hasManyThrough(Jabatan::class, JenisTingkatJabatan::class, 'jenis_jabatan_id', 'tingkat_jabatan_id', 'jenis_jabatan_id', 'jenis_tingkat_jabatan_id')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
    }

    /**
     * Menyimpan nama jenis jabatan
     * sebagai representasi objek.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->nama;
    }
}
