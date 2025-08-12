<?php
// Pastikan tidak ada output sebelum tag php pembuka
include(__DIR__ . '/../../koneksi.php');

if (isset($_POST['submit'])) {
    $nama_guru      = $_POST['nama_guru'];
    $nip            = $_POST['nip'];
    $jenis_kelamin  = $_POST['jenis_kelamin'];
    $alamat         = $_POST['alamat'];
    $no_tlp         = $_POST['no_tlp'];
    $id_sekolah     = $_POST['id_sekolah'];
    $username       = $_POST['username'];
    $password       = $_POST['password']; // Sebaiknya di-hash

    // 1. Cek apakah ID guru atau username sudah ada
    $cek = mysqli_query($coneksi, "SELECT * FROM guru WHERE username='$username'");

    if (!$cek) {
        die(json_encode(['status' => 'error', 'message' => 'Query error: ' . mysqli_error($coneksi)]));
    }

    if (mysqli_num_rows($cek) == 0) {
        // 2. Jika belum ada, insert data baru
        $sql = mysqli_query($coneksi, "INSERT INTO guru (
            nama_guru,
            nip,
            jenis_kelamin,
            alamat,
            no_tlp,
            id_sekolah,
            username,
            password
        ) VALUES (
            '$nama_guru',
            '$nip',
            '$jenis_kelamin',
            '$alamat',
            '$no_tlp',
            '$id_sekolah',
            '$username',
            '$password'
        )");

        if ($sql) {
            $_SESSION['flash_tambah'] = 'sukses';
            header('Location: ../../index.php?page=guru');
            exit();
        } else {
            $_SESSION['flash_error'] = mysqli_error($coneksi);
            header('Location: ../../index.php?page=guru');
            exit();
        }
    } else {
        $_SESSION['flash_duplikat'] = true;
        header('Location: ../../index.php?page=guru');
        exit();
    }
}
