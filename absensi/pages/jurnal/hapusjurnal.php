<?php
include('koneksi.php');

if (isset($_GET['id_jurnal'])) {
	$id = $_GET['id_jurnal'];

	$cek = mysqli_query($coneksi, "SELECT * FROM jurnal WHERE id_jurnal='$id'") or die(mysqli_error($coneksi));

	if (mysqli_num_rows($cek) > 0) {
		$hapus = mysqli_query($coneksi, "DELETE FROM jurnal WHERE id_jurnal='$id'") or die(mysqli_error($coneksi));
		if ($hapus) {
			$_SESSION['flash_hapus'] = 'sukses';
		}
		echo '<script>window.location.href = "index.php?page=jurnal";</script>';
		exit();
	} else {
		echo '<script>window.location.href = "index.php?page=jurnal";</script>';
		exit();
	}
}
?>