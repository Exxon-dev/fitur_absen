<?php include('../../koneksi.php'); ?>
<?php
if (isset($_POST['submit'])) {
	$id_sekolah = $_POST['id_sekolah'];
	$nama_sekolah = $_POST['nama_sekolah'];
	$alamat_sekolah = $_POST['alamat_sekolah'];
	$kepala_sekolah = $_POST['kepala_sekolah'];


	$cek = mysqli_query($coneksi, "SELECT * FROM sekolah WHERE id_sekolah='$id_sekolah'") or die(mysqli_error($coneksi));

	if (mysqli_num_rows($cek) == 0) {
		$sql = mysqli_query($coneksi, "INSERT INTO sekolah(id_sekolah, nama_sekolah, alamat_sekolah, kepala_sekolah) VALUES('$id_sekolah', '$nama_sekolah', '$alamat_sekolah','$kepala_sekolah')") or die(mysqli_error($coneksi));
		if ($sql) {
            $_SESSION['flash_tambah'] = 'sukses';
            header('Location: ../../index.php?page=sekolah');
            exit();
        } else {
            $_SESSION['flash_error'] = mysqli_error($coneksi);
            header('Location: ../../index.php?page=sekolah');
            exit();
        }
    } else {
        $_SESSION['flash_duplikat'] = true;
        header('Location: ../../index.php?page=sekolah');
        exit();
    }
}
?>