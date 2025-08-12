<?php
include('../../koneksi.php');

if (isset($_POST['submit'])) {
    $id_perusahaan    = $_POST['id_perusahaan'];
    $nama_pembimbing  = $_POST['nama_pembimbing'];
    $no_tlp           = $_POST['no_tlp'];
    $alamat           = $_POST['alamat'];
    $jenis_kelamin    = $_POST['jenis_kelamin'];
    $username         = $_POST['username'];
    $password         = $_POST['password'];

    // Perbaikan query cek - sebelumnya ada kesalahan sintaks
    $cek = mysqli_query($coneksi, "SELECT * FROM pembimbing WHERE nama_pembimbing='$nama_pembimbing'") or die(mysqli_error($coneksi));

    if (mysqli_num_rows($cek) == 0) {
        $sql = mysqli_query($coneksi, "INSERT INTO pembimbing (
        id_perusahaan,
        nama_pembimbing,
        no_tlp,
        alamat,
        jenis_kelamin,
        username, 
        password) 
        VALUES (
        '$id_perusahaan', 
        '$nama_pembimbing', 
        '$no_tlp',
        '$alamat',
        '$jenis_kelamin',
        '$username',
        '$password')")
         or die(mysqli_error($coneksi));

        if ($sql) {
            $_SESSION['flash_tambah'] = 'sukses';
            header('Location: ../../index.php?page=pembimbing');
            exit();
        } else {
            $_SESSION['flash_error'] = mysqli_error($coneksi);
            header('Location: ../../index.php?page=pembimbing');
            exit();
        }
    } else {
        $_SESSION['flash_duplikat'] = true;
        header('Location: ../../index.php?page=pembimbing');
        exit();
    }
}
