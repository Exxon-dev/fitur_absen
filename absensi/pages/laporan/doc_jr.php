<?php
include('../../koneksi.php'); // Pastikan ini menghubungkan ke database

$id_siswa = $_GET['id_siswa'] ?? null;
if (!$id_siswa) {
    echo "ID siswa tidak ditemukan.";
    exit();
}

// Ambil data siswa dari tabel siswa
$query_siswa = "
    SELECT 
        s.nama_siswa, 
        s.id_siswa 
    FROM 
        siswa s 
    WHERE 
        s.id_siswa = ?
";

$stmt_siswa = $coneksi->prepare($query_siswa);
$stmt_siswa->bind_param("i", $id_siswa);
$stmt_siswa->execute();
$result_siswa = $stmt_siswa->get_result();

if ($result_siswa->num_rows > 0) {
    $data_siswa = $result_siswa->fetch_assoc();
    $nama_siswa = htmlspecialchars($data_siswa['nama_siswa']);
    $id_siswa = htmlspecialchars($data_siswa['id_siswa']);
} else {
    echo "Data siswa tidak ditemukan.";
    exit();
}

// Ambil data jurnal dari tabel absen dan catatan
$query_jurnal = "
    SELECT 
        j.tanggal,  
        j.keterangan,
        c.catatan  -- Mengambil isi_catatan dari tabel catatan
    FROM 
        jurnal j 
    LEFT JOIN 
        catatan c ON j.id_jurnal = c.id_jurnal  -- Pastikan ada relasi yang benar
    WHERE 
        j.id_siswa = ? 
    ORDER BY j.tanggal
";

$stmt_jurnal = $coneksi->prepare($query_jurnal);
$stmt_jurnal->bind_param("i", $id_siswa);
$stmt_jurnal->execute();
$result_jurnal = $stmt_jurnal->get_result();

// Mengambil semua data jurnal
$jurnal_data = [];
while ($row = $result_jurnal->fetch_assoc()) {
    $jurnal_data[] = $row;
}

$stmt_siswa->close();
$stmt_jurnal->close();
$coneksi->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jurnal Kegiatan Praktik Kerja Lapangan</title>
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
        <tabel>
            <tr>
            <div class="text-center mb-8">
                <h1 class="font-bold style9">JURNAL KEGIATAN PRAKTIK KERJA LAPANGAN</h1>
            </div>
            </tr>
            <tr>
            <div class="style9">
                <td>NAMA: <?php echo $nama_siswa; ?></td><br>
                <td>ID Siswa: <?php echo $id_siswa; ?></td>
            </div>
            </tr>
        </tabel>
        <table width="96%" border="1" align="center" cellpadding="3" cellspacing="0" class="style9">
            <thead>
                <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                    <th class="left bottom top">No</th>
                    <th class="left bottom top">Hari/Tanggal</th>
                    <th class="left bottom top">Keterangan</th>
                    <th class="left bottom top right">Catatan</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Isi tabel dengan data jurnal
                $no = 1;
                foreach ($jurnal_data as $record) {
                    echo "<tr height='25' class='style27'>
                        <td class='top bottom left text-center'>{$no}</td>
                        <td class='top bottom left text-center'>" . $record['tanggal'] . "</td>
                        <td class='top bottom left text-center'>" . $record['keterangan'] . "</td>
                        <td class='top bottom left right text-center'>" . $record['catatan'] . "</td>
                    </tr>";
                    $no++;
                }
                ?>
                <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
            </tbody>
        </table>
        <div class="flex justify-end items-center mb-8">
            <div class="text-center">
                <div class="style9">.........................202...</div>
                <div class="style9">PEMBIMBING DUDI</div>
            <div class=""></div><br><br><br>
            <div class="style9">(.............................................)</div>
        </div>
    </div>
</body>
</html>
