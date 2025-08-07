<?php
include('koneksi.php');

// Mulai session jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Pastikan pembimbing sudah login dan memiliki id_perusahaan
if (!isset($_SESSION['id_pembimbing']) || !isset($_SESSION['id_perusahaan'])) {
    header("Location: ../sign-in.php");
    exit();
}

$id_perusahaan = $_SESSION['id_perusahaan'];
$tanggal = isset($_GET['tanggal']) ? mysqli_real_escape_string($coneksi, $_GET['tanggal']) : date('Y-m-d');
$limit = 6;
$page_no = isset($_GET['page_no']) ? (int)$_GET['page_no'] : 1;
$offset = ($page_no - 1) * $limit;
$search = isset($_GET['search']) ? mysqli_real_escape_string($coneksi, $_GET['search']) : '';

// Hitung total data SESUAI query tampilan dengan filter perusahaan dan tanggal
$count_sql = "
    SELECT COUNT(*) AS total
    FROM siswa
    LEFT JOIN jurnal ON siswa.id_siswa = jurnal.id_siswa
    LEFT JOIN catatan ON jurnal.id_jurnal = catatan.id_jurnal
    WHERE siswa.nama_siswa LIKE '%$search%'
    AND siswa.id_perusahaan = '$id_perusahaan'
    AND DATE(jurnal.tanggal) = '$tanggal'
    AND catatan.id_catatan IN (
        SELECT MAX(id_catatan) 
        FROM catatan 
        WHERE DATE(tanggal) = '$tanggal'
        GROUP BY id_jurnal
    )
";
$count_result = mysqli_query($coneksi, $count_sql);
$total_rows = mysqli_fetch_assoc($count_result)['total'] ?? 0;
$total_pages = max(1, ceil($total_rows / $limit));
?>

<!DOCTYPE html>
<html>

<head>
    <title>Data Jurnal dan Catatan Harian</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .clickable-row {
            cursor: pointer;
        }

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

        .table thead th {
            background-color: #007bff;
            color: white;
        }

        .table tbody tr:hover {
            background-color: #e9ecef;
        }

        .pagination {
            justify-content: center;
        }

        .date-picker {
            max-width: 200px;
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
        <h2 class="text-center text-primary">Data Jurnal dan Catatan Harian</h2>
        <hr>

        <!-- Form Filter Tanggal -->
        <div class="d-flex justify-content-between flex-wrap align-items-center mb-3">
            <!-- Form Pencarian -->
            <form method="GET" class="form-inline">
                <div class="form-row">
                    <input type="hidden" name="page" value="catatan" />
                    <input type="hidden" name="tanggal" value="<?= htmlspecialchars($tanggal) ?>" />
                    <input type="text" name="search" class="form-control mr-2" placeholder="Cari nama siswa..." value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <form method="GET" class="form-inline">
                <input type="hidden" name="page" value="catatan" />
                <div class="form-row">
                    <label for="tanggal" class="mr-2">Tanggal:</label>
                    <input type="date" name="tanggal" class="form-control date-picker" value="<?= htmlspecialchars($tanggal) ?>">
                    <button type="submit" class="btn btn-primary ml-2">Filter</button>
                    <?php if ($tanggal != date('Y-m-d')): ?>
                        <a href="?page=catatan" class="btn btn-secondary ml-2">Hari Ini</a>
                    <?php endif; ?>
                </div>
            </form>

        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Jurnal</th>
                        <th>Catatan</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "
                        SELECT
                            siswa.id_siswa,
                            siswa.nama_siswa,
                            jurnal.id_jurnal,
                            jurnal.keterangan AS keterangan_jurnal,
                            catatan.catatan,
                            catatan.tanggal AS waktu_catatan,
                            jurnal.tanggal AS waktu_jurnal
                        FROM siswa
                        LEFT JOIN jurnal ON siswa.id_siswa = jurnal.id_siswa
                        LEFT JOIN catatan ON jurnal.id_jurnal = catatan.id_jurnal
                        WHERE siswa.nama_siswa LIKE '%$search%'
                        AND siswa.id_perusahaan = '$id_perusahaan'
                        AND DATE(jurnal.tanggal) = '$tanggal'
                        AND catatan.id_catatan IN (
                            SELECT MAX(id_catatan) 
                            FROM catatan 
                            WHERE DATE(tanggal) = '$tanggal'
                            GROUP BY id_jurnal
                        )
                        ORDER BY siswa.nama_siswa ASC
                        LIMIT $limit OFFSET $offset
                    ";

                    $result = mysqli_query($coneksi, $sql) or die(mysqli_error($coneksi));

                    if (mysqli_num_rows($result) > 0) {
                        $no = $offset + 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            $id_jurnal = $row['id_jurnal'] ?? 0;
                            $catatan = !empty($row['catatan']) ? $row['catatan'] : '-';
                            $keterangan = !empty($row['keterangan_jurnal']) ? $row['keterangan_jurnal'] : 'Tidak ada jurnal';
                            $waktu = !empty($row['waktu_catatan']) ? $row['waktu_catatan'] : $row['waktu_jurnal'];

                            echo '<tr class="clickable-row" data-href="index.php?page=tambahcatatan&id_jurnal=' . $id_jurnal . '">';
                            echo '<td>' . $no . '</td>';
                            echo '<td>' . htmlspecialchars($row['nama_siswa']) . '</td>';
                            echo '<td>' . htmlspecialchars($keterangan) . '</td>';
                            echo '<td>' . htmlspecialchars($catatan) . '</td>';
                            echo '<td>' . htmlspecialchars($waktu) . '</td>';
                            echo '</tr>';
                            $no++;
                        }
                    } else {
                        echo '<tr><td colspan="5" class="text-center">Tidak ada data ditemukan untuk tanggal ' . htmlspecialchars($tanggal) . '.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php if ($page_no > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=catatan&tanggal=<?= urlencode($tanggal) ?>&search=<?= urlencode($search) ?>&page_no=<?= $page_no - 1 ?>">&laquo;</a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= ($i == $page_no) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=catatan&tanggal=<?= urlencode($tanggal) ?>&search=<?= urlencode($search) ?>&page_no=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($page_no < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=catatan&tanggal=<?= urlencode($tanggal) ?>&search=<?= urlencode($search) ?>&page_no=<?= $page_no + 1 ?>">&raquo;</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".clickable-row").click(function() {
                window.location = $(this).data("href");
            });
        });
    </script>
</body>

</html>