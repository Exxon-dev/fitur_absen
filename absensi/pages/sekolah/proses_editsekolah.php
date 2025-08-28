<?php include('../../koneksi.php'); ?>
<?php

if (isset($_POST['submit'])) {
<<<<<<< HEAD
	$id_sekolah = $_POST['id_sekolah'];
	$nama_sekolah = $_POST['nama_sekolah'];
	$alamat_sekolah = $_POST['alamat_sekolah'];
	$kepala_sekolah = $_POST['kepala_sekolah'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$logo_sekolah = $_FILES['logo_sekolah']['name'];
=======
    $id_sekolah = $_POST['id_sekolah'];
    $nama_sekolah = $_POST['nama_sekolah'];
    $alamat_sekolah = $_POST['alamat_sekolah'];
    $kepala_sekolah = $_POST['kepala_sekolah']; 
    
    // Ambil data lama untuk mendapatkan nama file logo
    $query = mysqli_query($coneksi, "SELECT logo_sekolah FROM sekolah WHERE id_sekolah='$id_sekolah'");
    $data = mysqli_fetch_assoc($query);
    $logo_lama = $data['logo_sekolah'];
    
    // Jika ada file logo baru yang diupload
    if (!empty($_FILES['logo_sekolah']['name'])) {
        $target_dir = "../../../uploads";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        
        $logo_sekolah = basename($_FILES["logo_sekolah"]["name"]);
        $target_file = $target_dir . $logo_sekolah;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["logo_sekolah"]["tmp_name"]);
        if ($check === false) {
            $_SESSION['flash_error'] = "File bukan gambar.";
            header("Location: ../../index.php?page=sekolah");
            exit();
        }
        
        // Check file size (max 2MB)
        if ($_FILES["logo_sekolah"]["size"] > 2000000) {
            $_SESSION['flash_error'] = "Ukuran file terlalu besar. Maksimal 2MB.";
            header("Location: ../../index.php?page=sekolah");
            exit();
        }
        
        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $_SESSION['flash_error'] = "Hanya file JPG, JPEG, PNG & GIF yang diizinkan.";
            header("Location: ../../index.php?page=sekolah");
            exit();
        }
        
        // Check if file already exists
        if (file_exists($target_file)) {
            // Jika file sudah ada, tambahkan timestamp ke nama file
            $logo_sekolah = pathinfo($logo_sekolah, PATHINFO_FILENAME) . '_' . time() . '.' . $imageFileType;
            $target_file = $target_dir . $logo_sekolah;
        }
        
        // Upload file baru
        if (move_uploaded_file($_FILES["logo_sekolah"]["tmp_name"], $target_file)) {
            // Hapus file lama jika ada
            if (!empty($logo_lama) && file_exists($target_dir . $logo_lama)) {
                unlink($target_dir . $logo_lama);
            }
        } else {
            $_SESSION['flash_error'] = "Terjadi kesalahan saat mengupload file.";
            header("Location: ../../index.php?page=sekolah");
            exit();
        }
    } else {
        // Jika tidak ada file baru, gunakan logo lama
        $logo_sekolah = $logo_lama;
    }
>>>>>>> 1ba93e3e1841f0db196d55408850db39c813b6be

    $sql = mysqli_query($coneksi, "UPDATE sekolah SET 
    nama_sekolah='$nama_sekolah',
    alamat_sekolah='$alamat_sekolah', 
    kepala_sekolah='$kepala_sekolah', 
    logo_sekolah='$logo_sekolah'
    WHERE 
    id_sekolah='$id_sekolah'")
        or die(mysqli_error($coneksi));

<<<<<<< HEAD
	$sql = mysqli_query($coneksi, "UPDATE sekolah SET 
	nama_sekolah='$nama_sekolah',
	alamat_sekolah='$alamat_sekolah', 
	kepala_sekolah='$kepala_sekolah', 
	username='$username', 
	password='$password', 
	logo_sekolah='$logo_sekolah'
	WHERE 
	id_sekolah='$id_sekolah'")
		or die(mysqli_error($coneksi));
=======
    if ($sql) {
        $_SESSION['flash_edit'] = 'sukses';
    } else {
        $_SESSION['flash_error'] = mysqli_error($coneksi);
    }
>>>>>>> 1ba93e3e1841f0db196d55408850db39c813b6be

    header("Location: ../../index.php?page=sekolah");
    exit();
} else {
    header("Location: ../../index.php?page=sekolah");
    exit();
}
?>