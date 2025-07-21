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

        .form-row {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Edit Siswa</h2>
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
            $kelas              = $_POST['kelas'];
            $pro_keahlian       = $_POST['pro_keahlian'];
            $TTL                = $_POST['TTL'];
            $id_sekolah         = $_POST['id_sekolah'];
            $id_perusahaan      = $_POST['id_perusahaan'];
            $tanggal_mulai      = $_POST['tanggal_mulai'];
            $tanggal_selesai    = $_POST['tanggal_selesai'];
            $id_pembimbing      = $_POST['id_pembimbing'];
            $id_guru            = $_POST['id_guru'];
            $username           = $_POST['username'];
            $password           = $_POST['password'];

            $sql = mysqli_query($coneksi, "UPDATE siswa SET 
    nis='$nis',
    nisn='$nisn', 
    nama_siswa='$nama_siswa', 
    no_wa='$no_wa',
    username='$username', 
    password='$password', 
    kelas='$kelas', 
    pro_keahlian='$pro_keahlian',
    TTL='$TTL'
WHERE id_siswa='$id_siswa'");

            if ($sql) {
                echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                echo '<script>Swal.fire({icon:"success",title:"Sukses!",text:"Data siswa berhasil diupdate",position:"top",showConfirmButton:false,timer:1200,toast:true}); setTimeout(function(){window.location.href="index.php?page=editsiswa&id_siswa=' . $id_siswa . '&pesan=sukses";},1200);</script>';
                exit();
            } else {
                $err = htmlspecialchars(mysqli_error($coneksi),ENT_QUOTES);
                echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                echo '<script>Swal.fire({icon:"error",title:"Gagal!",text:"' . $err . '",position:"top",showConfirmButton:false,timer:3000,toast:true});</script>';
            }
        }
        ?>

        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id_siswa" value="<?php echo $id_siswa; ?>">

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
                    <label>Kelas</label>
                    <select name="kelas" class="form-control" required>
                        <option value="<?php echo htmlspecialchars($data['kelas']); ?>"><?php echo htmlspecialchars($data['kelas']); ?></option>
                        <option value="12 RPL A">12 RPL A</option>
                        <option value="12 RPL B">12 RPL B</option>
                        <option value="12 RPL C">12 RPL C</option>
                        <option value="12 ELIND A">12 ELIND A</option>
                        <option value="12 ELIND B">12 ELIND B</option>
                        <option value="12 ELIND C">12 ELIND C</option>
                        <option value="12 MEKA A">12 MEKA A</option>
                        <option value="12 MEKA B">12 MEKA B</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Program Keahlian</label>
                    <select name="pro_keahlian" class="form-control" required>
                        <option value="<?php echo htmlspecialchars($data['pro_keahlian']); ?>"><?php echo htmlspecialchars($data['pro_keahlian']); ?></option>
                        <option value="Elektronika">Elektronika</option>
                        <option value="Perangkat Lunak">Perangkat Lunak</option>
                        <option value="Mekatronika">Mekatronika</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Tempat Tanggal Lahir</label>
                    <input type="text" name="TTL" class="form-control" value="<?php echo htmlspecialchars($data['TTL']); ?>" required>
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

            <div class="form-group">
                <input type="submit" name="submit" class="btn btn-primary" value="SIMPAN">
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</body>

</html>