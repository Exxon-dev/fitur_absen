<?php include('koneksi.php'); ?>
<?php
		if(isset($_GET['id_jurnal'])){
			$id_jurnal = $_GET['id_jurnal'];

			$select = mysqli_query($coneksi, "SELECT * FROM jurnal WHERE id_jurnal='$id_jurnal'") or die(mysqli_error($coneksi));
			
			if(mysqli_num_rows($select) == 0){
				echo '<div class="alert alert-warning">id_sekolah tidak ada dalam database.</div>';
				exit();
			} else{
				$data = mysqli_fetch_assoc($select);
			}
		}
		?>
		
		<?php
		if(isset($_POST['submit'])){
			$id_jurnal	 = $_POST['id_jurnal'];
			$tanggal	 = $_POST['tanggal'];
			$keterangan	 = $_POST['keterangan'];
            $id_siswa	 = $_POST['id_siswa'];
			
			
			$sql = mysqli_query($coneksi, "UPDATE jurnal SET tanggal='$tanggal',keterangan='$keterangan', id_siswa='$id_siswa' WHERE id_jurnal='$id_jurnal'") or die(mysqli_error($coneksi));
			if($sql){
				   echo '<script>alert("Berhasil menambahkan data."); document.location="../../index.php?page=jurnal";</script>';
			   }else{
				   echo '<div class="alert alert-warning">Gagal melakukan proses tambah data.</div>';
			   }
	        }
 
	         else {
	            echo 'You should select a file to upload !!';
	        }
		
		?>