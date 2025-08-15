<?php
include('../../koneksi.php');

$id_siswa = $_GET['id_siswa'] ?? null;
if (!$id_siswa) {
    echo "ID siswa tidak ditemukan.";
    exit();
}

$query_siswa = "
    SELECT 
        s.nama_siswa, 
        s.nisn
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
    $nisn = htmlspecialchars($data_siswa['nisn']);
} else {
    echo "Data siswa tidak ditemukan.";
    exit();
}

$query_absen = "
    SELECT 
        a.tanggal, 
        a.jam_masuk, 
        a.jam_keluar, 
        a.keterangan 
    FROM 
        absen a 
    WHERE 
        a.id_siswa = ? 
    ORDER BY a.tanggal
";

$stmt_absen = $coneksi->prepare($query_absen);
$stmt_absen->bind_param("i", $id_siswa);
$stmt_absen->execute();
$result_absen = $stmt_absen->get_result();

$kehadiran = [];
while ($row = $result_absen->fetch_assoc()) {
    $kehadiran[] = $row;
}

$stmt_siswa->close();
$stmt_absen->close();
$coneksi->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Hadir Praktik Kerja Lapangan</title>
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

        .style6 {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 16px;
        }

        .style9 {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 11px;
        }

        .style27 {
            font-family: Verdana, Arial, Helvetica, sans-serif;
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

        .align-justify {
            text-align: justify;
        }

        .align-center {
            text-align: center;
        }

        .align-right {
            text-align: right;
        }

        /* Atur alignment tiap kolom tabel */
        table td:nth-child(1),
        table th:nth-child(1) {
            text-align: center;
        }

        table td:nth-child(2),
        table th:nth-child(2) {
            text-align: left;
            padding-left: 5px;
        }

        table td:nth-child(3),
        table td:nth-child(4),
        table th:nth-child(3),
        table th:nth-child(4) {
            text-align: center;
        }

        table td:nth-child(5),
        table th:nth-child(5) {
            text-align: left;
            padding-left: 5px;
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
    <div class="printable">
        <div class="text-center mb-8">
            <p class="font-bold style9">DAFTAR HADIR PESERTA DIDIK PRAKTIK KERJA LAPANGAN</p>
        </div>
        <table cellspacing="2">
            <div class="style9 mb-4">
                <p>NAMA: <?php echo $nama_siswa; ?></p>
                <p>NISN: <?php echo $nisn; ?></p>
            </div>
        </table>
        <table width="96%" border="1" align="center" cellpadding="3" cellspacing="0" class="style9">
            <thead>
                <tr height="35">
                    <th class="left bottom top">No</th>
                    <th class="left bottom top">Hari/Tanggal</th>
                    <th class="left bottom top">MASUK</th>
                    <th class="left bottom top">PULANG</th>
                    <th class="left bottom top right">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($kehadiran as $record) {
                    echo "<tr height='25' class='style27'>
                        <td class='top bottom left text-center'>{$no}</td>
                        <td class='top bottom left text-center'>" . htmlspecialchars($record['tanggal']) . "</td>
                        <td class='top bottom left text-center'>" . htmlspecialchars($record['jam_masuk']) . "</td>
                        <td class='top bottom left text-center'>" . htmlspecialchars($record['jam_keluar'] ?? '') . "</td>
                        <td class='top bottom left right text-center'>" . htmlspecialchars($record['keterangan']) . "</td>
                    </tr>";
                    $no++;
                }
                ?>
                <tr>
                    <td colspan="5">&nbsp;</td>
                </tr>
            </tbody>
        </table>
        <div class="flex justify-end items-center mb-8">
            <div class="text-center">
                <div class="style9">Kajoran, ..................... 202...</div>
                <div class="style9">PEMBIMBING DUDI</div>
                <br><br><br>
                <div class="style9">(.............................................)</div>
            </div>
        </div>
    </div>
</body>

</html>
