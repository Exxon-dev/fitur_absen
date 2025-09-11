<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include('koneksi.php');

// Cek id admin dari session atau GET
if (!isset($_GET['username'])) {
    // Jika tidak ada parameter id, coba dari session
    if (!isset($_SESSION['username'])) {
        header("Location: sign-in.php");
        exit();
    }
    $username = $_SESSION['username'];
} else {
    $username = $_GET['username'];
}

// Inisialisasi variabel error
$error_password = $_SESSION['error_password'] ?? '';
$success = $_SESSION['success'] ?? '';
$form_data = $_SESSION['form_data'] ?? array();

// Hapus data session setelah digunakan
unset($_SESSION['error_password']);
unset($_SESSION['success']);
unset($_SESSION['form_data']);

// Get admin data dengan prepared statement
$stmt = mysqli_prepare($coneksi, "SELECT * FROM users WHERE username = ? AND level = 'admin'");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    echo '<div class="alert alert-warning">ID admin tidak ada dalam database.</div>';
    exit();
} else {
    $data = mysqli_fetch_assoc($result);
}

// Process form submission for account update
if (isset($_POST['submit_akun'])) {
    $username_new = mysqli_real_escape_string($coneksi, $_POST['username']);
    $password = mysqli_real_escape_string($coneksi, $_POST['password']);
    $konfirmasi_password = mysqli_real_escape_string($coneksi, $_POST['konfirmasi_password']);
    $foto_lama = mysqli_real_escape_string($coneksi, $_POST['foto_lama'] ?? 'default.png');

    $profile = $foto_lama;
    $has_error = false;

    // Validasi konfirmasi password
    if ($password !== $konfirmasi_password) {
        $_SESSION['error_password'] = 'Konfirmasi password tidak sesuai';
        $has_error = true;
    }

    // Jika ada error, redirect kembali ke form
    if ($has_error) {
        $_SESSION['form_data'] = $_POST;
        header("Location: index.php?page=profile_admin&username=" . $username);
        exit();
    }

    // Handle file upload
    if (!empty($_FILES['foto']['name'])) {
        $fotoName = $_FILES['foto']['name'];
        $fotoTmp = $_FILES['foto']['tmp_name'];
        $fotoSize = $_FILES['foto']['size'];
        $fotoError = $_FILES['foto']['error'];
        $fotoExt = strtolower(pathinfo($fotoName, PATHINFO_EXTENSION));

        // Ekstensi yang diizinkan
        $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];
        $maxFileSize = 2 * 1024 * 1024; // 2MB

        if (in_array($fotoExt, $allowedExt)) {
            if ($fotoError === UPLOAD_ERR_OK) {
                if ($fotoSize <= $maxFileSize) {
                    $fotoBaru = uniqid('admin_', true) . '.' . $fotoExt;
                    $uploadDir = __DIR__ . '/image/';
                    $uploadPath = $uploadDir . $fotoBaru;

                    // Buat folder jika belum ada
                    if (!file_exists($uploadDir)) {
                        if (!mkdir($uploadDir, 0755, true)) {
                            echo '<script>
                                Swal.fire({
                                    icon: "error",
                                    title: "Error Folder",
                                    text: "Gagal membuat folder upload",
                                    position: "top"
                                });
                            </script>';
                            exit();
                        }
                    }

                    if (move_uploaded_file($fotoTmp, $uploadPath)) {
                        // Hapus foto lama jika bukan default
                        if (!empty($foto_lama) && $foto_lama !== 'default.png') {
                            $oldPath = $uploadDir . $foto_lama;
                            if (file_exists($oldPath)) {
                                unlink($oldPath);
                            }
                        }
                        $profile = $fotoBaru;
                    } else {
                        echo '<script>
                            Swal.fire({
                                icon: "error",
                                title: "Upload Gagal",
                                text: "Gagal menyimpan file",
                                position: "top"
                            });
                        </script>';
                    }
                } else {
                    echo '<script>
                        Swal.fire({
                            icon: "error",
                            title: "File Terlalu Besar",
                            text: "Ukuran file maksimal 2MB",
                            position: "top"
                        });
                    </script>';
                }
            } else {
                $errorMsg = getUploadError($fotoError);
                echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Upload Gagal",
                        text: "' . $errorMsg . '",
                        position: "top"
                    });
                </script>';
            }
        } else {
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Format Tidak Didukung",
                    text: "Hanya menerima file JPG, JPEG, PNG, atau GIF",
                    position: "top"
                });
            </script>';
        }
    }

    // Gunakan prepared statement untuk update
    $stmt = mysqli_prepare($coneksi, "UPDATE users SET 
        profile = ?, 
        username = ?, 
        password = ?
        WHERE username = ? AND level = 'admin'");

    mysqli_stmt_bind_param($stmt, "ssss", $profile, $username_new, $password, $username);

    if (mysqli_stmt_execute($stmt)) {
        // Update session username jika berubah
        if ($username_new !== $username) {
            $_SESSION['username'] = $username_new;
            $username = $username_new; // Update variabel untuk redirect
        }

        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '<script>Swal.fire({icon:"success",title:"Sukses!",text:"Data akun berhasil diupdate",position:"top",showConfirmButton:false,timer:1200,toast:true}); setTimeout(function(){window.location.href="index.php?page=profile_admin&username=' . $username . '&pesan=sukses";},1200);</script>';
        exit();
    } else {
        $err = htmlspecialchars(mysqli_error($coneksi), ENT_QUOTES);
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '<script>Swal.fire({icon:"error",title:"Gagal!",text:"' . $err . '",position:"top",showConfirmButton:false,timer:3000,toast:true});</script>';
    }
}

