<?php
// Aktifkan error reporting di awal file
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Pastikan session_start() pertama kali
session_start();

// Pastikan tidak ada output sebelum ini
ob_start();

// Perbaiki path koneksi
require_once('../../koneksi.php');

// Debug koneksi
if (!$coneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
<<<<<<< HEAD
    // Ambil data dari form dengan escape
    $nama_perusahaan = mysqli_real_escape_string($coneksi, $_POST['nama_perusahaan'] ?? '');
    $alamat_perusahaan = mysqli_real_escape_string($coneksi, $_POST['alamat_perusahaan'] ?? '');
    $no_tel = mysqli_real_escape_string($coneksi, $_POST['no_tel'] ??'');

    // Validasi input
    if (empty($nama_perusahaan) || empty($alamat_perusahaan)) {
        $_SESSION['flash_error'] = "Nama dan alamat perusahaan harus diisi";
=======
    $nama_perusahaan    = $_POST['nama_perusahaan'];
    $pimpinan           = $_POST['pimpinan'];
    $alamat_perusahaan  = $_POST['alamat_perusahaan'];
    $no_tlp             = $_POST['no_tlp'];


    $cek = mysqli_query($coneksi, "SELECT * FROM perusahaan WHERE nama_perusahaan='$nama_perusahaan'") or die(mysqli_error($coneksi));

    if (mysqli_num_rows($cek) == 0) {
        $sql = mysqli_query($coneksi, "INSERT INTO perusahaan ( 
        nama_perusahaan, 
        pimpinan, 
        alamat_perusahaan, 
        no_tlp ) 
        VALUES(
        '$nama_perusahaan', 
        '$pimpinan', 
        '$alamat_perusahaan',
        '$no_tlp' 
        )") or die(mysqli_error($coneksi));

        if ($sql) {
            $_SESSION['flash_tambah'] = 'sukses';
            header('Location: ../../index.php?page=perusahaan');
            exit();
        } else {
            $_SESSION['flash_error'] = mysqli_error($coneksi);
            header('Location: ../../index.php?page=perusahaan');
            exit();
        }
    } else {
        $_SESSION['flash_duplikat'] = true;
>>>>>>> fitur
        header('Location: ../../index.php?page=perusahaan');
        exit();
    }

    // Gunakan prepared statement untuk keamanan
    $stmt = $coneksi->prepare("INSERT INTO perusahaan(nama_perusahaan, alamat_perusahaan, no_tel) VALUES(?, ?, ?)");
    $stmt->bind_param("sss", $nama_perusahaan, $alamat_perusahaan, $no_tel);
    
    if ($stmt->execute()) {
        $_SESSION['flash_tambah'] = 'sukses';
        header('Location: ../../index.php?page=perusahaan');
        exit();
    } else {
        $_SESSION['flash_error'] = mysqli_error($coneksi);
        header('Location: ../../index.php?page=perusahaan');
        exit();
    }
} else {
    $_SESSION['flash_duplikat'] = true;
    header('Location: ../../index.php?page=perusahaan');
    exit();
}

?>