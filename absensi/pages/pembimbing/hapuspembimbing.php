<?php
include('koneksi.php');
if (isset($_GET['id_pembimbing'])) {
    $id_pembimbing = $_GET['id_pembimbing'];
    $hapus = mysqli_query($coneksi, "DELETE FROM pembimbing WHERE id_pembimbing='$id_pembimbing'");
    if ($hapus) {
        $_SESSION['flash_hapus'] = 'sukses';
    }
    echo '<script>window.location.href = "index.php?page=pembimbing";</script>';
    exit();
} else {
    echo '<script>window.location.href = "index.php?page=pembimbing";</script>';
    exit();
}
?>