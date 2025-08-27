<?php include('koneksi.php'); ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Sekolah</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Penyesuaian posisi */
        body {
            padding-left: 270px;
            transition: padding-left 0.3s;
            background-color: #f8f9fa;
        }

        h2 {
            color: #007bff;
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

        .main-container {
            margin-top: 20px;
            margin-right: 20px;
            margin-left: 0;
            width: auto;
            max-width: none;
        }

        /* Style asli */
        .container-custom {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
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
        
        /* Tambahan kecil untuk perbaikan tampilan */
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .btn {
            min-width: 120px;
        }
    </style>
</head>

<body>
    <h2 class="text-left ml-3">Tambah Sekolah</h2>
    <div class="main-container container-custom">
        <form action="pages/sekolah/proses_tambahsekolah.php" method="post" enctype="multipart/form-data" id="formTambahSekolah">
            <input type="hidden" name="id_sekolah" class="form-control" size="4">
            
            <div class="form-group row">
                <div class="col-sm-6">
                    <label>Nama Sekolah</label>
                    <input type="text" name="nama_sekolah" class="form-control" required>
                </div>
                <div class="col-sm-6">
                    <label>Kepala Sekolah</label>
                    <input type="text" name="kepala_sekolah" class="form-control" required>
                </div>
            </div>
            
            <div class="form-group row">
                <div class="col-sm-12">
                    <label>Alamat Sekolah</label>
                    <input type="text" name="alamat_sekolah" class="form-control" required>
                </div>
            </div>
            
            <div class="form-group row">
                <div class="col-sm-12">
                    <label>Logo Sekolah</label>
                    <input type="file" name="logo_sekolah" id="logo_sekolah" class="form-control-file" accept="image/*" required>
                    <div class="file-info">Maksimal ukuran file: 2MB. Format yang diterima: JPG, PNG, GIF.</div>
                    <div id="preview" class="mt-2" style="display:none;">
                        <img id="preview-image" src="#" alt="Preview Logo" style="max-width: 200px; max-height: 200px;">
                    </div>
                    <div id="error-message" class="text-danger mt-2" style="display:none;"></div>
                </div>
            </div>
            
            <div class="form-group row mt-4">
                <div class="col-sm-12 text-right">
                    <a href="index.php?page=sekolah" class="btn btn-warning">KEMBALI</a>
                    <button type="submit" name="submit" class="btn btn-primary">SIMPAN</button>
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

    <script>
        // Preview image sebelum upload
        document.getElementById('logo_sekolah').addEventListener('change', function(e) {
            const preview = document.getElementById('preview');
            const previewImage = document.getElementById('preview-image');
            const errorMessage = document.getElementById('error-message');
            const file = this.files[0];
            
            // Sembunyikan pesan error
            errorMessage.style.display = 'none';
            
            // Validasi ukuran file (max 2MB)
            if (file && file.size > 2 * 1024 * 1024) {
                errorMessage.textContent = 'Ukuran file terlalu besar. Maksimal 2MB.';
                errorMessage.style.display = 'block';
                this.value = '';
                preview.style.display = 'none';
                return;
            }
            
            // Validasi tipe file
            const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (file && !validTypes.includes(file.type)) {
                errorMessage.textContent = 'Format file tidak didukung. Harap gunakan JPG, PNG, atau GIF.';
                errorMessage.style.display = 'block';
                this.value = '';
                preview.style.display = 'none';
                return;
            }
            
            if (file) {
                const reader = new FileReader();


                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    preview.style.display = 'block';
                }

                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        });
        
        // Validasi form sebelum submit
        document.getElementById('formTambahSekolah').addEventListener('submit', function(e) {
            const inputs = this.querySelectorAll('input[required]');
            let valid = true;
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    valid = false;
                    input.style.borderBottomColor = '#dc3545';
                } else {
                    input.style.borderBottomColor = '#007bff';
                }
            });
            
            if (!valid) {
                e.preventDefault();
                alert('Harap isi semua field yang wajib diisi.');
            }
        });
    </script>

</body>

</html>