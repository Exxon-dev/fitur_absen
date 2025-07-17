<?php include('koneksi.php'); ?>
<!DOCTYPE html>
<html>
<head>
	<title>editsekolah</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	
</head>
<body>
	
	<div class="container" style="margin-top:20px">
		<h2>Edit Sekolah</h2>
		
		<hr>
		
		<?php
		if(isset($_GET['id_sekolah'])){
			$id_sekolah = $_GET['id_sekolah'];

			$select = mysqli_query($coneksi, "SELECT * FROM sekolah WHERE id_sekolah='$id_sekolah'") or die(mysqli_error($coneksi));
			
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
			$id_sekolah		 = $_POST['id_sekolah'];
			$nama_sekolah	 = $_POST['nama_sekolah'];
			$alamat_sekolah	 = $_POST['alamat_sekolah'];
            $kepala_sekolah	 = $_POST['kepala_sekolah'];
			$logo_sekolah	 = $_FILES['logo_sekolah']['name'];
			
			
			$sql = mysqli_query($coneksi, "UPDATE sekolah SET nama_sekolah='$nama_sekolah',alamat_sekolah='$alamat_sekolah', krpala_sekolah='$kepala_sekolah' WHERE id_sekolah='$id_sekolah'") or die(mysqli_error($coneksi));
			if($sql){
				   echo '<script>alert("Berhasil menambahkan data."); document.location="sekolah.php";</script>';
			   }else{
				   echo '<div class="alert alert-warning">Gagal melakukan proses tambah data.</div>';
			   }
	        }
 
	         else {
	            echo 'You should select a file to upload !!';
	        }
		
		?>
		
		<form action="pages/sekolah/proses_editsekolah.php?id_sekolah=<?php echo $id_sekolah; ?>" method="post"  enctype="multipart/form-data">
			<div class="form-group row">
				<label class="col-sm-2 col-form-label"> Nama Sekolah</label>
				<div class="col-sm-10">
				<input type="hidden" name="id_sekolah" value=<?php echo $_GET['id_sekolah'];?>></<input>
					<input type="text" name="nama_sekolah" class="form-control" value="<?php echo $data['nama_sekolah']; ?>" required>
				</div>
        </div>
			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Alamat  Sekolah</label>
				<div class="col-sm-10">
				<input type="text" name="alamat_sekolah" class="form-control" value="<?php echo $data['alamat_sekolah']; ?>" required>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Kepala Sekolah</label>
				<div class="col-sm-10">
				<input type="text" name="kepala_sekolah" class="form-control" value="<?php echo $data['kepala_sekolah']; ?>" required>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Logo Sekolah</label>
				<div class="col-sm-10">
					<input type="file" name="logo_sekolah" class="form-control-file" accept="image/*" value="<?php echo $data['logo_sekolah'] ?>" >
				</div>
			</div>
			<div class="form-row">
                <div class="col-md-3">
                    <input type="submit" name="submit" class="btn btn-primary" value="SIMPAN">
                </div>
                <div class="col-md-3 offset-md-6 text-center-center">
                    <a href="index.php?page=sekolah" class="btn btn-warning">KEMBALI</a>
                </div>
            </div>
		</form>
		
	</div>
	
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	
</body>
</html>