<?php
// Pastikan tidak ada output sebelum tag php pembuka
include(__DIR__.'/../../koneksi.php');

if(isset($_POST['submit'])){
    $id_guru = $_POST['id_guru'];
    $nama_guru = $_POST['nama_guru'];
    $id_sekolah = $_POST['id_sekolah'];
    $username = $_POST['username'];
    $password = $_POST['password']; // Sebaiknya di-hash

    // 1. Cek apakah ID guru atau username sudah ada
    $cek = mysqli_query($coneksi, "SELECT * FROM guru WHERE id_guru='$id_guru' OR username='$username'");
    
    if(!$cek) {
        die(json_encode(['status' => 'error', 'message' => 'Query error: ' . mysqli_error($coneksi)]));
    }
    
    if(mysqli_num_rows($cek) == 0){
        // 2. Jika belum ada, insert data baru
        $sql = mysqli_query($coneksi, "INSERT INTO guru (
            id_guru,
            nama_guru,
            id_sekolah,
            username,
            password
        ) VALUES (
            '$id_guru',
            '$nama_guru', 
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
?>