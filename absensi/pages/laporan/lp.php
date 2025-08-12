<?php
include('../../koneksi.php');

$id_siswa = $_GET['id_siswa'] ?? null;
if (!$id_siswa) {
    echo "ID siswa tidak ditemukan.";
    exit();
}

$query = " 
SELECT 
    s.nama_siswa, 
    s.NISN,
    s.pro_keahlian,
    p.nama_perusahaan AS id_perusahaan, 
    b.nama_pembimbing AS id_pembimbing,
    sk.nama_sekolah,
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
    s.id_siswa = ? ";

$stmt = $coneksi->prepare($query);
$stmt->bind_param("i", $id_siswa);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    echo "Data tidak ditemukan.";
    exit();
}

$stmt->close();
$coneksi->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LEMBAR PENGESAHAN</title>
    <style type="text/css">
        @page {
            size: A4;
            margin: 20mm;
        }
        .printable {
            margin: 20px;
        }
        @media print {
            .no-print {
                display: none; 
            }
        }
        .style6 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 16px;  }
        .style9 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; }
        .style10 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; }
        .top	{border-top: 1px solid #000000; }
        .bottom	{border-bottom: 1px solid #000000; }
        .left	{border-left: 1px solid #000000; }
        .right	{border-right: 1px solid #000000; }
        .all	{border: 1px solid #000000; }
        .style26 {font-family: Verdana, Arial, Helvetica, sans-serif}
        .style27 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: bold; }
        .align-justify {text-align:justify; }
        .align-center {text-align:center; }
        .align-right {text-align:right; }
    </style>
    <script>
        function printReport() {
            window.print();
        }
        
        window.onload = function() {
            printReport();
        };
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="printable">
        <h1 class="text-center font-bold mb-8 style9">LEMBAR PENGESAHAN</h1>
        <p class="mb-4 style9">Laporan praktik kerja lapangan yang telah dilaksanakan oleh : </p>
        <p class="mb-4 style9">Nama : <?php echo htmlspecialchars($data['nama_siswa']); ?></p>
        <p class="mb-4 style9">NISN : <?php echo htmlspecialchars($data['NISN']); ?></p>
        <p class="mb-4 style9">Program Keahlian : <?php echo htmlspecialchars($data['pro_keahlian']); ?></p>
        <p class="mb-8 style9">Ditulis sebagai syarat kelulusan tahun pelajaran 2024/2025.</p>
        <p class="mb-4 style9">Menyetujui,</p>
        <div class="flex justify-between mb-8">
            <div class="text-center">
                <p class="style9">Pembimbing Sekolah</p>
                <p class="mt-8 style9">....................................</p>
                <p class="style9">NIP. ....................................</p>
            </div>
            <div class="text-center">
                <p class="style9">Pembimbing Dunia Kerja</p>
                <p class="mt-8 style9">....................................</p>
                <p class="style9">....................................</p>
            </div>
        </div>
        <p class="mb-4 style9">Mengetahui,</p>
        <div class="flex justify-between mb-8">
            <div class="text-center">
                <p class="style9">Kepala SMK Negeri Tembarak</p>
            </div>
            <div class="text-center">
                <p class="style9">Ka. ProgI.............</p>
            </div>
        </div><br><br>
        <p class="text-left style9">Aster Aswiny, S. Pd, M. Pd</p>
        <p class="text-left style9">NIP. NIP. 19700615 199512 2 002</p>
    </div>
</body>
</html>