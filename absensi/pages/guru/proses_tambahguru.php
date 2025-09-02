<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include('../../koneksi.php');

if (isset($_POST['submit'])) {
    // Escape semua input
    $nama_guru = mysqli_real_escape_string($coneksi, $_POST['nama_guru']);
    $nip = mysqli_real_escape_string($coneksi, $_POST['nip']);
    $jenis_kelamin = mysqli_real_escape_string($coneksi, $_POST['jenis_kelamin']);
    $alamat = mysqli_real_escape_string($coneksi, $_POST['alamat']);
    $no_tlp = mysqli_real_escape_string($coneksi, $_POST['no_tlp']);
    $id_sekolah = mysqli_real_escape_string($coneksi, $_POST['id_sekolah']);
    $id_perusahaan = mysqli_real_escape_string($coneksi, $_POST['id_perusahaan']);
    $username = mysqli_real_escape_string($coneksi, $_POST['username']);
    $password = mysqli_real_escape_string($coneksi, $_POST['password']);

    // Simpan data form ke session untuk kembali mengisi form jika error
    $_SESSION['form_data'] = $_POST;

    // Validasi username sudah digunakan
    $check_username = mysqli_query($coneksi, "SELECT * FROM guru WHERE username = '$username'");
    
    if (mysqli_num_rows($check_username) > 0) {
        $_SESSION['error_username'] = 'Username sudah digunakan';
        header('Location: ../../index.php?page=tambahguru');
        exit();
    }

    // Validasi password (HANYA memeriksa apakah tidak kosong)
    if (empty($password)) {
        $_SESSION['error_password'] = 'Password harus diisi';
        header('Location: ../../index.php?page=tambahguru');
        exit();
    }

    // Insert data baru
    $sql = mysqli_query($coneksi, "INSERT INTO guru (
        profile,
        nama_guru,
        nip,
        jenis_kelamin,
        alamat,
        no_tlp,
        id_sekolah,
        id_perusahaan,
        username,
        password
    ) VALUES (
        '',
        '$nama_guru',
        '$nip',
        '$jenis_kelamin',
        '$alamat',
        '$no_tlp',
        '$id_sekolah',
        '$id_perusahaan',
        '$username',
        '$password'
    )");

    if ($sql) {
        $_SESSION['flash_tambah'] = 'sukses';
        unset($_SESSION['form_data']);
        unset($_SESSION['error_username']);
        unset($_SESSION['error_password']);
        header('Location: ../../index.php?page=guru');
        exit();
    } else {
        $_SESSION['flash_error'] = mysqli_error($coneksi);
        header('Location: ../../index.php?page=tambahguru');
        exit();
    }
} else {
    header('Location: ../../index.php?page=tambahguru');
    exit();
}
?>
