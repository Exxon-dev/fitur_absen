<?php include('../../koneksi.php'); ?>
<?php
if (isset($_POST['submit'])) {
	$id_pembimbing = $_POST['id_pembimbing'];
	$nama_pembimbing = $_POST['nama_pembimbing'];
	$username = $_POST['username'];
	$password = $_POST['password'];


	$cek = mysqli_query($coneksi, "SELECT * FROM pembimbing WHERE id_pembimbing='id_pembimbing'") or die(mysqli_error($coneksi));

	if (mysqli_num_rows($cek) == 0) {
		$sql = mysqli_query($coneksi, "INSERT INTO pembimbing(id_pembimbing, nama_pembimbing, username, password) VALUES('$id_pembimbing', '$nama_pembimbing', '$username','$password')") or die(mysqli_error($coneksi));

		if ($sql) {
			// Sukses insert, redirect ke halaman pembimbing.php dengan pesan sukses
			header('Location: ../../index.php?page=pembimbing&pesan=sukses');
			exit();
		} else {
			// Gagal insert, redirect ke halaman siswa.php dengan pesan gagal
			$err = urlencode(mysqli_error($coneksi));
			header('Location: ../../index.php?page=pembimbing&pesan=gagal&error=' . $err);
			exit();
		}
	} else {
		// Data sudah ada, redirect ke halaman pembimbing.php dengan pesan duplikat
		header('Location: ../../index.php?page=pembimbing&pesan=duplikat');
		exit();
	}
}
?>