<?php
include('../../koneksi.php');
if (isset($_GET['id_guru'])) {
	$id_guru = $_GET['id_guru'];
	$select = mysqli_query($coneksi, "SELECT * FROM guru WHERE id_guru='$id_guru'") or die(mysqli_error($coneksi));
	if (mysqli_num_rows($select) == 0) {
		echo '<div class="alert alert-warning">id_guru tidak ada dalam database.</div>';
		exit();
	} else {
		$data = mysqli_fetch_assoc($select);
	}
}

if (isset($_POST['submit'])) {
	$id_guru = $_POST['id_guru'];
	$nama_guru = $_POST['nama_guru'];
	$id_sekolah = $_POST['id_sekolah'];
	$username = $_POST['username'];
	$password = $_POST['password'];

	$sql = mysqli_query($coneksi, "UPDATE guru SET nama_guru='$nama_guru', id_sekolah='$id_sekolah', username='$username', password='$password' WHERE id_guru='$id_guru'");
	if ($sql) {
        $_SESSION['flash_edit'] = 'sukses';
    }
    
    header("Location: ../../index.php?page=guru");
    exit();
} else {
    header("Location: ../../index.php?page=guru");
    exit();
}
?>