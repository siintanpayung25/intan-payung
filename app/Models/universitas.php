<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Universitas extends Model
{
    use HasFactory;

    // Tentukan nama tabel yang sesuai (jika tidak mengikuti konvensi plural)
    protected $table = 'universitas';

    // Kolom yang dapat diisi secara mass-assignment
    protected $fillable = [
        'instansi_id',  // ID Instansi
        'negara_id',    // ID Negara (foreign key)
        'nama',         // Nama Instansi
    ];

    // Kolom primary key
    protected $primaryKey = 'instansi_id';

    // Mengatur tipe data primary key sebagai string
    protected $keyType = 'string';

    // Menentukan apakah primary key adalah auto-increment
    public $incrementing = false;

    /**
     * Relasi dengan model WilayahNegara (Instansi belongs to Negara)
     * (Setiap instansi dimiliki oleh satu negara)
     */
    public function negara()
    {
        return $this->belongsTo(Wilayah_Negara::class, 'negara_id', 'negara_id')
            ->onDelete('cascade')
            ->onUpdate('cascade');
    }

    /**
     * Menyimpan nama instansi
     * sebagai representasi objek.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->nama;
    }
}
