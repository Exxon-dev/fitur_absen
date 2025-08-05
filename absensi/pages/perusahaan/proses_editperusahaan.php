<?php include('../../koneksi.php'); ?>
<?php
if (isset($_GET['id_perusahaan'])) {
    $id_perusahaan = $_GET['id_perusahaan'];

    $select = mysqli_query($coneksi, "SELECT * FROM perusahaan WHERE id_perusahaan='$id_perusahaan'") or die(mysqli_error($coneksi));

    if (mysqli_num_rows($select) == 0) {
        echo '<div class="alert alert-warning">id_perusahaan tidak ada dalam database.</div>';
        exit();
    } else {
        $data = mysqli_fetch_assoc($select);
    }
}
?>

<?php
if (isset($_POST['submit'])) {
    $id_perusahaan = $_POST['id_perusahaan'];
    $nama_perusahaan = $_POST['nama_perusahaan'];
    $alamat_perusahaan = $_POST['alamat_perusahaan'];
    $no_tel = $_POST['no_tel'];


    $sql = mysqli_query($coneksi, "UPDATE perusahaan SET nama_perusahaan='$nama_perusahaan',alamat_perusahaan='$alamat_perusahaan', no_tel='$no_tel' WHERE id_perusahaan='$id_perusahaan'") or die(mysqli_error($coneksi));
    if ($sql) {
        $_SESSION['flash_edit'] = 'sukses';
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
?>