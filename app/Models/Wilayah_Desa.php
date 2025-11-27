<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah_Desa extends Model
{
    use HasFactory;

    // Menentukan nama tabel
    protected $table = 'wilayah_desas';

    // Kolom yang dapat diisi secara mass-assignment
    protected $fillable = [
        'desa_id',
        'negara_id',
        'provinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'kode_desa',
        'nama',
        'status_pemdesa_id'
    ];

    // Tentukan primary key
    protected $primaryKey = 'desa_id';  // Kolom desa_id sebagai primary key

    // Tentukan tipe primary key
    protected $keyType = 'string';  // Ganti dengan 'int' jika menggunakan tipe integer

    // Relasi dengan Wilayah_Negara
    public function negara()
    {
        return $this->belongsTo(Wilayah_Negara::class, 'negara_id', 'negara_id')
            ->onDelete('cascade')
            ->onUpdate('cascade');
    }

    // Relasi dengan Wilayah_Provinsi
    public function provinsi()
    {
        return $this->belongsTo(Wilayah_Provinsi::class, 'provinsi_id', 'provinsi_id')
            ->onDelete('cascade')
            ->onUpdate('cascade');
    }

    // Relasi dengan Wilayah_Kabupaten
    public function kabupaten()
    {
        return $this->belongsTo(Wilayah_Kabupaten::class, 'kabupaten_id', 'kabupaten_id')
            ->onDelete('cascade')
            ->onUpdate('cascade');
    }

    // Relasi dengan Wilayah_Kecamatan
    public function kecamatan()
    {
        return $this->belongsTo(Wilayah_Kecamatan::class, 'kecamatan_id', 'kecamatan_id')
            ->onDelete('cascade')
            ->onUpdate('cascade');
    }

    // Relasi dengan Status Pemerintahan Desa
    public function statusPemerintahan()
    {
        return $this->belongsTo(StatusPemerintahanDesa::class, 'status_pemdesa_id', 'status_pemdesa_id')
            ->onDelete('set null')
            ->onUpdate('cascade');
    }

    /**
     * Menyimpan desa_id secara otomatis berdasarkan gabungan kecamatan_id dan kode_desa.
     *
     * @param  array  $attributes
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($desa) {
            // Membuat ID desa berdasarkan gabungan kecamatan_id dan kode_desa
            $desa->desa_id = $desa->kecamatan_id . $desa->kode_desa;
        });
    }

    /**
     * Format nama desa untuk ditampilkan.
     *
     * @return string
     */
    public function __toString()
    {
        return "[{$this->kode_desa}] {$this->nama}";
    }
}
