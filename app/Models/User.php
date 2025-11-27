<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Tentukan nama tabel jika berbeda dari default.
     * Tidak perlu menambahkan karena defaultnya sudah sesuai dengan Laravel.
     * protected $table = 'users'; 
     */

    // Tentukan primary key yang digunakan untuk autentikasi
    protected $primaryKey = 'nip';  // Ubah primary key menjadi 'nip'

    // Tentukan tipe data primary key
    protected $keyType = 'string';  // Menggunakan tipe string untuk nip

    // Tentukan apakah primary key auto increment
    public $incrementing = false;  // Jangan gunakan auto increment

    /**
     * Kolom yang bisa diisi (fillable)
     * Anda bisa menambahkan kolom lain yang dibutuhkan
     */
    protected $fillable = [
        'nip',  // Masukkan nip di sini
        'name',
        'email',
        'password',
        // Tambahkan kolom lain jika diperlukan
    ];

    /**
     * Kolom yang perlu disembunyikan saat serialisasi
     * Misalnya menyembunyikan password dan token
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Pengaturan cast untuk tipe data tertentu
     * Misalnya email_verified_at bertipe datetime
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relasi dengan model Pegawai berdasarkan 'nip'
     * Menggunakan hasOne karena satu user hanya memiliki satu pegawai
     */
    public function pegawai()
    {
        return $this->hasOne(Pegawai::class, 'nip', 'nip');
    }

    /**
     * Relasi dengan tabel 'roles' jika Anda menggunakan sistem roles
     * Misalnya jika ada relasi user-roles (Opsional, jika menggunakan sistem role)
     */
    // public function roles()
    // {
    //     return $this->belongsToMany(Role::class);
    // }

    /**
     * Set password dan enkripsi sebelum menyimpan ke database
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * Method untuk mengecek apakah user memiliki akses
     * Misalnya mengecek peran (role) atau status tertentu
     */
    // public function hasRole($role)
    // {
    //     return in_array($role, $this->roles->pluck('name')->toArray());
    // }

    // Anda bisa menambahkan lebih banyak relasi atau metode sesuai kebutuhan.

    // Menambahkan relasi ke level_user
    public function levelUser()
    {
        return $this->hasOneThrough(LevelUser::class, Pegawai::class, 'nip', 'id', 'nip', 'level_user_id');
    }
}
