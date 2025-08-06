<?php include('../../koneksi.php'); ?>
<?php
if (isset($_POST['submit'])) {
    $nama_perusahaan    = $_POST['nama_perusahaan'];
    $pimpinan           = $_POST['pimpinan'];
    $alamat_perusahaan  = $_POST['alamat_perusahaan'];
    $no_tlp             = $_POST['no_tlp'];


    $cek = mysqli_query($coneksi, "SELECT * FROM perusahaan WHERE nama_perusahaan='$nama_perusahaan'") or die(mysqli_error($coneksi));

    if (mysqli_num_rows($cek) == 0) {
        $sql = mysqli_query($coneksi, "INSERT INTO perusahaan ( 
        nama_perusahaan, 
        pimpinan, 
        alamat_perusahaan, 
        no_tlp ) 
        VALUES(
        '$nama_perusahaan', 
        '$pimpinan', 
        '$alamat_perusahaan',
        '$no_tlp' 
        )") or die(mysqli_error($coneksi));

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