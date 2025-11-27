<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah_StatusAdmKabupaten extends Model
{
    use HasFactory;

    // Menentukan nama tabel
    protected $table = 'wilayah_status_adm_kabupatens';

    // Kolom yang dapat diisi secara mass-assignment
    protected $fillable = [
        'status_adminkab_id', // ID status administratif kabupaten
        'nama' // Nama status administratif kabupaten
    ];

    // Tentukan primary key
    protected $primaryKey = 'status_adminkab_id'; // Ganti dengan nama kolom yang sesuai

    // Tentukan tipe primary key
    protected $keyType = 'string'; // Ganti dengan 'int' jika primary key menggunakan tipe data integer

    /**
     * Menyimpan nama status administratif kabupaten
     * sebagai representasi objek.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->nama;
    }
}
