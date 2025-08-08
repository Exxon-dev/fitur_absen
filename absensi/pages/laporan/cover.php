<?php
session_start();
include('../../koneksi.php');

// Perbaiki cara ambil parameter GET
$id_siswa = $_GET['id_siswa'] ?? null;

// Validasi ID siswa
if (!$id_siswa || !is_numeric($id_siswa)) {
    die("ID Siswa tidak valid");
}

// Pengecekan session untuk semua role
if (!isset($_SESSION['id_siswa']) || !isset($_SESSION['level'])) {
    header("Location: ../sign-in.php");
    exit();
}

// Query untuk ambil data lengkap siswa
$query = "
    SELECT 
        s.nama_siswa,
        s.nis,
        s.nisn,
        s.pro_keahlian,
        p.nama_perusahaan AS nama_perusahaan, 
        b.nama_pembimbing AS nama_pembimbing,
        sk.nama_sekolah,
        sk.logo_sekolah,
        sk.alamat_sekolah
    FROM 
        siswa s
    LEFT JOIN 
        perusahaan p ON s.id_perusahaan = p.id_perusahaan
    LEFT JOIN 
        pembimbing b ON s.id_pembimbing = b.id_pembimbing
    LEFT JOIN 
        sekolah sk ON s.id_sekolah = sk.id_sekolah
    WHERE 
        s.id_siswa = ?
";

$stmt = $coneksi->prepare($query);
if (!$stmt) {
    die("Prepare gagal: " . $coneksi->error);
}

$stmt->bind_param("i", $id_siswa);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Data siswa dengan ID $id_siswa tidak ditemukan");
}

$data = $result->fetch_assoc();
$stmt->close();
$coneksi->close();

// Cek logo sekolah
$logoFileName = $data['logo_sekolah'] ?? '';
$basePath = '/fitur_absen/absensi/pages/image/';
$defaultLogo = $basePath . 'default_logo.png';

// Debug: Tampilkan nama file logo dari database
error_log("Logo dari database: " . $logoFileName);

// Jika logo ada di database
if (!empty($logoFileName)) {
    // Jika logo berada di folder uploads
    if (strpos($logoFileName, '../../uploads/') === 0) {
        $logoPath = '/' . $logoFileName;
    }
    // Jika logo berada di folder image sekolah
    else {
        $logoPath = $basePath . 'logo_sekolah/' . $logoFileName;
    }

    $fullLogoPath = $_SERVER['DOCUMENT_ROOT'] . $logoPath;

    // Debug: Tampilkan path lengkap yang digunakan
    error_log("Full path logo: " . $fullLogoPath);

    // Verifikasi file benar-benar ada
    if (!file_exists($fullLogoPath)) {
        error_log("File logo tidak ditemukan, menggunakan default");
        $logoPath = $defaultLogo;
    }
} else {
    error_log("Logo tidak ada di database, menggunakan default");
    $logoPath = $defaultLogo;
}

// Debug: Tampilkan path logo yang akan digunakan
error_log("Path logo yang digunakan: " . $logoPath);

header("Content-Type: text/html; charset=UTF-8");
header("Cache-Control: no-cache, must-revalidate");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAPORAN KEGIATAN PRAKTIK KERJA INDUSTRI (PRAKERIN) </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style type="text/css">
        @page {
            size: A4;
            margin: 30mm;
        }

        body {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 16px;
            margin: 0;
            padding: 0;
        }

        .table-logo {
            width: 150px;
            height: 150px;
            object-fit: contain;
            margin: 0 auto;
        }

        .text-center {
            text-align: center;
        }

        .text-lg {
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <table width="100%" border="0" cellspacing="0" cellpadding="10">
        <tr>
            <td colspan="2" class="text-center" style="padding-top: 30mm;">
                <h1 class="text-lg">LAPORAN KEGIATAN</h1>
                <h1 class="text-lg">PRAKTIK KERJA INDUSTRI (PRAKERIN)</h1>
                <h2 class="text-lg"><?= htmlspecialchars($data['pro_keahlian']); ?></h2>
                <h2 class="text-lg">DI <?php echo strtoupper(htmlspecialchars($data['nama_perusahaan'])); ?></h2>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="text-center" style="padding-top: 30mm;">
                <img src="<?= htmlspecialchars($logoPath); ?>"
                    alt="Logo <?= htmlspecialchars($data['nama_sekolah']); ?>"
                    class="table-logo"
                    onerror="this.onerror=null; this.src='<?= $defaultLogo ?>'">
            </td>
        </tr>
        <tr>
            <td colspan="2" class="text-center" style="padding-top: 30mm;">
                <p>Disusun oleh:</p>
                <p>Nama: <?php echo htmlspecialchars($data['nama_siswa']); ?></p>
                <p>NIS: <?php echo htmlspecialchars($data['nis']); ?></p>
                <p>NISN: <?php echo htmlspecialchars($data['nisn']); ?></p>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="text-center" style="padding-top: 20mm;">
                <p class="text-lg"><?php echo htmlspecialchars($data['nama_sekolah']); ?></p>
                <p class="text-lg">PEMERINTAH PROPINSI JAWA TENGAH</p>
            </td>
        </tr>
    </table>

    <script>
        // Auto print saat halaman selesai load
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };

        // Fallback jika gambar error
        document.addEventListener('DOMContentLoaded', function() {
            var logo = document.querySelector('.table-logo');
            logo.onerror = function() {
                this.src = '<?= $defaultLogo ?>';
            };
        });
    </script>
</body>

</html>