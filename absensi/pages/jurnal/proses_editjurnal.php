<?php 
session_start();
include('../../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_jurnal = $_POST['id_jurnal'];
    $tanggal = $_POST['tanggal'];
    $keterangan = $_POST['keterangan'];
    
    $sql = mysqli_query($coneksi, "UPDATE jurnal SET tanggal='$tanggal', keterangan='$keterangan' 
            WHERE id_jurnal='$id_jurnal'");
    
    if ($sql) {
        $_SESSION['flash_edit'] = 'sukses';
    } else {
        $_SESSION['flash_error'] = "Gagal update: " . mysqli_error($coneksi);
    }
    
    header("Location: ../../index.php?page=jurnal");
    exit();
} else {
    $_SESSION['flash_error'] = "Invalid request method. Harus menggunakan POST.";
    header("Location: ../../index.php?page=jurnal");
    exit();
}
?>