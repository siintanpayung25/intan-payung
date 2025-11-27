<?php

return [
    'required' => ':attribute wajib diisi.',
    'email' => ':attribute harus berupa alamat email yang valid.',
    'url' => ':attribute harus berupa URL yang valid.',
    'unique' => ':attribute sudah digunakan.',
    'before_or_equal' => ':attribute harus lebih kecil atau sama dengan Tanggal Selesai.',
    'after_or_equal' => ':attribute harus lebih besar atau sama dengan Tanggal Mulai.',
    'date_format' => ':attribute harus dalam format yang sesuai.',
    'regex' => ':attribute harus berupa angka desimal, contoh: 1.5 atau 0.75.',
    'attributes' => [
        'nip' => 'Nama Pegawai',
        'kategori_id' => 'Kategori Pelatihan',
        'skala_id' => 'Skala Pelatihan',
        'bentuk_id' => 'Bentuk Pelatihan',
        'jenis_id' => 'Jenis Pelatihan',
        'nama' => 'Nama Pelatihan',
        'tgl_mulai' => 'Tanggal Mulai',
        'tgl_selesai' => 'Tanggal Selesai',
        'instansi_id' => 'Instansi',
        'durasi' => 'Durasi Pelatihan',
        'link_bukti_dukung' => 'Link Bukti Dukung',
        'nomor_sertifikat' => 'Nomor sertifikat',
    ],
];
