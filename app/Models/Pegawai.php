<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    // Tentukan nama tabel
    protected $table = 'pegawais';

    // Tentukan primary key
    protected $primaryKey = 'nip';

    // Tentukan tipe data primary key
    protected $keyType = 'string';

    // Tentukan apakah primary key auto increment
    public $incrementing = false;

    // Tentukan kolom yang bisa diisi (fillable)
    protected $fillable = [
        'nip',
        'nip_bps',
        'nama',
        'unor_id',
        'provinsi_id',
        'kabupaten_id',
        'jenis_jabatan_id',
        'jenis_tingkat_jabatan_id',
        'tingkat_jabatan_id',
        'jabatan_id',
        'status_fungsional_id',
        'tingkat_pendidikan_id',
        'pendidikan_id',
        'status_ais_id',
        'tmt_cpns',
        'golongan_id',
        'tmt_golongan',
        'status_pegawai_id',
        'perkiraan_pensiun',
        'tmt_bmp',
        'nomor_sk_bmp',
        'agama_id',
        'jenis_kelamin_id',
        'nik',
        'wilayah_domisili',
        'provinsi_domisili_id',
        'kabupaten_domisili_id',
        'kecamatan_domisili_id',
        'desa_domisili_id',
        'alamat_domisili',
        'rt_domisili',
        'rw_domisili',
        'kode_pos_domisili',
        'wilayah_ktp',
        'provinsi_ktp_id',
        'kabupaten_ktp_id',
        'kecamatan_ktp_id',
        'desa_ktp_id',
        'alamat_ktp',
        'rt_ktp',
        'rw_ktp',
        'kode_pos_ktp',
        'foto_profile',
        'tanggal',
        'level_user_id'
    ];

    // Relasi dengan tabel lain
    public function unor()
    {
        return $this->belongsTo(Unor::class, 'unor_id');
    }

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id');
    }

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id');
    }

    public function jenisJabatan()
    {
        return $this->belongsTo(JenisJabatan::class, 'jenis_jabatan_id');
    }

    public function jenisTingkatJabatan()
    {
        return $this->belongsTo(JenisTingkatJabatan::class, 'jenis_tingkat_jabatan_id');
    }

    public function tingkatJabatan()
    {
        return $this->belongsTo(TingkatJabatan::class, 'tingkat_jabatan_id');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    public function statusFungsional()
    {
        return $this->belongsTo(StatusFungsional::class, 'status_fungsional_id');
    }

    public function tingkatPendidikan()
    {
        return $this->belongsTo(TingkatPendidikan::class, 'tingkat_pendidikan_id');
    }

    public function pendidikan()
    {
        return $this->belongsTo(Pendidikan::class, 'pendidikan_id');
    }

    public function statusAis()
    {
        return $this->belongsTo(StatusAis::class, 'status_ais_id');
    }

    public function golongan()
    {
        return $this->belongsTo(Pangkat_Golongan::class, 'golongan_id');
    }

    public function statusPegawai()
    {
        return $this->belongsTo(StatusPegawai::class, 'status_pegawai_id');
    }

    public function agama()
    {
        return $this->belongsTo(Agama::class, 'agama_id');
    }

    public function jenisKelamin()
    {
        return $this->belongsTo(JenisKelamin::class, 'jenis_kelamin_id');
    }

    public function provinsiDomisili()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_domisili_id');
    }

    public function kabupatenDomisili()
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_domisili_id');
    }

    public function kecamatanDomisili()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_domisili_id');
    }

    public function desaDomisili()
    {
        return $this->belongsTo(Desa::class, 'desa_domisili_id');
    }

    public function provinsiKtp()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_ktp_id');
    }

    public function kabupatenKtp()
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_ktp_id');
    }

    public function kecamatanKtp()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_ktp_id');
    }

    public function desaKtp()
    {
        return $this->belongsTo(Desa::class, 'desa_ktp_id');
    }

    public function levelUser()
    {
        return $this->belongsTo(LevelUser::class, 'level_user_id');
    }

    public function save(array $options = [])
    {
        // Mengisi field wilayah_domisili dan wilayah_ktp sebelum menyimpan
        $this->wilayah_domisili = "{$this->DesaDomisili->desa_id}";
        $this->wilayah_ktp = "{$this->DesaKtp->desa_id}";

        parent::save($options);
    }
}
