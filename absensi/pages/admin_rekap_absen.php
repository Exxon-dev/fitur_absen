<?php
include('koneksi.php');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set timezone
date_default_timezone_set('Asia/Jakarta');

$tanggal = date('Y-m-d');
$batas_telat = '08:00:00'; // Batas waktu terlambat

// Get all students with prepared statement
$query_siswa = mysqli_prepare($coneksi, "SELECT id_siswa, nama_siswa, no_wa FROM siswa ORDER BY nama_siswa");
mysqli_stmt_execute($query_siswa);
$result_siswa = mysqli_stmt_get_result($query_siswa);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Rekap Absensi - <?= htmlspecialchars($tanggal) ?></title>
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

        .status-hadir {
            color: #28a745;
        }

        .status-telat {
            color: #ffc107;
        }

        .status-belum {
            color: #dc3545;
        }

        .btn-wa {
            background-color: #25D366;
            color: white;
        }

        .btn-wa:hover {
            background-color: #128C7E;
            color: white;
        }

        .table-light th {
            background-color: #007bff;
            color: white;
        }

        .tabletbody tr:hover {
            background-color: #e9ecef;
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
            <h2 class="text-primary"><i class="bi bi-calendar-check"></i> Rekap Absensi <?= htmlspecialchars($tanggal) ?></h2>
            <a href="javascript:window.location.reload()" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-arrow-clockwise"></i> Refresh
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Nama Siswa</th>
                        <th>No WA</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($siswa = mysqli_fetch_assoc($result_siswa)): ?>
                        <?php
                        $id = $siswa['id_siswa'];
                        $nama = htmlspecialchars($siswa['nama_siswa']);
                        $wa = htmlspecialchars($siswa['no_wa']);

                        // Check attendance with prepared statement
                        $query_absen = mysqli_prepare($coneksi, "SELECT jam_masuk FROM absen WHERE id_siswa = ? AND tanggal = ?");
                        mysqli_stmt_bind_param($query_absen, "is", $id, $tanggal);
                        mysqli_stmt_execute($query_absen);
                        $result_absen = mysqli_stmt_get_result($query_absen);
                        $absen = mysqli_fetch_assoc($result_absen);

                        if (!$absen) {
                            $status_class = 'status-belum';
                            $status_icon = '<i class="bi bi-x-circle"></i>';
                            $status_text = 'Belum Absen';
                            $pesan = "📢 Hai *$nama* ,kamu belum melakukan absen hari ini ($tanggal). Harap segera absen!";
                        } elseif ($absen['jam_masuk'] > $batas_telat) {
                            $status_class = 'status-telat';
                            $status_icon = '<i class="bi bi-exclamation-triangle"></i>';
                            $status_text = 'Telat';
                            $pesan = "⚠️ Hai *$nama* , telat dalam melakukan absensi pada pukul {$absen['jam_masuk']}. Jangan sampai telat lagi!⚠️";
                        } else {
                            $status_class = 'status-hadir';
                            $status_icon = '<i class="bi bi-check-circle"></i>';
                            $status_text = 'Hadir';
                            $pesan = null;
                        }
                        ?>
                        <tr>
                            <td><?= $nama ?></td>
                            <td><?= substr($wa, 0, 6) ?>...</td>
                            <td class="<?= $status_class ?>">
                                <?= $status_icon ?> <?= $status_text ?>
                            </td>
                            <td>
                                <?php if ($pesan && !empty($wa)): ?>
                                    <button class="btn btn-sm btn-wa" onclick="kirimNotifikasi('<?= addslashes($wa) ?>', '<?= addslashes($pesan) ?>')">
                                        <i class="bi bi-whatsapp"></i> Kirim WA
                                    </button>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif ?>
                            </td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- SweetAlert for notifications -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        async function kirimNotifikasi(no, pesan) {
            // Show confirmation dialog
            const {
                isConfirmed
            } = await Swal.fire({
                title: 'Kirim Notifikasi?',
                html: `<p>Kirim pesan ke <b>${no}</b>?</p>
                  <textarea class="form-control mt-2" readonly>${pesan}</textarea>`,
                icon: 'question',
                showCancelButton: true,
                cancelButtonText: 'Batal',
                confirmButtonText: 'Kirim',
                confirmButtonColor: '#25D366'
            });

            if (!isConfirmed) return;

            // Show loading
            Swal.fire({
                title: 'Mengirim...',
                html: 'Sedang mengirim notifikasi WhatsApp',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {
                const response = await fetch('pages/kirim_notif_manual.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        wa: no,
                        pesan: pesan
                    })
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.error || 'Gagal mengirim pesan');
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Notifikasi berhasil dikirim',
                    timer: 2000
                });
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: error.message,
                    footer: 'Periksa koneksi atau coba lagi nanti'
                });
            }
        }
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>