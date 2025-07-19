<?php
// Tambahkan di awal file

include('../../koneksi.php');

// Perbaiki cara ambil parameter GET
$id_siswa = $_GET['id_siswa'] ?? null;

// Validasi ID siswa
if (!$id_siswa || !is_numeric($id_siswa)) {
    die("ID Siswa tidak valid");
}

// Pengecekan session (sesuaikan dengan kebutuhan)
if (isset($_SESSION['id_siswa'])) {
    header("Location: ../../sign-in.php");
    exit();
}

// Query untuk ambil data lengkap siswa
$query = "
    SELECT 
        s.nama_siswa, 
        s.kelas, 
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

// ... (bagian logo dan HTML tetap sama)

// Cek logo sekolah
$logoFile = $_SERVER['DOCUMENT_ROOT'] . '/fitur_absen/pages/image/' . $data['logo_sekolah'];
$logoPath = '/fitur_absen/pages/image/' . $data['logo_sekolah'];
if (!file_exists($logoFile) || empty($data['logo_sekolah'])) {
    $logoPath = '/fitur_absen/pages/image/default.png';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Kegiatan Praktik Kerja Lapangan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function printReport() {
            window.print();
        }
        window.onload = printReport;
    </script>
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

        .style6 { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; }
        .style9 { font-size: 11px; }
        .style10 { font-size: 10px; }
        .style27 { font-size: 11px; font-weight: normal; }
        .align-center { text-align: center; }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td, th {
            padding: 8px;
            vertical-align: top;
        }

        .table-logo {
            width: 100px;
            height: 100px;
            object-fit: contain;
            margin: 0 auto;
        }

        .text-lg { font-size: 18px; font-weight: bold; }
    </style>
</head>
<body>
    <table cellspacing="0" cellpadding="10">
        <tr>
            <td colspan="2" class="align-center"><br><br><br>
                <p class="text-xl font-bold">BUKU KEGIATAN</p>
                <p class="text-xl font-bold mt-2">PRAKTIK KERJA LAPANGAN</p>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="align-center"><br><br><br>
                <img alt="Logo dari <?php echo htmlspecialchars($data['nama_sekolah']); ?>" class="table-logo" src="<?php echo $logoPath; ?>" />
            </td>
        </tr>
        <tr>
            <td colspan="2" class="style6 align-center"><br><br><br>
                <p><span class="style27">Nama Peserta Didik : <?php echo htmlspecialchars($data['nama_siswa']); ?></span></p>
                <p><span class="style27">Kelas / Program Keahlian : <?php echo htmlspecialchars($data['kelas']); ?></span></p>
                <p><span class="style27">DUDI Tempat PKL : <?php echo htmlspecialchars($data['nama_perusahaan']); ?></span></p>
                <p><span class="style27">Pembimbing (DUDI) : <?php echo htmlspecialchars($data['nama_pembimbing']); ?></span></p>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="style6 align-center"><br><br><br>
                <p class="text-lg">PEMERINTAH PROPINSI JAWA TENGAH</p>
                <p class="text-lg">DINAS PENDIDIKAN DAN KEBUDAYAAN</p>
                <p class="text-lg"><?php echo htmlspecialchars($data['nama_sekolah']); ?></p>
                <p class="text-lg"><?php echo htmlspecialchars($data['alamat_sekolah']); ?></p>
            </td>
        </tr>
    </table>
</body>
</html>
