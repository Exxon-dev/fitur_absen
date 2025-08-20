<?php
include('koneksi.php');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if pembimbing is logged in
if (!isset($_SESSION['id_perusahaan'])) {
    header("Location: ../sign-in.php");
    exit();
}

// Set timezone
date_default_timezone_set('Asia/Jakarta');

// Data hari
$hariInggris = date('l');
$hariIndo = [
    'Sunday'    => 'Minggu',
    'Monday'    => 'Senin',
    'Tuesday'   => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday'  => 'Kamis',
    'Friday'    => 'Jumat',
    'Saturday'  => 'Sabtu'
];
$hari = $hariIndo[$hariInggris];
$id_perusahaan = $_SESSION['id_perusahaan'];
$tanggal = date('Y-m-d'); // Format tanggal disesuaikan dengan database (Y-m-d)
$batas_telat = '08:00:00'; // Batas waktu terlambat

// Query untuk mendapatkan semua data sekaligus
$query = mysqli_query($coneksi, "
    SELECT 
        s.id_siswa, 
        s.nama_siswa, 
        s.no_wa, 
        a.tanggal, 
        a.jam_masuk, 
        a.jam_keluar, 
        a.keterangan, 
        a.ip_address
    FROM siswa s
    LEFT JOIN absen a ON s.id_siswa = a.id_siswa AND DATE(a.tanggal) = '$tanggal'
    WHERE s.id_perusahaan = '$id_perusahaan'
    ORDER BY s.nama_siswa ASC
");

$num_rows = mysqli_num_rows($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Rekap Absensi - <?= htmlspecialchars(date('d-m-Y')) ?></title>
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

        h3 {
            color: #007bff;
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

        .badge-belum {
            background-color: #FFCDD2;
            color: #B71C1C;
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

        .btn-wa {
            background-color: #128C7E;
            color: white;
        }

        .btn-wa:hover {
            background-color: #145A32;
            color: white;
        }

        .table-light th {
            background-color: #007bff;
            color: white;
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

        .table tbody tr:hover {
            background-color: #e9ecef;
        }

        .ip-info {
            font-family: monospace;
            font-size: 0.9em;
        }

        .empty-message {
            text-align: center;
            padding: 20px;
            color: #6c757d;
            font-style: italic;
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
    <h3 class="text-bold my-4">Rekap Absensi <?= htmlspecialchars(date('m-d-Y')) ?></h3>
    <div class="main-container container-custom">
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama Siswa</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Jam Masuk</th>
                        <th class="text-center">Jam Keluar</th>
                        <th class="text-center">IP Address</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($num_rows > 0): ?>
                        <?php
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($query)):
                            $id = $row['id_siswa'];
                            $nama = htmlspecialchars($row['nama_siswa']);

                            // Initialize variables
                            $jam_masuk_display = '-';
                            $jam_keluar_display = '-';
                            $badge_class = 'badge-belum';
                            $status_icon = '<i class="bi bi-x-circle"></i>';
                            $status_text = 'Belum Absen';
                            $show_wa_button = false;
                            $pesan = null;
                            $ip_address = $row['ip_address'] ?? '-';

                            // Check if student has attendance data
                            if (!empty($row['tanggal'])) {
                                $jam_masuk_display = $row['jam_masuk'] ?? '-';
                                $jam_keluar_display = $row['jam_keluar'] ?? '-';
                                $keterangan = $row['keterangan'] ?? 'Hadir';

                                switch ($keterangan) {
                                    case 'sakit':
                                        $badge_class = 'badge-sakit';
                                        $status_icon = '<i class="bi bi-emoji-frown"></i>';
                                        $status_text = 'Sakit';
                                        $pesan = "🤒 Hai *$nama* , status absensi hari $hari (" . date('m-d-Y') . ") adalah SAKIT. Semoga lekas sembuh! 🤒";
                                        $show_wa_button = true;
                                        break;
                                    case 'izin':
                                        $badge_class = 'badge-izin';
                                        $status_icon = '<i class="bi bi-info-circle"></i>';
                                        $status_text = 'Izin';
                                        $pesan = "ℹ️ Hai *$nama* , status absensi hari $hari (" . date('m-d-Y') . ") adalah IZIN. Jangan lupa konfirmasi ke pembimbing! ℹ️";
                                        $show_wa_button = true;
                                        break;
                                    case 'alpa':
                                        $badge_class = 'badge-alpa';
                                        $status_icon = '<i class="bi bi-exclamation-triangle"></i>';
                                        $status_text = 'Alpa';
                                        $pesan = "⚠️ Hai *$nama* , status absensi hari $hari (" . date('m-d-Y') . ") adalah ALPA. Harap segera konfirmasi ke pembimbing! ⚠️";
                                        $show_wa_button = true;
                                        break;
                                    default:
                                        if (!empty($row['jam_masuk']) && $row['jam_masuk'] > $batas_telat) {
                                            $badge_class = 'badge-telat';
                                            $status_icon = '<i class="bi bi-clock-history"></i>';
                                            $status_text = 'Telat';
                                            $pesan = "⏰ Hai *$nama* , telat dalam melakukan absensi hari $hari (" . date('m-d-Y') . ") pada pukul {$row['jam_masuk']}. Jangan sampai telat lagi! ⏰";
                                            $show_wa_button = true;
                                        } else if (!empty($row['jam_masuk'])) {
                                            $badge_class = 'badge-hadir';
                                            $status_icon = '<i class="bi bi-check-circle"></i>';
                                            $status_text = 'Hadir';
                                            $pesan = "✅ Hai *$nama* , absensi hari $hari (" . date('m-d-Y') . ") sudah tercatat. Terima kasih! ✅";
                                            $show_wa_button = false;
                                        }
                                }
                            } else {
                                $pesan = "📢 Hai *$nama* , kamu belum melakukan absen hari $hari (" . date('m-d-Y') . "). Harap segera absen!";
                                $show_wa_button = true;
                            }
                        ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= $nama ?></td>
                                <td class="text-center">
                                    <span class="badge-status <?= $badge_class ?>">
                                        <?= $status_icon ?> <?= $status_text ?>
                                    </span>
                                </td>
                                <td class="text-center"><?= $jam_masuk_display ?></td>
                                <td class="text-center"><?= $jam_keluar_display ?></td>
                                <td class="ip-info text-center"><?= htmlspecialchars($ip_address) ?></td>
                                <td class="text-center">
                                    <?php if ($show_wa_button && $pesan && !empty($row['no_wa'])): ?>
                                        <button class="btn btn-sm btn-wa" onclick="kirimNotifikasi('<?= addslashes($row['no_wa']) ?>', '<?= addslashes($pesan) ?>')">
                                            <i class="bi bi-whatsapp"></i> Kirim WA
                                        </button>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="empty-message">
                                <i class="bi bi-exclamation-circle"></i> Tidak ada data siswa ditemukan
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- SweetAlert for notifications -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        async function kirimNotifikasi(no, pesan) {
            if (!no) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Nomor WhatsApp tidak tersedia',
                });
                return;
            }

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
                confirmButtonColor: '#145A32'
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