<?php 
if (isset($_GET['page'])) {
    $page = $_GET['page'];
    switch ($page) {
        case 'cover':
            include $_SERVER['DOCUMENT_ROOT'] . '/fitur_absen/absensi/pages/laporan/cover.php';
            break;
        case 'df':
            include $_SERVER['DOCUMENT_ROOT'] . '/fitur_absen/absensi/pages/laporan/daftarhadir.php';
            break;
        case 'jr':
            include $_SERVER['DOCUMENT_ROOT'] . '/fitur_absen/absensi/pages/laporan/doc_jr.php';
            break;
        case 'catatan':
            include $_SERVER['DOCUMENT_ROOT'] . '/fitur_absen/absensi/pages/laporan/doc_catatan.php';
            break;
        case 'dn':
            include $_SERVER['DOCUMENT_ROOT'] . '/fitur_absen/absensi/pages/laporan/daftarnilai.php';
            break;
        case 'sk':
            include $_SERVER['DOCUMENT_ROOT'] . '/fitur_absen/absensi/pages/laporan/sk.php';
            break;
        case 'nkp':
            include $_SERVER['DOCUMENT_ROOT'] . '/fitur_absen/absensi/pages/laporan/nkp.php';
            break;
        case 'lp':
            include $_SERVER['DOCUMENT_ROOT'] . '/fitur_absen/absensi/pages/laporan/lp.php';
            break;
        case 'bl':
            include $_SERVER['DOCUMENT_ROOT'] . '/fitur_absen/absensi/pages/laporan/bl.php';
            break;
        default:
            echo "Maaf, halaman yang anda tuju tidak ada.";
            break;
    }
} 
?>
