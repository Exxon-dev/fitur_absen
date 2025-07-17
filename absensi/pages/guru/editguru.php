<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('koneksi.php');
// Cek id_guru dari GET
if (isset($_GET['id_guru'])) {
    $id_guru = $_GET['id_guru'];
    $select = mysqli_query($coneksi, "SELECT * FROM guru WHERE id_guru='$id_guru'");
    if (mysqli_num_rows($select) == 0) {
        // Jika data tidak ditemukan, redirect ke guru.php
        echo '<script>window.location.replace("index.php?page=guru");</script>';
        exit();
    } else {
        $data = mysqli_fetch_assoc($select);
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Guru</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 20px;
            padding: 20px;
            border-radius: 8px;
            background-color: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 20px;
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
    </style>
</head>

<body>
    <div class="container">
        <h2>Edit Guru</h2>
        <hr>



        <?php
        // Proses update
        if (isset($_POST['submit'])) {
            $id_guru = $_POST['id_guru'];
            $nama_guru = $_POST['nama_guru'];
            $id_sekolah = $_POST['id_sekolah'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $sql = mysqli_query($coneksi, "UPDATE guru SET nama_guru='$nama_guru', id_sekolah='$id_sekolah', username='$username', password='$password' WHERE id_guru='$id_guru'");
            if ($sql) {
                echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                echo '<script>Swal.fire({icon:"success",title:"Sukses!",text:"Data guru berhasil diupdate",position:"top",showConfirmButton:false,timer:1200,toast:true}); setTimeout(function(){window.location.href="index.php?page=editguru&id_guru=' . $id_guru . '&pesan=sukses";},1200);</script>';
                exit();
            } else {
                $err = htmlspecialchars(mysqli_error($coneksi),ENT_QUOTES);
                echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                echo '<script>Swal.fire({icon:"error",title:"Gagal!",text:"' . $err . '",position:"top",showConfirmButton:false,timer:3000,toast:true});</script>';
            }
        }
        ?>
        <?php if (isset($data) && $data): ?>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id_guru" value="<?php echo $id_guru; ?>">
            <div class="form-row">
                <div class="form-group col-md-5">
                    <label>Nama Guru</label>
                    <input type="text" name="nama_guru" class="form-control" value="<?php echo htmlspecialchars($data['nama_guru'] ?? ''); ?>" required>
                </div>
                <div class="form-group col-md-5">
                    <label>ID Sekolah</label>
                    <input type="text" name="id_sekolah" class="form-control" value="<?php echo htmlspecialchars($data['id_sekolah'] ?? ''); ?>" required>
                </div>
                <div class="form-group col-md-5">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($data['username'] ?? ''); ?>" required>
                </div>
                <div class="form-group col-md-5">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" value="<?php echo htmlspecialchars($data['password'] ?? ''); ?>" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col text-left">
                    <input type="submit" name="submit" class="btn btn-primary" value="SIMPAN">
                </div>
            </div>
        </form>
        <?php else: ?>
            <div class="alert alert-warning">Data guru tidak ditemukan.</div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.15.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</body>

</html>