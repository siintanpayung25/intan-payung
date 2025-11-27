<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PelatihanKategoriJenisPivotSeeder1_01 extends Seeder
{
    public function run()
    {
        // Mendapatkan tanggal dan waktu sekarang untuk created_at
        $createdAt = Carbon::now();

        // Data kategori dan jenis untuk insert ke dalam pivot
        $data = [
            ['kategori_id' => '101', 'jenis_id' => '01'],
            ['kategori_id' => '101', 'jenis_id' => '02'],
            ['kategori_id' => '101', 'jenis_id' => '03'],
            ['kategori_id' => '101', 'jenis_id' => '04'],
            ['kategori_id' => '102', 'jenis_id' => '05'],
            ['kategori_id' => '102', 'jenis_id' => '06'],
            ['kategori_id' => '102', 'jenis_id' => '07'],
            ['kategori_id' => '102', 'jenis_id' => '08'],
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
