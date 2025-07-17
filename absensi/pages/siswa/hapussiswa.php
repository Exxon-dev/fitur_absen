<?php
include('koneksi.php');
if (isset($_GET['id_siswa'])) {
    $id_siswa = $_GET['id_siswa'];
    $hapus = mysqli_query($coneksi, "DELETE FROM siswa WHERE id_siswa='$id_siswa'");
    if ($hapus) {
        $_SESSION['flash_hapus'] = 'sukses';
    }
    echo '<script>window.location.href = "index.php?page=siswa";</script>';
    exit();
} else {
    echo '<script>window.location.href = "index.php?page=siswa";</script>';
    exit();
}
?>