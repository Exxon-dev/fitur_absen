<?php include('koneksi.php'); ?>
<!DOCTYPE html>
<html>

<head>
	<title>Tambah Perusahaan</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
		integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link rel="stylesheet" href="style.css">
	<style>
		body {
			padding-left: 270px;
			transition: padding-left 0.3s;
			background-color: #f8f9fa;
		}

		h2 {
			color: #007bff;
		}

		.main-container {
			margin-top: 20px;
			margin-right: 20px;
			margin-left: 0;
			width: auto;
			max-width: none;
		}

		.form-control {
			border: none;
			border-bottom: 2px solid #007bff;
			border-radius: 0;
			box-shadow: none;
		}

		.form-control:focus {
			border-color: #0056b3;
			box-shadow: none;
		}

		/* Style asli */
		.container-custom {
			background-color: #ffffff;
			border-radius: 10px;
			padding: 20px;
			box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
		}

		.btn-custom {
			margin-top: 20px;
		}

		@media (max-width: 991px) {
			body {
				padding-left: 0;
			}

			.main-container {
				margin-right: 15px;
				margin-left: 15px;
			}
		}
	</style>
</head>

<body>
	<div class="main-container container-custom" style="margin-top:20px">
		<h2 class="text-center">Tambah Perusahaan</h2>
		<hr>
		<form action="pages/perusahaan/proses_tambahperusahaan.php" method="post" class="tes" enctype="multipart/form-data">
			<div class="form-group row">
				<!-- Dua field di kiri -->
				<div class="col-md-6">
					<div class="form-group">
						<label>Nama Perusahaan</label>
						<input type="text" name="nama_perusahaan" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Direktur</label>
						<input type="text" name="pimpinan" class="form-control" required>
					</div>
				</div>
				
				<!-- Dua field di kanan -->
				<div class="col-md-6">
					<div class="form-group">
						<label>Alamat Perusahaan</label>
						<input type="text" name="alamat_perusahaan" class="form-control" required>
					</div>
					<div class="form-group">
						<label>No Telepon</label>
						<input type="text" name="no_tlp" class="form-control" required>
					</div>
				</div>
			</div>

			<hr>
			
			<!-- Tombol aksi -->
			<div class="form-group row btn-custom">
				<div class="col text-right">
					<a href="index.php?page=perusahaan" class="btn btn-warning mr-2">KEMBALI</a>
					<input type="submit" name="submit" class="btn btn-primary" value="SIMPAN">
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