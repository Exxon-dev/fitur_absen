<?php
include "koneksi.php";

// Cek login guru
if (!isset($_SESSION['id_guru'])) {
    header("Location: sign-in.php");
    exit();
}

$id_guru = $_SESSION['id_guru'];

// Ambil data guru & sekolah
$stmt = mysqli_prepare($coneksi, "SELECT id_sekolah, nama_guru FROM guru WHERE id_guru = ?");
mysqli_stmt_bind_param($stmt, "i", $id_guru);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$dataGuru = mysqli_fetch_assoc($result);

if (!$dataGuru) {
    header("Location: sign-in.php");
    exit();
}

$id_sekolah = $dataGuru['id_sekolah'];
$nama_guru = $dataGuru['nama_guru'];

$tanggal = date('Y-m-d');

// Ambil data siswa yang dibimbing guru ini
$query_siswa = mysqli_query($coneksi, "SELECT * FROM siswa WHERE id_guru = '$id_guru' ORDER BY id_siswa ASC") or die(mysqli_error($coneksi));
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Absensi Siswa - <?php echo htmlspecialchars($nama_guru); ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            padding-left: 270px;
            /* tetap untuk desktop */
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
        }

        .body-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
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

        .badge-belum {
            background-color: #E0E0E0;
            color: #424242;
        }

        .table-responsive {
            border: none !important;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table td,
        .table th {
            border: 1px solid #dee2e6 !important;
            vertical-align: middle;
        }

        /* ===== Responsif untuk layar kecil (mobile/tablet) ===== */
        @media (max-width: 768px) {
            body {
                padding-left: 0;
                /* hilangkan padding kiri agar konten muat penuh */
            }

            .body-card {
                padding: 15px;
                margin-bottom: 15px;
            }

            /* Supaya tabel bisa digulir horizontal */
            .table-responsive {
                overflow-x: scroll;
            }

            /* Ukuran font tabel bisa disesuaikan agar muat */
            .table td,
            .table th {
                font-size: 14px;
                padding: 8px;
            }

            /* Jika kamu ingin badge lebih kecil */
            .badge-sakit,
            .badge-izin,
            .badge-alpa,
            .badge-belum {
                font-size: 0.8rem;
                padding: 4px 8px;
            }
        }
    </style>
</head>

<body class="row">
    <div class="body">
        <div class="body-card">
            <div class="container my-70">
                <a href="index.php?page=tambah_siswa" class="btn btn-primary"><i class="fas fa-plus"></i>tambah</a>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="thead-primary bg-primary text-white">
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Status</th>
                                <th>Sakit</th>
                                <th>Izin</th>
                                <th>Alpa</th>
                            </tr>
                        </thead>
<tbody>
    <?php
    $index = 1;
    // Reset pointer result set ke awal
    mysqli_data_seek($query_siswa, 0);
    
    while ($siswa = mysqli_fetch_assoc($query_siswa)) {
        // Pastikan format tanggal sesuai database (YYYY-MM-DD)
        $tanggal = date('Y-m-d'); // Contoh, sesuaikan dengan kebutuhan
        
        // Query untuk mendapatkan data absen
        $query_absen = mysqli_query($coneksi, 
            "SELECT keterangan FROM absen 
             WHERE id_siswa = '".$siswa['id_siswa']."' 
             AND tanggal = '".$tanggal."'");
        
        $absen = mysqli_fetch_assoc($query_absen);
        $keterangan = isset($absen['keterangan']) ? $absen['keterangan'] : null;

        // Tentukan kelas badge dan teks status
        $badgeClass = 'badge-secondary'; // Default: Belum absen
        $statusText = 'Belum Absen';
        
        if ($keterangan) {
            switch (strtolower($keterangan)) {
                case 'hadir':
                    $badgeClass = 'badge-success';
                    $statusText = 'Hadir';
                    break;
                case 'sakit':
                    $badgeClass = 'badge-warning';
                    $statusText = 'Sakit';
                    break;
                case 'izin':
                    $badgeClass = 'badge-info';
                    $statusText = 'Izin';
                    break;
                case 'alpa':
                    $badgeClass = 'badge-danger';
                    $statusText = 'Alpa';
                    break;
            }
        }
    ?>
    <tr>
        <td><?= $index; ?></td>
        <td><?= htmlspecialchars($siswa['nama_siswa']); ?></td>
        <td>
            <span class="badge <?= $badgeClass; ?>">
                <?= $statusText; ?>
            </span>
        </td>
        <td><input type="radio" name="absen_<?= $siswa['id_siswa']; ?>" value="sakit" <?= ($keterangan === 'sakit') ? 'checked' : ''; ?> disabled></td>
        <td><input type="radio" name="absen_<?= $siswa['id_siswa']; ?>" value="izin" <?= ($keterangan === 'izin') ? 'checked' : ''; ?> disabled></td>
        <td><input type="radio" name="absen_<?= $siswa['id_siswa']; ?>" value="alpa" <?= ($keterangan === 'alpa') ? 'checked' : ''; ?> disabled></td>
    </tr>
    <?php
        $index++;
    }
    ?>
</tbody>    
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>