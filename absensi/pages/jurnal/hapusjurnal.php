<?php
include('koneksi.php');
 
if(isset($_GET['id_jurnal'])){
	$id_jurnal = $_GET['id_jurnal'];

	$cek = mysqli_query($coneksi, "SELECT * FROM jurnal WHERE id_jurnal='$id_jurnal'") or die(mysqli_error($coneksi));
	
	if(mysqli_num_rows($cek) > 0){
		$del = mysqli_query($coneksi, "DELETE FROM jurnal WHERE id_jurnal='$id_jurnal'") or die(mysqli_error($coneksi));
		if($del){
			echo '<script>alert("Berhasil menghapus data."); document.location="index.php?page=jurnal";</script>';
		}else{
			echo '<script>alert("Gagal menghapus data."); document.location="index.php?page=jurnal";</script>';
		}
	}else{
		echo '<script>alert("id_jurnal tidak ditemukan di database."); document.location="index.php?page=jurnal";</script>';
	}
}else{
	echo '<script>alert("id_jurnal tidak ditemukan di database."); document.location="index.php?page=jurnal";</script>';
}
 
?>
