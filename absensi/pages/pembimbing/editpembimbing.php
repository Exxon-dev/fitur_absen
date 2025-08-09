<?php include('koneksi.php'); ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edit Pembimbing</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <style>
        /* Penyesuaian posisi */
        body {
            padding-left: 270px;
            transition: padding-left 0.3s;
            background-color: #f8f9fa;
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

        h2 {
            margin-bottom: 20px;
            color: #007bff;
            text-align: center;
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

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-warning {
            background-color: #ffc107;
            border: none;
        }

        .btn-warning:hover {
            background-color: #e0a800;
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
    <div class="main-container container-custom">
        <h2>Profile Pembimbing</h2>
        <hr>

        <?php
        if (isset($_GET['id_pembimbing'])) {
            $id_pembimbing = $_GET['id_pembimbing'];
            $select = mysqli_query($coneksi, "SELECT * FROM pembimbing WHERE id_pembimbing='$id_pembimbing'") or die(mysqli_error($coneksi));

            if (mysqli_num_rows($select) == 0) {
                echo '<div class="alert alert-warning">ID Pembimbing tidak ada dalam database.</div>';
                exit();
            } else {
                $data = mysqli_fetch_assoc($select);
            }
        }

        if (isset($_POST['submit'])) {
            $id_pembimbing    = $_POST['id_pembimbing'];
            $id_perusahaan    = $_POST['id_perusahaan'];
            $nama_pembimbing  = $_POST['nama_pembimbing'];
            $no_tlp           = $_POST['no_tlp'];
            $alamat           = $_POST['alamat'];
            $jenis_kelamin    = $_POST['jenis_kelamin'];
            $username         = $_POST['username'];
            $password         = $_POST['password'];
            $foto_lama        = $_POST['foto_lama'] ?? 'default.jpg';

            $profile = $foto_lama;

            // Jika ada upload foto baru
            if (!empty($_FILES['foto']['name'])) {
                $fotoName   = $_FILES['foto']['name'];
                $fotoTmp    = $_FILES['foto']['tmp_name'];
                $fotoExt    = pathinfo($fotoName, PATHINFO_EXTENSION);
                $fotoBaru   = uniqid('guru_') . '.' . $fotoExt;
                $uploadPath = __DIR__ . "/../image/" . $fotoBaru;

                if (move_uploaded_file($fotoTmp, $uploadPath)) {
                    // Hapus foto lama jika bukan default
                    $oldProfilePath = __DIR__ . "/../image/" . $foto_lama;
                    if (!empty($foto_lama) && file_exists($oldProfilePath) && $foto_lama !== 'default.jpg') {
                        unlink($oldProfilePath);
                    }
                    $profile = $fotoBaru;
                }
            }

            $sql = mysqli_query($coneksi, "UPDATE pembimbing SET 
                nama_pembimbing = '$nama_pembimbing',
                no_tlp          = '$no_tlp',
                alamat          = '$alamat',
                id_perusahaan   = '$id_perusahaan',
                jenis_kelamin   = '$jenis_kelamin',
                username        = '$username', 
                profile         = '$profile', 
                password        = '$password'
                WHERE id_pembimbing = '$id_pembimbing'")
                or die(mysqli_error($coneksi));

            if ($sql) {
                echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                echo '<script>Swal.fire({icon:"success",title:"Sukses!",text:"Data pembimbing berhasil diupdate",position:"top",showConfirmButton:false,timer:1200,toast:true}); setTimeout(function(){window.location.href="index.php?page=editpembimbing&id_pembimbing=' . $id_pembimbing . '&pesan=sukses";},1200);</script>';
                exit();
            } else {
                $err = htmlspecialchars(mysqli_error($coneksi), ENT_QUOTES);
                echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                echo '<script>Swal.fire({icon:"error",title:"Gagal!",text:"' . $err . '",position:"top",showConfirmButton:false,timer:3000,toast:true});</script>';
            }
        }
        ?>

        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id_pembimbing" value="<?php echo $id_pembimbing; ?>">
            <input type="hidden" name="foto_lama" value="<?php echo $data['profile']; ?>">
            <div class="d-flex justify-content-center mb-3 position-relative" style="width: 100px; height: 100px; margin: auto;">
                <img id="previewFoto" src="http://localhost/fitur_absen/absensi/pages/image/<?php echo $data['profile']; ?>" alt="Foto Guru" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                <label for="foto" class="position-absolute"
                    style="bottom: 0; right: 0;  background-color: rgba(0, 0, 0, 0.6); border-radius: 100%; padding: 6px; cursor: pointer;">
                    <i class="fa fa-camera text-white"></i>
                </label>
                <input type="file" id="foto" name="foto" style="display: none;">
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Nama Pembimbing</label>
                    <input type="text" name="nama_pembimbing" class="form-control"
                        value="<?php echo $data['nama_pembimbing']; ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label>No. Telepon / HP</label>
                    <input type="text" name="no_tlp" class="form-control" value="<?php echo htmlspecialchars($data['no_tlp'] ?? ''); ?>">
                </div>
                <div class="form-group col-md-6">
                    <label>Alamat</label>
                    <input type="text" name="alamat" class="form-control" value="<?php echo $data['alamat']; ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Perusahaan</label>
                    <select name="id_perusahaan" class="form-control" required>
                        <option value="">Perusahaan</option>
                        <?php
                        $data_perusahaan = mysqli_query($coneksi, "SELECT * FROM perusahaan");
                        while ($row = mysqli_fetch_array($data_perusahaan)) {
                        ?>
                            <option value="<?php echo htmlspecialchars($row['id_perusahaan']); ?>"
                                <?php if ($row['id_perusahaan'] == $data['id_perusahaan']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($row['nama_perusahaan']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control">
                        <option value="Laki-laki" <?php if (($data['jenis_kelamin'] ?? '') == 'Laki-laki') echo 'selected'; ?>>Laki-laki</option>
                        <option value="Perempuan" <?php if (($data['jenis_kelamin'] ?? '') == 'Perempuan') echo 'selected'; ?>>Perempuan</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" value="<?php echo $data['username']; ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" value="<?php echo $data['password']; ?>" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col text-right">
                    <input type="submit" name="submit" class="btn btn-primary" value="SIMPAN">
                </div>
            </div>
        </form>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script>
    document.getElementById('foto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const imgPreview = document.getElementById('previewFoto');
        if (file && imgPreview) {
            imgPreview.src = URL.createObjectURL(file);
        }
    });
    </script>

</body>

</html>