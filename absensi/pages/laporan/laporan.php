<?php
include('koneksi.php');

$id_siswa = $_SESSION['id_siswa'];

// Cek kalau BELUM login
if (!isset($_SESSION['level'])) {
    header("Location: sign-in.php");
    exit();
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

    /* Perubahan: Semua label sekarang berwarna hitam */
    .form-group label {
        font-weight: 400;
        color: #000000; /* Warna hitam untuk semua label */
    }

    .date-display {
        font-size: 14px;
        color: #666;
        margin-top: 5px;
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
    <h2>Laporan Siswa</h2>
    <div class="main-container container-custom">
        <hr>

        <form id="myForm" action="pages/laporan/preview.php" method="GET" target="_blank">
            <!-- Input hidden untuk ID siswa dari session -->
            <input type="hidden" name="id_siswa" value="<?php echo htmlspecialchars($id_siswa); ?>">

            <div class="form-group">
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

            <!-- Filter Section dengan Garis Biru -->
            <div class="filter-section">
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
                            <div class="date-display" id="startDateDisplay"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="endDate">Tanggal Selesai:</label>
                            <input type="date" id="endDate" name="end_date" class="form-control" value="<?= $end_date ?>">
                            <div class="date-display" id="endDateDisplay"></div>
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
            </div>

            <button type="submit" class="btn btn-primary btn-block mt-4">Preview</button>
        </form>

        <!-- Choices.js -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
        <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Choices.js for report select
            new Choices('#reportSelect', {
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

            // Format tanggal untuk ditampilkan (d-m-Y)
            function formatDateForDisplay(dateString) {
                if (!dateString) return '';
                
                const date = new Date(dateString);
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = date.getFullYear();
                
                return `${month}-${day}-${year}`;
            }

            // Handle submit form
            document.getElementById('myForm').addEventListener('submit', function(e) {
                // Validasi pilihan laporan
                if (!document.getElementById('reportSelect').value) {
                    alert('Silakan pilih laporan terlebih dahulu');
                    e.preventDefault();
                    return false;
                }
            });
        });
        </script>
    </div>
</body>
</html>