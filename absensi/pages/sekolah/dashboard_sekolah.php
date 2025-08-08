<?php
include "koneksi.php";;

// Mendapatkan ID sekolah yang sedang login
$id_sekolah = $_SESSION['id_sekolah'] ?? null;
$nama_sekolah = $_SESSION['nama_sekolah'] ?? null;

// Jika tidak ada sekolah yang login, arahkan ke halaman sign-in
if (!$id_sekolah) {
    header("Location: sign-in.php");
    exit();
}

// Mengambil data sekolah
$stmt = mysqli_prepare($coneksi, "SELECT nama_sekolah FROM sekolah WHERE id_sekolah = ?");
mysqli_stmt_bind_param($stmt, "i", $id_sekolah);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$sekolah = mysqli_fetch_assoc($result);
$nama_sekolah = $sekolah ? $sekolah['nama_sekolah'] : "Sekolah";

// Mengambil data siswa yang terkait dengan sekolah yang sedang login
$query_siswa = mysqli_query($coneksi, "SELECT * FROM siswa WHERE id_sekolah = '$id_sekolah' ORDER BY id_siswa ASC") or die(mysqli_error($coneksi));

// Mengambil data sekolah (untuk statistik)
$query_sekolah = mysqli_query($coneksi, "SELECT * FROM sekolah ORDER BY id_sekolah ASC") or die(mysqli_error($coneksi));

// Mengambil data perusahaan (untuk statistik)
$query_perusahaan = mysqli_query($coneksi, "SELECT * FROM perusahaan ORDER BY id_perusahaan ASC") or die(mysqli_error($coneksi));

