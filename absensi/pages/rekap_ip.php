<?php
include('koneksi.php');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['id_perusahaan'])) {
    header("Location: ../sign-in.php");
    exit();
}

// Set timezone
date_default_timezone_set('Asia/Jakarta');

$tanggal = date('Y-m-d');
$id_perusahaan = $_SESSION['id_perusahaan'];

// Modified query to only show students supervised by this pembimbing
$query = mysqli_query($coneksi, "
    SELECT s.nama_siswa, s.no_wa, a.tanggal, a.jam_masuk, a.jam_keluar, 
           a.keterangan, a.ip_address, a.lokasi, a.koordinat
    FROM absen a
    INNER JOIN siswa s ON a.id_siswa = s.id_siswa
    WHERE a.tanggal = '$tanggal'
    AND s.id_perusahaan = '$id_perusahaan'
    ORDER BY a.jam_masuk ASC
");

// Count number of rows
$num_rows = mysqli_num_rows($query);

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap IP dan Lokasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        /* Penyesuaian posisi */
        body {
            padding-left: 270px;
            transition: padding-left 0.3s;
            background-color: #f8f9fa;
        }

        .main-container {
            margin-top: 20px;
            margin-right: 20px;
            margin-left: 0;
            width: auto;
            max-width: none;
        }

        /* Style asli */
        .container-custom {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .table-responsive {
            margin-top: 20px;
        }

        .badge-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: bold;
        }

        .badge-hadir {
            background-color: #C8E6C9;
            color: #1B5E20;
        }

        .badge-telat {
            background-color: #FFECB3;
            color: #FF8F00;
        }

        .badge-sakit {
            background-color: #FFE0B2;
            color: #E65100;
        }

        .badge-izin {
            background-color: #BBDEFB;
            color: #0D47A1;
        }

        .badge-alpa {
            background-color: #FFCDD2;
            color: #B71C1C;
        }

        .table-light th {
            background-color: #007bff;
            color: white;
        }

        .table tbody tr:hover {
            background-color: #e9ecef;
        }

        .empty-message {
            text-align: center;
            padding: 20px;
            color: #6c757d;
            font-style: italic;
        }

        .ip-info {
            font-family: monospace;
            font-size: 0.9em;
        }

        @media (max-width: 991px) {
            body {
                padding-left: 0;
            }

            .main-container {
                margin-right: 15px;
                margin-left: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="main-container container-custom">
        <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
            <div class="text-center"></div>
            <h2 class="text-primary"><i class="bi bi-clock-history"></i> Rekap IP <?= htmlspecialchars($tanggal) ?></h2>
            <a href="javascript:window.location.reload()" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-arrow-clockwise"></i> Refresh
            </a>
        </div><br>

        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Status</th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        <th>IP Address</th>
                        <th>Lokasi</th>
                        <th>Koordinat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($num_rows > 0): ?>
                        <?php 
                        $no = 1;
                        $batas_telat = '08:00:00';
                        while ($row = mysqli_fetch_assoc($query)): 
                            // Determine status badge
                            $keterangan = $row['keterangan'] ?? 'Hadir';
                            $jam_masuk = $row['jam_masuk'] ?? null;
                            
                            switch ($keterangan) {
                                case 'sakit':
                                    $badge_class = 'badge-sakit';
                                    $status_icon = '<i class="bi bi-emoji-frown"></i>';
                                    $status_text = 'Sakit';
                                    break;
                                case 'izin':
                                    $badge_class = 'badge-izin';
                                    $status_icon = '<i class="bi bi-info-circle"></i>';
                                    $status_text = 'Izin';
                                    break;
                                case 'alpa':
                                    $badge_class = 'badge-alpa';
                                    $status_icon = '<i class="bi bi-exclamation-triangle"></i>';
                                    $status_text = 'Alpa';
                                    break;
                                default:
                                    if ($jam_masuk && $jam_masuk > $batas_telat) {
                                        $badge_class = 'badge-telat';
                                        $status_icon = '<i class="bi bi-clock-history"></i>';
                                        $status_text = 'Telat';
                                    } else {
                                        $badge_class = 'badge-hadir';
                                        $status_icon = '<i class="bi bi-check-circle"></i>';
                                        $status_text = 'Hadir';
                                    }
                            }
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['nama_siswa']) ?></td>
                                <td>
                                    <span class="badge-status <?= $badge_class ?>">
                                        <?= $status_icon ?> <?= $status_text ?>
                                    </span>
                                </td>
                                <td><?= $row['jam_masuk'] ?? '-' ?></td>
                                <td><?= $row['jam_keluar'] ?? '-' ?></td>
                                <td class="ip-info"><?= htmlspecialchars($row['ip_address'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($row['lokasi'] ?? '-') ?></td>
                                <td class="ip-info"><?= htmlspecialchars($row['koordinat'] ?? '-') ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="empty-message">
                                <i class="bi bi-exclamation-circle"></i> Siswa belum absen
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>