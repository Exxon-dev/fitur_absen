<?php include('koneksi.php'); ?>
<!DOCTYPE html>
<html>

<head>
	<title>tambahperusahaan</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
		integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link rel="stylesheet" href="style.css">
</head>

<body>

	<div class="container" style="margin-top:20px">
		<h2>Tambah Perusahaan</h2>
		<hr>
		<form action="pages/perusahaan/proses_tambahperusahaan.php" method="post" class="tes"
			enctype="multipart/form-data">
			<div class="form-group row">
				<label class="col-sm-2 col-form-label"> ID Perusahaan</label>
				<div class="col-sm-10">
					<input type="text" name="id_perusahaan" class="form-control" size="4" required>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Nama Perusahaan</label>
				<div class="col-sm-10">
					<input type="text" name="nama_perusahaan" class="form-control" size="4" required>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Alamat Perusahaan</label>
				<div class="col-sm-10">
					<input type="text" name="alamat_perusahaan" class="form-control" size="4" required>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-3">
					<input type="submit" name="submit" class="btn btn-primary" value="SIMPAN">
				</div>
				<div class="col-md-3 offset-md-6 text-center-center">
					<a href="index.php?page=perusahaan" class="btn btn-warning">KEMBALI</a>
				</div>
			</div>
		</form>
	</div>

	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
		integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
		crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
		integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
		crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
		integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
		crossorigin="anonymous"></script>

</body>

</html>