// Menghitung jumlah data
$jumlah_siswa = mysqli_num_rows($query_siswa);
$jumlah_sekolah = mysqli_num_rows($query_sekolah);
$jumlah_perusahaan = mysqli_num_rows($query_perusahaan);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Absensi Siswa - <?php echo htmlspecialchars($nama_sekolah); ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body {
            padding-left: 270px;
            transition: padding-left 0.3s;
            background-color: #f8f9fa;
        }

        .main-container {
            margin-top: 20px;
            margin-right: 20px;
            margin-left: 0;
            max-width: none;
        }

        .container-custom {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        h3 {
            color: #007bff
        }

        h2 {
            color: #007bff
        }

        /* Card Styles */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .card-header {
            border-radius: 10px 10px 0 0 !important;
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        

        /* Status Badges */
        .badge-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: bold;
            display: inline-block;
            text-align: center;
            min-width: 70px;
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

        .badge-hadir {
            background-color: #C8E6C9;
            color: #1B5E20;
        }

        .badge-belum {
            background-color: #E0E0E0;
            color: #424242;
        }

        /* Table Styles */
        .table-responsive {
            margin-top: 20px;
        }

        .table thead th {
            background-color: #007bff;
            color: white;
            border: none;
        }

        .table tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
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

        /* Mobile Card View */
        .student-cards {
            display: none;
        }

        .student-card {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .student-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .student-name {
            font-weight: bold;
        }

        .radio-section {
            display: flex;
            justify-content: space-between;
        }

        @media (max-width: 991px) {
            body {
                padding-left: 0;
            }

            .main-container {
                margin: 15px;
            }

            .table-responsive {
                display: none;
            }

            .student-cards {
                display: block;
            }
        }

        @media (max-width: 576px) {
            .radio-section {
                flex-direction: column;
            }
            
            .radio-label {
                margin-bottom: 8px;
            }
        }
    </style>
</head>

<body>
    <!-- Main content -->
    <div class="main-container container-custom">
        <div class="text-center">
            <h3>Selamat datang di sistem absensi siswa</h3>
            <h3><?php echo htmlspecialchars($nama_sekolah, ENT_QUOTES); ?></h3>
        </div>
        <hr>
        
        <!-- Statistik Cards -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-3 pt-2">
                            <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons">group</i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">Siswa</p>
                                <h4 class="mb-0"><?php echo $jumlah_siswa; ?></h4>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-3 pt-2">
                            <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons">school</i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">Sekolah</p>
                                <h4 class="mb-0"><?php echo $jumlah_sekolah; ?></h4>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6">
                    <div class="card">
                        <div class="card-header p-3 pt-2">
                            <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons">location_city</i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">Perusahaan</p>
                                <h4 class="mb-0"><?php echo $jumlah_perusahaan; ?></h4>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                    </div>
                </div>
            </div>
        </div>

        <h2 class="text-center my-4">Absensi Siswa</h2>

        <!-- Desktop Table View -->
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Status</th>
                        <th>Sakit</th>
                        <th>Izin</th>
                        <th>Alpa</th>
                        <th>Hadir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $dataSiswa = mysqli_query($coneksi, "SELECT * FROM siswa WHERE id_sekolah = '$id_sekolah' ORDER BY id_siswa ASC") or die(mysqli_error($coneksi));
                    $index = 1;
                    $today = date('Y-m-d');

                    while ($siswa = mysqli_fetch_assoc($dataSiswa)) {
                        $attendanceQuery = mysqli_query($coneksi, "SELECT keterangan FROM absen WHERE id_siswa = {$siswa['id_siswa']} AND tanggal = '$today'") or die(mysqli_error($coneksi));
                        $attendance = mysqli_fetch_assoc($attendanceQuery);

                        $keterangan = $attendance['keterangan'] ?? null;
                        $badgeClass = 'badge-belum';
                        $statusText = 'Belum Absen';

                        if ($keterangan) {
                            switch ($keterangan) {
                                case 'sakit':
                                    $badgeClass = 'badge-sakit';
                                    $statusText = 'Sakit';
                                    break;
                                case 'izin':
                                    $badgeClass = 'badge-izin';
                                    $statusText = 'Izin';
                                    break;
                                case 'alpa':
                                    $badgeClass = 'badge-alpa';
                                    $statusText = 'Alpa';
                                    break;
                                default:
                                    $badgeClass = 'badge-hadir';
                                    $statusText = 'Hadir';
                            }
                        }

                        echo '
                        <tr>
                            <td>' . $index . '</td>
                            <td>' . htmlspecialchars($siswa['nama_siswa']) . '</td>
                            <td><span class="badge-status ' . $badgeClass . '">' . $statusText . '</span></td>
                            <td>
                                <label class="radio-label disabled">
                                    <input type="radio" name="absen_' . $siswa['id_siswa'] . '" value="sakit" ' . ($keterangan === 'sakit' ? 'checked' : '') . ' disabled>
                                    <span>Sakit</span>
                                </label>
                            </td>
                            <td>
                                <label class="radio-label disabled">
                                    <input type="radio" name="absen_' . $siswa['id_siswa'] . '" value="izin" ' . ($keterangan === 'izin' ? 'checked' : '') . ' disabled>
                                    <span>Izin</span>
                                </label>
                            </td>
                            <td>
                                <label class="radio-label disabled">
                                    <input type="radio" name="absen_' . $siswa['id_siswa'] . '" value="alpa" ' . ($keterangan === 'alpa' ? 'checked' : '') . ' disabled>
                                    <span>Alpa</span>
                                </label>
                            </td>
                            <td>
                                <label class="radio-label disabled">
                                    <input type="radio" name="absen_' . $siswa['id_siswa'] . '" value="hadir" ' . (!$keterangan || $keterangan === 'hadir' ? 'checked' : '') . ' disabled>
                                    <span>Hadir</span>
                                </label>
                            </td>
                        </tr>
                        ';
                        $index++;
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="student-cards">
            <?php
            // Reset query untuk mobile view
            $dataSiswa = mysqli_query($coneksi, "SELECT * FROM siswa WHERE id_sekolah = '$id_sekolah' ORDER BY id_siswa ASC") or die(mysqli_error($coneksi));
            $index = 1;
            $today = date('Y-m-d');

            while ($siswa = mysqli_fetch_assoc($dataSiswa)) {
                $attendanceQuery = mysqli_query($coneksi, "SELECT keterangan FROM absen WHERE id_siswa = {$siswa['id_siswa']} AND tanggal = '$today'") or die(mysqli_error($coneksi));
                $attendance = mysqli_fetch_assoc($attendanceQuery);

                $keterangan = $attendance['keterangan'] ?? null;
                $badgeClass = 'badge-belum';
                $statusText = 'Belum Absen';

                if ($keterangan) {
                    switch ($keterangan) {
                        case 'sakit':
                            $badgeClass = 'badge-sakit';
                            $statusText = 'Sakit';
                            break;
                        case 'izin':
                            $badgeClass = 'badge-izin';
                            $statusText = 'Izin';
                            break;
                        case 'alpa':
                            $badgeClass = 'badge-alpa';
                            $statusText = 'Alpa';
                            break;
                        default:
                            $badgeClass = 'badge-hadir';
                            $statusText = 'Hadir';
                    }
                }

                echo '
                <div class="student-card">
                    <div class="student-header">
                        <div>
                            <div class="student-name">' . $index . '. ' . htmlspecialchars($siswa['nama_siswa']) . '</div>
                        </div>
                        <span class="badge-status ' . $badgeClass . '">' . $statusText . '</span>
                    </div>
                    
                    <div class="radio-section">
                        <label class="radio-label disabled">
                            <input type="radio" name="absen_mobile_' . $siswa['id_siswa'] . '" value="hadir" ' . (!$keterangan || $keterangan === 'hadir' ? 'checked' : '') . ' disabled>
                            <span>Hadir</span>
                        </label>
                        <label class="radio-label disabled">
                            <input type="radio" name="absen_mobile_' . $siswa['id_siswa'] . '" value="sakit" ' . ($keterangan === 'sakit' ? 'checked' : '') . ' disabled>
                            <span>Sakit</span>
                        </label>
                        <label class="radio-label disabled">
                            <input type="radio" name="absen_mobile_' . $siswa['id_siswa'] . '" value="izin" ' . ($keterangan === 'izin' ? 'checked' : '') . ' disabled>
                            <span>Izin</span>
                        </label>
                        <label class="radio-label disabled">
                            <input type="radio" name="absen_mobile_' . $siswa['id_siswa'] . '" value="alpa" ' . ($keterangan === 'alpa' ? 'checked' : '') . ' disabled>
                            <span>Alpa</span>
                        </label>
                    </div>
                </div>
                ';
                $index++;
            }
            ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const namaSekolah = "<?php echo !empty($nama_sekolah) ? htmlspecialchars($nama_sekolah, ENT_QUOTES) : 'Sekolah'; ?>";

            // Cek apakah alert sudah pernah ditampilkan
            if (!localStorage.getItem('sekolahAlertShown')) {
                Swal.fire({
                    title: `Selamat datang ${namaSekolah}!`,
                    text: "Anda berhasil login ke sistem",
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    toast: true,
                    background: '#f8f9fa',
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });

                // Set flag di localStorage bahwa alert sudah ditampilkan
                localStorage.setItem('sekolahAlertShown', 'true');
            }
        });
    </script>
</body>
</html>