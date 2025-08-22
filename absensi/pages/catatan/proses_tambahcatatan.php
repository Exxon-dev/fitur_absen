<?php
include('../../koneksi.php');

$id_jurnal = $_POST['id_jurnal'] ?? '';
$catatan = $_POST['catatan'] ?? '';
$id_pembimbing = $_SESSION['id_pembimbing'] ?? ($_POST['id_pembimbing'] ?? '');
$role = $_SESSION['level'] ?? '';
$tanggal_hari_ini = date('Y-m-d');
$mode = $_POST['mode'] ?? 'tambah';
$id_catatan = $_POST['id_catatan'] ?? '';

if ($role !== 'pembimbing') {
    $_SESSION['flash_error'] = 'Akses ditolak';
    header('Location: ../../index.php?page=catatan');
    exit();
}

if (isset($_POST['submit'])) {
    // Validasi input wajib
    if (empty($id_jurnal) || empty($catatan)) {
        $_SESSION['flash_error'] = 'ID Jurnal dan Catatan wajib diisi!';
        header('Location: ../../index.php?page=catatan');
        exit();
    }

    if ($mode === 'update' && !empty($id_catatan)) {
        // Update catatan yang sudah ada
        $sql = mysqli_query($coneksi, "UPDATE catatan 
                                      SET catatan = '$catatan', tanggal = '$tanggal_hari_ini'
                                      WHERE id_catatan = '$id_catatan'");

        if ($sql) {
            $_SESSION['flash_update'] = 'sukses';
        } else {
            $_SESSION['flash_error'] = 'Gagal mengupdate catatan: ' . mysqli_error($coneksi);
        }
    } else {
        // Cek duplikat hanya untuk mode tambah
        $cek = mysqli_query($coneksi, "SELECT 1 FROM catatan 
                                       WHERE id_jurnal='$id_jurnal' 
                                       AND id_pembimbing='$id_pembimbing'");
        if (mysqli_num_rows($cek) > 0) {
            $_SESSION['flash_duplikat'] = true;
            header('Location: ../../index.php?page=catatan');
            exit();
        }

        $catatan = mysqli_real_escape_string($coneksi, $_POST['catatan']);

        $sql = mysqli_query($coneksi, "INSERT INTO catatan (id_jurnal, tanggal, `catatan`, id_pembimbing) 
        VALUES ('$id_jurnal', '$tanggal_hari_ini', '$catatan', '$id_pembimbing')");


        if ($sql) {
            $_SESSION['flash_tambah'] = 'sukses';
        } else {
            $_SESSION['flash_error'] = 'Gagal menambahkan catatan: ' . mysqli_error($coneksi);
        }
    }

    header('Location: ../../index.php?page=catatan');
    exit();
} else {
    // Kalau akses tanpa submit
    header('Location: ../../index.php?page=catatan');
    exit();
}
