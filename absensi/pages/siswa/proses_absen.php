<?php
// Pastikan tidak ada output sebelum header
if (ob_get_level()) ob_clean();

include '../../koneksi.php';

// Set header pertama kali
header('Content-Type: application/json');

try {
    // Validasi session
    if (!isset($_SESSION['id_siswa'])) {
        throw new Exception(json_encode([
            'status' => 'error',
            'message' => 'Akses ditolak - Silakan login terlebih dahulu'
        ]));
    }

    // Pastikan tidak ada output yang tidak diinginkan
    if (headers_sent()) {
        throw new Exception(json_encode([
            'status' => 'error',
            'message' => 'Header sudah terkirim'
        ]));
    }

    $id_siswa = $_SESSION['id_siswa'];
    $tanggal = date('Y-m-d');
    $jam = date('H:i:s');
    $keterangan = 'Hadir';

    // Validasi koneksi database
    if (!$coneksi) {
        throw new Exception(json_encode([
            'status' => 'error',
            'message' => 'Koneksi database gagal'
        ]));
    }


    // Ambil aksi dari request
    $action = $_POST['action'] ?? '';

    // Cek status absensi hari ini
    $stmt = mysqli_prepare($coneksi, "SELECT jam_masuk, jam_keluar FROM absen WHERE id_siswa=? AND tanggal=?");
    mysqli_stmt_bind_param($stmt, "is", $id_siswa, $tanggal);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $absen = mysqli_fetch_assoc($result);

    if ($action == 'simpan_masuk') {
        if ($absen && $absen['jam_masuk']) {
            die(json_encode(['success' => false, 'message' => 'Sudah absen masuk hari ini']));
        }
        $jam_masuk = date('H:i:s');
        if ($absen) {
            // Update jika record sudah ada (tapi jam_masuk kosong)
            $stmt = mysqli_prepare($coneksi, "UPDATE absen SET jam_masuk=? WHERE id_siswa=? AND tanggal=?");
            mysqli_stmt_bind_param($stmt, "sis", $jam_masuk, $id_siswa, $tanggal);
            mysqli_stmt_execute($stmt);
        } else {
            // Insert baru, jam_keluar langsung NULL di query tanpa bind_param
            $keterangan = 'Hadir';
            $stmt = mysqli_prepare($coneksi, "INSERT INTO absen (id_siswa, tanggal, jam_masuk, jam_keluar, keterangan) VALUES (?, ?, ?, NULL, ?)");
            mysqli_stmt_bind_param($stmt, "isss", $id_siswa, $tanggal, $jam_masuk, $keterangan);
            mysqli_stmt_execute($stmt);
        }
        die(json_encode(['success' => true, 'message' => 'Absen masuk berhasil']));
    } elseif ($action == 'simpan_keluar') {
        if (!$absen || !$absen['jam_masuk']) {
            die(json_encode(['success' => false, 'message' => 'Belum absen masuk']));
        }
        if ($absen['jam_keluar']) {
            die(json_encode(['success' => false, 'message' => 'Sudah absen pulang hari ini']));
        }
        $jam_keluar = date('H:i:s');
        $stmt = mysqli_prepare($coneksi, "UPDATE absen SET jam_keluar=? WHERE id_siswa=? AND tanggal=?");
        mysqli_stmt_bind_param($stmt, "sis", $jam_keluar, $id_siswa, $tanggal);
        mysqli_stmt_execute($stmt);
        die(json_encode(['success' => true, 'message' => 'Absen pulang berhasil']));
    } else {
        die(json_encode(['success' => false, 'message' => 'Aksi tidak valid']));
    }

} catch (Exception $e) {
    // Pastikan output error juga berupa JSON
    die(json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]));
}
?>