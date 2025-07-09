<?php
// download.php?file=namafile.ext
if (!isset($_GET['file'])) {
    http_response_code(400);
    echo 'File tidak ditemukan.';
    exit;
}

$filename = basename($_GET['file']);
$filepath = __DIR__ . '/uploads/' . $filename;

if (!file_exists($filepath)) {
    http_response_code(404);
    echo 'File tidak ditemukan.';
    exit;
}

// Force download
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filepath));
readfile($filepath);
exit; 