<?php include('koneksi.php'); ?>
<!DOCTYPE html>
<html>
<head>
	<title>editperusahaan</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	
</head>
<body>
	
	<div class="container" style="margin-top:20px">
		<h2>Edit Perusahaan</h2>
		
		<hr>
		
		<?php
		if(isset($_GET['id_perusahaan'])){
			$id_perusahaan = $_GET['id_perusahaan'];
			
			$select = mysqli_query($coneksi, "SELECT * FROM perusahaan WHERE id_perusahaan='$id_perusahaan'") or die(mysqli_error($coneksi));
			
			if(mysqli_num_rows($select) == 0){
				echo '<div class="alert alert-warning">id_perusahaan tidak ada dalam database.</div>';
				exit();
			}else{
				$data = mysqli_fetch_assoc($select);
			}
		}
		?>
		
		<?php
		if(isset($_POST['submit'])){
			$id_perusahaan		 = $_POST['id_perusahaan'];
			$nama_perusahaan	 = $_POST['nama_perusahaan'];
			$alamat_perusahaan	 = $_POST['alamat_perusahaan'];
			
			
			$sql = mysqli_query($coneksi, "UPDATE perusahaan SET nama_perusahaan='$nama_perusahaan',alamat_perusahaan='$alamat_perusahaan' WHERE id_perusahaan='$id_perusahaan'") or die(mysqli_error($coneksi));
			if($sql){
				   echo '<script>alert("Berhasil menambahkan data."); document.location="perusahaan.php";</script>';
			   }else{
				   echo '<div class="alert alert-warning">Gagal melakukan proses tambah data.</div>';
			   }
	        }
		
		?>
		
		<form action="pages/perusahaan/proses_editperusahaan.php?id_perusahaan=<?php echo $id_perusahaan; ?>" method="post"  enctype="multipart/form-data">
			<div class="form-group row">
				<label class="col-sm-2 col-form-label"> Nama Perusahaan</label>
				<div class="col-sm-10">
				<input type="hidden" name="id_perusahaan" value=<?php echo $_GET['id_perusahaan'];?>></<input>
					<input type="text" name="nama_perusahaan" class="form-control" value="<?php echo $data['nama_perusahaan']; ?>" required>
				</div>
        </div>
			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Alamat  Perusahaan</label>
				<div class="col-sm-10">
				<input type="text" name="alamat_perusahaan" class="form-control" value="<?php echo $data['alamat_perusahaan']; ?>" required>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-2 col-form-label">&nbsp;</label>
				<div class="col-sm-10">
					<input type="submit" name="submit" class="btn btn-primary" value="SIMPAN">
					<a href="index.php?page=perusahaan" class="btn btn-warning">KEMBALI</a>
				</div>
			</div>
		</form>
		
	</div>
	
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	
</body>
</html>