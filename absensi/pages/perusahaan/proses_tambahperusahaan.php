<?php include('../../koneksi.php'); ?>
<?php
if (isset($_POST['submit'])) {
    $id_perusahaan = $_POST['id_perusahaan'];
    $nama_perusahaan = $_POST['nama_perusahaan'];
    $alamat_perusahaan = $_POST['alamat_perusahaan'];


    $cek = mysqli_query($coneksi, "SELECT * FROM perusahaan WHERE id_perusahaan='$id_perusahaan'") or die(mysqli_error($coneksi));

    if (mysqli_num_rows($cek) == 0) {
        $sql = mysqli_query($coneksi, "INSERT INTO perusahaan(id_perusahaan, nama_perusahaan, alamat_perusahaan) VALUES('$id_perusahaan', '$nama_perusahaan', '$alamat_perusahaan')") or die(mysqli_error($coneksi));

        if ($sql) {
            $_SESSION['flash_tambah'] = 'sukses';
            header('Location: ../../index.php?page=perusahaan');
            exit();
        } else {
            $_SESSION['flash_error'] = mysqli_error($coneksi);
            header('Location: ../../index.php?page=perusahaan');
            exit();
        }
    } else {
        $_SESSION['flash_duplikat'] = true;
        header('Location: ../../index.php?page=perusahaan');
        exit();
    }
}
?>