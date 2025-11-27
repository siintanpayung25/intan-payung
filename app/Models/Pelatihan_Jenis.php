<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelatihan_Jenis extends Model
{
    use HasFactory;

    protected $table = 'pelatihan_jeniss'; // Nama tabel

    protected $fillable = ['jenis_id', 'nama'];  // Kolom yang dapat diisi

    protected $primaryKey = 'jenis_id';  // Primary key

    public $incrementing = false;  // Menonaktifkan auto increment

    protected $keyType = 'string';  // Jenis ID menggunakan string

    /**
     * Relasi dengan Pelatihan
     * (Setiap jenis pelatihan memiliki banyak pelatihan)
     */
    public function pelatihans()
    {
        return $this->hasMany(Pelatihan::class, 'jenis_id', 'jenis_id');
    }

    /**
     * Relasi Many-to-Many dengan Pelatihan_Kategori melalui pivot table pelatihan_kategori_jenis_pivot
     */
    public function kategoris()
    {
        // Relasi Many-to-Many dengan Pelatihan_Kategori melalui pivot table pelatihan_kategori_jenis_pivot
        // Menambahkan kategori_jenis_id di pivot
        return $this->belongsToMany(Pelatihan_Kategori::class, 'pelatihan_kategori_jenis_pivot', 'jenis_id', 'kategori_id')
            ->withPivot('kategori_jenis_id') // Menambahkan kolom pivot baru yaitu kategori_jenis_id
            ->withTimestamps(); // Menambahkan timestamps untuk pivot table
    }

    /**
     * Menyimpan nama jenis pelatihan sebagai representasi objek.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->nama;
    }
}
