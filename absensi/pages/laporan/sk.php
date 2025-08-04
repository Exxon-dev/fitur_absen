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
        s.pro_keahlian,
        s.tanggal_mulai,
        s.tanggal_selesai,
        s.TL,
        s.TTGL,
        s.NISN,
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
    <title>Surat Keterangan</title>
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
        <h1 class="text-center text-xl font-bold mb-4 style9">SURAT KETERANGAN</h1>
        <p class="text-center mb-8 style9">Nomor : ....................................</p>
        <p class="mb-4 style9">Yang bertanda tangan dibawah ini pimpinan .................................... menerangkan bahwa :</p>
        <div class="mb-2 style9">
            <div class="flex mb-2">
                <span class="w-1/5">Nama </span>
                <span class="w-2/5">: <?php echo htmlspecialchars($data['nama_siswa']); ?></span>
            </div>
            <div class="flex mb-2">
                <span class="w-1/5">Tempat lahir </span>
                <span class="w-2/5">: <?php echo htmlspecialchars($data['TL']); ?></span>
            </div>
            <div class="flex mb-2">
                <span class="w-1/5">Tanggal lahir </span>
                <span class="w-2/5">: <?php echo htmlspecialchars($data['TTGL']); ?></span>
            </div>
            <div class="flex mb-2">
                <span class="w-1/5">NISN </span>
                <span class="w-2/5">: <?php echo htmlspecialchars($data['NISN']); ?></span>
            </div>
            <div class="flex mb-2">
                <span class="w-1/5">Program Keahlian </span>
                <span class="w-2/5">: <?php echo htmlspecialchars($data['pro_keahlian']); ?></span>
            </div>
            <div class="flex mb-2">
                <span class="w-1/5">Asal Sekolah </span>
                <span class="w-2/5">: <?php echo htmlspecialchars($data['nama_sekolah']); ?></span>
            </div>
        <p class="mb-4 style9">Telah mengikuti Praktik Kerja Lapangan yang dilaksanakan di <?php echo htmlspecialchars($data['id_perusahaan']); ?> dari tanggal <?php echo htmlspecialchars($data['tanggal_mulai']); ?> s.d <?php echo htmlspecialchars($data['tanggal_selesai']); ?> dengan hasil</p>
        <tabel>
            <tr>
                <td>&nbsp;</td>
                <td><div class="border border-black h-10 mb-4"></div></td>
                <td>&nbsp;</td>
            </tr>
        </tabel>
        <p class="mb-4 style9">Surat keterangan ini dikeluarkan sebagai identitas telah melaksanakan Praktik Kerja Lapangan.</p>
        <div class="flex justify-end style9">
            <div class="text-center">
                <p clas="style9">................, ............20......</p>
                <p class="style9">Pimpinan Perusahaan</p>
                <br><br><br><br><br>
                <p class="style9">(.............................................)</p>
            </div>
        </div>
    </div>
</body>
</html>