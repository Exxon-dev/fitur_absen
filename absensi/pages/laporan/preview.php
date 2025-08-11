<?php 
if (isset($_GET['page'])) {
    $page = $_GET['page'];
    switch ($page) {
        case 'cover':
            include 'cover.php';
            break;
        case 'df':
            include 'daftarhadir.php';
            break;
        case 'jr':
            include 'doc_jr.php';
            break;
        case 'catatan':
            include 'doc_catatan.php';
            break;
        case 'dn':
            include 'daftarnilai.php';
            break;
        case 'sk':
            include 'sk.php';
            break;
        case 'nkp':
            include 'nkp.php';
            break;
        case 'lp':
            include 'lp.php';
            break;
        case 'bl':
            include 'bl.php';
            break;
        default:
            echo "Maaf, halaman yang anda tuju tidak ada.";
            break;
    }
} 
?>
