<?php 
include('../../koneksi.php');
session_start();

if (isset($_POST['submit'])) {
    $id_pembimbing = $_POST['id_pembimbing'];
    $nama_pembimbing = $_POST['nama_pembimbing'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = mysqli_query($coneksi, "UPDATE pembimbing SET nama_pembimbing='$nama_pembimbing', username='$username', password='$password' WHERE id_pembimbing='$id_pembimbing'");
    
    if ($sql) {
        $_SESSION['flash_edit'] = 'sukses';
    }
    
    header("Location: ../../index.php?page=pembimbing");
    exit();
} else {
    header("Location: ../../index.php?page=pembimbing");
    exit();
}
?>