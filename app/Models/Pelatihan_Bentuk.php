<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelatihan_Bentuk extends Model
{
    use HasFactory;

    // Tentukan nama tabel yang sesuai (jika tidak mengikuti konvensi plural)
    protected $table = 'pelatihan_bentuks'; // Nama tabel

    // Kolom yang dapat diisi secara mass-assignment
    protected $fillable = ['bentuk_id', 'nama'];

    // Kolom primary key
    protected $primaryKey = 'bentuk_id';

    // Mengatur tipe data primary key sebagai string
    protected $keyType = 'string';

    // Menentukan apakah primary key adalah auto-increment
    public $incrementing = false;

    /**
     * Relasi dengan model Pelatihan
     * (Setiap bentuk pelatihan memiliki banyak pelatihan)
     */
    public function pelatihans()
    {
        return $this->hasMany(Pelatihan::class, 'bentuk_id', 'bentuk_id')
            ->onDelete('cascade')
            ->onUpdate('cascade');
    }

    /**
     * Menyimpan nama bentuk pelatihan
     * sebagai representasi objek.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->nama;
    }
}
