<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PelatihanKategoriJenisPivotSeeder1_03 extends Seeder
{
    public function run()
    {
        // Mendapatkan tanggal dan waktu sekarang untuk created_at
        $createdAt = Carbon::now();

        // Data kategori dan jenis untuk insert ke dalam pivot
        $data = [
            ['kategori_id' => '104', 'jenis_id' => '21'],
            ['kategori_id' => '104', 'jenis_id' => '22'],
            ['kategori_id' => '105', 'jenis_id' => '23'],
            ['kategori_id' => '109', 'jenis_id' => '24'],
            // Tambahkan data lainnya sesuai dengan kebutuhan
        ];

        foreach ($data as $item) {
            DB::table('pelatihan_kategori_jenis_pivot')->insert([
                'kategori_jenis_id' => $item['kategori_id'] . $item['jenis_id'], // Gabungkan kategori_id dan jenis_id
                'kategori_id' => $item['kategori_id'],
                'jenis_id' => $item['jenis_id'],
                'created_at' => $createdAt,
                'updated_at' => null, // Kolom updated_at tetap null
            ]);
        }
    }
}
