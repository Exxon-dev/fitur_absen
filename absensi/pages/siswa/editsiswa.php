<?php include('koneksi.php');
// Pastikan ID siswa ada dalam session
if (!isset($_SESSION['id_siswa'])) {
    header("Location: sign-in.php"); // Arahkan ke halaman login jika belum login
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Siswa</title>
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

        .form-row {
            margin-bottom: 15px;
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
        <div class="text-center">
            <h2>Edit Siswa</h2>
        </div>
        <hr>

        <?php
        if (isset($_GET['id_siswa'])) {
            $id_siswa = $_GET['id_siswa'];
            $select = mysqli_query($coneksi, "SELECT * FROM siswa WHERE id_siswa='$id_siswa'") or die(mysqli_error($coneksi));

            if (mysqli_num_rows($select) == 0) {
                echo '<div class="alert alert-warning">ID siswa tidak ada dalam database.</div>';
                exit();
            } else {
                $data = mysqli_fetch_assoc($select);
            }
        }

        if (isset($_POST['submit'])) {
            $nis               = $_POST['nis'];
            $nisn               = $_POST['nisn'];
            $nama_siswa         = $_POST['nama_siswa'];
            $no_wa              = $_POST['no_wa'];
            $pro_keahlian       = $_POST['pro_keahlian'];
            $TL                 = $_POST['TL'];
            $TTGL               = $_POST['TTGL'];
            $id_sekolah         = $_POST['id_sekolah'];
            $id_perusahaan      = $_POST['id_perusahaan'];
            $tanggal_mulai      = $_POST['tanggal_mulai'];
            $tanggal_selesai    = $_POST['tanggal_selesai'];
            $id_pembimbing      = $_POST['id_pembimbing'];
            $id_guru            = $_POST['id_guru'];
            $username           = $_POST['username'];
            $password           = $_POST['password'];
            $foto_lama          = $_POST['foto_lama'] ?? 'default.jpg';

            $profile = $foto_lama;

            // Jika ada upload foto baru
            if (!empty($_FILES['foto']['name'])) {
                $fotoName   = $_FILES['foto']['name'];
                $fotoTmp    = $_FILES['foto']['tmp_name'];
                $fotoExt    = pathinfo($fotoName, PATHINFO_EXTENSION);
                $fotoBaru   = uniqid('siswa_') . '.' . $fotoExt;
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

            $sql = mysqli_query($coneksi, "UPDATE siswa SET 
            profile='$profile',
            nis='$nis',
            nisn='$nisn', 
            nama_siswa='$nama_siswa', 
            no_wa='$no_wa',
            username='$username', 
            password='$password', 
            kelas='$kelas', 
            pro_keahlian='$pro_keahlian',
            TL='$TL',
            TTGL='$TTGL'
            WHERE id_siswa='$id_siswa'");

            if ($sql) {
                echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                echo '<script>Swal.fire({icon:"success",title:"Sukses!",text:"Data siswa berhasil diupdate",position:"top",showConfirmButton:false,timer:1200,toast:true}); setTimeout(function(){window.location.href="index.php?page=editsiswa&id_siswa=' . $id_siswa . '&pesan=sukses";},1200);</script>';
                exit();
            } else {
                $err = htmlspecialchars(mysqli_error($coneksi), ENT_QUOTES);
                echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                echo '<script>Swal.fire({icon:"error",title:"Gagal!",text:"' . $err . '",position:"top",showConfirmButton:false,timer:3000,toast:true});</script>';
            }
        }
        ?>

        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id_siswa" value="<?php echo $id_siswa; ?>">
            <input type="hidden" name="foto_lama" value="<?php echo $data['profile']; ?>">
            <div class="d-flex justify-content-center mb-3 position-relative" style="width: 100px; height: 100px; margin: auto;">
                <img id="previewFoto" src="http://localhost/fitur_absen/absensi/pages/image/<?php echo $data['profile']; ?>" alt="Foto Siswa" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                <label for="foto" class="position-absolute"
                    style="bottom: 0; right: 0;  background-color: rgba(0, 0, 0, 0.6); border-radius: 100%; padding: 6px; cursor: pointer;">
                    <i class="fa fa-camera text-white"></i>
                </label>
                <input type="file" id="foto" name="foto" style="display: none;">
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>NIS</label>
                    <input type="text" name="nis" class="form-control" value="<?php echo htmlspecialchars($data['nis']); ?>" required>
                </div>
                <div class="form-group col-md-3">
                    <label>NISN</label>
                    <input type="text" name="nisn" class="form-control" value="<?php echo htmlspecialchars($data['nisn']); ?>" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Nama Siswa</label>
                    <input type="text" name="nama_siswa" class="form-control" value="<?php echo htmlspecialchars($data['nama_siswa']); ?>" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Program Keahlian</label>
                    <input name="pro_keahlian" class="form-control" value="<?php echo htmlspecialchars($data['pro_keahlian']); ?>" required>
                    </input>
                </div>
                <div class="form-group col-md-3">
                    <label>Tempat Lahir</label>
                    <input type="text" name="TL" class="form-control" value="<?php echo htmlspecialchars($data['TL']); ?>" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="TTGL" class="form-control" value="<?php echo htmlspecialchars($data['TTGL']); ?>" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Sekolah</label>
                    <select name="id_sekolah" class="form-control" required>

                        <?php
                        $data_sekolah = mysqli_query($coneksi, "SELECT * FROM sekolah");
                        while ($row = mysqli_fetch_array($data_sekolah)) {
                        ?>
                            <option value="<?php echo htmlspecialchars($row['id_sekolah']); ?>" <?php echo ($row['id_sekolah'] == $data['id_sekolah']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($row['nama_sekolah']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Perusahaan</label>
                    <select name="id_perusahaan" class="form-control" required>
                        <?php
                        $data_perusahaan = mysqli_query($coneksi, "SELECT * FROM perusahaan");
                        while ($row = mysqli_fetch_array($data_perusahaan)) {
                        ?>
                            <option value="<?php echo htmlspecialchars($row['id_perusahaan']); ?>" <?php echo ($row['id_perusahaan'] == $data['id_perusahaan']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($row['nama_perusahaan']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control" value="<?php echo htmlspecialchars($data['tanggal_mulai']); ?>" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control" value="<?php echo htmlspecialchars($data['tanggal_selesai']); ?>" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Pembimbing</label>
                    <select name="id_pembimbing" class="form-control" required>
                        <?php
                        $data_pembimbing = mysqli_query($coneksi, "SELECT * FROM pembimbing");
                        while ($row = mysqli_fetch_array($data_pembimbing)) {
                        ?>
                            <option value="<?php echo htmlspecialchars($row['id_pembimbing']); ?>" <?php echo ($row['id_pembimbing'] == $data['id_pembimbing']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($row['nama_pembimbing']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Guru</label>
                    <select name="id_guru" class="form-control" required>
                        <?php
                        $data_guru = mysqli_query($coneksi, "SELECT * FROM guru");
                        while ($row = mysqli_fetch_array($data_guru)) {
                        ?>
                            <option value="<?php echo htmlspecialchars($row['id_guru']); ?>" <?php echo ($row['id_guru'] == $data['id_guru']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($row['nama_guru']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($data['username']); ?>" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" value="<?php echo htmlspecialchars($data['password']); ?>" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Nomor WhatsApp:</label>
                    <input type="text" name="no_wa" class="form-control" value="<?php echo htmlspecialchars($data['no_wa']); ?>" required>
                </div>
            </div>

            <div class="form-group text-right">
                <input type="submit" name="submit" class="btn btn-primary" value="SIMPAN">
            </div>
        </form>
    </div>
                            
    <!-- Tambahkan ini di <head> -->
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