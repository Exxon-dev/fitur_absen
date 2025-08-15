<?php
include('koneksi.php');

$id_guru = $_SESSION['id_guru'];

// Cek kalau BELUM login
if (!isset($_SESSION['id_guru'])) {
    header("Location: sign-in.php");
    exit();
}

// // Ambil ID sekolah dari session

// Query untuk mengambil daftar siswa berdasarkan ID sekolah
$query = "SELECT id_siswa, nama_siswa FROM siswa WHERE id_guru = '$id_guru'";
$result = mysqli_query($coneksi, $query);

// Cek jika query berhasil
if (!$result) {
    die('Query gagal: ' . mysqli_error($coneksi));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buka Laporan Siswa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
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

        h1 {
            color: #007bff;
        }

        .form-control {
            border: none;
            border-bottom: 2px solid #007bff;
            border-radius: 0;
            box-shadow: none;
        }

        .form-control:focus {
            border-color: #0056b3;
            box-shadow: none;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            color: #777;
        }

        .btn-warning {
            background-color: #ffc107;
            border: none;
        }

        .btn-warning:hover {
            background-color: #e0a800;
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
    <h1>Laporan Siswa</h1>
    <div class="main-container container-custom">
        <hr>

        <form id="myForm" action="pages/laporan/preview.php" method="GET" target="_blank">
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="siswa_search">Cari Nama Siswa:</label>
                    <input type="text" id="siswa_search" class="form-control" list="siswa_list" placeholder="Ketik nama siswa..." autocomplete="off">

                    <datalist id="siswa_list">
                        <?php
                        mysqli_data_seek($result, 0);
                        while ($row = mysqli_fetch_assoc($result)): ?>
                            <option value="<?= htmlspecialchars($row['nama_siswa']) ?>" data-id="<?= $row['id_siswa'] ?>">
                            <?php endwhile; ?>
                    </datalist>
                </div>

                <!-- Input hidden untuk menyimpan ID siswa -->
                <input type="hidden" name="id_siswa" id="selected_siswa_id">
                <div class="form-group col-md-6">
                    <label for="reportSelect">Pilih Laporan:</label>
                    <select id="reportSelect" name="page" class="form-control" required>
                        <option value="">-- Pilih Laporan --</option>
                        <option value="cover">Cover</option>
                        <option value="df">Daftar Hadir</option>
                        <option value="jr">Laporan Jurnal</option>
                        <option value="catatan">Lembar Catatan Kegiatan</option>
                        <option value="dn">Lembar Daftar Nilai</option>
                        <option value="sk">Lembar Surat Keterangan</option>
                        <option value="nkp">Lembar Nilai Kepuasan Pelanggan</option>
                        <option value="lp">Lembar Pengesahan</option>
                        <option value="bl">Lembar Bimbingan Laporan</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block mt-4">Priview</button>
        </form>

        <!-- Script untuk menangkap ID siswa -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#siswa_search').on('input', function() {
                    const selectedOption = $(`#siswa_list option[value="${$(this).val()}"]`);
                    if (selectedOption.length) {
                        $('#selected_siswa_id').val(selectedOption.data('id'));
                    }
                });
            });
        </script>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>

</body>

</html>