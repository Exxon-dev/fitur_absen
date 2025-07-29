<?php
if (ob_get_level()) ob_clean();

include '../../koneksi.php';
session_start();
header('Content-Type: application/json');

try {
    if (!isset($_SESSION['id_siswa'])) {
        throw new Exception('Akses ditolak - Silakan login terlebih dahulu');
    }

    $id_siswa = $_SESSION['id_siswa'];
    $tanggal = date('Y-m-d');
    $jam = date('H:i:s');
    $keterangan = 'Hadir';

    if (!$coneksi) {
        throw new Exception('Koneksi database gagal');
    }

    $action = $_POST['action'] ?? '';

    // Ambil IP & Lokasi
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $lokasi = 'Lokasi tidak diketahui';
    $koordinat = '';

    $ipinfo = @file_get_contents("http://ipinfo.io/{$ip_address}/json");
    if ($ipinfo) {
        $ipdata = json_decode($ipinfo, true);

        $city = $ipdata['city'] ?? '';
        $region = $ipdata['region'] ?? '';
        $country = $ipdata['country'] ?? '';
        $org = $ipdata['org'] ?? '';
        $timezone = $ipdata['timezone'] ?? '';
        $loc = $ipdata['loc'] ?? ''; // format: "latitude,longitude"

        $lokasi_parts = [];
        if ($city) $lokasi_parts[] = $city;
        if ($region) $lokasi_parts[] = $region;
        if ($country) $lokasi_parts[] = $country;
        if ($org) $lokasi_parts[] = "Provider: $org";
        if ($timezone) $lokasi_parts[] = "Zona: $timezone";

        $lokasi = implode(' | ', $lokasi_parts);
        if ($loc) {
            $koordinat = $loc;
            $lokasi .= " | Koordinat: $loc";
        }
    }

    // Cek absensi hari ini
    $stmt = mysqli_prepare($coneksi, "SELECT jam_masuk, jam_keluar FROM absen WHERE id_siswa=? AND tanggal=?");
    mysqli_stmt_bind_param($stmt, "is", $id_siswa, $tanggal);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $absen = mysqli_fetch_assoc($result);

    if ($action == 'simpan_masuk') {
        if ($absen && $absen['jam_masuk']) {
            die(json_encode(['success' => false, 'message' => 'Sudah absen masuk hari ini']));
        }

        $jam_masuk = $jam;

        if ($absen) {
            $stmt = mysqli_prepare($coneksi, "UPDATE absen SET jam_masuk=?, ip_address=?, lokasi=?, koordinat=? WHERE id_siswa=? AND tanggal=?");
            mysqli_stmt_bind_param($stmt, "ssssis", $jam_masuk, $ip_address, $lokasi, $koordinat, $id_siswa, $tanggal);
        } else {
            $stmt = mysqli_prepare($coneksi, "INSERT INTO absen (id_siswa, tanggal, jam_masuk, keterangan, ip_address, lokasi, koordinat) VALUES (?, ?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "issssss", $id_siswa, $tanggal, $jam_masuk, $keterangan, $ip_address, $lokasi, $koordinat);
        }

        mysqli_stmt_execute($stmt);
        die(json_encode([
            'success' => true,
            'message' => 'Absen masuk berhasil',
            'ip' => $ip_address,
            'lokasi' => $lokasi,
            'koordinat' => $koordinat
        ]));

    } elseif ($action == 'simpan_keluar') {
        if (!$absen || !$absen['jam_masuk']) {
            die(json_encode(['success' => false, 'message' => 'Belum absen masuk']));
        }

        if ($absen['jam_keluar']) {
            die(json_encode(['success' => false, 'message' => 'Sudah absen pulang hari ini']));
        }

        $jam_keluar = $jam;

        $stmt = mysqli_prepare($coneksi, "UPDATE absen SET jam_keluar=?, ip_address=?, lokasi=?, koordinat=? WHERE id_siswa=? AND tanggal=?");
        mysqli_stmt_bind_param($stmt, "ssssis", $jam_keluar, $ip_address, $lokasi, $koordinat, $id_siswa, $tanggal);
        mysqli_stmt_execute($stmt);

        die(json_encode([
            'success' => true,
            'message' => 'Absen pulang berhasil',
            'ip' => $ip_address,
            'lokasi' => $lokasi,
            'koordinat' => $koordinat
        ]));

    } else {
        die(json_encode(['success' => false, 'message' => 'Aksi tidak valid']));
    }

} catch (Exception $e) {
    die(json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]));
}
?>
