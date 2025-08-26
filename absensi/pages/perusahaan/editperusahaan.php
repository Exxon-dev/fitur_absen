<?php include('koneksi.php'); ?>
<!DOCTYPE html>
<html>

<head>
	<title>editperusahaan</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
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

		.container-custom {
			background-color: #ffffff;
			border-radius: 10px;
			padding: 20px;
			box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
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

		.hapusPerusahaan {
			color: white;
			background-color: #344767;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
			border: none;
			padding: 8px 16px;
			border-radius: 4px;
			transition: all 0.3s ease;
		}

		.hapusPerusahaan:hover {
			background-color: #5a6268;
			color: white;
			transform: translateY(-1px);
			box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
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
	<h2 class="text-left">Edit Perusahaan</h2>
	<div class="main-container container-custom" style="margin-top:20px">

		<?php
		if (isset($_GET['id_perusahaan'])) {
			$id_perusahaan = $_GET['id_perusahaan'];

			$select = mysqli_query($coneksi, "SELECT * FROM perusahaan WHERE id_perusahaan='$id_perusahaan'") or die(mysqli_error($coneksi));

			if (mysqli_num_rows($select) == 0) {
				echo '<div class="alert alert-warning">id_perusahaan tidak ada dalam database.</div>';
				exit();
			} else {
				$data = mysqli_fetch_assoc($select);
			}
		}
		?>

		<?php
		if (isset($_POST['submit'])) {
			$id_perusahaan		 = $_POST['id_perusahaan'];
			$nama_perusahaan	 = $_POST['nama_perusahaan'];
			$pimpinan	 		 = $_POST['pimpinan'];
			$alamat_perusahaan	 = $_POST['alamat_perusahaan'];
			$no_tlp	 			 = $_POST['no_tlp'];

			$sql = mysqli_query($coneksi, "UPDATE perusahaan SET 
			nama_perusahaan			= '$nama_perusahaan',
			pimpinan				= '$pimpinan',
			alamat_perusahaan 		= '$alamat_perusahaan',
			no_tlp					= '$no_tlp' 
			WHERE id_perusahaan='$id_perusahaan'") or die(mysqli_error($coneksi));
			
			if ($sql) {
				echo '<script>alert("Berhasil mengupdate data."); document.location="perusahaan.php";</script>';
			} else {
				echo '<div class="alert alert-warning">Gagal melakukan proses update data.</div>';
			}
		}
		?>

		<form action="" method="post" enctype="multipart/form-data">
			<input type="hidden" name="id_perusahaan" value="<?php echo $data['id_perusahaan']; ?>">
			
			<div class="form-group row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Nama Perusahaan</label>
						<input type="text" name="nama_perusahaan" class="form-control" value="<?php echo $data['nama_perusahaan']; ?>" required>
					</div>
					<div class="form-group">
						<label>Direktur</label>
						<input type="text" name="pimpinan" class="form-control" value="<?php echo $data['pimpinan']; ?>" required>
					</div>
				</div>
				
				<!-- Dua field di kanan -->
				<div class="col-md-6">
					<div class="form-group">
						<label>Alamat Perusahaan</label>
						<input type="text" name="alamat_perusahaan" class="form-control" value="<?php echo $data['alamat_perusahaan']; ?>" required>
					</div>
					<div class="form-group">
						<label>No Telepon</label>
						<input type="text" name="no_tlp" class="form-control" value="<?php echo $data['no_tlp']; ?>" required>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="d-flex flex-wrap justify-content-between align-items-center">
					<!-- Tombol Hapus di kiri -->
					<button type="button" class="btn btn-danger hapusPerusahaan"
						id="btnHapus" data-id="<?php echo $data['id_perusahaan']; ?>">
						HAPUS
					</button>

					<!-- Tombol Kembali dan Simpan di kanan (tapi berdampingan) -->
					<div class="d-flex flex-wrap justify-content-end gap-2">
						<a href="index.php?page=perusahaan" class="btn btn-warning mr-2">KEMBALI</a>
						<input type="submit" name="submit" class="btn btn-primary" value="Update">
					</div>
				</div>
			</div>
		</form>
	</div>
	
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
		// SweetAlert untuk konfirmasi hapus
		document.addEventListener('DOMContentLoaded', function() {
			const deleteBtn = document.getElementById('btnHapus');
			if (deleteBtn) {
				deleteBtn.addEventListener('click', function(e) {
					e.preventDefault();
					const id = this.getAttribute('data-id');
					Swal.fire({
						title: "Apakah Anda yakin?",
						text: "Data yang dihapus tidak dapat dikembalikan!",
						icon: "warning",
						showCancelButton: true,
						confirmButtonColor: "#344767",
						cancelButtonColor: "#3085d6",
						confirmButtonText: "Ya, hapus!",
						cancelButtonText: "Batal"
					}).then((result) => {
						if (result.isConfirmed) {
							window.location.href = `index.php?page=hapusperusahaan&id_perusahaan=${id}`;
						}
					});
				});
			}
		});
	</script>

	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

</body>

</html>