<?php
// Debug: tampilkan error PHP di browser
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include(__DIR__.'/../../koneksi.php');

if (isset($_POST['submit'])) {
    $nis = $_POST['nis'];
    $nisn = $_POST['nisn'];
    $nama_siswa = $_POST['nama_siswa'];
    $no_wa = $_POST['no_wa'];
    $kelas = $_POST['kelas'];
    $pro_keahlian = $_POST['pro_keahlian'];
    $TTL = $_POST['TTL'];
    $id_sekolah = $_POST['id_sekolah'];
    $id_perusahaan = $_POST['id_perusahaan'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    $id_pembimbing = $_POST['id_pembimbing'];   
    $id_guru = $_POST['id_guru'];
    $username = $_POST['username'];
    $password = $_POST['password']; // Sebaiknya di-hash

    // 1. Cek apakah ID siswa atau username sudah ada
    $cek = mysqli_query($coneksi, "SELECT * FROM siswa WHERE nisn='$nisn' OR username='$username'");
    
    if(!$cek) {
        echo '<pre>Query error: '.mysqli_error($coneksi).'</pre>';
        exit();
    }
    
    if(mysqli_num_rows($cek) == 0){
        $sql = mysqli_query($coneksi, "INSERT INTO siswa (
        nis,
        nisn,
        nama_siswa,
        no_wa,
        kelas,
        pro_keahlian,
        TTL,
        id_sekolah,
        id_perusahaan,
        tanggal_mulai,
        tanggal_selesai,
        id_pembimbing,
        id_guru,
        username, 
        password)
        VALUES (
        '$nis',
        '$nisn',
        '$nama_siswa',
        '$no_wa',
        '$kelas',
        '$pro_keahlian',
        '$TTL',
        '$id_sekolah',
        '$id_perusahaan',
        '$tanggal_mulai',
        '$tanggal_selesai',
        '$id_pembimbing',
        '$id_guru',
        '$username',
        '$password')");
        
        if ($sql) {
            $_SESSION['flash_tambah'] = 'sukses';
            header('Location: ../../index.php?page=siswa');
            exit();
        } else {
            $_SESSION['flash_error'] = mysqli_error($coneksi);
            header('Location: ../../index.php?page=siswa');
            exit();
        }
    } else {
        $_SESSION['flash_duplikat'] = true;
        header('Location: ../../index.php?page=siswa');
        exit();
    }
}
?>