<?php
include('koneksi.php');

if (isset($_GET['id_sekolah'])) {
	$id_sekolah = $_GET['id_sekolah'];

	$cek = mysqli_query($coneksi, "SELECT * FROM sekolah WHERE id_sekolah='$id_sekolah'") or die(mysqli_error($coneksi));

	if (mysqli_num_rows($cek) > 0) {
		$hapus = mysqli_query($coneksi, "DELETE FROM sekolah WHERE id_sekolah='$id_sekolah'") or die(mysqli_error($coneksi));
		if ($hapus) {
			$_SESSION['flash_hapus'] = 'sukses';
		}
		echo '<script>window.location.href = "index.php?page=sekolah";</script>';
		exit();
	} else {
		echo '<script>window.location.href = "index.php?page=sekolah";</script>';
		exit();
	}
}
