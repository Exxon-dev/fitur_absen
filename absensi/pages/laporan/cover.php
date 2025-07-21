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
if (isset($_SESSION['logged_in'])) {
    header("Location: ../sign-in.php");
    exit();
}

// Query untuk ambil data lengkap siswa
$query = "
    SELECT 
        s.nama_siswa, 
        s.kelas,
        s.nis,
        s.nisn,
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
$logoFile = $_SERVER['DOCUMENT_ROOT'] . '/fitur_absen/pages/image/' . $data['logo_sekolah'];
$logoPath = '/fitur_absen/pages/image/' . $data['logo_sekolah'];
if (!file_exists($logoFile) || empty($data['logo_sekolah'])) {
    $logoPath = '/fitur_absen/pages/image/default.png';
}

// Header untuk semua user
header("Content-Type: text/html; charset=UTF-8");
header("Cache-Control: no-cache, must-revalidate");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAPORAN KEGIATAN PRAKTIK KERJA INDUSTRI (PRAKERIN)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style type="text/css">
        @page {
            size: A4;
            margin: 20mm;
        }
        body {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 16px;
            margin: 0;
            padding: 0;
        }
        .table-logo {
            width: 100px;
            height: 100px;
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
                <h2 class="text-lg">PENGEMBANGAN PERANGKAT LUNAK DAN GIM</h2>
                <h2 class="text-lg">DI <?php echo strtoupper(htmlspecialchars($data['nama_perusahaan'])); ?></h2>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="text-center" style="padding-top: 20mm;">
                <img src="<?php echo $logoPath; ?>" alt="Logo Sekolah" class="table-logo">
            </td>
        </tr>
        <tr>
            <td colspan="2" class="text-center" style="padding-top: 20mm;">
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
                this.src = '<?php echo "/fitur_absen/pages/image/default.png"; ?>';
            };
        });
    </script>
</body>
</html>