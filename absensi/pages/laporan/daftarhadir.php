<?php
include('../../koneksi.php');

// Ambil parameter filter dari URL
$id_siswa = $_GET['id_siswa'] ?? null;
$filter_type = $_GET['filter_type'] ?? 'daily';
$start_date = $_GET['start_date'] ?? date('Y-m-d');
$end_date = $_GET['end_date'] ?? date('Y-m-d');
$month = $_GET['month'] ?? date('m');
$year = $_GET['year_monthly'] ?? $_GET['year_yearly'] ?? date('Y');

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

// Query absen dengan filter
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
";

// Tambahkan kondisi berdasarkan jenis filter
if ($filter_type == 'daily') {
    $query_absen .= " AND a.tanggal BETWEEN ? AND ? ";
} elseif ($filter_type == 'monthly') {
    $query_absen .= " AND MONTH(a.tanggal) = ? AND YEAR(a.tanggal) = ? ";
} elseif ($filter_type == 'yearly') {
    $query_absen .= " AND YEAR(a.tanggal) = ? ";
}

$query_absen .= " ORDER BY a.tanggal";

$stmt_absen = $coneksi->prepare($query_absen);

// Bind parameter berdasarkan jenis filter
if ($filter_type == 'daily') {
    $stmt_absen->bind_param("iss", $id_siswa, $start_date, $end_date);
} elseif ($filter_type == 'monthly') {
    $stmt_absen->bind_param("iis", $id_siswa, $month, $year);
} elseif ($filter_type == 'yearly') {
    $stmt_absen->bind_param("ii", $id_siswa, $year);
}

$stmt_absen->execute();
$result_absen = $stmt_absen->get_result();

$kehadiran = [];
while ($row = $result_absen->fetch_assoc()) {
    $kehadiran[] = $row;
}

// Buat judul berdasarkan jenis filter
if ($filter_type == 'daily') {
    $judul_periode = "Tanggal: " . date('d-m-Y', strtotime($start_date)) . " - " . date('d-m-Y', strtotime($end_date));
} elseif ($filter_type == 'monthly') {
    $month_names = [
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    ];
    $judul_periode = "Bulan: " . $month_names[$month - 1] . " " . $year;
} elseif ($filter_type == 'yearly') {
    $judul_periode = "Tahun: " . $year;
}

$stmt_siswa->close();
$stmt_absen->close();
$coneksi->close();

// Fungsi untuk format tanggal menjadi dd-mm-yyyy
function formatTanggalSimple($tanggal)
{
    if (empty($tanggal)) return '';
    return date('d-m-Y', strtotime($tanggal));
}

// Jika tidak ada data, tampilkan pesan error dan exit
if (count($kehadiran) === 0) {
    echo "
    <!DOCTYPE html>
    <html>
    <head>
        <title>Tidak Ada Data</title>
        <style>
            body { 
                font-family: Arial, sans-serif; 
                display: flex; 
                justify-content: center; 
                align-items: center; 
                height: 100vh; 
                margin: 0; 
            }
            .error-container { 
                text-align: center; 
                padding: 20px; 
                border: 1px solid #ccc; 
                border-radius: 5px; 
                background-color: #f8d7da;
                color: #721c24;
            }
            .btn { 
                margin-top: 15px; 
                padding: 8px 16px; 
                background-color: #007bff; 
                color: white; 
                border: none; 
                border-radius: 4px; 
                cursor: pointer; 
            }
            .btn:hover { 
                background-color: #0056b3; 
            }
        </style>
    </head>
    <body>
        <div class='error-container'>
            <h2>Tidak ada data kehadiran untuk periode yang dipilih</h2>
            <p>Periode: $judul_periode</p>
            <p>Nama: $nama_siswa</p>
            <button class='btn' onclick='window.close()'>Tutup</button>
        </div>
    </body>
    </html>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Hadir Praktik Kerja Lapangan - <?= $judul_periode ?></title>
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
            width: 5%;
        }

        table td:nth-child(2),
        table th:nth-child(2) {
            text-align: center;
            width: 25%;
        }

        table td:nth-child(3),
        table td:nth-child(4),
        table th:nth-child(3),
        table th:nth-child(4) {
            text-align: center;
            width: 15%;
        }

        table td:nth-child(5),
        table th:nth-child(5) {
            text-align: center;
            width: 40%;
        }

        .periode-info {
            text-align: center;
            font-size: 11px;
            margin-bottom: 10px;
            font-weight: bold;
        }
    </style>
    <script>
        function printReport() {
            window.print();
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <div class="printable">
        <div class="text-center mb-4">
            <p class="font-bold style9">DAFTAR HADIR PESERTA DIDIK PRAKTIK KERJA LAPANGAN</p>
            <div class="text-center font-bold style9" style="font-size: 11px; margin-bottom: 15px;">
                <?= $judul_periode ?>
            </div>
        </div>
            <div class="style9 mb-4 ml-7">
                <p>NAMA: <?php echo $nama_siswa; ?></p>
                <p>NISN: <?php echo $nisn; ?></p>
            </div>

        <table width="96%" border="1" align="center" cellpadding="3" cellspacing="0" class="style9">
            <thead>
                <tr height="35">
                    <th class="left bottom top">No</th>
                    <th class="left bottom top">Tanggal</th>
                    <th class="left bottom top">Masuk</th>
                    <th class="left bottom top">Pulang</th>
                    <th class="left bottom top right">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($kehadiran as $record) {
                    echo "<tr height='25' class='style27'>
                        <td class='top bottom left align-center'>{$no}</td>
                        <td class='top bottom left align-center'>" . formatTanggalSimple($record['tanggal']) . "</td>
                        <td class='top bottom left align-center'>" . htmlspecialchars($record['jam_masuk']) . "</td>
                        <td class='top bottom left align-center'>" . htmlspecialchars($record['jam_keluar'] ?? '') . "</td>
                        <td class='top bottom left right align-center'>" . htmlspecialchars($record['keterangan']) . "</td>
                    </tr>";
                    $no++;
                }

                // Tambahkan baris kosong jika kurang dari 15 data
                $remaining = 7 - count($kehadiran);
                for ($i = 0; $i < $remaining; $i++) {
                    echo "<tr height='25'>
                        <td class='top bottom left align-center'>&nbsp;</td>
                        <td class='top bottom left'></td>
                        <td class='top bottom left'></td>
                        <td class='top bottom left'></td>
                        <td class='top bottom left right'></td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="flex justify-end items-center mt-8">
            <div class="text-center">
                <div class="style9">.......... , ............... <?= date('Y') ?></div>
                <div class="style9">PEMBIMBING DUDI</div>
                <br><br><br>
                <div class="style9">(.............................................)</div>
            </div>
        </div>
    </div>
</body>

</html>