<?php 
// pages/laporan/preview.php
include('../../koneksi.php');
if (!isset($_SESSION['level'])) {
    // Redirect atau tampilkan error jika role tidak terdeteksi
    header('Location: login.php');
    exit();
}

// Ambil semua parameter filter
$id_siswa = $_GET['id_siswa'];
$page = $_GET['page'];
$filter_type = isset($_GET['filter_type']) ? $_GET['filter_type'] : 'daily';

// Simpan parameter filter dalam session untuk digunakan di halaman yang di-include
$_SESSION['filter_params'] = [
    'filter_type' => $filter_type,
    'id_siswa' => $id_siswa
];

// Set parameter berdasarkan jenis filter
if ($filter_type == 'daily') {
    // $_SESSION['filter_params']['start_date'] = $_GET['start_date'];
    // $_SESSION['filter_params']['end_date'] = $_GET['end_date'];
} elseif ($filter_type == 'monthly') {
    $_SESSION['filter_params']['month'] = $_GET['month'];
    $_SESSION['filter_params']['year'] = isset($_GET['year_monthly']) ? $_GET['year_monthly'] : date('Y');
} elseif ($filter_type == 'yearly') {
    $_SESSION['filter_params']['year'] = isset($_GET['year_yearly']) ? $_GET['year_yearly'] : date('Y');
}

// Redirect ke halaman yang sesuai
if (isset($page)) {
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