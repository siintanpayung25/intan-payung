<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelatihan_Sifat_Tna extends Model
{
    use HasFactory;

    // Menentukan nama tabel (jika berbeda dengan nama model)
    protected $table = 'pelatihan_sifat_tna';

    // Menentukan primary key yang digunakan
    protected $primaryKey = 'sifat_tna_id';

    // Menonaktifkan auto increment karena sifat_tna_id adalah string
    public $incrementing = false;

    // Menentukan tipe data primary key
    protected $keyType = 'string'; // Karena sifat_tna_id adalah string

    // Kolom yang dapat diisi
    protected $fillable = [
        'sifat_tna_id',
        'nama',
        'deskripsi',
    ];

    // Jika Anda ingin menambahkan logika lain, seperti relasi atau event, bisa ditambahkan di sini
}
