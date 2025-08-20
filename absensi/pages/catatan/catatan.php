<?php
include('koneksi.php');

// Ambil data dari session
$level = $_SESSION['level'] ?? '';
$id_siswa = $_SESSION['id_siswa'] ?? null;
$id_perusahaan = $_SESSION['id_perusahaan'] ?? null;
$id_guru = $_SESSION['id_guru'] ?? null;
$id_pembimbing = $_SESSION['id_pembimbing'] ?? null;

// Parameter dari URL - format tanggal Y-m-d
$tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
$limit = 10;
$page_no = isset($_GET['page_no']) ? (int)$_GET['page_no'] : 1;
$offset = ($page_no - 1) * $limit;
$search = isset($_GET['search']) ? mysqli_real_escape_string($coneksi, $_GET['search']) : '';

// Cek apakah siswa sudah memiliki jurnal hari ini
$jurnal_hari_ini = null;
if ($level === 'siswa' && $id_siswa) {
    $cek_jurnal = "SELECT * FROM jurnal WHERE id_siswa = '$id_siswa' AND DATE(tanggal) = '$tanggal'";
    $result_jurnal = mysqli_query($coneksi, $cek_jurnal);
    $jurnal_hari_ini = mysqli_fetch_assoc($result_jurnal);
}

// Validasi waktu tambah jurnal
$current_time = date('H:i');
$current_day = date('N'); // 1 (Senin) sampai 7 (Minggu)

$allow_jurnal = false;
$time_message = '';

if ($current_day == 7) { // Hari Minggu
    $allow_jurnal = false;
    $time_message = 'Hari Minggu tidak bisa menambahkan jurnal';
} else {
    if ($current_day == 6) { // Hari Sabtu
        $allow_jurnal = ($current_time >= '11:00' && $current_time <= '12:15');
        $time_message = 'Jurnal hanya bisa ditambahkan/diupdate antara jam 11.00 - 12.15 pada hari Sabtu';
    } else { // Hari Senin-Jumat
        $allow_jurnal = ($current_time >= '15:00' && $current_time <= '16:15');
        $time_message = 'Jurnal hanya bisa ditambahkan/diupdate antara jam 15.00 - 16.15 pada hari Senin-Jumat';
    }
}

// Membangun kondisi WHERE berdasarkan level pengguna
$where_conditions = [];

if ($level === 'siswa') {
    $where_conditions[] = "siswa.id_siswa = '$id_siswa'";
} elseif ($level === 'pembimbing') {
    $where_conditions[] = "siswa.id_perusahaan = '$id_perusahaan'";
    if ($search) {
        $where_conditions[] = "siswa.nama_siswa LIKE '%$search%'";
    }
} elseif ($level === 'guru') {
    $where_conditions[] = "siswa.id_guru = '$id_guru'";
    if ($search) {
        $where_conditions[] = "siswa.nama_siswa LIKE '%$search%'";
    }
}

$where_clause = $where_conditions ? implode(' AND ', $where_conditions) : '1=1';

// Hitung total data untuk pagination
$count_sql = "
    SELECT COUNT(DISTINCT siswa.id_siswa) AS total
    FROM siswa
    LEFT JOIN jurnal ON siswa.id_siswa = jurnal.id_siswa AND DATE(jurnal.tanggal) = '$tanggal'
    WHERE $where_clause
";
$count_result = mysqli_query($coneksi, $count_sql);
$total_rows = mysqli_fetch_assoc($count_result)['total'] ?? 0;
$total_pages = max(1, ceil($total_rows / $limit));

// Query untuk mendapatkan data
$sql = "
    SELECT
        siswa.id_siswa,
        siswa.nama_siswa,
        jurnal.id_jurnal,
        jurnal.keterangan AS keterangan_jurnal,
        jurnal.tanggal AS tanggal_jurnal,
        (
            SELECT catatan.catatan
            FROM catatan
            WHERE catatan.id_jurnal = jurnal.id_jurnal
            " . ($level === 'pembimbing' ? "AND catatan.id_pembimbing = '$id_pembimbing'" : "") . "
            ORDER BY catatan.tanggal ASC
            LIMIT 1
        ) AS catatan,
        (
            SELECT catatan.tanggal
            FROM catatan
            WHERE catatan.id_jurnal = jurnal.id_jurnal
            " . ($level === 'pembimbing' ? "AND catatan.id_pembimbing = '$id_pembimbing'" : "") . "
            ORDER BY catatan.tanggal ASC
            LIMIT 1
        ) AS waktu_catatan
    FROM siswa
    LEFT JOIN jurnal ON siswa.id_siswa = jurnal.id_siswa AND DATE(jurnal.tanggal) = '$tanggal'
    WHERE $where_clause
    GROUP BY siswa.id_siswa, jurnal.id_jurnal
    ORDER BY siswa.nama_siswa ASC
    LIMIT $limit OFFSET $offset
";

