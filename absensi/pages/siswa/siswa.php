<?php
include('koneksi.php');
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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

        h2 {
            color: #007bff;
        }

        .table thead th {
            background-color: #007bff;
            color: white;
        }

        .table tbody tr:hover {
            background-color: #e9ecef;
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
<h2 class="text-left">Data Siswa</h2>
    <div class="main-container container-custom">
        <a href="index.php?page=tambahsiswa" class="btn btn-primary mb-3">Tambah Siswa</a>
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">NISN</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Sekolah</th>
                        <th class="text-center">Tempat Prakerin</th>
                        <th class="text-center">Tanggal Mulai</th>
                        <th class="text-center">Tanggal Selesai</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Query untuk mengambil data siswa dengan urutan terbaru di atas
                    $sql = mysqli_query($coneksi, "
                    SELECT 
                        s.id_siswa, s.nis, s.nisn, s.nama_siswa,
                        s.tanggal_mulai, s.tanggal_selesai,
                        sk.nama_sekolah, 
                        p.nama_perusahaan
                    FROM siswa s
                    LEFT JOIN sekolah sk ON s.id_sekolah = sk.id_sekolah
                    LEFT JOIN perusahaan p ON s.id_perusahaan = p.id_perusahaan
                    ORDER BY s.id_siswa DESC, sk.nama_sekolah ASC, p.nama_perusahaan ASC
                ") or die(mysqli_error($coneksi));

                    if (mysqli_num_rows($sql) > 0) {
                        $no = 1;
                        while ($data = mysqli_fetch_assoc($sql)) {
                            // Format tanggal dari Y-m-d ke m-d-Y
                            $tanggal_mulai = !empty($data['tanggal_mulai']) ? date('m-d-Y', strtotime($data['tanggal_mulai'])) : '';
                            $tanggal_selesai = !empty($data['tanggal_selesai']) ? date('m-d-Y', strtotime($data['tanggal_selesai'])) : '';
                            
                            echo '
                        <tr style="text-align:center; cursor:pointer;" onclick="window.location=\'index.php?page=editsiswa1&id_siswa=' . $data['id_siswa'] . '\'">
                            <td class="text-center">' . $no++ . '</td>
                            <td>' . htmlspecialchars($data['nisn']) . '</td>
                            <td class="text-left">' . htmlspecialchars($data['nama_siswa']) . '</td>
                            <td class="text-left">' . htmlspecialchars($data['nama_sekolah']) . '</td>
                            <td class="text-left">' . htmlspecialchars($data['nama_perusahaan'] ?? '') . '</td>
                            <td>' . htmlspecialchars($tanggal_mulai) . '</td>
                            <td>' . htmlspecialchars($tanggal_selesai) . '</td>
                        </tr>';
                        }
                    } else {
                        echo '
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data.</td>
                        </tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- SweetAlert Flash Notifications -->
        <?php
        if (isset($_SESSION['flash_hapus']) && $_SESSION['flash_hapus'] == 'sukses') {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'info',
                    title: 'Sukses!',
                    text: 'Data siswa berhasil dihapus',
                    position: 'top',
                    showConfirmButton: false,
                    timer: 3000,
                    toast: true
                });
            });
        </script>";
            unset($_SESSION['flash_hapus']);
        }

        if (isset($_SESSION['flash_edit']) && $_SESSION['flash_edit'] == 'sukses') {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: 'Data siswa berhasil diupdate',
                    position: 'top',
                    showConfirmButton: false,
                    timer: 3000,
                    toast: true
                });
            });
        </script>";
            unset($_SESSION['flash_edit']);
        }

        if (isset($_SESSION['flash_tambah']) && $_SESSION['flash_tambah'] == 'sukses') {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: 'Data siswa berhasil ditambahkan',
                    position: 'top',
                    showConfirmButton: false,
                    timer: 3000,
                    toast: true
                });
            });
        </script>";
            unset($_SESSION['flash_tambah']);
        }

        if (isset($_SESSION['flash_error'])) {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '" . addslashes($_SESSION['flash_error']) . "',
                    position: 'top',
                    showConfirmButton: false,
                    timer: 3000,
                    toast: true
                });
            });
        </script>";
            unset($_SESSION['flash_error']);
        }

        if (isset($_SESSION['flash_duplikat'])) {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    text: 'ID siswa atau Username sudah terdaftar',
                    position: 'top',
                    showConfirmButton: false,
                    timer: 3000,
                    toast: true
                });
            });
        </script>";
            unset($_SESSION['flash_duplikat']);
        }
        ?>
</body>

</html>