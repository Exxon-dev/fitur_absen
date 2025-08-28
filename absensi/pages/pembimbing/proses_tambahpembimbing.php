<?php
include('../../koneksi.php');

// Inisialisasi variabel error
$_SESSION['error_username'] = '';
$_SESSION['error_password'] = '';
$_SESSION['success'] = '';
$_SESSION['form_data'] = $_POST;

// Validasi username sudah digunakan
$username = mysqli_real_escape_string($coneksi, $_POST['username']);
$check_username = mysqli_query($coneksi, "SELECT * FROM pembimbing WHERE username = '$username'");

<<<<<<< HEAD
    if (mysqli_num_rows($cek) == 0) {
        $sql = mysqli_query($coneksi, "INSERT INTO pembimbing (
=======
if (!$check_username) {
    $_SESSION['error'] = 'Terjadi kesalahan dalam validasi username: ' . mysqli_error($coneksi);
    header("Location: ../../index.php?page=tambahpembimbing");
    exit();
}

if (mysqli_num_rows($check_username) > 0) {
    $_SESSION['error_username'] = 'Username sudah digunakan';
    header("Location: ../../index.php?page=tambahpembimbing");
    exit();
}

// Validasi password sudah digunakan (jika ingin memeriksa password plaintext)
$password = mysqli_real_escape_string($coneksi, $_POST['password']);
$check_password = mysqli_query($coneksi, "SELECT * FROM pembimbing WHERE password = '$password'");

if (!$check_password) {
    $_SESSION['error'] = 'Terjadi kesalahan dalam validasi password: ' . mysqli_error($coneksi);
    header("Location: ../../index.php?page=tambahpembimbing");
    exit();
}

if (mysqli_num_rows($check_password) > 0) {
    $_SESSION['error_password'] = 'Password sudah digunakan oleh pembimbing lain';
    header("Location: ../../index.php?page=tambahpembimbing");
    exit();
}

// Jika semua validasi passed, simpan data ke database
$id_perusahaan = mysqli_real_escape_string($coneksi, $_POST['id_perusahaan']);
$nama_pembimbing = mysqli_real_escape_string($coneksi, $_POST['nama_pembimbing']);
$no_tlp = mysqli_real_escape_string($coneksi, $_POST['no_tlp']);
$alamat = mysqli_real_escape_string($coneksi, $_POST['alamat']);
$jenis_kelamin = mysqli_real_escape_string($coneksi, $_POST['jenis_kelamin']);

// Perbaikan query cek nama pembimbing
$cek = mysqli_query($coneksi, "SELECT * FROM pembimbing WHERE nama_pembimbing='$nama_pembimbing'") or die(mysqli_error($coneksi));

if (mysqli_num_rows($cek) == 0) {
    $sql = mysqli_query($coneksi, "INSERT INTO pembimbing (
        profile,
>>>>>>> 1ba93e3e1841f0db196d55408850db39c813b6be
        id_perusahaan,
        nama_pembimbing,
        no_tlp,
        alamat,
        jenis_kelamin,
        username, 
        password) 
        VALUES (
        '$id_perusahaan', 
        '$nama_pembimbing', 
        '$no_tlp',
        '$alamat',
        '$jenis_kelamin',
        '$username',
        '$password')") or die(mysqli_error($coneksi));

    if ($sql) {
        $_SESSION['flash_tambah'] = 'sukses';
        unset($_SESSION['form_data']);
        unset($_SESSION['error_nama']);
        unset($_SESSION['error_username']);
        unset($_SESSION['error_password']);
        header('Location: ../../index.php?page=pembimbing');
        exit();
    } else {
        $_SESSION['flash_error'] = 'Terjadi kesalahan: ' . mysqli_error($coneksi);
        header('Location: ../../index.php?page=tambahpembimbing');
        exit();
    }
<<<<<<< HEAD
}
=======
} else {
    $_SESSION['error_nama'] = 'Nama pembimbing sudah digunakan';
    header('Location: ../../index.php?page=tambahpembimbing');
    exit();
}
?>
>>>>>>> 1ba93e3e1841f0db196d55408850db39c813b6be
