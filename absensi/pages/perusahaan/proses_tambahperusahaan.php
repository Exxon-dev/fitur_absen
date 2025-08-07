<?php
// Aktifkan error reporting di awal file
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
ob_start();

require_once('../../koneksi.php');

// Debug koneksi
if (!$coneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    // Ambil data dari form dengan escape
    $nama_perusahaan    = mysqli_real_escape_string($coneksi, $_POST['nama_perusahaan'] ?? '');
    $pimpinan           = mysqli_real_escape_string($coneksi, $_POST['pimpinan'] ?? '');
    $alamat_perusahaan  = mysqli_real_escape_string($coneksi, $_POST['alamat_perusahaan'] ?? '');
    $no_tlp             = mysqli_real_escape_string($coneksi, $_POST['no_tlp'] ?? '');

    // Validasi input wajib
    if (empty($nama_perusahaan) || empty($alamat_perusahaan)) {
        $_SESSION['flash_error'] = "Nama dan alamat perusahaan harus diisi";
        header('Location: ../../index.php?page=perusahaan');
        exit();
    }

    // Cek apakah perusahaan sudah ada
    $cek = mysqli_query($coneksi, "SELECT * FROM perusahaan WHERE nama_perusahaan='$nama_perusahaan'") or die(mysqli_error($coneksi));

    if (mysqli_num_rows($cek) == 0) {
        // Gunakan prepared statement untuk keamanan
        $stmt = $coneksi->prepare("INSERT INTO perusahaan (nama_perusahaan, pimpinan, alamat_perusahaan, no_tlp) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nama_perusahaan, $pimpinan, $alamat_perusahaan, $no_tlp);

        if ($stmt->execute()) {
            $_SESSION['flash_tambah'] = 'sukses';
        } else {
            $_SESSION['flash_error'] = "Gagal menambahkan data: " . mysqli_error($coneksi);
        }
        $stmt->close();
    } else {
        $_SESSION['flash_duplikat'] = true;
    }

    header('Location: ../../index.php?page=perusahaan');
    exit();
} else {
    $_SESSION['flash_error'] = "Form tidak disubmit dengan benar.";
    header('Location: ../../index.php?page=perusahaan');
    exit();
}
?>
