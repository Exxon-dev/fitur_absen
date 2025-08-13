<?php
require_once('../../koneksi.php');
$role = $_SESSION['role'] ?? '';
$id_catatan = $_GET['id_catatan'] ?? '';

if ($role !== 'pembimbing') {
	header('Location: ../../index.php?page=catatan&pesan=gagal_hapus&error='.urlencode('Akses ditolak'));
	exit();
}

if ($id_catatan) {
	// Pastikan id_catatan adalah angka
	if (!is_numeric($id_catatan)) {
		header('Location: ../../index.php?page=catatan&pesan=gagal_hapus&error='.urlencode('ID Catatan tidak valid'));
		exit();
	}
	$del = mysqli_query($coneksi, "DELETE FROM catatan WHERE id_catatan='$id_catatan'");
	if ($del) {
        $_SESSION['flash_hapus'] = 'sukses';
    }
    echo '<script>window.location.href = "../../index.php?page=catatan";</script>';
    exit();
} else {
    echo '<script>window.location.href = "../../index.php?page=catatan";</script>';
    exit();
}
?>