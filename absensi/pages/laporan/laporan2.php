<?php
include('koneksi.php');

// Inisialisasi session jika belum ada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Cek kalau BELUM login
if (!isset($_SESSION['id_pembimbing'])) {
    header("Location: sign-in.php");
    exit();
}

// Ambil id_pembimbing dari session
$id_pembimbing = $_SESSION['id_pembimbing'];

// Query untuk mengambil daftar siswa berdasarkan ID pembimbing
$query = "SELECT id_siswa, nama_siswa FROM siswa WHERE id_pembimbing = '$id_pembimbing' ORDER BY nama_siswa";
$result = mysqli_query($coneksi, $query);

// Cek jika query berhasil
if (!$result) {
    die('Query gagal: ' . mysqli_error($coneksi));
}

// Set nilai default untuk filter
$filter_type = isset($_GET['filter_type']) ? $_GET['filter_type'] : 'daily';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');
$month = isset($_GET['month']) ? $_GET['month'] : date('m');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');
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

    h2 {
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

    .filter-option {
        display: none;
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
        <h2 class="text-center">Laporan Siswa</h2>
        <hr>

        <form id="myForm" action="pages/laporan/preview.php" method="GET" target="_blank">

            <div class="row">
                <div class="form-group col-md-6">
                    <label for="siswaSelect">Siswa:</label>
                    <select id="siswaSelect" name="id_siswa" class="form-control" required>
                        <option value="">Cari Siswa...</option>
                        <?php
                        // Reset pointer hasil query ke awal
                        mysqli_data_seek($result, 0);
                        
                        // Loop melalui hasil query
                        while ($row = mysqli_fetch_assoc($result)): 
                        ?>
                        <option value="<?= $row['id_siswa'] ?>">
                            <?= htmlspecialchars($row['nama_siswa']) ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label for="reportSelect">Laporan:</label>
                    <select id="reportSelect" name="page" class="form-control" required>
                        <option value="">Cari laporan...</option>
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

            <!-- Filter Type Selection -->
            <div class="form-group">
                <label for="filterType">Filter Berdasarkan:</label>
                <select id="filterType" name="filter_type" class="form-control" required>
                    <option value="daily" <?= $filter_type == 'daily' ? 'selected' : '' ?>>Harian</option>
                    <option value="monthly" <?= $filter_type == 'monthly' ? 'selected' : '' ?>>Bulanan</option>
                    <option value="yearly" <?= $filter_type == 'yearly' ? 'selected' : '' ?>>Tahunan</option>
                </select>
            </div>

            <!-- Daily Filter -->
            <div id="dailyFilter" class="filter-option">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="startDate">Tanggal Mulai:</label>
                        <input type="date" id="startDate" name="start_date" class="form-control" value="<?= $start_date ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="endDate">Tanggal Selesai:</label>
                        <input type="date" id="endDate" name="end_date" class="form-control" value="<?= $end_date ?>">
                    </div>
                </div>
            </div>

            <!-- Monthly Filter -->
            <div id="monthlyFilter" class="filter-option">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="monthSelect">Bulan:</label>
                        <select id="monthSelect" name="month" class="form-control">
                            <option value="01" <?= $month == '01' ? 'selected' : '' ?>>Januari</option>
                            <option value="02" <?= $month == '02' ? 'selected' : '' ?>>Februari</option>
                            <option value="03" <?= $month == '03' ? 'selected' : '' ?>>Maret</option>
                            <option value="04" <?= $month == '04' ? 'selected' : '' ?>>April</option>
                            <option value="05" <?= $month == '05' ? 'selected' : '' ?>>Mei</option>
                            <option value="06" <?= $month == '06' ? 'selected' : '' ?>>Juni</option>
                            <option value="07" <?= $month == '07' ? 'selected' : '' ?>>Juli</option>
                            <option value="08" <?= $month == '08' ? 'selected' : '' ?>>Agustus</option>
                            <option value="09" <?= $month == '09' ? 'selected' : '' ?>>September</option>
                            <option value="10" <?= $month == '10' ? 'selected' : '' ?>>Oktober</option>
                            <option value="11" <?= $month == '11' ? 'selected' : '' ?>>November</option>
                            <option value="12" <?= $month == '12' ? 'selected' : '' ?>>Desember</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="yearSelectMonthly">Tahun:</label>
                        <select id="yearSelectMonthly" name="year_monthly" class="form-control">
                            <?php
                            $currentYear = date('Y');
                            for ($i = $currentYear - 2; $i <= $currentYear + 5; $i++) {
                                $selected = ($i == $year) ? 'selected' : '';
                                echo "<option value='$i' $selected>$i</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Yearly Filter -->
            <div id="yearlyFilter" class="filter-option">
                <div class="form-group">
                    <label for="yearSelectYearly">Tahun:</label>
                    <select id="yearSelectYearly" name="year_yearly" class="form-control">
                        <?php
                        $currentYear = date('Y');
                        for ($i = $currentYear - 2; $i <= $currentYear + 5; $i++) {
                            $selected = ($i == $year) ? 'selected' : '';
                            echo "<option value='$i' $selected>$i</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block mt-4">Preview</button>
        </form>

        <!-- Choices.js -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
        <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Choices.js for selects
            const siswaSelect = new Choices('#siswaSelect', {
                searchEnabled: true,
                searchPlaceholderValue: 'Ketik nama siswa...',
                itemSelectText: 'Pilih',
                noResultsText: 'Siswa tidak ditemukan',
                noChoicesText: 'Tidak ada data siswa'
            });

            const reportSelect = new Choices('#reportSelect', {
                searchEnabled: true,
                searchPlaceholderValue: 'Cari laporan...',
                shouldSort: false,
                itemSelectText: 'Pilih',
                noResultsText: 'Laporan tidak ditemukan',
                noChoicesText: 'Tidak ada pilihan lain'
            });

            // Filter type change handler
            const filterType = document.getElementById('filterType');
            const dailyFilter = document.getElementById('dailyFilter');
            const monthlyFilter = document.getElementById('monthlyFilter');
            const yearlyFilter = document.getElementById('yearlyFilter');

            function updateFilterVisibility() {
                // Hide all filters first
                dailyFilter.style.display = 'none';
                monthlyFilter.style.display = 'none';
                yearlyFilter.style.display = 'none';

                // Show the selected filter
                switch(filterType.value) {
                    case 'daily':
                        dailyFilter.style.display = 'block';
                        break;
                    case 'monthly':
                        monthlyFilter.style.display = 'block';
                        break;
                    case 'yearly':
                        yearlyFilter.style.display = 'block';
                        break;
                }
            }

            // Initial update
            updateFilterVisibility();

            // Add event listener for changes
            filterType.addEventListener('change', updateFilterVisibility);
        });
        </script>
    </div>
</body>
</html>