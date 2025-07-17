<?php include('../../koneksi.php'); ?>
<?php
		if(isset($_GET['id_catatan'])){
			$id_catatan = $_GET['id_catatan'];

			$select = mysqli_query($coneksi, "SELECT * FROM catatan WHERE id_catatan='$id_catatan'") or die(mysqli_error($coneksi));
			
			if(mysqli_num_rows($select) == 0){
				echo '<div class="alert alert-warning">id_sekolah tidak ada dalam database.</div>';
				exit();
			}else{
				$data = mysqli_fetch_assoc($select);
			}
		}
		?>
		
		<?php
		if(isset($_POST['submit'])){
			$id_catatan	     = $_POST['id_catatan'];
			$id_pembimbing	 = $_POST['id_pembimbing'];
			$catatan	     = $_POST['catatan'];
            $id_jurnal   	 = $_POST['id_jurnal'];
			
			
			$sql = mysqli_query($coneksi, "UPDATE catatan SET id_pembimbing='$id_pembimbing',catatan='$catatan', id_jurnal='$id_jurnal' WHERE id_catatan='$id_catatan'") or die(mysqli_error($coneksi));
			if($sql){
				   echo '<script>alert("Berhasil menambahkan data."); document.location="../../index.php?page=catatan";</script>';
			   }else{
				   echo '<div class="alert alert-warning">Gagal melakukan proses tambah data.</div>';
			   }
	        }
 
	         else {
	            echo 'You should select a file to upload !!';
	        }
		
		?>