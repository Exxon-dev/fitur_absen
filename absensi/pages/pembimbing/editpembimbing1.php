<?php
include('koneksi.php');
if (isset($_SESSION['id_pembimbing'])) {
    header("Location: sign-in.php");
    exit();
}

// Proses update data pembimbing
if (isset($_POST['submit'])) {
    $id_pembimbing      = $_POST['id_pembimbing'];
    $nama_pembimbing    = $_POST['nama_pembimbing'];
    $username           = $_POST['username'];
    $password           = $_POST['password'];

    $sql = mysqli_query($coneksi, "UPDATE pembimbing SET 
    nama_pembimbing = '$nama_pembimbing', 
    username        = '$username', 
    password        = '$password' 
    WHERE 
    id_pembimbing   ='$id_pembimbing'");

    if ($sql) {
        header('Location: index.php?page=pembimbing&pesan=sukses');
        exit();
    } else {
        $err = urlencode(mysqli_error($coneksi));
        header('Location: index.php?page=pembimbing&pesan=gagal&error=' . $err);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Detail Pembimbing</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        <h2 class="text-center">Edit Pembimbing</h2>
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
        ?>

        <form action="pages/pembimbing/proses_editpembimbing.php" method="post">
            <input type="hidden" name="id_pembimbing" value="<?php echo $id_pembimbing; ?>">
        <div class="row">
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
                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-control">
                    <option value="Laki-laki" <?php if (($data['jenis_kelamin'] ?? '') == 'Laki-laki') echo 'selected'; ?>>Laki-laki</option>
                    <option value="Perempuan" <?php if (($data['jenis_kelamin'] ?? '') == 'Perempuan') echo 'selected'; ?>>Perempuan</option>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $data['username']; ?>"
                    required>
            </div>
            <div class="form-group col-md-6">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $data['password']; ?>"
                    required>
            </div>
            <div class="form-group row">
                <div class="col text-left">
                    <button type="button" class="btn btn-danger" id="btnHapus"
                        data-id="<?php echo $data['id_pembimbing']; ?>">Hapus</button>
                </div>
                <div class="col text-right">
                    <a href="index.php?page=pembimbing" class="btn btn-warning">Kembali</a>
                    <input type="submit" name="submit" class="btn btn-primary" value="SIMPAN">
                </div>
            </div>
        </div>
        </form>

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
                            confirmButtonColor: "#d33",
                            cancelButtonColor: "#3085d6",
                            confirmButtonText: "Ya, hapus!",
                            cancelButtonText: "Batal"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = `index.php?page=hapuspembimbing&id_pembimbing=${id}`;
                            }
                        });
                    });
                }
            });
        </script>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</body>

</html>