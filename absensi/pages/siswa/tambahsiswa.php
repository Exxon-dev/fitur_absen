<?php 
include('koneksi.php');
session_start();

// Mengambil data dari session jika ada (setelah redirect dari proses)
$error_nis = $_SESSION['error_nis'] ?? '';
$error_nisn = $_SESSION['error_nisn'] ?? '';
$error_username = $_SESSION['error_username'] ?? '';
$error_password = $_SESSION['error_password'] ?? '';
$success = $_SESSION['success'] ?? '';
$form_data = $_SESSION['form_data'] ?? array();

// Hapus data session setelah digunakan
unset($_SESSION['error_nis']);
unset($_SESSION['error_nisn']);
unset($_SESSION['error_username']);
unset($_SESSION['error_password']);
unset($_SESSION['success']);
unset($_SESSION['form_data']);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Siswa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <style>
        /* Style yang sama seperti sebelumnya */
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
        
        <form action="pages/siswa/proses_tambahsiswa.php" method="POST" onsubmit="return validateForm()">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>NIS</label>
                    <input type="text" name="nis" id="nis" class="form-control" 
                           value="<?php echo isset($form_data['nis']) ? $form_data['nis'] : ''; ?>" 
                           oninput="generateUsernamePassword()">
                </div>
                <div class="form-group col-md-4">
                    <label>NISN</label>
                    <input type="text" name="nisn" id="nisn" class="form-control <?php echo !empty($error_nisn) ? 'is-invalid' : ''; ?>" 
                           value="<?php echo isset($form_data['nisn']) ? $form_data['nisn'] : ''; ?>" 
                           required maxlength="10" minlength="10" oninput="validateNISN()">
                    <div id="nisnError" class="error-message"><?php echo $error_nisn; ?></div>
                </div>
                <div class="form-group col-md-4">
                    <label>Nama Siswa</label>
                    <input type="text" name="nama_siswa" class="form-control" 
                           value="<?php echo isset($form_data['nama_siswa']) ? $form_data['nama_siswa'] : ''; ?>" required>
                </div>
                <div class="form-group col-md-4">
                    <label>Sekolah</label>
                    <select name="id_sekolah" class="form-control" required>
                        <option value="">Pilih Sekolah</option>
                        <?php
                        $data_sekolah = mysqli_query($coneksi, "SELECT * FROM sekolah");
                        while ($row = mysqli_fetch_array($data_sekolah)) {
                            $selected = (isset($form_data['id_sekolah']) && $form_data['id_sekolah'] == $row['id_sekolah']) ? 'selected' : '';
                        ?>
                            <option value="<?php echo htmlspecialchars($row['id_sekolah']); ?>" <?php echo $selected; ?>>
                                <?php echo htmlspecialchars($row['nama_sekolah']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label>Program Keahlian</label>
                    <input type="text" name="pro_keahlian" class="form-control" 
                           value="<?php echo isset($form_data['pro_keahlian']) ? $form_data['pro_keahlian'] : ''; ?>" required>
                </div>
                <div class="form-group col-md-4">
                    <label>Perusahaan</label>
                    <select name="id_perusahaan" class="form-control" required>
                        <option value="">Pilih Perusahaan</option>
                        <?php
                        $data_perusahaan = mysqli_query($coneksi, "SELECT * FROM perusahaan");
                        while ($row = mysqli_fetch_array($data_perusahaan)) {
                            $selected = (isset($form_data['id_perusahaan']) && $form_data['id_perusahaan'] == $row['id_perusahaan']) ? 'selected' : '';
                        ?>
                            <option value="<?php echo htmlspecialchars($row['id_perusahaan']); ?>" <?php echo $selected; ?>>
                                <?php echo htmlspecialchars($row['nama_perusahaan']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Pembimbing</label>
                    <select name="id_pembimbing" class="form-control" required>
                        <option value="">Pilih Pembimbing</option>
                        <?php
                        $data_pembimbing = mysqli_query($coneksi, "SELECT * FROM pembimbing");
                        while ($row = mysqli_fetch_array($data_pembimbing)) {
                            $selected = (isset($form_data['id_pembimbing']) && $form_data['id_pembimbing'] == $row['id_pembimbing']) ? 'selected' : '';
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
                            $selected = (isset($form_data['id_guru']) && $form_data['id_guru'] == $row['id_guru']) ? 'selected' : '';
                        ?>
                            <option value="<?php echo htmlspecialchars($row['id_guru']); ?>" <?php echo $selected; ?>>
                                <?php echo htmlspecialchars($row['nama_guru']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Username</label>
                    <input type="text" name="username" id="username" class="form-control <?php echo !empty($error_username) ? 'is-invalid' : ''; ?>" 
                           value="<?php echo isset($form_data['username']) ? $form_data['username'] : ''; ?>" required readonly>
                    <div id="usernameError" class="error-message"><?php echo $error_username; ?></div>
                </div>
                <div class="form-group col-md-3">
                    <label>Password</label>
                    <input type="password" name="password" id="password" class="form-control <?php echo !empty($error_password) ? 'is-invalid' : ''; ?>" 
                           value="<?php echo isset($form_data['password']) ? $form_data['password'] : ''; ?>" required readonly>
                    <div id="passwordError" class="error-message"><?php echo $error_password; ?></div>
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
        function generateUsernamePassword() {
            const nisInput = document.getElementById('nis');
            const usernameInput = document.getElementById('username');
            const passwordInput = document.getElementById('password');
            
            const nisValue = nisInput.value.trim();
            
            if (nisValue) {
                usernameInput.value = nisValue;
                passwordInput.value = nisValue;
            } else {
                usernameInput.value = '';
                passwordInput.value = '';
            }
            
            // Tetap jalankan validasi
            validateUsername();
            validatePassword();
        }

        function validateNISN() {
            const nisnInput = document.getElementById('nisn');
            const nisnError = document.getElementById('nisnError');
            const nisnValue = nisnInput.value.trim();

            if (nisnValue.length !== 10) {
                nisnError.textContent = 'NISN harus terdiri dari 10 karakter';
                nisnInput.classList.add('is-invalid');
                return false;
            } else {
                nisnError.textContent = '';
                nisnInput.classList.remove('is-invalid');
                return true;
            }
        }

        function validateUsername() {
            const usernameInput = document.getElementById('username');
            const usernameError = document.getElementById('usernameError');
            const usernameValue = usernameInput.value.trim();

            if (usernameValue === '') {
                usernameError.textContent = 'USERNAME harus diisi';
                usernameInput.classList.add('is-invalid');
                return false;
            } else {
                usernameError.textContent = '';
                usernameInput.classList.remove('is-invalid');
                return true;
            }
        }

        function validatePassword() {
            const passwordInput = document.getElementById('password');
            const passwordError = document.getElementById('passwordError');
            const passwordValue = passwordInput.value.trim();

            if (passwordValue === '') {
                passwordError.textContent = 'PASSWORD harus diisi';
                passwordInput.classList.add('is-invalid');
                return false;
            } else {
                passwordError.textContent = '';
                passwordInput.classList.remove('is-invalid');
                return true;
            }
        }

        function validateForm() {
            const isNISNValid = validateNISN();
            const isUsernameValid = validateUsername();
            const isPasswordValid = validatePassword();

            if (!isNISNValid || !isUsernameValid || !isPasswordValid) {
                if (!isNISNValid) {
                    document.getElementById('nisn').focus();
                } else if (!isUsernameValid) {
                    document.getElementById('username').focus();
                } else {
                    document.getElementById('password').focus();
                }
                return false;
            }
            return true;
        }

        // Validasi real-time saat pengguna mengetik
        document.getElementById('nisn').addEventListener('input', validateNISN);
        document.getElementById('username').addEventListener('input', validateUsername);
        document.getElementById('password').addEventListener('input', validatePassword);
        
        // Jalankan fungsi generate saat halaman dimuat jika NIS sudah ada nilai
        window.onload = function() {
            generateUsernamePassword();
        };
    </script>
</body>

</html>