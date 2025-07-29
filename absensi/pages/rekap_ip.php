<?php
include('koneksi.php');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set timezone
date_default_timezone_set('Asia/Semarang');

$tanggal = date('Y-m-d');

$query = mysqli_query($coneksi, "
    SELECT s.nama_siswa, s.no_wa, a.tanggal, a.jam_masuk, a.jam_keluar, a.keterangan, a.ip_address, a.lokasi, a.koordinat
    FROM absen a
    INNER JOIN siswa s ON a.id_siswa = s.id_siswa
    WHERE a.tanggal = '$tanggal'
    ORDER BY a.jam_masuk ASC
");

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap IP dan Lokaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .table-responsive {
            margin-top: 20px;
        }
        .table-light th {
            background-color: #007bff;
            color: white;
        }
        .table tbody tr:hover {
            background-color: #e9ecef;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
            <div class="text-center"></div>
            <h2 class="text-primary"><i class="bi bi-clock-history"></i> Jam Masuk & Keluar <?= htmlspecialchars($tanggal) ?></h2>
            <a href="javascript:window.location.reload()" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-arrow-clockwise"></i> Refresh
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        <th>Koordinat</th>
                        <th>IP Address</th>
                        <th>Lokasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while ($row = mysqli_fetch_assoc($query)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['nama_siswa']) ?></td>
                            <td><?= htmlspecialchars($row['tanggal']) ?></td>
                            <td><?= $row['jam_masuk'] ?? '-' ?></td>
                            <td><?= $row['jam_keluar'] ?? '-' ?></td>
                            <td><?= htmlspecialchars($row['koordinat']) ?></td>
                            <td><?= htmlspecialchars($row['ip_address'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['lokasi'] ?? '-') ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
