<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include('../../koneksi.php');
session_start(); // Pastikan session_start() ada

if (isset($_POST['submit'])) {
    $id_sekolah     = $_POST['id_sekolah'];
    $nama_sekolah   = $_POST['nama_sekolah'];
    $alamat_sekolah = $_POST['alamat_sekolah'];
    $kepala_sekolah = $_POST['kepala_sekolah'];
    $foto_lama = $_POST['foto_lama'] ?? 'default.png';

    $logo_sekolah = 'default.png'; 

	if (mysqli_num_rows($cek) == 0) {
		$sql = mysqli_query($coneksi, "INSERT INTO sekolah (
        nama_sekolah, 
        alamat_sekolah, 
        kepala_sekolah, 
        logo_sekolah ) 
        VALUES 
        ('$nama_sekolah', 
        '$alamat_sekolah',
        '$kepala_sekolah',
        '$logo_sekolah')")
        or die(mysqli_error($coneksi));
    
		if ($sql) {
            $_SESSION['flash_tambah'] = 'sukses';
            header('Location: ../../index.php?page=sekolah');
            exit();
        }}
    // Handle file upload
    if (!empty($_FILES['logo_sekolah']['name'])) {
        // Define upload directory - relative path dari file proses ini
        $uploadDir = "../../../uploads/";
        
        $fotoName = $_FILES['logo_sekolah']['name'];
        $fotoTmp = $_FILES['logo_sekolah']['tmp_name'];
        $fotoSize = $_FILES['logo_sekolah']['size'];
        $fotoError = $_FILES['logo_sekolah']['error'];
        $fotoExt = strtolower(pathinfo($fotoName, PATHINFO_EXTENSION));

        // Ekstensi yang diizinkan
        $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];
        $maxFileSize = 2 * 1024 * 1024; // 2MB

        if (in_array($fotoExt, $allowedExt)) {
            if ($fotoError === UPLOAD_ERR_OK) {
                if ($fotoSize <= $maxFileSize) {
                    // Create directory if it doesn't exist
                    if (!file_exists($uploadDir)) {
                        if (!mkdir($uploadDir, 0755, true)) {
                            $_SESSION['flash_error'] = "Gagal membuat folder upload";
                            header('Location: ../../index.php?page=sekolah');
                            exit();
                        }
                    }

                    $logo_sekolah = uniqid('logo_', true) . '.' . $fotoExt;
                    $uploadPath = $uploadDir . $logo_sekolah;

                    if (!move_uploaded_file($fotoTmp, $uploadPath)) {
                        $_SESSION['flash_error'] = "Gagal menyimpan file";
                        header('Location: ../../index.php?page=sekolah');
                        exit();
                    }
                } else {
                    $_SESSION['flash_error'] = "Ukuran file maksimal 2MB";
                    header('Location: ../../index.php?page=sekolah');
                    exit();
                }
            } else {
                $_SESSION['flash_error'] = "Error upload file: " . $fotoError;
                header('Location: ../../index.php?page=sekolah');
                exit();
            }
        } else {
            $_SESSION['flash_error'] = "Hanya menerima file JPG, JPEG, PNG, atau GIF";
            header('Location: ../../index.php?page=sekolah');
            exit();
        }
    }
    
    // Cek duplikasi nama sekolah
    $cek = mysqli_query($coneksi, "SELECT * FROM sekolah WHERE nama_sekolah='$nama_sekolah'") 
           or die(mysqli_error($coneksi));

    if (mysqli_num_rows($cek) == 0) {
        $sql = mysqli_query($coneksi, "INSERT INTO sekolah (
            nama_sekolah, 
            alamat_sekolah, 
            kepala_sekolah, 
            logo_sekolah ) 
            VALUES 
            ('$nama_sekolah', 
            '$alamat_sekolah',
            '$kepala_sekolah', 
            '$logo_sekolah')")
            or die(mysqli_error($coneksi));
    
        if ($sql) {
            $_SESSION['flash_tambah'] = 'sukses';
            header('Location: ../../index.php?page=sekolah');
            exit();
        } else {
            // Hapus file yang sudah diupload jika query database gagal
            if ($logo_sekolah !== 'default.png' && file_exists($uploadDir . $logo_sekolah)) {
                unlink($uploadDir . $logo_sekolah);
            }
            $_SESSION['flash_error'] = "Error database: " . mysqli_error($coneksi);
            header('Location: ../../index.php?page=sekolah');
            exit();
        }
    } else {
        // Hapus file yang sudah diupload jika ada duplikasi
        if ($logo_sekolah !== 'default.png' && file_exists($uploadDir . $logo_sekolah)) {
            unlink($uploadDir . $logo_sekolah);
        }
        $_SESSION['flash_duplikat'] = true;
        header('Location: ../../index.php?page=sekolah');
        exit();
    }
} else {
    $_SESSION['flash_error'] = "Form tidak disubmit dengan benar.";
    header('Location: ../../index.php?page=sekolah');
    exit();
}

// Fungsi untuk mendapatkan pesan error upload
function getUploadError($errorCode) {
    $errors = [
        UPLOAD_ERR_INI_SIZE => 'File terlalu besar (melebihi ukuran yang diizinkan server)',
        UPLOAD_ERR_FORM_SIZE => 'File terlalu besar (melebihi ukuran yang diizinkan form)',
        UPLOAD_ERR_PARTIAL => 'File hanya terupload sebagian',
        UPLOAD_ERR_NO_FILE => 'Tidak ada file yang diupload',
        UPLOAD_ERR_NO_TMP_DIR => 'Folder temporary tidak ada',
        UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk',
        UPLOAD_ERR_EXTENSION => 'Upload dihentikan oleh ekstensi PHP'
    ];
    
    return $errors[$errorCode] ?? 'Unknown error';
}