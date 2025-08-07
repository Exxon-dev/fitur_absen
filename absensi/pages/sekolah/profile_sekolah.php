<?php include('koneksi.php'); ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Edit Sekolah</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        crossorigin="anonymous">
    <style>
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
            color: #007bff;
            margin-bottom: 20px;
            text-align: center;
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
        <h2>Profile Sekolah</h2>
        <hr>

        <?php
        if (isset($_GET['id_sekolah'])) {
            $id_sekolah = $_GET['id_sekolah'];
            $select = mysqli_query($coneksi, "SELECT * FROM sekolah WHERE id_sekolah='$id_sekolah'") or die(mysqli_error($coneksi));

            if (mysqli_num_rows($select) == 0) {
                echo '<div class="alert alert-warning">ID sekolah tidak ada dalam database.</div>';
                exit();
            } else {
                $data = mysqli_fetch_assoc($select);
            }
        }

        if (isset($_POST['submit'])) {
            $id_sekolah = $_POST['id_sekolah'];
            $nama_sekolah = $_POST['nama_sekolah'];
            $alamat_sekolah = $_POST['alamat_sekolah'];
            $kepala_sekolah = $_POST['kepala_sekolah'];
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Handle logo$logo_sekolah upload
            $logo_sekolah = $data['logo_sekolah'];
            if (!empty($_FILES['logo_sekolah']['name'])) {
                $target_dir = "../uploads/";
                if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
                $filename = time() . '_' . basename($_FILES["logo_sekolah"]["name"]);
                $target_file = $target_dir . $filename;

                if (move_uploaded_file($_FILES["logo_sekolah"]["tmp_name"], $target_file)) {
                    $logo_sekolah = $target_file;
                }
            }

            $update = mysqli_query($coneksi, "UPDATE sekolah SET 
            nama_sekolah='$nama_sekolah',
            alamat_sekolah='$alamat_sekolah',
            kepala_sekolah='$kepala_sekolah',
            username='$username',
            password='$password',
            logo_sekolah='$logo_sekolah'
            WHERE id_sekolah='$id_sekolah'
        ") or die(mysqli_error($coneksi));

            if ($update) {
                echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                echo '<script>Swal.fire({icon:"success",title:"Sukses!",text:"Data siswa berhasil diupdate",position:"top",showConfirmButton:false,timer:1200,toast:true}); setTimeout(function(){window.location.href="index.php?page=profile_sekolah&id_sekolah=' . $id_sekolah . '&pesan=sukses";},1200);</script>';
                exit();
            } else {
                $err = htmlspecialchars(mysqli_error($coneksi), ENT_QUOTES);
                echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                echo '<script>Swal.fire({icon:"error",title:"Gagal!",text:"' . $err . '",position:"top",showConfirmButton:false,timer:3000,toast:true});</script>';
            }
        }
        ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_sekolah" value="<?php echo $data['id_sekolah']; ?>">

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Nama Sekolah</label>
                <div class="col-sm-15">
                    <input type="text" name="nama_sekolah" class="form-control" value="<?php echo $data['nama_sekolah']; ?>" required>
                </div>
           
                <label class="col-sm-2 col-form-label">Alamat Sekolah</label>
                <div class="col-sm-15">
                    <input type="text" name="alamat_sekolah" class="form-control" value="<?php echo $data['alamat_sekolah']; ?>" required>
                </div>
            
                <label class="col-sm-2 col-form-label">Kepala Sekolah</label>
                <div class="col-sm-15">
                    <input type="text" name="kepala_sekolah" class="form-control" value="<?php echo $data['kepala_sekolah']; ?>" required>
                </div>
        
                <label class="col-sm-2 col-form-label">Username</label>
                <div class="col-sm-15">
                    <input type="text" name="username" class="form-control" value="<?php echo $data['username']; ?>" required>
                </div>
            
                <label class="col-sm-2 col-form-label">Password</label>
                <div class="col-sm-15">
                    <input type="password" name="password" class="form-control" value="<?php echo $data['password']; ?>" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Logo Sekolah</label>
                <div class="col-sm-15">
                    <?php if (!empty($data['logo_sekolah'])): ?>
                        <img src="<?php echo $data['logo_sekolah']; ?>" alt="Logo Sekolah" style="max-height: 80px; display: block; margin-bottom: 10px;">
                    <?php endif; ?>
                    <input type="file" name="logo_sekolah" class="form-control-file" accept="image/*">
                </div>
            </div>
            <br>

            <div class="form-row">
                <div class="col text-right">
                    <input type="submit" name="submit" class="btn btn-primary" value="SIMPAN">
                </div>
            </div>
        </form>
    </div>

</body>

</html>