$result = mysqli_query($coneksi, $sql) or die(mysqli_error($coneksi));
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Data Jurnal dan Catatan Harian</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
    .clickable-row {
        cursor: pointer;
    }

    body {
        padding-left: 270px;
        background-color: #f8f9fa;
        transition: padding-left 0.3s;
    }

    .main-container {
        margin: 20px 20px 0 0;
        max-width: none;
    }

    .container-custom {
        background-color: #fff;
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

    .time-alert {
        color: #dc3545;
        font-weight: bold;
        margin-left: 10px;
    }

    @media (max-width: 991px) {
        body {
            padding-left: 0;
        }

        .main-container {
            margin: 0 15px;
        }
    }
    </style>
</head>

<body>
    <h2 class="text-primary text-center text-md-left">Data Jurnal dan Catatan Harian</h2>
    <div class="main-container container-custom">
        <hr />
        <!-- Form Filter dan Pencarian -->
        <div class="d-flex justify-content-between flex-wrap align-items-center mb-3">
            <?php if ($level === 'siswa'): ?>
            <div class="form-inline">
                <div class="from-control mb-3">
                    <?php if ($allow_jurnal): ?>
                    <a href="index.php?page=tambahjurnal&id_siswa=<?= $id_siswa ?>"
                        class="btn btn-<?= $jurnal_hari_ini ? 'primary' : 'primary' ?>">
                        <i class="fas fa-<?= $jurnal_hari_ini ? 'edit' : 'plus' ?>"></i>
                        <?= $jurnal_hari_ini ? 'Update Jurnal' : 'Tambah Jurnal' ?>
                    </a>
                    <?php else: ?>
                    <button type="button" class="btn btn-light" id="disabledJurnalButton">
                        <i class="fas fa-<?= $jurnal_hari_ini ? 'edit' : 'plus' ?>"></i>
                        <?= $jurnal_hari_ini ? 'Update Jurnal' : 'Tambah Jurnal' ?>
                        <span class="time-alert"></span>
                    </button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Form Pencarian (hanya untuk pembimbing dan guru) -->
            <?php if ($level === 'pembimbing' || $level === 'guru'): ?>
            <form method="GET" class="form-iniline">
                <input type="hidden" name="page" value="catatan" />
                <input type="hidden" name="tanggal" value="<?= htmlspecialchars($tanggal) ?>" />
                <div class="input-group-append">
                    <input type="text" name="search" class="form-control" placeholder="cari nama siswa..."
                        value="<?= htmlspecialchars($search) ?>" aria-label="Cari nama siswa"
                        aria-describedby="button-search">
                    <button class="btn btn-primary ms-1" type="submit" id="button-search">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
            <?php else: ?>
            <div></div> <!-- Placeholder untuk menjaga layout -->
            <?php endif; ?>

            <!-- Form Filter Tanggal -->
            <form method="GET" class="form-inline">
                <input type="hidden" name="page" value="catatan" />

                <?php
                // jika ada tanggal dari GET, gunakan itu, kalau tidak pakai hari ini
                $tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
                ?>

                <input type="date" name="tanggal" class="form-control date-picker mb-2"
                    value="<?= htmlspecialchars($tanggal) ?>" pattern="\d{4}-\d{2}-\d{2}" />

                <button type="submit" class="btn btn-primary ml-2 mb-2">
                    <i class="fa-solid fa-filter"></i>
                </button>
            </form>

        </div>

        <!-- Tabel Data -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-primary">
                    <tr class="text-center">
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Jurnal</th>
                        <th>Catatan Pembimbing</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php $no = $offset + 1; ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <?php
                            $id_jurnal = $row['id_jurnal'] ?? 0;
                            $catatan = !empty($row['catatan']) ? $row['catatan'] : '-';
                            $keterangan = !empty($row['keterangan_jurnal']) ? $row['keterangan_jurnal'] : 'Belum ada jurnal';

                            // Format waktu ke m-d-Y
                            $waktu = '-';
                            if (!empty($row['waktu_catatan'])) {
                                $waktu = date('m-d-Y', strtotime($row['waktu_catatan']));
                            } elseif (!empty($row['tanggal_jurnal'])) {
                                $waktu = date('m-d-Y', strtotime($row['tanggal_jurnal']));
                            }

                            // Link tambah catatan
                            $href = "index.php?page=tambahcatatan&id_jurnal=$id_jurnal";
                            ?>
                    <tr class="clickable-row" data-href="<?= $href ?>">
                        <td class="text-center"><?= $no ?></td>
                        <td><?= htmlspecialchars($row['nama_siswa']) ?></td>
                        <td><?= htmlspecialchars($keterangan) ?></td>
                        <td><?= htmlspecialchars($catatan) ?></td>
                        <td><?= htmlspecialchars($waktu) ?></td>
                    </tr>
                    <?php $no++; ?>
                    <?php endwhile; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data siswa ditemukan untuk tanggal
                            <?= htmlspecialchars(date('m-d-Y', strtotime($tanggal))) ?>.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php if ($page_no > 1): ?>
                <li class="page-item">
                    <a class="page-link"
                        href="?page=catatan&tanggal=<?= urlencode($tanggal) ?>&search=<?= urlencode($search) ?>&page_no=<?= $page_no - 1 ?>">
                        &laquo; Sebelumnya
                    </a>
                </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= ($i == $page_no) ? 'active' : '' ?>">
                    <a class="page-link"
                        href="?page=catatan&tanggal=<?= urlencode($tanggal) ?>&search=<?= urlencode($search) ?>&page_no=<?= $i ?>">
                        <?= $i ?>
                    </a>
                </li>
                <?php endfor; ?>

                <?php if ($page_no < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link"
                        href="?page=catatan&tanggal=<?= urlencode($tanggal) ?>&search=<?= urlencode($search) ?>&page_no=<?= $page_no + 1 ?>">
                        Selanjutnya &raquo;
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    $(document).ready(function() {
        $(".clickable-row").click(function() {
            var href = $(this).data("href");
            if (href && href !== "#") {
                window.location = href;
            }
        });

        // Handle click on disabled button
        $('#disabledJurnalButton').click(function() {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: '<?= $time_message ?>',
                confirmButtonText: 'OK'
            });
        });
    });
    </script>
</body>

</html>