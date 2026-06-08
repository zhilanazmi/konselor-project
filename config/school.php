<?php

return [
    'nama' => env('SEKOLAH_NAMA', 'Nama Sekolah'),
    'alamat' => env('SEKOLAH_ALAMAT', 'Alamat Sekolah'),
    'telepon' => env('SEKOLAH_TELEPON', '-'),
    'email' => env('SEKOLAH_EMAIL', '-'),
    'kode_pos' => env('SEKOLAH_KODE_POS', ''),
    'kepala_sekolah' => [
        'nama' => env('KEPALA_SEKOLAH_NAMA', 'Nama Kepala Sekolah'),
        'nip' => env('KEPALA_SEKOLAH_NIP', '-'),
    ],
];
