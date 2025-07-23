<?php
// Pastikan output buffer clean sebelum memulai
if (ob_get_level()) ob_clean();

// Mulai session dan set header JSON
session_start();
header('Content-Type: application/json');

// Include koneksi database
require_once('../../koneksi.php');

// Validasi dasar
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'siswa') {
    echo json_encode(['status' => 'error', 'message' => 'Akses ditolak: Level user tidak valid']);
    exit();
}

if (!isset($_SESSION['id_siswa'])) {
    echo json_encode(['status' => 'error', 'message' => 'Session siswa tidak valid']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method. Harus menggunakan POST.']);
    exit();
}

if (!isset($_POST['keterangan'])) {
    echo json_encode(['status' => 'error', 'message' => 'Keterangan tidak dikirim']);
    exit();
}

// Siapkan variabel
$id_siswa = $_SESSION['id_siswa'];
$tanggal_hari_ini = date('Y-m-d');
$keterangan = trim($_POST['keterangan']);

// Validasi input
if (empty($keterangan)) {
    echo json_encode(['status' => 'error', 'message' => 'Keterangan tidak boleh kosong']);
    exit();
}

try {
    // Gunakan prepared statement untuk keamanan
    $coneksi->autocommit(false); // Mulai transaction

    // 1. Cek apakah sudah ada jurnal hari ini
    $stmt = $coneksi->prepare("SELECT id_jurnal FROM jurnal WHERE id_siswa = ? AND tanggal = ?");
    $stmt->bind_param("is", $id_siswa, $tanggal_hari_ini);
    $stmt->execute();
    $result = $stmt->get_result();
    $jurnal_hari_ini = $result->fetch_assoc();
    $stmt->close();

    // 2. Lakukan insert atau update
    if ($jurnal_hari_ini) {
        // Update jurnal yang ada
        $stmt = $coneksi->prepare("UPDATE jurnal SET keterangan = ? WHERE id_jurnal = ?");
        $stmt->bind_param("si", $keterangan, $jurnal_hari_ini['id_jurnal']);
    } else {
        // Buat entri baru
        $stmt = $coneksi->prepare("INSERT INTO jurnal (tanggal, keterangan, id_siswa) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $tanggal_hari_ini, $keterangan, $id_siswa);
    }

    // Eksekusi query
    if ($stmt->execute()) {
        $coneksi->commit();
        echo json_encode(['status' => 'success', 'message' => 'Jurnal berhasil disimpan.']);
    } else {
        $coneksi->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan jurnal.']);
    }
    $stmt->close();
} catch (Exception $e) {
    $coneksi->rollback();
    error_log('Error jurnal: ' . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
    ]);
} finally {
    $coneksi->autocommit(true);
}
?>