<?php
include('koneksi.php');

if (isset($_GET['id_perusahaan'])) {
    $id_perusahaan = $_GET['id_perusahaan'];

    $cek = mysqli_query($coneksi, "SELECT * FROM perusahaan WHERE id_perusahaan='$id_perusahaan'") or die(mysqli_error($coneksi));

    if (mysqli_num_rows($cek) > 0) {
        $hapus = mysqli_query($coneksi, "DELETE FROM perusahaan WHERE id_perusahaan='$id_perusahaan'") or die(mysqli_error($coneksi));
        if ($hapus) {
            $_SESSION['flash_hapus'] = 'sukses';
        }
        echo '<script>window.location.href = "index.php?page=perusahaan";</script>';
        exit();
    } else {
        echo '<script>window.location.href = "index.php?page=perusahaan";</script>';
        exit();
    }
}
?>