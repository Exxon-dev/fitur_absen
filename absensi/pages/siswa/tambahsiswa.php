<?php 
include('koneksi.php');

// Fungsi untuk mengecek apakah NIS sudah ada di database
function cekNisExist($nis, $exclude_id = null) {
    global $coneksi;
    $query = "SELECT * FROM siswa WHERE nis = '$nis'";
    if ($exclude_id) {
        $query .= " AND id_siswa != $exclude_id";
    }
    $result = mysqli_query($coneksi, $query);
    return mysqli_num_rows($result) > 0;
}

// Fungsi untuk mengecek apakah NISN sudah ada di database
function cekNisnExist($nisn, $exclude_id = null) {
    global $coneksi;
    $query = "SELECT * FROM siswa WHERE nisn = '$nisn'";
    if ($exclude_id) {
        $query .= " AND id_siswa != $exclude_id";
    }
    $result = mysqli_query($coneksi, $query);
    return mysqli_num_rows($result) > 0;
}

// Proses form submission
$error_nis = '';
$error_nisn = '';
$success = '';

if (isset($_POST['submit'])) {
    $nis = mysqli_real_escape_string($coneksi, $_POST['nis']);
    $nisn = mysqli_real_escape_string($coneksi, $_POST['nisn']);
    $nama_siswa = mysqli_real_escape_string($coneksi, $_POST['nama_siswa']);
    $pro_keahlian = mysqli_real_escape_string($coneksi, $_POST['pro_keahlian']);
    $TL = mysqli_real_escape_string($coneksi, $_POST['TL']);
    $TTGL = mysqli_real_escape_string($coneksi, $_POST['TTGL']);
    $id_sekolah = mysqli_real_escape_string($coneksi, $_POST['id_sekolah']);
    $id_perusahaan = mysqli_real_escape_string($coneksi, $_POST['id_perusahaan']);
    $tanggal_mulai = mysqli_real_escape_string($coneksi, $_POST['tanggal_mulai']);
    $tanggal_selesai = mysqli_real_escape_string($coneksi, $_POST['tanggal_selesai']);
    $id_pembimbing = mysqli_real_escape_string($coneksi, $_POST['id_pembimbing']);
    $id_guru = mysqli_real_escape_string($coneksi, $_POST['id_guru']);
    $username = mysqli_real_escape_string($coneksi, $_POST['username']);
    $password = mysqli_real_escape_string($coneksi, $_POST['password']);
    $no_wa = mysqli_real_escape_string($coneksi, $_POST['no_wa']);
    
    // Validasi format NIS dan NISN
    $nis_valid = (strlen($nis) >= 8 && strlen($nis) <= 12 && is_numeric($nis));
    $nisn_valid = (strlen($nisn) == 10 && is_numeric($nisn));
    
    if (!$nis_valid) {
        $error_nis = 'NIS harus terdiri dari 8-12 digit angka';
    } elseif (!$nisn_valid) {
        $error_nisn = 'NISN harus terdiri dari 10 digit angka';
    } else {
        // Validasi NIS dan NISN di database
        $nis_exist = cekNisExist($nis);
        $nisn_exist = cekNisnExist($nisn);
        
        if ($nis_exist) {
            $error_nis = 'NIS sudah digunakan';
        } elseif ($nisn_exist) {
            $error_nisn = 'NISN sudah digunakan';
        } else {
            // Jika NIS dan NISN belum ada, simpan data
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $query = "INSERT INTO siswa (nis, nisn, nama_siswa, pro_keahlian, TL, TTGL, id_sekolah, id_perusahaan, tanggal_mulai, tanggal_selesai, id_pembimbing, id_guru, username, password, no_wa) 
                      VALUES ('$nis', '$nisn', '$nama_siswa', '$pro_keahlian', '$TL', '$TTGL', '$id_sekolah', '$id_perusahaan', '$tanggal_mulai', '$tanggal_selesai', '$id_pembimbing', '$id_guru', '$username', '$hashed_password', '$no_wa')";
            
            if (mysqli_query($coneksi, $query)) {
                $success = 'Data siswa berhasil ditambahkan';
                // Reset form values
                $_POST = array();
            } else {
                $error_nis = 'Terjadi kesalahan: ' . mysqli_error($coneksi);
            }
        }
    }
}
?>

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

        .success-message {
            color: green;
            font-size: 1em;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #d4edda;
            border-radius: 5px;
        }

        .is-invalid {
            border-color: red !important;
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
    <h2 class="text-left">Tambah Siswa</h2>
    <div class="main-container container-custom">
        <?php if (!empty($success)): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form action="" method="POST" onsubmit="return validateForm()">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>NIS</label>
                    <input type="text" name="nis" id="nis" class="form-control <?php echo !empty($error_nis) ? 'is-invalid' : ''; ?>" 
                           value="<?php echo isset($_POST['nis']) ? $_POST['nis'] : ''; ?>" 
                           required minlength="8" maxlength="12" oninput="validateNIS()">
                    <div id="nisError" class="error-message"><?php echo $error_nis; ?></div>
                </div>
                <div class="form-group col-md-3">
                    <label>NISN</label>
                    <input type="text" name="nisn" id="nisn" class="form-control <?php echo !empty($error_nisn) ? 'is-invalid' : ''; ?>" 
                           value="<?php echo isset($_POST['nisn']) ? $_POST['nisn'] : ''; ?>" 
                           required maxlength="10" minlength="10" oninput="validateNISN()">
                    <div id="nisnError" class="error-message"><?php echo $error_nisn; ?></div>
                </div>
                <div class="form-group col-md-3">
                    <label>Nama Siswa</label>
                    <input type="text" name="nama_siswa" class="form-control" 
                           value="<?php echo isset($_POST['nama_siswa']) ? $_POST['nama_siswa'] : ''; ?>" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Program Keahlian</label>
                    <select name="pro_keahlian" class="form-control" required>
                        <option value="">Pilih Program Keahlian</option>
                        <option value="Multimedia" <?php echo (isset($_POST['pro_keahlian']) && $_POST['pro_keahlian'] == 'Multimedia') ? 'selected' : ''; ?>>Multimedia</option>
                        <option value="Rekayasa Perangkat Lunak" <?php echo (isset($_POST['pro_keahlian']) && $_POST['pro_keahlian'] == 'Rekayasa Perangkat Lunak') ? 'selected' : ''; ?>>Rekayasa Perangkat Lunak</option>
                        <option value="Perkantoran" <?php echo (isset($_POST['pro_keahlian']) && $_POST['pro_keahlian'] == 'Perkantoran') ? 'selected' : ''; ?>>Perkantoran</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Tempat Lahir</label>
                    <input type="text" name="TL" class="form-control" 
                           value="<?php echo isset($_POST['TL']) ? $_POST['TL'] : ''; ?>" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="TTGL" class="form-control" 
                           value="<?php echo isset($_POST['TTGL']) ? $_POST['TTGL'] : ''; ?>" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Sekolah</label>
                    <select name="id_sekolah" class="form-control" required>
                        <option value="">Pilih Sekolah</option>
                        <?php
                        $data_sekolah = mysqli_query($coneksi, "SELECT * FROM sekolah");
                        while ($row = mysqli_fetch_array($data_sekolah)) {
                            $selected = (isset($_POST['id_sekolah']) && $_POST['id_sekolah'] == $row['id_sekolah']) ? 'selected' : '';
                        ?>
                            <option value="<?php echo htmlspecialchars($row['id_sekolah']); ?>" <?php echo $selected; ?>>
                                <?php echo htmlspecialchars($row['nama_sekolah']); ?>
                            </option>
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
                            $selected = (isset($_POST['id_perusahaan']) && $_POST['id_perusahaan'] == $row['id_perusahaan']) ? 'selected' : '';
                        ?>
                            <option value="<?php echo htmlspecialchars($row['id_perusahaan']); ?>" <?php echo $selected; ?>>
                                <?php echo htmlspecialchars($row['nama_perusahaan']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control" 
                           value="<?php echo isset($_POST['tanggal_mulai']) ? $_POST['tanggal_mulai'] : ''; ?>" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control" 
                           value="<?php echo isset($_POST['tanggal_selesai']) ? $_POST['tanggal_selesai'] : ''; ?>" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Pembimbing</label>
                    <select name="id_pembimbing" class="form-control" required>
                        <option value="">Pilih Pembimbing</option>
                        <?php
                        $data_pembimbing = mysqli_query($coneksi, "SELECT * FROM pembimbing");
                        while ($row = mysqli_fetch_array($data_pembimbing)) {
                            $selected = (isset($_POST['id_pembimbing']) && $_POST['id_pembimbing'] == $row['id_pembimbing']) ? 'selected' : '';
                        ?>
                            <option value="<?php echo htmlspecialchars($row['id_pembimbing']); ?>" <?php echo $selected; ?>>
                                <?php echo htmlspecialchars($row['nama_pembimbing']); ?>
                            </option>
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
                            $selected = (isset($_POST['id_guru']) && $_POST['id_guru'] == $row['id_guru']) ? 'selected' : '';
                        ?>
                            <option value="<?php echo htmlspecialchars($row['id_guru']); ?>" <?php echo $selected; ?>>
                                <?php echo htmlspecialchars($row['nama_guru']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" 
                           value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Nomor WhatsApp:</label>
                    <input type="text" name="no_wa" class="form-control" placeholder="628xxx" 
                           value="<?php echo isset($_POST['no_wa']) ? $_POST['no_wa'] : ''; ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="col text-right">
                    <a href="index.php?page=siswa" class="btn btn-warning">KEMBALI</a>
                    <input type="submit" name="submit" class="btn btn-primary" value="SIMPAN">
                </div>
            </div>
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
                nisInput.classList.add('is-invalid');
                return false;
            } else if (!/^\d+$/.test(nisValue)) {
                nisError.textContent = 'NIS harus berupa angka';
                nisInput.classList.add('is-invalid');
                return false;
            } else {
                nisError.textContent = '';
                nisInput.classList.remove('is-invalid');
                return true;
            }
        }

        function validateNISN() {
            const nisnInput = document.getElementById('nisn');
            const nisnError = document.getElementById('nisnError');
            const nisnValue = nisnInput.value.trim();

            if (nisnValue.length !== 10) {
                nisnError.textContent = 'NISN harus terdiri dari 10 karakter';
                nisnInput.classList.add('is-invalid');
                return false;
            } else if (!/^\d+$/.test(nisnValue)) {
                nisnError.textContent = 'NISN harus berupa angka';
                nisnInput.classList.add('is-invalid');
                return false;
            } else {
                nisnError.textContent = '';
                nisnInput.classList.remove('is-invalid');
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

        // Validasi real-time saat pengguna mengetik
        document.getElementById('nis').addEventListener('input', validateNIS);
        document.getElementById('nisn').addEventListener('input', validateNISN);
    </script>
</body>

</html>