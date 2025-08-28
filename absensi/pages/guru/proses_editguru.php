<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include('../../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_guru = $_POST['id_guru'];
    $nama_guru = $_POST['nama_guru'];
    $nip = $_POST['nip'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    $no_tlp = $_POST['no_tlp'];
    $id_sekolah = $_POST['id_sekolah'];
    $id_perusahaan = $_POST['id_perusahaan']; // Pastikan ini ada

    // Update ke database termasuk id_perusahaan
    $sql = mysqli_query($coneksi, "UPDATE guru SET 
        nama_guru='$nama_guru', 
        nip='$nip', 
        jenis_kelamin='$jenis_kelamin', 
        alamat='$alamat', 
        no_tlp='$no_tlp', 
        id_sekolah='$id_sekolah',
        id_perusahaan='$id_perusahaan' 
        WHERE id_guru='$id_guru'");

    if ($sql) {
        $_SESSION['flash_edit'] = 'sukses';
    } else {
        $_SESSION['flash_edit'] = 'gagal';
        $_SESSION['error_message'] = mysqli_error($coneksi);
    }

    header("Location: ../../index.php?page=guru");
    exit();
} else {
    header("Location: ../../index.php?page=guru");
    exit();
}
?>