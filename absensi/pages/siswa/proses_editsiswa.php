<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include('../../koneksi.php');

if (isset($_GET['id_siswa'])) {
    $id_siswa = $_GET['id_siswa'];

    $select = mysqli_query($coneksi, "SELECT * FROM siswa WHERE id_siswa='$id_siswa'") or die(mysqli_error($coneksi));

    if (mysqli_num_rows($select) == 0) {
        echo '<div class="alert alert-warning">id_siswa tidak ada dalam database.</div>';
        exit();
    } else {
        $data = mysqli_fetch_assoc($select);
    }
}

if (isset($_POST['submit'])) {
    $id_siswa = $_POST['id_siswa'];
    $nis = $_POST['nis'];
    $nisn = $_POST['nisn'];
    $nama_siswa = $_POST['nama_siswa'];
    $pro_keahlian = $_POST['pro_keahlian'];
    $id_sekolah = $_POST['id_sekolah'];
    $id_perusahaan = $_POST['id_perusahaan'];
    $id_pembimbing = $_POST['id_pembimbing'];
    $id_guru = $_POST['id_guru'];
    
    // Fixed the SQL query by removing the comma before WHERE
    $sql = mysqli_query($coneksi, "UPDATE siswa SET 
        nis='$nis',
        nisn='$nisn', 
        nama_siswa='$nama_siswa', 
        pro_keahlian='$pro_keahlian', 
        id_sekolah='$id_sekolah',
        id_perusahaan='$id_perusahaan', 
        id_pembimbing='$id_pembimbing', 
        id_guru='$id_guru' 
        WHERE id_siswa='$id_siswa'");
    
    if ($sql) {
        $_SESSION['flash_edit'] = 'sukses';
    }

    header("Location: ../../index.php?page=siswa");
    exit();
} else {
    header("Location: ../../index.php?page=siswa");
    exit();
}
?>