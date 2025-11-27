<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelatihan_Kategori extends Model
{
    use HasFactory;

    // Tentukan nama tabel yang sesuai
    protected $table = 'pelatihan_kategoris'; // Nama tabel

    // Kolom yang dapat diisi secara mass-assignment
    protected $fillable = ['kategori_id', 'bentuk_id', 'kode_kategori', 'nama'];

    // Kolom primary key
    protected $primaryKey = 'kategori_id';

    // Mengatur tipe data primary key sebagai string
    protected $keyType = 'string';

    // Menentukan apakah primary key adalah auto-increment
    public $incrementing = false;

    /**
     * Relasi dengan model Pelatihan
     * (Setiap kategori pelatihan memiliki banyak pelatihan)
     */
    public function pelatihans()
    {
        return $this->hasMany(Pelatihan::class, 'kategori_id', 'kategori_id')
            ->onDelete('cascade')
            ->onUpdate('cascade');
    }

    /**
     * Relasi Many-to-One dengan Pelatihan_Bentuk
     * (Setiap kategori pelatihan memiliki satu bentuk)
     */
    public function bentuk()
    {
        return $this->belongsTo(Pelatihan_Bentuk::class, 'bentuk_id', 'bentuk_id')
            ->onDelete('cascade')
            ->onUpdate('cascade');
    }

    /**
     * Relasi Many-to-Many dengan Pelatihan_Jenis melalui pivot table pelatihan_kategori_jenis_pivot
     */
    public function jenis()
    {
        // Relasi Many-to-Many dengan Pelatihan_Jenis melalui pivot table pelatihan_kategori_jenis_pivot
        // Pastikan menggunakan kategori_jenis_id sebagai primary key di pivot
        return $this->belongsToMany(Pelatihan_Jenis::class, 'pelatihan_kategori_jenis_pivot', 'kategori_id', 'jenis_id')
            ->withPivot('kategori_jenis_id') // Menambahkan kolom pivot baru yaitu kategori_jenis_id
            ->withTimestamps(); // Menambahkan timestamps untuk pivot table
    }

    /**
     * Menyimpan nama kategori pelatihan sebagai representasi objek.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->nama;
    }
}
