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
	$nis = $_POST['nis'];
	$nisn = $_POST['nisn'];
	$nama_siswa = $_POST['nama_siswa'];
	$pro_keahlian = $_POST['pro_keahlian'];
	$TL = $_POST['TL'];
	$TTGL = $_POST['TTGL'];
	$id_sekolah = $_POST['id_sekolah'];
	$id_perusahaan = $_POST['id_perusahaan'];
	$tanggal_mulai = $_POST['tanggal_mulai'];
	$tanggal_selesai = $_POST['tanggal_selesai'];
	$id_pembimbing = $_POST['id_pembimbing'];
	$id_guru = $_POST['id_guru'];
	$username = $_POST['username'];
	$password = $_POST['password'];
    $foto_lama        = $_POST['foto_lama'] ?? 'default.jpg';


	$sql = mysqli_query($coneksi, "UPDATE siswa SET nisn='$nisn', nama_siswa='$nama_siswa', pro_keahlian='$pro_keahlian', TL='$TL', TTGL='$TTGL', id_sekolah='$id_sekolah',id_perusahaan='$id_perusahaan', tanggal_mulai='$tanggal_mulai', tanggal_selesai='$tanggal_selesai', id_pembimbing='$id_pembimbing', id_guru='$id_guru', username='$username', password='$password' WHERE id_siswa='$id_siswa'");

	$profile = $foto_lama;

	// Jika ada upload foto baru
	if (!empty($_FILES['foto']['name'])) {
		$fotoName   = $_FILES['foto']['name'];
		$fotoTmp    = $_FILES['foto']['tmp_name'];
		$fotoExt    = pathinfo($fotoName, PATHINFO_EXTENSION);
		$fotoBaru   = uniqid('siswa_') . '.' . $fotoExt;
		$uploadPath = __DIR__ . "/../image/" . $fotoBaru;

		if (move_uploaded_file($fotoTmp, $uploadPath)) {
			// Hapus foto lama jika bukan default
			$oldProfilePath = __DIR__ . "/../image/" . $foto_lama;
			if (!empty($foto_lama) && file_exists($oldProfilePath) && $foto_lama !== 'default.jpg') {
				unlink($oldProfilePath);
			}
			$profile = $fotoBaru;
		}
	}

	$sql = mysqli_query($coneksi, "UPDATE siswa SET 

	profile='$profile', 
	nisn='$nisn', 
	nama_siswa='$nama_siswa', 
	kelas='$kelas', 
	pro_keahlian='$pro_keahlian', 
	TL='$TL', 
	TTGL='$TTGL', 
	id_sekolah='$id_sekolah',
	id_perusahaan='$id_perusahaan', 
	tanggal_mulai='$tanggal_mulai', 
	tanggal_selesai='$tanggal_selesai', 
	id_pembimbing='$id_pembimbing', 
	id_guru='$id_guru', 
	username='$username', 
	password='$password' 
	WHERE 
	id_siswa='$id_siswa'");
	if ($sql) {
		$_SESSION['flash_edit'] = 'sukses';
	}

	header("Location: ../../index.php?page=siswa");
	exit();
} else {
	header("Location: ../../index.php?page=siswa");
	exit();
}
?>