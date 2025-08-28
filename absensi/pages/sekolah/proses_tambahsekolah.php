<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include('../../koneksi.php');

if (isset($_POST['submit'])) {
<<<<<<< HEAD
	$id_sekolah     = $_POST['id_sekolah'];
	$nama_sekolah   = $_POST['nama_sekolah'];
	$alamat_sekolah = $_POST['alamat_sekolah'];
	$kepala_sekolah = $_POST['kepala_sekolah'];
    $username       = $_POST['username'];
    $password       = $_POST['password'];
    $logo_sekolah   = $_FILES['logo_sekolah']['name'];
=======
    $id_sekolah     = $_POST['id_sekolah'];
    $nama_sekolah   = $_POST['nama_sekolah'];
    $alamat_sekolah = $_POST['alamat_sekolah'];
    $kepala_sekolah = $_POST['kepala_sekolah'];
    $foto_lama = $_POST['foto_lama'] ?? 'default.png';
>>>>>>> 1ba93e3e1841f0db196d55408850db39c813b6be

    $profile = $foto_lama;

<<<<<<< HEAD
	if (mysqli_num_rows($cek) == 0) {
		$sql = mysqli_query($coneksi, "INSERT INTO sekolah (
        nama_sekolah, 
        alamat_sekolah, 
        kepala_sekolah, 
        username, 
        password, 
        logo_sekolah ) 
        VALUES 
        ('$nama_sekolah', 
        '$alamat_sekolah',
        '$kepala_sekolah', 
        '$username', 
        '$password', 
        '$logo_sekolah')")
        or die(mysqli_error($coneksi));
    
		if ($sql) {
            $_SESSION['flash_tambah'] = 'sukses';
            header('Location: ../../index.php?page=sekolah');
            exit();
=======
    // Handle file upload
    if (!empty($_FILES['foto']['name'])) {
        // Define upload directory - use absolute server path
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/fitur_absen/absensi/pages/image/';

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
                    // Create directory if it doesn't exist
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

                    $fotoBaru = uniqid('logo_', true) . '.' . $fotoExt;
                    $uploadPath = $uploadDir . $fotoBaru;

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
>>>>>>> 1ba93e3e1841f0db196d55408850db39c813b6be
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
    
    $cek = mysqli_query($coneksi, "SELECT * FROM sekolah WHERE nama_sekolah='$nama_sekolah'") or die(mysqli_error($coneksi));

    if (mysqli_num_rows($cek) == 0) {
        // Upload file
        if (move_uploaded_file($_FILES["logo_sekolah"]["tmp_name"], $target_file)) {
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
                if (file_exists($target_file)) {
                    unlink($target_file);
                }
                $_SESSION['flash_error'] = "Error database: " . mysqli_error($coneksi);
                header('Location: ../../index.php?page=sekolah');
                exit();
            }
        } else {
            // Debug informasi error upload
            $error = "Gagal upload file. Kemungkinan penyebab: ";
            if (!is_writable($target_dir)) {
                $error .= "Direktori tidak dapat ditulis. ";
            }
            if (!is_dir($target_dir)) {
                $error .= "Direktori tidak valid. ";
            }
            
            // Dapatkan error terakhir dari PHP
            $last_error = error_get_last();
            if ($last_error) {
                $error .= "PHP Error: " . $last_error['message'];
            }
            
            $_SESSION['flash_error'] = $error;
            header('Location: ../../index.php?page=sekolah');
            exit();
        }
    } else {
        $_SESSION['flash_duplikat'] = true;
        header('Location: ../../index.php?page=sekolah');
        exit();
    }
} else {
    $_SESSION['flash_error'] = "Form tidak disubmit dengan benar.";
    header('Location: ../../index.php?page=sekolah');
    exit();
}
?>