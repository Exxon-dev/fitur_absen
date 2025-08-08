<?php
include('../../koneksi.php'); // Pastikan ini menghubungkan ke database

$id_siswa = $_GET['id_siswa'] ?? null;
if (!$id_siswa) {
    echo "ID siswa tidak ditemukan.";
    exit();
}

// Ambil data siswa dari tabel siswa, sekarang ambil NISN juga
$query_siswa = "
    SELECT 
        s.nama_siswa, 
        s.nisn,
        sk.nama_sekolah
    FROM 
        siswa s
    JOIN 
        sekolah sk ON s.id_sekolah = sk.id_sekolah
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
    $nisn = htmlspecialchars($data_siswa['nisn']);
    $nama_sekolah = htmlspecialchars($data_siswa['nama_sekolah']);
} else {
    echo "Data siswa tidak ditemukan.";
    exit();
}

// Ambil data jurnal dari tabel absen dan catatan
$query_jurnal = "
    SELECT 
        j.tanggal,  
        j.keterangan,
        c.catatan  
    FROM 
        jurnal j 
    LEFT JOIN 
        catatan c ON j.id_jurnal = c.id_jurnal 
    WHERE 
        j.id_siswa = ? 
    ORDER BY j.tanggal
";

$stmt_jurnal = $coneksi->prepare($query_jurnal);
$stmt_jurnal->bind_param("i", $id_siswa);
$stmt_jurnal->execute();
$result_jurnal = $stmt_jurnal->get_result();

$jurnal_data = [];
while ($row = $result_jurnal->fetch_assoc()) {
    $jurnal_data[] = $row;
}

$stmt_siswa->close();
$stmt_jurnal->close();
$coneksi->close();

// Split data into chunks of 7 records per page
$pages = array_chunk($jurnal_data, 7);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Jurnal Kegiatan Praktik Kerja Lapangan</title>
    <style type="text/css">
        @page { 
            size: A4; 
            margin: 20mm; 
        }
        body {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
        }
        .printable { 
            margin: 20px; 
        }
        .page-break {
            page-break-after: always;
        }
        @media print { 
            .no-print { 
                display: none; 
            } 
        }
        .style6 {
            font-size: 16px;
        }
        .style9 {
            font-size: 11px;
        }
        .style10 {
            font-size: 10px;
        }
        .style27 {
            font-size: 11px; 
            font-weight: bold;
        }
        .top {
            border-top: 1px solid #000000;
        }
        .bottom {
            border-bottom: 1px solid #000000;
        }
        .left {
            border-left: 1px solid #000000;
        }
        .right {
            border-right: 1px solid #000000;
        }
        .all {
            border: 1px solid #000000;
        }
        .align-center {
            text-align:center;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            padding: 5px;
        }
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
    <?php foreach ($pages as $page_num => $page_data): ?>
    <div class="printable <?= ($page_num < count($pages)-1 ? 'page-break' : '') ?>">
        <div class="text-center mb-8">
            <h1 class="font-bold style9">JURNAL PRAKERIN</h1>
            <h1 class="font-bold style9"><?php echo $nama_sekolah; ?></h1>
            <h1 class="font-bold style9">TAHUN AJARAN 2025/2026</h1>
        </div>

        <div class="style9 mb-4">
            <p>NAMA: <?php echo $nama_siswa; ?></p>
            <p>NISN: <?php echo $nisn; ?></p>
        </div>

        <table width="96%" border="1" align="center" cellpadding="3" cellspacing="0" class="style9">
            <thead>
                <tr>
                    <th class="left bottom top">No</th>
                    <th class="left bottom top">Hari/Tanggal</th>
                    <th class="left bottom top">Uraian Kegiatan/Uraian Materi</th>
                    <th class="left bottom top right">Catatan</th>
                    <th class="left bottom top right">Tanda Tangan</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = ($page_num * 7) + 1;
                foreach ($page_data as $record) {
                    echo "<tr height='25' class='style27'>
                        <td class='top bottom left text-center'>{$no}</td>
                        <td class='top bottom left text-left'>" . htmlspecialchars($record['tanggal']) . "</td>
                        <td class='top bottom left text-left'>" . htmlspecialchars($record['keterangan']) . "</td>
                        <td class='top bottom left right text-left'>" . htmlspecialchars($record['catatan'] ?? '') . "</td>
                        <td class='top bottom left right text-left'>&nbsp;</td>
                    </tr>";
                    $no++;
                }
                
                // Fill remaining rows if less than 7
                $remaining_rows = 7 - count($page_data);
                for ($i = 0; $i < $remaining_rows; $i++) {
                    echo "<tr height='25'>
                        <td class='left bottom'>&nbsp;</td>
                        <td class='left bottom'>&nbsp;</td>
                        <td class='left bottom'>&nbsp;</td>
                        <td class='left bottom right'>&nbsp;</td>
                        <td class='left bottom right'>&nbsp;</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>

        <?php if ($page_num == count($pages)-1): ?>
        <div class="flex justify-end items-center mb-8 mt-12">
            <div class="text-center">
                <div class="style9">Kajoran, ..................... 202...</div>
                <div class="style9">PEMBIMBING DUDI</div>
                <br><br><br>
                <div class="style9">(.............................................)</div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
</body>
</html>