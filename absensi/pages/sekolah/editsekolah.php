<?php include('koneksi.php'); ?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>editsekolah</title>
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
		.hapusSekolah {
            color: white;
            /* Text putih */
            background-color: #344767;
            /* Warna abu-abu Bootstrap */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            /* Shadow */
            border: none;
            /* Hilangkan border */
            padding: 8px 16px;
            /* Padding yang sesuai */
            border-radius: 4px;
            /* Sedikit rounded corners */
            transition: all 0.3s ease;
            /* Efek transisi halus */
        }

        .hapusSekolah:hover {
            background-color: #5a6268;
            /* Warna lebih gelap saat hover */
            color: white;
            /* Tetap putih saat hover */
            transform: translateY(-1px);
            /* Sedikit efek angkat */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
            /* Shadow lebih besar saat hover */
        }
		
		.file-info {
			font-size: 0.875rem;
			color: #6c757d;
			margin-top: 5px;
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
<h2 class="text-left">Edit Sekolah</h2>
	<div class="main-container container-custom" style="margin-top:20px">
		<?php
		if (isset($_GET['id_sekolah'])) {
			$id_sekolah = $_GET['id_sekolah'];

			$select = mysqli_query($coneksi, "SELECT * FROM sekolah WHERE id_sekolah='$id_sekolah'") or die(mysqli_error($coneksi));

			if (mysqli_num_rows($select) == 0) {
				echo '<div class="alert alert-warning">id_sekolah tidak ada dalam database.</div>';
				exit();
			} else {
				$data = mysqli_fetch_assoc($select);
			}
		}
		if (isset($_POST['submit'])) {
			$id_sekolah		 = $_POST['id_sekolah'];
			$nama_sekolah	 = $_POST['nama_sekolah'];
			$alamat_sekolah	 = $_POST['alamat_sekolah'];
			$kepala_sekolah	 = $_POST['kepala_sekolah'];
			$username		 = $_POST['username'];
			$password		 = $_POST['password'];
			$logo_sekolah	 = $_FILES['logo_sekolah']['name'];


			$sql = mysqli_query($coneksi, "UPDATE sekolah SET 
			nama_sekolah='$nama_sekolah',
			alamat_sekolah='$alamat_sekolah', 
			kepala_sekolah='$kepala_sekolah',
			username='$username',
			password='$password', 
			logo_sekolah='$logo_sekolah' 
			WHERE 
			id_sekolah='$id_sekolah'")
				or die(mysqli_error($coneksi));

			if ($sql) {
				echo '<script>alert("Berhasil menambahkan data."); document.location="sekolah.php";</script>';
			} else {
				echo '<div class="alert alert-warning">Gagal melakukan proses tambah data.</div>';
			}
		}

		?>
		<form action="pages/sekolah/proses_editsekolah.php?id_sekolah=<?php echo $id_sekolah; ?>" method="post" enctype="multipart/form-data">
			<input type="hidden" name="id_sekolah" value="<?php echo $data['id_sekolah']; ?>">
			
			<div class="form-group row">
                <div class="col-sm-6">
                    <label>Nama Sekolah</label>
                    <input type="text" name="nama_sekolah" class="form-control" value="<?php echo $data['nama_sekolah']; ?>" required>
                </div>
                <div class="col-sm-6">
                    <label>Kepala Sekolah</label>
                    <input type="text" name="alamat_sekolah" class="form-control" value="<?php echo $data['alamat_sekolah']; ?>" required>
                </div>
            </div>
            
            <div class="form-group row">
                <div class="col-sm-6">
                    <label>Alamat Sekolah</label>
                    <input type="text" name="kepala_sekolah" class="form-control" value="<?php echo $data['kepala_sekolah']; ?>" required>
                </div>

                <div class="col-sm-6">
                    <label>Logo Sekolah</label>
                    <input type="file" name="logo_sekolah" id="logo_sekolah" class="form-control-file" accept="image/*" >
                    <div class="file-info">Maksimal ukuran file: 2MB. Format yang diterima: JPG, PNG, GIF.</div>
                    <div id="preview" class="mt-2" style="display:none;">
                        <img id="preview-image" src="#" alt="Preview Logo" style="max-width: 200px; max-height: 200px;">
                    </div>
                    <div id="error-message" class="text-danger mt-2" style="display:none;"></div>
                </div>
            </div>
			<div class="form-row">
				<div class="col text-left">
					<button type="button" class="btn btn-secondary" id="btnHapus"
						data-id="<?php echo $data['id_sekolah']; ?>">Hapus</button>
				</div>
				<div class="col text-right">
					<a href="index.php?page=sekolah" class="btn btn-warning">KEMBALI</a>
					<input type="submit" name="submit" class="btn btn-primary" value="UPDATE">
				</div>
			</div>
		</form>
	</div>

	
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
						confirmButtonColor: "#6c757d",
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
		
		// Preview image sebelum upload
		document.getElementById('logo_sekolah').addEventListener('change', function(e) {
			const preview = document.getElementById('preview');
			const previewImage = document.getElementById('preview-image');
			
			if (this.files && this.files[0]) {
				const reader = new FileReader();
				
				reader.onload = function(e) {
					previewImage.src = e.target.result;
					preview.style.display = 'block';
				}
				
				reader.readAsDataURL(this.files[0]);
			} else {
				preview.style.display = 'none';
			}
		});
	</script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

</body>

</html>