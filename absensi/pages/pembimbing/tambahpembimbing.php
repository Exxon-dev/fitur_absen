<?php
include('koneksi.php');

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
    <title>Tambah Pembimbing</title>
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
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
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
        <h2 class="text-center">Tambah Pembimbing</h2>
        <hr>
        <form action="pages/pembimbing/proses_tambahpembimbing.php" method="post" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Nama Pembimbing</label>
                    <input type="text" name="nama_pembimbing" class="form-control" 
                           value="<?php echo isset($form_data['nama_pembimbing']) ? htmlspecialchars($form_data['nama_pembimbing']) : ''; ?>" required>
                </div>

                <div class="form-group col-md-4">
                    <label>No. Telepon/HP</label>
                    <input type="text" name="no_tlp" class="form-control" 
                           value="<?php echo isset($form_data['no_tlp']) ? htmlspecialchars($form_data['no_tlp']) : ''; ?>" required>
                </div>

                <div class="form-group col-md-4">
                    <label>Alamat</label>
                    <input type="text" name="alamat" class="form-control" 
                           value="<?php echo isset($form_data['alamat']) ? htmlspecialchars($form_data['alamat']) : ''; ?>" required>
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

                <div class="form-group col-md-4">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki" <?php echo (isset($form_data['jenis_kelamin']) && $form_data['jenis_kelamin'] == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                        <option value="Perempuan" <?php echo (isset($form_data['jenis_kelamin']) && $form_data['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                    </select>
                </div>

                <div class="form-group col-md-2">
                    <label>Username</label>
                    <input type="text" name="username" id="username" class="form-control <?php echo !empty($error_username) ? 'is-invalid' : ''; ?>" 
                           value="<?php echo isset($form_data['username']) ? htmlspecialchars($form_data['username']) : ''; ?>" required>
                    <?php if (!empty($error_username)): ?>
                        <div class="error-message"><?php echo $error_username; ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group col-md-2">
                    <label>Password</label>
                    <input type="password" name="password" id="password" class="form-control <?php echo !empty($error_password) ? 'is-invalid' : ''; ?>"
                            value="<?php echo isset($form_data['password']) ? htmlspecialchars($form_data['password']) : ''; ?>" required>
                    <?php if (!empty($error_password)): ?>
                        <div class="error-message"><?php echo $error_password; ?></div>
                    <?php endif; ?>
                </div>
            </div><br>

            <div class="form-group row">
                <div class="col text-right">
                    <a href="index.php?page=pembimbing" class="btn btn-warning">KEMBALI</a>
                    <input type="submit" name="submit" class="btn btn-primary" value="SIMPAN" onclick="return validateForm()">
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script>
        function validateUsername() {
            const usernameInput = document.getElementById('username');
            const usernameError = document.getElementById('usernameError');
            const usernameValue = usernameInput.value.trim();

            if (usernameValue === '') {
                usernameInput.classList.add('is-invalid');
                return false;
            } else {
                usernameInput.classList.remove('is-invalid');
                return true;
            }
        }

        function validatePassword() {
            const passwordInput = document.getElementById('password');
            const passwordError = document.getElementById('passwordError');
            const passwordValue = passwordInput.value.trim();

            if (passwordValue === '') {
                passwordInput.classList.add('is-invalid');
                return false;
            } else {
                passwordInput.classList.remove('is-invalid');
                return true;
            }
        }

        function validateForm() {
            const isUsernameValid = validateUsername();
            const isPasswordValid = validatePassword();

            return isUsernameValid && isPasswordValid;
        }

        document.getElementById('username').addEventListener('input', validateUsername);
        document.getElementById('password').addEventListener('input', validatePassword);
    </script>
</body>
</html>