// Process form submission for admin info update
if (isset($_POST['submit_info'])) {
    $nama = mysqli_real_escape_string($coneksi, $_POST['nama']);
    $no_telp = mysqli_real_escape_string($coneksi, $_POST['no_telp'] ?? '');
    $alamat = mysqli_real_escape_string($coneksi, $_POST['alamat'] ?? '');

    // Gunakan prepared statement untuk update
    $stmt = mysqli_prepare($coneksi, "UPDATE users SET 
        no_telp = ?, 
        alamat = ?, 
        nama = ?
        WHERE username = ? AND level = 'admin'");

    mysqli_stmt_bind_param($stmt, "ssss", $no_telp, $alamat, $nama, $username);

    if (mysqli_stmt_execute($stmt)) {
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '<script>Swal.fire({icon:"success",title:"Sukses!",text:"Informasi admin berhasil diupdate",position:"top",showConfirmButton:false,timer:1200,toast:true}); setTimeout(function(){window.location.href="index.php?page=profile_admin&username=' . $username . '&pesan=sukses";},1200);</script>';
        exit();
    } else {
        $err = htmlspecialchars(mysqli_error($coneksi), ENT_QUOTES);
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '<script>Swal.fire({icon:"error",title:"Gagal!",text:"' . $err . '",position:"top",showConfirmButton:false,timer:3000,toast:true});</script>';
    }
}

function getUploadError($errorCode)
{
    switch ($errorCode) {
        case UPLOAD_ERR_INI_SIZE:
            return "Ukuran file melebihi limit server";
        case UPLOAD_ERR_FORM_SIZE:
            return "Ukuran file melebihi limit form";
        case UPLOAD_ERR_PARTIAL:
            return "File hanya terupload sebagian";
        case UPLOAD_ERR_NO_FILE:
            return "Tidak ada file yang diupload";
        case UPLOAD_ERR_NO_TMP_DIR:
            return "Folder temporary tidak ada";
        case UPLOAD_ERR_CANT_WRITE:
            return "Gagal menulis ke disk";
        case UPLOAD_ERR_EXTENSION:
            return "Upload dihentikan oleh ekstensi PHP";
        default:
            return "Error tidak diketahui (Code: $errorCode)";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Admin - <?php echo htmlspecialchars($data['nama']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <style>
        :root {
            --primary: #3498db;
            --success: #2ecc71;
            --warning: #f39c12;
            --danger: #e74c3c;
            --light: #f8f9fa;
            --dark: #343a40;
        }

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

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: white;
            padding: 15px 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
        }

        .profile-container {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 20px;
        }

        .profile-card {
            background-color: white;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid var(--primary);
            margin: 0 auto 15px;
            display: block;
        }

        .profile-info {
            background-color: white;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .info-group {
            margin-bottom: 15px;
        }

        .info-label {
            font-weight: bold;
            color: var(--dark);
            display: block;
            margin-bottom: 5px;
        }

        .info-value {
            padding: 10px;
            background-color: var(--light);
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: var(--dark);
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn {
            padding: 10px 15px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        .btn-warning {
            background-color: var(--warning);
            color: white;
        }

        .btn-warning:hover {
            background-color: #e67e22;
        }

        .btn-danger {
            background-color: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .btn-info {
            background-color: #17a2b8;
            color: white;
        }

        .btn-info:hover {
            background-color: #138496;
        }

        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .edit-mode {
            display: none;
        }

        #file-input {
            display: none;
        }

        .file-upload {
            display: inline-block;
            padding: 8px 15px;
            background-color: var(--primary);
            color: white;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }

        .file-upload:hover {
            background-color: #2980b9;
        }

        @media (max-width: 768px) {
            body {
                padding-left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .profile-container {
                grid-template-columns: 1fr;
            }
        }

        /* Tambahan untuk form yang sejajar */
        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }

        .form-col {
            flex: 0 0 50%;
            max-width: 50%;
            padding-right: 15px;
            padding-left: 15px;
            box-sizing: border-box;
        }

        .info-value.editable {
            padding: 0;
            background-color: transparent;
            border: none;
        }

        .info-value.editable input,
        .info-value.editable select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            background-color: white;
        }

        .swal2-title-custom {
            font-size: 16px !important;
            color: #333 !important;
        }

        .swal2-popup.swal2-toast {
            padding: 10px 15px !important;
            width: auto !important;
            max-width: 400px !important;
        }

        .error-message {
            color: #e74c3c;
            font-size: 0.85rem;
            margin-top: 5px;
        }

        .is-invalid {
            border-color: #e74c3c !important;
        }

        .password-match {
            color: #28a745;
            font-size: 0.85rem;
            margin-top: 5px;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            justify-content: center;
        }
        .button-group-info {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            justify-content: right;
        }
    </style>
</head>

<body>
    <div class="main-content">
        <h2>Profil Admin</h2>

        <!-- Form for Account Update -->
        <form action="" method="post" enctype="multipart/form-data" id="account-form" onsubmit="return validateAccountForm()">
            <input type="hidden" name="username_old" value="<?php echo htmlspecialchars($username); ?>">
            <input type="hidden" name="foto_lama" value="<?php echo htmlspecialchars($data['profile'] ?? 'default.png'); ?>">

            <div class="profile-container">
                <div class="profile-card">
                    <!-- Tampilkan foto profil -->
                    <div class="profile-picture-container">
                        <?php
                        $imageDir = '/fitur_absen/absensi/pages/image/';
                        $defaultImage = $imageDir . 'default.png';
                        $profileImage = (!empty($data['profile'])) ? $imageDir . $data['profile'] : $defaultImage;

                        echo '<img src="' . htmlspecialchars($profileImage) . '" alt="Profile Picture" class="profile-picture" id="profile-picture">';
                        ?>

                        <div class="file-upload-wrapper">
                            <input type="file" name="foto" id="file-input" accept="image/*" onchange="previewImage(this)">
                            <label for="file-input" class="file-upload">
                                <i class="fas fa-camera"></i> Ganti Foto
                            </label>
                        </div>
                    </div>

                    <div id="view-mode">
                        <h3><?php echo htmlspecialchars($data['nama']); ?></h3>
                        <p>Admin Sistem</p>
                        <p><?php echo htmlspecialchars($data['username']); ?></p>

                        <div class="button-group">
                            <button type="button" class="btn btn-warning" onclick="enableEdit()">
                                <i class="fas fa-edit"></i> Edit Akun
                            </button>
                        </div>
                    </div>

                    <div id="edit-mode" class="edit-mode">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username"
                                value="<?php echo htmlspecialchars($data['username']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password"
                                value="<?php echo htmlspecialchars($data['password']); ?>" required
                                oninput="validatePassword()">
                        </div>

                        <div class="form-group">
                            <label for="konfirmasi_password">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="konfirmasi_password" name="konfirmasi_password"
                                value="<?php echo htmlspecialchars($data['password']); ?>" required
                                oninput="validatePassword()">
                            <div id="passwordError" class="error-message"><?php echo $error_password; ?></div>
                            <div id="passwordMatch" class="password-match"></div>
                        </div>

                        <div class="button-group">
                            <button type="button" class="btn btn-danger" onclick="disableEdit()">Batal</button>
                            <button type="submit" name="submit_akun" class="btn btn-primary">Update Akun</button>
                        </div>
                    </div>
                </div>

                <!-- Form for Admin Info Update -->
                <div class="profile-info">
                    <form action="" method="post" id="info-form">
                        <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">

                        <h3><i class="fas fa-info-circle"></i> Informasi Admin</h3>

                        <div class="form-row">
                            <!-- Left Column -->
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="nama">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="nama" name="nama"
                                        value="<?php echo htmlspecialchars($data['nama']); ?>" required
                                        style="background-color: #e9ecef; border: 1px solid #ced4da;" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="no_telp">Nomor Telepon</label>
                                    <input type="text" class="form-control" id="no_telp" name="no_telp"
                                        value="<?php echo htmlspecialchars($data['no_telp'] ?? ''); ?>" 
                                        placeholder="628xxxxxx" 
                                        style="background-color: #e9ecef; border: 1px solid #ced4da;" readonly>
                                </div>

                                <div class="form-group">
                                    <label class="info-label">Level Akses</label>
                                    <div class="info-value">
                                        <?php echo htmlspecialchars($data['level']); ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <input type="text" class="form-control" id="alamat" name="alamat"
                                        value="<?php echo htmlspecialchars($data['alamat'] ?? ''); ?>"
                                        style="background-color: #e9ecef; border: 1px solid #ced4da;" readonly>
                                </div>

                                <div class="form-group">
                                    <label class="info-label">Status</label>
                                    <div class="info-value">
                                        <?php echo ($data['is_active'] == 1) ? 'Aktif' : 'Non-Aktif'; ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="info-label">Terakhir Login</label>
                                    <div class="info-value">
                                        <?php echo date('d F Y , H:i:s'); ?>
                                    </div>
                                </div>

                                <!-- Tombol Update Informasi Admin -->
                                <div class="button-group-info" style="margin-top: 30px;">
                                    <button type="button" class="btn btn-info" onclick="enableInfoEdit()">
                                        <i class="fas fa-edit"></i> Edit Informasi Admin
                                    </button>
                                    <button type="button" class="btn btn-danger" id="cancel-info-btn" style="display: none;" onclick="disableInfoEdit()">
                                        Batal
                                    </button>
                                    <button type="submit" name="submit_info" class="btn btn-primary" id="update-info-btn" style="display: none;">
                                        Update Informasi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Fungsi untuk preview gambar sebelum upload dengan SweetAlert2
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    // Update preview gambar
                    document.getElementById('profile-picture').src = e.target.result;

                    // Tampilkan notifikasi dengan SweetAlert2
                    const fileName = input.files[0].name;
                    const fileSize = (input.files[0].size / 1024 / 1024).toFixed(2); // dalam MB

                    Swal.fire({
                        title: 'Foto Baru Dipilih',
                        text: `Nama: ${fileName} (${fileSize} MB)`,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 3000,
                        toast: true,
                        background: '#ffffff',
                        customClass: {
                            title: 'swal2-title-custom'
                        }
                    });
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        // Fungsi untuk mengaktifkan mode edit akun
        function enableEdit() {
            document.getElementById('view-mode').style.display = 'none';
            document.getElementById('edit-mode').style.display = 'block';
        }

        // Fungsi untuk menonaktifkan mode edit akun
        function disableEdit() {
            document.getElementById('view-mode').style.display = 'block';
            document.getElementById('edit-mode').style.display = 'none';
        }

        // Fungsi untuk mengaktifkan mode edit informasi admin
        function enableInfoEdit() {
            // Aktifkan input fields untuk informasi admin
            const infoInputs = document.querySelectorAll('.profile-info input[type="text"]');
            infoInputs.forEach(input => {
                if (input.name !== 'level' && input.name !== 'status') {
                    input.style.backgroundColor = 'white';
                    input.style.border = '1px solid #ddd';
                    input.readOnly = false;
                }
            });

            // Tampilkan tombol update dan batal
            document.getElementById('update-info-btn').style.display = 'inline-block';
            document.getElementById('cancel-info-btn').style.display = 'inline-block';

            // Sembunyikan tombol edit informasi
            document.querySelector('.btn-info').style.display = 'none';
        }

        // Fungsi untuk menonaktifkan mode edit informasi admin
        function disableInfoEdit() {
            // Nonaktifkan input fields untuk informasi admin
            const infoInputs = document.querySelectorAll('.profile-info input[type="text"]');
            infoInputs.forEach(input => {
                if (input.name !== 'level' && input.name !== 'status') {
                    input.style.backgroundColor = '#e9ecef';
                    input.style.border = '1px solid #ced4da';
                    input.readOnly = true;
                }
            });

            // Sembunyikan tombol update dan batal
            document.getElementById('update-info-btn').style.display = 'none';
            document.getElementById('cancel-info-btn').style.display = 'none';

            // Tampilkan tombol edit informasi
            document.querySelector('.btn-info').style.display = 'inline-block';
        }

        // Preview gambar saat memilih file
        document.getElementById('file-input').addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    document.getElementById('profile-picture').src = e.target.result;
                }

                reader.readAsDataURL(this.files[0]);
            }
        });

        // Auto-hide alert setelah 5 detik
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);

        function validatePassword() {
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('konfirmasi_password');
            const passwordError = document.getElementById('passwordError');
            const passwordMatch = document.getElementById('passwordMatch');

            const passwordValue = passwordInput.value;
            const confirmPasswordValue = confirmPasswordInput.value;

            if (passwordValue !== confirmPasswordValue) {
                passwordError.textContent = 'Konfirmasi password tidak sesuai';
                passwordMatch.textContent = '';
                passwordInput.classList.add('is-invalid');
                confirmPasswordInput.classList.add('is-invalid');
                return false;
            } else if (passwordValue.length > 0 && confirmPasswordValue.length > 0) {
                passwordError.textContent = '';
                passwordMatch.textContent = 'Password sesuai ✓';
                passwordInput.classList.remove('is-invalid');
                confirmPasswordInput.classList.remove('is-invalid');
                return true;
            } else {
                passwordError.textContent = '';
                passwordMatch.textContent = '';
                passwordInput.classList.remove('is-invalid');
                confirmPasswordInput.classList.remove('is-invalid');
                return false;
            }
        }

        function validateAccountForm() {
            const isPasswordValid = validatePassword();

            if (!isPasswordValid) {
                document.getElementById('konfirmasi_password').focus();

                // Tampilkan pesan error dengan SweetAlert2
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: 'Konfirmasi password tidak sesuai',
                    position: 'top',
                    showConfirmButton: false,
                    timer: 3000,
                    toast: true
                });

                return false;
            }
            return true;
        }

        // Validasi real-time saat pengguna mengetik
        document.getElementById('password').addEventListener('input', validatePassword);
        document.getElementById('konfirmasi_password').addEventListener('input', validatePassword);

        // Jalankan validasi saat halaman dimuat untuk menampilkan error dari server
        document.addEventListener('DOMContentLoaded', function() {
            validatePassword();
        });
    </script>
</body>

</html>