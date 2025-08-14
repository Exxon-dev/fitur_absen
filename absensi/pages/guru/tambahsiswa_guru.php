<?php include('koneksi.php'); ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Siswa</title>
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

        .error-message {
            color: red;
            font-size: 0.8em;
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
    <div class="main-container container-custom">
        <h2 class="text-center">Tambah Siswa</h2>
        <hr>
        <form action="" method="POST" onsubmit="return validateForm()">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>NIS</label>
                    <input type="text" name="nis" id="nis" class="form-control" minlength="8" maxlength="12" oninput="validateNIS()">
                    <div id="nisError" class="error-message"></div>
                </div>
                <div class="form-group col-md-3">
                    <label>NISN</label>
                    <input type="text" name="nisn" id="nisn" class="form-control" maxlength="10" minlength="10" oninput="validateNISN()">
                    <div id="nisnError" class="error-message"></div>
                </div>
                <div class="form-group col-md-3">
                    <label>Nama Siswa</label>
                    <input type="text" name="nama_siswa" class="form-control" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Program Keahlian</label>
                    <select name="pro_keahlian" class="form-control" required>
                        <option value="">Pilih Program Keahlian</option>
                        <option value="Multimedia">Multimedia</option>
                        <option value="Rekayasa Perangkat Lunak">Rekayasa Perangkat Lunak</option>
                        <option value="Perkantoran">Perkantoran</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Tempat Lahir</label>
                    <input type="text" name="TL" class="form-control">
                </div>
                <div class="form-group col-md-3">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="TTGL" class="form-control">
                </div>
                <div class="form-group col-md-3">
                    <label>Sekolah</label>
                    <select name="id_sekolah" class="form-control" required>
                        <option value="">Pilih Sekolah</option>
                        <?php
                        $data_sekolah = mysqli_query($coneksi, "SELECT * FROM sekolah");
                        while ($row = mysqli_fetch_array($data_sekolah)) {
                        ?>
                            <option value="<?= htmlspecialchars($row['id_sekolah']); ?>"><?= htmlspecialchars($row['nama_sekolah']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Perusahaan</label>
                    <select name="id_perusahaan" class="form-control" required>
                        <option value="">Pilih Perusahaan</option>
                        <?php
                        $data_perusahaan = mysqli_query($coneksi, "SELECT * FROM perusahaan");
                        while ($row = mysqli_fetch_array($data_perusahaan)) {
                        ?>
                            <option value="<?= htmlspecialchars($row['id_perusahaan']); ?>"><?= htmlspecialchars($row['nama_perusahaan']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Pembimbing</label>
                    <select name="id_pembimbing" class="form-control" required>
                        <option value="">Pilih Pembimbing</option>
                        <?php
                        $data_pembimbing = mysqli_query($coneksi, "SELECT * FROM pembimbing");
                        while ($row = mysqli_fetch_array($data_pembimbing)) {
                        ?>
                            <option value="<?= htmlspecialchars($row['id_pembimbing']); ?>"><?= htmlspecialchars($row['nama_pembimbing']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Guru</label>
                    <select name="id_guru" class="form-control" required>
                        <option value="">Pilih Guru</option>
                        <?php
                        $data_guru = mysqli_query($coneksi, "SELECT * FROM guru");
                        while ($row = mysqli_fetch_array($data_guru)) {
                        ?>
                            <option value="<?= htmlspecialchars($row['id_guru']); ?>"><?= htmlspecialchars($row['nama_guru']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Nomor WhatsApp:</label>
                    <input type="text" name="no_wa" class="form-control" placeholder="628xxx" required>
                </div>
            </div>
            <div class="form-row">
                <div class="col text-right">
                    <a href="index.php?page=tambahsiswa_guru" class="btn btn-warning">KEMBALI</a>
                    <input type="submit" name="submit" class="btn btn-primary" value="SIMPAN">
                </div>
            </div>
            <?php
            if (isset($_POST['submit'])) {
                $nis            = $_POST['nis'];
                $nisn           = $_POST['nisn'];
                $nama_siswa     = $_POST['nama_siswa'];
                $no_wa          = $_POST['no_wa'];
                $pro_keahlian   = $_POST['pro_keahlian'];
                $TL             = $_POST['TL'];
                $TTGL           = $_POST['TTGL'];
                $id_sekolah     = $_POST['id_sekolah'];
                $id_perusahaan  = $_POST['id_perusahaan'];
                $tanggal_mulai  = $_POST['tanggal_mulai'];
                $tanggal_selesai = $_POST['tanggal_selesai'];
                $id_pembimbing  = $_POST['id_pembimbing'];
                $id_guru        = $_POST['id_guru'];
                $username       = $_POST['username'];
                $password       = $_POST['password']; // Sebaiknya di-hash

                // 1. Cek apakah ID siswa atau username sudah ada
                $cek = mysqli_query($coneksi, "SELECT * FROM siswa WHERE nisn='$nisn' OR username='$username'");

                if (!$cek) {
                    echo '<pre>Query error: ' . mysqli_error($coneksi) . '</pre>';
                    exit();
                }

                if (mysqli_num_rows($cek) == 0) {
                    $sql = mysqli_query($coneksi, "INSERT INTO siswa (
                        nis,
                        nisn,
                        nama_siswa,
                        no_wa,
                        pro_keahlian,
                        TL,
                        TTGL,
                        id_sekolah,
                        id_perusahaan,
                        tanggal_mulai,
                        tanggal_selesai,
                        id_pembimbing,
                        id_guru,
                        username,
                        password)
                        VALUES (
                        '$nis',
                        '$nisn',
                        '$nama_siswa',
                        '$no_wa',
                        '$pro_keahlian',
                        '$TL',
                        '$TTGL',
                        '$id_sekolah',
                        '$id_perusahaan',
                        '$tanggal_mulai',
                        '$tanggal_selesai',
                        '$id_pembimbing',
                        '$id_guru',
                        '$username',
                        '$password')");

                    if ($sql) {
                        // Redirect ke halaman absensi_siswa.php
                        header("Location: absensi_siswa.php");
                        exit();
                    } else {
                        echo "Gagal memasukkan data: " . mysqli_error($coneksi);
                    }
                }
            }
            ?>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

    <script>
        function validateNIS() {
            const nisInput = document.getElementById('nis');
            const nisError = document.getElementById('nisError');
            const nisValue = nisInput.value.trim();

            if (nisValue.length < 8 || nisValue.length > 12) {
                nisError.textContent = 'NIS harus terdiri dari 8-12 karakter';
                return false;
            } else {
                nisError.textContent = '';
                return true;
            }
        }

        function validateNISN() {
            const nisnInput = document.getElementById('nisn');
            const nisnError = document.getElementById('nisnError');
            const nisnValue = nisnInput.value.trim();

            if (nisnValue.length !== 10) {
                nisnError.textContent = 'NISN harus terdiri dari 10 karakter';
                return false;
            } else {
                nisnError.textContent = '';
                return true;
            }
        }

        function validateForm() {
            const isNISValid = validateNIS();
            const isNISNValid = validateNISN();

            if (!isNISValid || !isNISNValid) {
                if (!isNISValid) {
                    document.getElementById('nis').focus();
                } else {
                    document.getElementById('nisn').focus();
                }
                return false;
            }
            return true;
        }
    </script>
</body>

</html>