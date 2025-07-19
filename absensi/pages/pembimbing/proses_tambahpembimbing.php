<?php 
include('../../koneksi.php');
session_start();

if (isset($_POST['submit'])) {
    $id_pembimbing = $_POST['id_pembimbing'];
    $nama_pembimbing = $_POST['nama_pembimbing'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Perbaikan query cek - sebelumnya ada kesalahan sintaks
    $cek = mysqli_query($coneksi, "SELECT * FROM pembimbing WHERE id_pembimbing='$id_pembimbing'") or die(mysqli_error($coneksi));

    if (mysqli_num_rows($cek) == 0) {
        $sql = mysqli_query($coneksi, "INSERT INTO pembimbing(id_pembimbing, nama_pembimbing, username, password) VALUES('$id_pembimbing', '$nama_pembimbing', '$username','$password')") or die(mysqli_error($coneksi));

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
?>