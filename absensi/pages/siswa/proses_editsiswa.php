<?php include('../../koneksi.php'); ?>
<?php
if (isset($_GET['id_siswa'])) {
	$id_siswa = $_GET['id_siswa'];

	$select = mysqli_query($coneksi, "SELECT * FROM siswa WHERE id_siswa='$id_siswa'") or die(mysqli_error($coneksi));

	if (mysqli_num_rows($select) == 0) {
		echo '<div class="alert alert-warning">id_siswa tidak ada dalam database.</div>';
		exit();
	} else {
		$data = mysqli_fetch_assoc($select);
	}
}
?>

<?php
if (isset($_POST['submit'])) {
	$id_siswa = $_POST['id_siswa'];
	$nisn = $_POST['nisn'];
	$nama_siswa = $_POST['nama_siswa'];
	$kelas = $_POST['kelas'];
	$pro_keahlian = $_POST['pro_keahlian'];
	$TTL = $_POST['TTL'];
	$id_sekolah = $_POST['id_sekolah'];
	$id_perusahaan = $_POST['id_perusahaan'];
	$tanggal_mulai = $_POST['tanggal_mulai'];
	$tanggal_selesai = $_POST['tanggal_selesai'];
	$id_pembimbing = $_POST['id_pembimbing'];
	$id_guru = $_POST['id_guru'];
	$username = $_POST['username'];
	$password = $_POST['password'];

	$sql = mysqli_query($coneksi, "UPDATE siswa SET nisn='$nisn', nama_siswa='$nama_siswa', kelas='$kelas', pro_keahlian='$pro_keahlian', TTL='$TTL', id_sekolah='$id_sekolah',id_perusahaan='$id_perusahaan', tanggal_mulai='$tanggal_mulai', tanggal_selesai='$tanggal_selesai', id_pembimbing='$id_pembimbing', id_guru='$id_guru', username='$username', password='$password' WHERE id_siswa='$id_siswa'");
	if ($sql) {
		// Sukses update, redirect ke halaman siswa.php dengan pesan sukses
		header('Location: ../../index.php?page=siswa&pesan=sukses');
		exit();
	} else {
		// Gagal update, redirect ke halaman siswa.php dengan pesan gagal
		$err = urlencode(mysqli_error($coneksi));
		header('Location: ../../index.php?page=siswa&pesan=gagal&error=' . $err);
		exit();
	}
} else {
	// Data sudah ada, redirect ke halaman siswa.php dengan pesan duplikat
	header('Location: ../../index.php?page=siswa&pesan=duplikat');
	exit();
}
?>