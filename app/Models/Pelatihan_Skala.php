<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelatihan_Skala extends Model
{
    use HasFactory;

    // Tentukan nama tabel yang sesuai (jika tidak mengikuti konvensi plural)
    protected $table = 'pelatihan_skalas'; // Nama tabel

    // Kolom yang dapat diisi secara mass-assignment
    protected $fillable = ['skala_id', 'nama'];

    // Kolom primary key
    protected $primaryKey = 'skala_id';

    // Mengatur tipe data primary key sebagai string
    protected $keyType = 'string';

    // Menentukan apakah primary key adalah auto-increment
    public $incrementing = false;

    // Jika timestamps ingin diaktifkan
    // public $timestamps = true;


    /**
     * Relasi dengan model Pelatihan
     * (Setiap skala pelatihan memiliki banyak pelatihan)
     */
    public function pelatihans()
    {
        return $this->hasMany(Pelatihan::class, 'skala_id', 'skala_id')
            ->onDelete('cascade')
            ->onUpdate('cascade');
    }

    /**
     * Menyimpan nama skala pelatihan
     * sebagai representasi objek.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->nama;
    }
}
