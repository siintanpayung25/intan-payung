<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eselon extends Model
{
    use HasFactory;

    // Tentukan nama tabel yang sesuai
    protected $table = 'jabatan_eselons'; // Nama tabel

    // Kolom yang dapat diisi
    protected $fillable = ['eselon_id', 'nama'];

    // Kolom primary key
    protected $primaryKey = 'eselon_id';

    // Mengatur tipe data primary key sebagai string
    protected $keyType = 'string';

    // Menentukan apakah primary key adalah auto-increment
    public $incrementing = false;

    /**
     * Relasi dengan model Unor
     * (Setiap eselon memiliki banyak unor)
     */
    public function unors()
    {
        return $this->hasMany(Unor::class, 'eselon_id', 'eselon_id')
            ->onDelete('cascade')
            ->onUpdate('cascade');
    }

    /**
     * Menyimpan nama eselon
     * sebagai representasi objek.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->nama;
    }
}
