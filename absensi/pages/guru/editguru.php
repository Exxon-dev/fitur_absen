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
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

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
        <h2 class="text-center">Edit Guru</h2>
        <hr>
        <?php
        if (isset($_POST['submit'])) {
            $id_guru = $_GET['id_guru'];

            // Ambil data guru lama dari database
            $getGuru = mysqli_query($coneksi, "
                SELECT guru.*, sekolah.nama_sekolah 
                FROM guru 
                INNER JOIN sekolah ON guru.id_sekolah = sekolah.id_sekolah 
                WHERE guru.id_guru = '$id_guru'
            ");
            $data = mysqli_fetch_assoc($getGuru);

            $nama_guru = $_POST['nama_guru'];
            $nip = $_POST['nip'];
            $jenis_kelamin = $_POST['jenis_kelamin'];
            $alamat = $_POST['alamat'];
            $no_tlp = $_POST['no_tlp'];
            $id_sekolah = $_POST['id_sekolah'];
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Default profile lama
            $profile = $data['profile'] ?? 'default.jpg';

            // Jika ada upload foto baru
            if (!empty($_FILES['foto']['name'])) {
                $fotoName = $_FILES['foto']['name'];
                $fotoTmp = $_FILES['foto']['tmp_name'];
                $fotoExt = pathinfo($fotoName, PATHINFO_EXTENSION);
                $fotoBaru = uniqid('guru_') . '.' . $fotoExt;
                $uploadPath = __DIR__ . "/../image/" . $fotoBaru;

                if (move_uploaded_file($fotoTmp, $uploadPath)) {
                    echo "<pre>";
                    echo "✅ File berhasil di-upload ke: $uploadPath\n";

                    // Debug foto lama
                    $oldProfilePath = __DIR__ . "/../image/" . $data['profile'];
                    echo "Foto lama: " . $data['profile'] . "\n";
                    echo "Path lengkap foto lama: $oldProfilePath\n";
                    echo "Apakah file ada? " . (file_exists($oldProfilePath) ? "YA" : "TIDAK") . "\n";
                    echo "Apakah bukan default.jpg? " . ($data['profile'] !== 'default.jpg' ? "YA" : "TIDAK") . "\n";

                    // Coba hapus
                    if (!empty($data['profile']) && file_exists($oldProfilePath) && $data['profile'] !== 'default.jpg') {
                        if (unlink($oldProfilePath)) {
                            echo "✅ Foto lama berhasil dihapus.\n";
                        } else {
                            echo "❌ Gagal menghapus foto lama.\n";
                        }
                    } else {
                        echo "⚠️ Tidak menghapus karena tidak memenuhi syarat.\n";
                    }
                    echo "</pre>";

                    $profile = $fotoBaru;
                }
            }

            // Update ke database
            $sql = mysqli_query($coneksi, "UPDATE guru SET 
                nama_guru='$nama_guru', 
                nip='$nip', 
                jenis_kelamin='$jenis_kelamin', 
                alamat='$alamat', 
                no_tlp='$no_tlp', 
                id_sekolah='$id_sekolah', 
                username='$username', 
                password='$password',
                profile='$profile'
                WHERE id_guru='$id_guru'");

            if ($sql) {
                echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                echo '<script>Swal.fire({icon:"success",title:"Sukses!",text:"Data guru berhasil diupdate",position:"top",showConfirmButton:false,timer:1200,toast:true}); setTimeout(function(){window.location.href="index.php?page=editguru&id_guru=' . $id_guru . '&pesan=sukses";},1200);</script>';
                exit();
            } else {
                $err = htmlspecialchars(mysqli_error($coneksi), ENT_QUOTES);
                echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                echo '<script>Swal.fire({icon:"error",title:"Gagal!",text:"' . $err . '",position:"top",showConfirmButton:false,timer:3000,toast:true});</script>';
            }
        }
        ?>


        <?php if (isset($data) && $data): ?>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id_guru" value="<?php echo $id_guru; ?>">
                <div class="d-flex justify-content-center mb-3 position-relative" style="width: 100px; height: 100px; margin: auto;">
                    <img src="/fitur_absen/absensi/pages/image/<?php echo $data['profile'] ?? 'default.jpg'; ?>" alt="Foto Guru" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                    <label for="foto" class="position-absolute"
                        style="bottom: 0; right: 0;  background-color: rgba(0, 0, 0, 0.6); border-radius: 100%; padding: 6px; cursor: pointer;">
                        <i class="fa fa-camera text-white"></i>
                    </label>
                    <input type="file" id="foto" name="foto" style="display: none;">
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Nama Guru</label>
                        <input type="text" name="nama_guru" class="form-control" value="<?php echo htmlspecialchars($data['nama_guru'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>NIP</label>
                        <input type="text" name="nip" class="form-control" value="<?php echo htmlspecialchars($data['nip'] ?? ''); ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control">
                            <option value="Laki-laki" <?php if (($data['jenis_kelamin'] ?? '') == 'Laki-laki') echo 'selected'; ?>>Laki-laki</option>
                            <option value="Perempuan" <?php if (($data['jenis_kelamin'] ?? '') == 'Perempuan') echo 'selected'; ?>>Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Alamat</label>
                        <input type="text" name="alamat" class="form-control" value="<?php echo htmlspecialchars($data['alamat'] ?? ''); ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label>No. Telepon / HP</label>
                        <input type="text" name="no_tlp" class="form-control" value="<?php echo htmlspecialchars($data['no_tlp'] ?? ''); ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Nama Sekolah</label>
                        <select name="id_sekolah" class="form-control" required>
                            <?php
                            $querySekolah = mysqli_query($coneksi, "SELECT * FROM sekolah");
                            while ($sekolah = mysqli_fetch_assoc($querySekolah)) {
                                $selected = ($data['id_sekolah'] == $sekolah['id_sekolah']) ? 'selected' : '';
                                echo "<option value='{$sekolah['id_sekolah']}' $selected>{$sekolah['nama_sekolah']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($data['username'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" value="<?php echo htmlspecialchars($data['password'] ?? ''); ?>" required>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col text-right">
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