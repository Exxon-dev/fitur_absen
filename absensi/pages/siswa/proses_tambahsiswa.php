<?php
include('../../koneksi.php'); // Sesuaikan path

// Inisialisasi variabel error
$_SESSION['error_nisn'] = '';
$_SESSION['error_username'] = '';
$_SESSION['success'] = '';
$_SESSION['form_data'] = $_POST;

// Validasi NISN
if (strlen($_POST['nisn']) !== 10) {
    $_SESSION['error_nisn'] = 'NISN harus terdiri dari 10 karakter';
    header("Location: ../../index.php?page=tambahsiswa");
    exit();
}



// Validasi username sudah digunakan
$username = mysqli_real_escape_string($coneksi, $_POST['username']);
$check_username = mysqli_query($coneksi, "SELECT * FROM siswa WHERE username = '$username'");

if (!$check_username) {
    // Error dalam query
    $_SESSION['error'] = 'Terjadi kesalahan dalam validasi username: ' . mysqli_error($coneksi);
    header("Location: ../../index.php?page=tambahsiswa");
    exit();
}

if (mysqli_num_rows($check_username) > 0) {
    $_SESSION['error_username'] = 'Username sudah digunakan';
    header("Location: ../../index.php?page=tambahsiswa");
    exit();
}

// Jika semua validasi passed, simpan data ke database
$nis = mysqli_real_escape_string($coneksi, $_POST['nis']);
$nisn = mysqli_real_escape_string($coneksi, $_POST['nisn']);
$nama_siswa = mysqli_real_escape_string($coneksi, $_POST['nama_siswa']);
$id_sekolah = mysqli_real_escape_string($coneksi, $_POST['id_sekolah']);
$pro_keahlian = mysqli_real_escape_string($coneksi, $_POST['pro_keahlian']);
$id_perusahaan = mysqli_real_escape_string($coneksi, $_POST['id_perusahaan']);
$id_pembimbing = mysqli_real_escape_string($coneksi, $_POST['id_pembimbing']);
$id_guru = mysqli_real_escape_string($coneksi, $_POST['id_guru']);
$password = mysqli_real_escape_string($coneksi, $_POST['password']);

$query = "INSERT INTO siswa (nis, nisn, nama_siswa, id_sekolah, pro_keahlian, id_perusahaan, id_pembimbing, id_guru, username, password) 
          VALUES ('$nis', '$nisn', '$nama_siswa', '$id_sekolah', '$pro_keahlian', '$id_perusahaan', '$id_pembimbing', '$id_guru', '$username', '$password')";

// Eksekusi query
$result = mysqli_query($coneksi, $query);

if ($result) {
    $_SESSION['flash_tambah'] = 'sukses';
    header("Location: ../../index.php?page=siswa");
    exit();
} else {
    $_SESSION['flash_error'] = 'Terjadi kesalahan: ' . mysqli_error($coneksi);
    header("Location: ../../index.php?page=tambahsiswa");
    exit();
}

// Validasi NISN
if (strlen($_POST['nisn']) !== 10) {
    $_SESSION['error_nisn'] = 'NISN harus terdiri dari 10 karakter';
    header("Location: ../../index.php?page=tambahsiswa");
    exit();
}



// Validasi username sudah digunakan
$username = mysqli_real_escape_string($coneksi, $_POST['username']);
$check_username = mysqli_query($coneksi, "SELECT * FROM siswa WHERE username = '$username'");

if (!$check_username) {
    // Error dalam query
    $_SESSION['error'] = 'Terjadi kesalahan dalam validasi username: ' . mysqli_error($coneksi);
    header("Location: ../../index.php?page=tambahsiswa");
    exit();
}

if (mysqli_num_rows($check_username) > 0) {
    $_SESSION['error_username'] = 'Username sudah digunakan';
    header("Location: ../../index.php?page=tambahsiswa");
    exit();
}

// Jika semua validasi passed, simpan data ke database
$nis = mysqli_real_escape_string($coneksi, $_POST['nis']);
$nisn = mysqli_real_escape_string($coneksi, $_POST['nisn']);
$nama_siswa = mysqli_real_escape_string($coneksi, $_POST['nama_siswa']);
$id_sekolah = mysqli_real_escape_string($coneksi, $_POST['id_sekolah']);
$pro_keahlian = mysqli_real_escape_string($coneksi, $_POST['pro_keahlian']);
$id_perusahaan = mysqli_real_escape_string($coneksi, $_POST['id_perusahaan']);
$id_pembimbing = mysqli_real_escape_string($coneksi, $_POST['id_pembimbing']);
$id_guru = mysqli_real_escape_string($coneksi, $_POST['id_guru']);
$password = mysqli_real_escape_string($coneksi, $_POST['password']);

// Tambahkan field lainnya yang diperlukan
$no_wa = isset($_POST['no_wa']) ? mysqli_real_escape_string($coneksi, $_POST['no_wa']) : '';
$TL = isset($_POST['TL']) ? mysqli_real_escape_string($coneksi, $_POST['TL']) : '';
$TTGL = isset($_POST['TTGL']) ? mysqli_real_escape_string($coneksi, $_POST['TTGL']) : '';
$tanggal_mulai = isset($_POST['tanggal_mulai']) ? mysqli_real_escape_string($coneksi, $_POST['tanggal_mulai']) : '';
$tanggal_selesai = isset($_POST['tanggal_selesai']) ? mysqli_real_escape_string($coneksi, $_POST['tanggal_selesai']) : '';

$query = "INSERT INTO siswa (nis, nisn, nama_siswa, no_wa, pro_keahlian, TL, TTGL, id_sekolah, id_perusahaan, tanggal_mulai, tanggal_selesai, id_pembimbing, id_guru, username, password) 
          VALUES ('$nis', '$nisn', '$nama_siswa', '$no_wa', '$pro_keahlian', '$TL', '$TTGL', '$id_sekolah', '$id_perusahaan', '$tanggal_mulai', '$tanggal_selesai', '$id_pembimbing', '$id_guru', '$username', '$password')";
if (mysqli_query($coneksi, $query)) {
    $_SESSION['success'] = 'Data siswa berhasil ditambahkan';
    unset($_SESSION['form_data']); // Hapus data form dari session
    unset($_SESSION['error_username']);
    unset($_SESSION['error_password']);
    unset($_SESSION['error_nis']);
    unset($_SESSION['error_nisn']);
} else {
    $_SESSION['error'] = 'Terjadi kesalahan: ' . mysqli_error($coneksi);
}

header("Location: ../../index.php?page=siswa"); // Redirect ke halaman siswa, bukan tambahsiswa
exit();
