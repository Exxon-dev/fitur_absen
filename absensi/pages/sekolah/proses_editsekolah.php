<?php include('../../koneksi.php'); ?>
<?php
if (isset($_GET['id_sekolah'])) {
	$id_sekolah = $_GET['id_sekolah'];

	$select = mysqli_query($coneksi, "SELECT * FROM sekolah WHERE id_sekolah='$id_sekolah'") or die(mysqli_error($coneksi));

	if (mysqli_num_rows($select) == 0) {
		echo '<div class="alert alert-warning">id_sekolah tidak ada dalam database.</div>';
		exit();
	} else {
		$data = mysqli_fetch_assoc($select);
	}
}
?>

<?php
if (isset($_POST['submit'])) {
	$id_sekolah = $_POST['id_sekolah'];
	$nama_sekolah = $_POST['nama_sekolah'];
	$alamat_sekolah = $_POST['alamat_sekolah'];
	$kepala_sekolah = $_POST['kepala_sekolah'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$logo_sekolah = $_FILES['logo_sekolah']['name'];


	$sql = mysqli_query($coneksi, "UPDATE sekolah SET nama_sekolah='$nama_sekolah',alamat_sekolah='$alamat_sekolah', kepala_sekolah='$kepala_sekolah', username='$username', password='$password', logo_sekolah='$logo_sekolah' WHERE id_sekolah='$id_sekolah'") or die(mysqli_error($coneksi));
	if ($sql) {
        $_SESSION['flash_edit'] = 'sukses';
    }
    
    header("Location: ../../index.php?page=sekolah");
    exit();
} else {
    header("Location: ../../index.php?page=sekolah");
    exit();
}

?>