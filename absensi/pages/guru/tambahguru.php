<?php 
include 'koneksi.php';

// Mengambil data dari session jika ada (setelah redirect dari proses)
$error_username = $_SESSION['error_username'] ?? '';
$error_password = $_SESSION['error_password'] ?? '';
$success = $_SESSION['success'] ?? '';
$form_data = $_SESSION['form_data'] ?? array();

// Hapus data session setelah digunakan
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
    <title>Tambah Guru</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="assets/css/custom.css">

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

        select.form-control {
            border-radius: 2px;
            padding: 6px 12px;
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
    <h2 class="text-left">Tambah Guru</h2>
    <div class="main-container container-custom">
        <form action="pages/guru/proses_tambahguru.php" method="post" onsubmit="return validateForm()">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Nama Guru</label>
                    <input type="text" name="nama_guru" class="form-control" 
                           value="<?php echo isset($form_data['nama_guru']) ? $form_data['nama_guru'] : ''; ?>" required>
                </div>
                <div class="form-group col-md-4">
                    <label>Nip</label>
                    <input type="text" name="nip" class="form-control" 
                           value="<?php echo isset($form_data['nip']) ? $form_data['nip'] : ''; ?>" required>
                </div>
                <div class="form-group col-md-4">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control">
                        <option value="">Jenis Kelamin</option>
                        <option value="Laki-laki" <?php if (($form_data['jenis_kelamin'] ?? '') == 'Laki-laki') echo 'selected'; ?>>Laki-laki</option>
                        <option value="Perempuan" <?php if (($form_data['jenis_kelamin'] ?? '') == 'Perempuan') echo 'selected'; ?>>Perempuan</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label>Alamat</label>
                    <input type="text" name="alamat" class="form-control" 
                           value="<?php echo isset($form_data['alamat']) ? $form_data['alamat'] : ''; ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>No. Telepon / HP</label>
                    <input type="text" name="no_tlp" class="form-control" 
                           value="<?php echo isset($form_data['no_tlp']) ? $form_data['no_tlp'] : ''; ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>Sekolah</label>
                    <select name="id_sekolah" class="form-control" required>
                        <option value="">Pilih Sekolah</option>
                        <?php
                        $querySekolah = mysqli_query($coneksi, "SELECT * FROM sekolah");
                        while ($sekolah = mysqli_fetch_assoc($querySekolah)) {
                            $selected = (isset($form_data['id_sekolah']) && $form_data['id_sekolah'] == $sekolah['id_sekolah']) ? 'selected' : '';
                            echo "<option value='{$sekolah['id_sekolah']}' $selected>{$sekolah['nama_sekolah']}</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <div class="form-group col-md-4">
                    <label>Username</label>
                    <input type="text" name="username" id="username" class="form-control <?php echo !empty($error_username) ? 'is-invalid' : ''; ?>" 
                           value="<?php echo isset($form_data['username']) ? $form_data['username'] : ''; ?>" required>
                    <div id="usernameError" class="error-message"><?php echo $error_username; ?></div>
                </div>
                <div class="form-group col-md-4">
                    <label>Password</label>
                    <input type="password" name="password" id="password" class="form-control <?php echo !empty($error_password) ? 'is-invalid' : ''; ?>" required>
                    <div id="passwordError" class="error-message"><?php echo $error_password; ?></div>
                </div>
            </div>
            <div class="form-row">
                <div class="col text-right">
                    <a href="index.php?page=guru" class="btn btn-warning">KEMBALI</a>
                    <input type="submit" name="submit" class="btn btn-primary" value="SIMPAN">
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function validateUsername() {
            const usernameInput = document.getElementById('username');
            const usernameError = document.getElementById('usernameError');
            const usernameValue = usernameInput.value.trim();

            // Hanya memvalidasi bahwa username tidak kosong
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

            // Hanya memvalidasi bahwa password tidak kosong
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
            const isUsernameValid = validateUsername();
            const isPasswordValid = validatePassword();

            if (!isUsernameValid || !isPasswordValid) {
                if (!isUsernameValid) {
                    document.getElementById('username').focus();
                } else {
                    document.getElementById('password').focus();
                }
                return false;
            }
            return true;
        }

        // Validasi real-time saat pengguna mengetik
        document.getElementById('username').addEventListener('input', validateUsername);
        document.getElementById('password').addEventListener('input', validatePassword);
    </script>
</body>

</html>