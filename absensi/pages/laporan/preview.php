<?php 
// pages/laporan/preview.php
session_start(); // Pastikan session sudah dimulai
include('../../koneksi.php');

if (!isset($_SESSION['level'])) {
    header('Location: sign-in.php');
    exit();
}

// Ambil semua parameter filter dengan pengecekan yang lebih aman
$id_siswa = isset($_GET['id_siswa']) ? $_GET['id_siswa'] : null;
$page = isset($_GET['page']) ? $_GET['page'] : null;
$filter_type = isset($_GET['filter_type']) ? $_GET['filter_type'] : 'daily';

// Validasi parameter wajib
if (!$id_siswa || !$page) {
    die("Parameter tidak lengkap. ID Siswa dan Halaman wajib diisi.");
}

// Simpan parameter filter dalam session untuk digunakan di halaman yang di-include
$_SESSION['filter_params'] = [
    'filter_type' => $filter_type,
    'id_siswa' => $id_siswa
];

// Set parameter berdasarkan jenis filter dengan pengecekan yang aman
if ($filter_type == 'daily') {
    $_SESSION['filter_params']['start_date'] = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d');
    $_SESSION['filter_params']['end_date'] = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');
} elseif ($filter_type == 'monthly') {
    $_SESSION['filter_params']['month'] = isset($_GET['month']) ? $_GET['month'] : date('m');
    $_SESSION['filter_params']['year'] = isset($_GET['year_monthly']) ? $_GET['year_monthly'] : date('Y');
} elseif ($filter_type == 'yearly') {
    $_SESSION['filter_params']['year'] = isset($_GET['year_yearly']) ? $_GET['year_yearly'] : date('Y');
}

// Redirect ke halaman yang sesuai
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
?>