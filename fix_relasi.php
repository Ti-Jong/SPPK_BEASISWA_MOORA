<?php
$file = 'app/Models/CalonMahasiswa.php';
$c = file_get_contents($file);

// Cek apakah relasi prodi() masih pakai prodi_diterima_id
if (strpos($c, "belongsTo(Prodi::class, 'prodi_diterima_id')") !== false) {
    // Ganti hanya relasi prodi() yang pertama - dari prodi_diterima_id ke prodi_id
    $c = preg_replace(
        "/public function prodi\(\)\s*\{\s*return \\\\\->belongsTo\(Prodi::class,\s*'prodi_diterima_id'\);/",
        "public function prodi()\n    {\n        return \\\->belongsTo(Prodi::class, 'prodi_id');",
        $c,
        1  // hanya replace pertama kali
    );
    file_put_contents($file, $c);
    echo 'BERHASIL: relasi prodi() diubah ke prodi_id' . PHP_EOL;
} else {
    echo 'Sudah menggunakan prodi_id' . PHP_EOL;
}
