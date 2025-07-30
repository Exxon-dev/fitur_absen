<?php 
session_start();

include '../koneksi.php';

// Get user input
$username = $_POST['nama'];
$password = $_POST['katakunci'];

// Prepare statements to prevent SQL injection
$stmt = $coneksi->prepare("SELECT * FROM users WHERE username=? AND password=?");
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$login = $stmt->get_result();
$cek = $login->num_rows;

$stmt_siswa = $coneksi->prepare("SELECT * FROM siswa WHERE username=? AND password=?");
$stmt_siswa->bind_param("ss", $username, $password);
$stmt_siswa->execute();
$login_siswa = $stmt_siswa->get_result();
$cek_siswa = $login_siswa->num_rows;

$stmt_guru = $coneksi->prepare("SELECT * FROM guru WHERE username=? AND password=?");
$stmt_guru->bind_param("ss", $username, $password);
$stmt_guru->execute();
$login_guru = $stmt_guru->get_result();
$cek_guru = $login_guru->num_rows;

$stmt_pembimbing = $coneksi->prepare("SELECT * FROM pembimbing WHERE username=? AND password=?");
$stmt_pembimbing->bind_param("ss", $username, $password);
$stmt_pembimbing->execute();
$login_pembimbing = $stmt_pembimbing->get_result();
$cek_pembimbing = $login_pembimbing->num_rows;

$stmt_sekolah = $coneksi->prepare("SELECT * FROM sekolah WHERE username=? AND password=?");
$stmt_sekolah->bind_param("ss", $username, $password);
$stmt_sekolah->execute();
$login_sekolah = $stmt_sekolah->get_result();
$cek_sekolah = $login_sekolah->num_rows;

if ($cek > 0) {
    $data = $login->fetch_assoc();
    if ($data['level'] == "admin") {
        $_SESSION['username'] = $username;
        $_SESSION['level'] = "admin";
        header("location:../index.php?page=dashboard&login=berhasil&username=$username");
        exit;
    } else {
        header("location: sign-in.php");
        exit;
    }
} elseif ($cek_siswa > 0) {
    $data = $login_siswa->fetch_assoc();
    $_SESSION['username'] = $username;
    $_SESSION['id_siswa'] = $data['id_siswa'];
    $_SESSION['level'] = "siswa";
    header("location:../index.php?page=dashboard_siswa&login=berhasil&username=$username");
    exit;
} elseif ($cek_guru > 0) {
    $data = $login_guru->fetch_assoc();
    $_SESSION['username'] = $username;
    $_SESSION['id_sekolah'] = $data['id_sekolah'];
    $_SESSION['id_guru'] = $data['id_guru'];
    $_SESSION['level'] = "guru";
    $_SESSION['role'] = "guru";
    header("location:../index.php?page=dashboard_guru&login=berhasil&username=$username");
    exit;
} elseif ($cek_pembimbing > 0) {
    $data = $login_pembimbing->fetch_assoc();
    $_SESSION['username'] = $username;
    $_SESSION['id_perusahaan'] = $data['id_perusahaan'];
    $_SESSION['id_pembimbing'] = $data['id_pembimbing'];
    $_SESSION['level'] = "pembimbing";
    $_SESSION['role'] = "pembimbing";
    header("location:../index.php?page=dashboard_pembimbing&login=berhasil&username=$username");
    exit;
} elseif ($cek_sekolah > 0) {
    $data = $login_sekolah->fetch_assoc();
    $_SESSION['username'] = $username;
    $_SESSION['id_perusahaan'] = $data['id_perusahaan'];
    $_SESSION['id_sekolah'] = $data['id_sekolah'];
    $_SESSION['level'] = "sekolah";
    $_SESSION['role'] = "sekolah";
    header("location:../index.php?page=dashboard_sekolah&login=berhasil&username=$username");
    exit;
} else {
    header("location: sign-in.php?login=gagal");
    exit;
}

$stmt->close();
$stmt_siswa->close();
$stmt_guru->close();
$stmt_pembimbing->close();
$coneksi->close();
?>
