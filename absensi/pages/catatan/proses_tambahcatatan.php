<?php
include('../../koneksi.php');
session_start();
$id_jurnal = $_POST['id_jurnal'] ?? '';
$id_catatan = $_POST['id_catatan'] ?? '';
$catatan = $_POST['catatan'] ?? '';
$id_pembimbing = $_SESSION['id_pembimbing'] ?? ($_POST['id_pembimbing'] ?? '');
$role = $_SESSION['role'] ?? '';
$tanggal_hari_ini = date('Y-m-d');

if ($role !== 'pembimbing') {
    header('Location: ../../index.php?page=catatan&pesan=gagal&error='.urlencode('Akses ditolak'));
    exit();
}


if (isset($_POST['submit'])) {
    // Cek duplikat
    $cek = mysqli_query($coneksi, "SELECT * FROM catatan WHERE id_jurnal='$id_jurnal' AND id_pembimbing='$id_pembimbing'");
    if (mysqli_num_rows($cek) > 0) {
        header('Location: ../../index.php?page=catatan&pesan=duplikat');
        exit();
    }
    // Insert
    $sql = mysqli_query($coneksi, "INSERT INTO catatan (id_jurnal, tanggal, catatan, id_pembimbing) VALUES ('$id_jurnal', '$tanggal_hari_ini', '$catatan', '$id_pembimbing')");
    if ($sql) {
            $_SESSION['flash_tambah'] = 'sukses';
            header('Location: ../../index.php?page=catatan');
            exit();
        } else {
            $_SESSION['flash_error'] = mysqli_error($coneksi);
            header('Location: ../../index.php?page=catatan');
            exit();
        }
    } else {
        $_SESSION['flash_duplikat'] = true;
        header('Location: ../../index.php?page=catatan');
        exit();
    }
?>