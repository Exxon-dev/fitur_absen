<?php
include('koneksi.php');

if (isset($_GET['id_pembimbing'])) {
	$id_pembimbing = $_GET['id_pembimbing'];

	$cek = mysqli_query($coneksi, "SELECT * FROM pembimbing WHERE id_pembimbing='$id_pembimbing'") or die(mysqli_error($coneksi));

	if (mysqli_num_rows($cek) > 0) {
		$hapus = mysqli_query($coneksi, "DELETE FROM pembimbing WHERE id_pembimbing='$id_pembimbing'") or die(mysqli_error($coneksi));
		if ($hapus) {
			$_SESSION['flash_hapus'] = 'sukses';
		}
		echo '<script>window.location.href = "index.php?page=pembimbing&pesan=sukses_hapus";</script>';
		exit();
	} else {
		echo '<script>window.location.href = "index.php?page=pembimbing&pesan=gagal";</script>';
		exit();
	}
}
?>