<?php
$file = 'app/Models/CalonMahasiswa.php';
$lines = file($file);
$found = false;
foreach ($lines as $i => $line) {
    if (strpos($line, 'public function prodi()') !== false) {
        // Baris berikutnya adalah '{', dan berikutnya adalah return
        if (isset($lines[$i+2]) && strpos($lines[$i+2], 'prodi_diterima_id') !== false) {
            $lines[$i+2] = str_replace('prodi_diterima_id', 'prodi_id', $lines[$i+2]);
            $found = true;
            break;
        }
    }
}
if ($found) {
    file_put_contents($file, implode('', $lines));
    echo 'BERHASIL' . PHP_EOL;
} else {
    echo 'TIDAK DITEMUKAN' . PHP_EOL;
}
