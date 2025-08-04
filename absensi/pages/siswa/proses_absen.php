    <?php

    include '../../koneksi.php';
    header('Content-Type: application/json');

    // Koordinat default untuk Magelang
    define('DEFAULT_LAT', -7.4706);
    define('DEFAULT_LONG', 110.2178);
    define('MAGELANG_BOUNDS', [
        'min_lat' => -7.6,
        'max_lat' => -7.3,
        'min_long' => 110.1,
        'max_long' => 110.3
    ]);

    // Token API (harap ganti dengan token asli Anda)
    define('IPINFO_TOKEN', '43716bdc517142');

    try {
        if (!isset($_SESSION['id_siswa'])) {
            throw new Exception('Akses ditolak - Silakan login terlebih dahulu');
        }

        $id_siswa = $_SESSION['id_siswa'];
        $tanggal = date('Y-m-d');
        $jam = date('H:i:s');
        $action = $_POST['action'] ?? '';

        if (!$coneksi) {
            throw new Exception('Koneksi database gagal');
        }

        // Fungsi untuk mendapatkan IP pengguna
        function getRealIpAddr() {
            $ip_keys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
            foreach ($ip_keys as $key) {
                if (!empty($_SERVER[$key])) {
                    $ip = trim(explode(',', $_SERVER[$key])[0]);
                    if (filter_var($ip, FILTER_VALIDATE_IP)) {
                        return $ip;
                    }
                }
            }
            return '127.0.0.1';
        }

        $ip_address = getRealIpAddr();

        // Untuk testing lokal
        if ($ip_address == '::1' || $ip_address == '127.0.0.1') {
            $ip_address = '114.5.16.9'; // Contoh IP Magelang
        }

        // Data lokasi default Magelang
        $lokasi = 'Magelang, Jawa Tengah';
        $kota = 'Magelang';
        $koordinat = DEFAULT_LAT . ',' . DEFAULT_LONG;
        $isp = 'ISP Tidak Diketahui';

        // Gunakan API untuk deteksi lokasi
        $api_url = "https://ipinfo.io/{$ip_address}/json?token=" . IPINFO_TOKEN;
        $context = stream_context_create(['http' => ['timeout' => 2]]); // Timeout 2 detik
        $json = @file_get_contents($api_url, false, $context);
        
        if ($json !== false) {
            $ipdata = json_decode($json, true);
            
            // Debugging - simpan respon API ke log
            error_log("IP Info Response: " . print_r($ipdata, true));
            
            // Deteksi koordinat jika ada
            if (isset($ipdata['loc'])) {
                $loc_parts = explode(',', $ipdata['loc']);
                $latitude = floatval($loc_parts[0]);
                $longitude = floatval($loc_parts[1]);
                
                // Validasi apakah koordinat berada dalam wilayah Magelang
                if ($latitude >= MAGELANG_BOUNDS['min_lat'] && 
                    $latitude <= MAGELANG_BOUNDS['max_lat'] && 
                    $longitude >= MAGELANG_BOUNDS['min_long'] && 
                    $longitude <= MAGELANG_BOUNDS['max_long']) {
                    
                    $koordinat = "$latitude,$longitude";
                    $kota = 'Magelang';
                    
                    // Deteksi kecamatan berdasarkan koordinat (contoh sederhana)
                    if ($latitude < -7.45 && $longitude > 110.2) {
                        $kecamatan = 'Magelang Utara';
                    } elseif ($latitude > -7.45 && $longitude < 110.2) {
                        $kecamatan = 'Magelang Selatan';
                    } else {
                        $kecamatan = 'Kota Magelang';
                    }
                    
                    $lokasi = "$kecamatan, $kota, Jawa Tengah";
                    
                    // Deteksi ISP
                    if (isset($ipdata['org'])) {
                        $isp = preg_replace('/AS\d+\s*/', '', $ipdata['org']);
                        $isp = substr(trim($isp), 0, 99); // Sesuaikan dengan panjang kolom
                    }
                } else {
                    // Jika di luar Magelang tapi masih di Jateng
                    if ($latitude >= -8.303459 && $latitude <= -6.307630 && 
                        $longitude >= 108.973572 && $longitude <= 111.478348) {
                        $lokasi = "Diluar Magelang: " . ($ipdata['city'] ?? 'Lokasi Jateng');
                    } else {
                        $lokasi = "Lokasi diluar Jawa Tengah (Diarahkan ke Magelang)";
                    }
                }
            }
        }

        // Cek absensi hari ini
        $stmt = mysqli_prepare($coneksi, "SELECT jam_masuk, jam_keluar FROM absen WHERE id_siswa=? AND tanggal=?");
        mysqli_stmt_bind_param($stmt, "is", $id_siswa, $tanggal);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $absen = mysqli_fetch_assoc($result);

        if ($action === 'simpan_masuk') {
            if ($absen && $absen['jam_masuk']) {
                throw new Exception('Sudah absen masuk hari ini');
            }

            $jam_masuk = $jam;

            if ($absen) {
                $query = "UPDATE absen SET 
                        jam_masuk=?, 
                        ip_address=?, 
                        lokasi=?, 
                        koordinat=?, 
                        kota=?, 
                        isp=? 
                        WHERE id_siswa=? AND tanggal=?";
                $stmt = mysqli_prepare($coneksi, $query);
                mysqli_stmt_bind_param($stmt, "ssssssis", $jam_masuk, $ip_address, $lokasi, $koordinat, $kota, $isp, $id_siswa, $tanggal);
            } else {
                $query = "INSERT INTO absen (
                        id_siswa, tanggal, jam_masuk, keterangan, 
                        ip_address, lokasi, koordinat, kota, isp
                        ) VALUES (?, ?, ?, 'Hadir', ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($coneksi, $query);
                mysqli_stmt_bind_param($stmt, "isssssss", $id_siswa, $tanggal, $jam_masuk, $ip_address, $lokasi, $koordinat, $kota, $isp);
            }

            if (!mysqli_stmt_execute($stmt)) {
                error_log("Error MySQL: " . mysqli_error($coneksi));
                throw new Exception('Gagal menyimpan data absen. Detail: ' . mysqli_error($coneksi));
            }

            die(json_encode([
                'success' => true,
                'message' => 'Absen masuk berhasil',
                'lokasi' => $lokasi,
                'koordinat' => $koordinat,
                'kota' => $kota,
                'waktu' => $jam_masuk,
                'provider' => $isp
            ]));

        } elseif ($action === 'simpan_keluar') {
            if (!$absen || !$absen['jam_masuk']) {
                throw new Exception('Belum absen masuk');
            }

            if ($absen['jam_keluar']) {
                throw new Exception('Sudah absen pulang hari ini');
            }

            $jam_keluar = $jam;

            $query = "UPDATE absen SET 
                    jam_keluar=?, 
                    ip_address_keluar=?, 
                    lokasi_keluar=?, 
                    koordinat_keluar=?, 
                    kota_keluar=?, 
                    isp_keluar=? 
                    WHERE id_siswa=? AND tanggal=?";
            $stmt = mysqli_prepare($coneksi, $query);
            mysqli_stmt_bind_param($stmt, "ssssssis", $jam_keluar, $ip_address, $lokasi, $koordinat, $kota, $isp, $id_siswa, $tanggal);
            
            if (!mysqli_stmt_execute($stmt)) {
                error_log("Error MySQL: " . mysqli_error($coneksi));
                throw new Exception('Gagal menyimpan data absen pulang. Detail: ' . mysqli_error($coneksi));
            }

            die(json_encode([
                'success' => true,
                'message' => 'Absen pulang berhasil',
                'lokasi' => $lokasi,
                'koordinat' => $koordinat,
                'kota' => $kota,
                'waktu' => $jam_keluar,
                'provider' => $isp
            ]));
        } else {
            throw new Exception('Aksi tidak valid');
        }
    } catch (Exception $e) {
        error_log("Error System: " . $e->getMessage());
        die(json_encode([
            'success' => false,
            'message' => $e->getMessage(),
            'error_details' => [
                'ip' => $ip_address ?? '',
                'waktu' => $jam ?? ''
            ]
        ]));
    }