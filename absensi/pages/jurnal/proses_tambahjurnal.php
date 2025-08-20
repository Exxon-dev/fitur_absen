<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('../../koneksi.php');

// Validasi dasar
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['flash_jurnal_error'] = "Invalid request method";
    header("Location: ../../index.php?page=tambahjurnal");
    exit();
}

// Validasi level pengguna
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'siswa') {
    $_SESSION['flash_jurnal_error'] = "Akses ditolak: Hanya siswa yang dapat menambah jurnal";
    header("Location: ../../index.php?page=catatan");
    exit();
}

if (!isset($_SESSION['id_siswa'])) {
    $_SESSION['flash_jurnal_error'] = "Session siswa tidak valid";
    header("Location: ../../index.php?page=catatan");
    exit();
}

if (!isset($_POST['keterangan'])) {
    $_SESSION['flash_jurnal_error'] = "Keterangan tidak dikirim";
    header("Location: ../../index.php?page=tambahjurnal");
    exit();
}

$id_siswa = $_SESSION['id_siswa'];
$tanggal_hari_ini = date('Y-m-d');
$keterangan = trim($_POST['keterangan']);

if (empty($keterangan)) {
    $_SESSION['flash_jurnal_error'] = "Keterangan tidak boleh kosong";
    header("Location: ../../index.php?page=tambahjurnal");
    exit();
}

try {
    // Cek apakah sudah ada jurnal hari ini
    $check_query = "SELECT id_jurnal FROM jurnal WHERE id_siswa = ? AND tanggal = ?";
    $stmt = $coneksi->prepare($check_query);
    $stmt->bind_param("is", $id_siswa, $tanggal_hari_ini);
    $stmt->execute();
    $result = $stmt->get_result();
    $existing_jurnal = $result->fetch_assoc();
    $stmt->close();

    if ($existing_jurnal) {
        // Update jurnal yang ada
        $update_query = "UPDATE jurnal SET keterangan = ? WHERE id_jurnal = ?";
        $stmt = $coneksi->prepare($update_query);
        $stmt->bind_param("si", $keterangan, $existing_jurnal['id_jurnal']);
        
        if ($stmt->execute()) {
            $_SESSION['flash_jurnal_update'] = 'sukses';
        } else {
            throw new Exception("Gagal mengupdate jurnal: " . $stmt->error);
        }
    } else {
        // Buat entri baru
        $insert_query = "INSERT INTO jurnal (tanggal, keterangan, id_siswa) VALUES (?, ?, ?)";
        $stmt = $coneksi->prepare($insert_query);
        $stmt->bind_param("ssi", $tanggal_hari_ini, $keterangan, $id_siswa);
        
        if ($stmt->execute()) {
            $_SESSION['flash_jurnal_tambah'] = 'sukses';
        } else {
            throw new Exception("Gagal menyimpan jurnal: " . $stmt->error);
        }
    }
    $stmt->close();
    
    header("Location: ../../index.php?page=catatan");
    exit();
    
} catch (Exception $e) {
    $_SESSION['flash_jurnal_error'] = $e->getMessage();
    header("Location: ../../index.php?page=tambahjurnal");
    exit();
}
?>