<?php
session_start();
include('koneksi.php');

// Ambil data dari session
$level = $_SESSION['level'] ?? '';
$id_siswa = $_SESSION['id_siswa'] ?? null;
$id_perusahaan = $_SESSION['id_perusahaan'] ?? null;
$id_guru = $_SESSION['id_guru'] ?? null;
$id_pembimbing = $_SESSION['id_pembimbing'] ?? null;

// Ambil nama siswa untuk pesan alert
$nama_siswa = '';
if ($level === 'siswa' && $id_siswa) {
    $query_nama = "SELECT nama_siswa FROM siswa WHERE id_siswa = '$id_siswa'";
    $result_nama = mysqli_query($coneksi, $query_nama);
    $data_siswa = mysqli_fetch_assoc($result_nama);
    $nama_siswa = $data_siswa['nama_siswa'] ?? 'Siswa';
}

// Parameter dari URL - format tanggal Y-m-d
$tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
$limit = 10;
$page_no = isset($_GET['page_no']) ? (int)$_GET['page_no'] : 1;
$offset = ($page_no - 1) * $limit;
$search = isset($_GET['search']) ? mysqli_real_escape_string($coneksi, $_GET['search']) : '';

// Cek apakah tanggal yang dilihat adalah hari ini
$is_today = ($tanggal == date('Y-m-d'));

// Cek status absensi untuk siswa (hanya berlaku untuk hari ini)
$allow_jurnal = false;
$absen_status = ''; // untuk menyimpan status absensi

if ($level === 'siswa' && $id_siswa && $is_today) {
    // Cek absensi hari ini
    $cek_absen = "SELECT jam_masuk, jam_keluar FROM absen WHERE id_siswa = '$id_siswa' AND tanggal = '$tanggal'";
    $result_absen = mysqli_query($coneksi, $cek_absen);
    $absen_hari_ini = mysqli_fetch_assoc($result_absen);
    
    if ($absen_hari_ini) {
        if ($absen_hari_ini['jam_masuk'] && !$absen_hari_ini['jam_keluar']) {
            // Sudah absen masuk, belum pulang - boleh buat/update jurnal
            $allow_jurnal = true;
            $absen_status = 'masuk';
        } elseif ($absen_hari_ini['jam_masuk'] && $absen_hari_ini['jam_keluar']) {
            // Sudah absen pulang - tidak boleh buat/update jurnal
            $allow_jurnal = false;
            $absen_status = 'pulang';
        }
    } else {
        // Belum absen masuk - tidak boleh buat jurnal
        $allow_jurnal = false;
        $absen_status = 'belum';
    }
}

// Cek apakah siswa sudah memiliki jurnal untuk tanggal yang dilihat
$jurnal_tanggal_ini = null;
if ($level === 'siswa' && $id_siswa) {
    $cek_jurnal = "SELECT * FROM jurnal WHERE id_siswa = '$id_siswa' AND DATE(tanggal) = '$tanggal'";
    $result_jurnal = mysqli_query($coneksi, $cek_jurnal);
    $jurnal_tanggal_ini = mysqli_fetch_assoc($result_jurnal);
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
        (
            SELECT c.catatan
            FROM catatan c
            WHERE 
                (c.id_jurnal = jurnal.id_jurnal OR c.id_siswa = siswa.id_siswa)
                AND DATE(c.tanggal) = '$tanggal'
                " . ($level === 'pembimbing' ? "AND c.id_pembimbing = '$id_pembimbing'" : "") . "
            ORDER BY c.tanggal DESC
            LIMIT 1
        ) AS catatan
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        .table td,
        .table th {
            border: 1px solid #dee2e6 !important;
            vertical-align: middle;
        }

        .journal-text {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .btn-disabled {
            opacity: 0.6;
            cursor: not-allowed;
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
                        <?php if ($is_today && $allow_jurnal): ?>
                            <!-- Hari ini dan boleh buat/update jurnal -->
                            <a href="index.php?page=tambahjurnal&id_siswa=<?= $id_siswa ?>"
                                class="btn btn-primary">
                                <i class="fas fa-<?= $jurnal_tanggal_ini ? 'edit' : 'plus' ?>"></i>
                                <?= $jurnal_tanggal_ini ? 'Update Jurnal' : 'Tambah Jurnal' ?>
                            </a>
                        <?php else: ?>
                            <!-- Bukan hari ini atau tidak boleh buat/update jurnal -->
                            <button class="btn btn-light btn-disabled" id="btnJurnalDisabled">
                                <i class="fas fa-<?= $jurnal_tanggal_ini ? 'edit' : 'plus' ?>"></i>
                                <?= $jurnal_tanggal_ini ? 'Update Jurnal' : 'Tambah Jurnal' ?>
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
            <form method="GET" class="form-inline" id="filterForm">
                <input type="hidden" name="page" value="catatan" />

                <?php
                $tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
                ?>

                <input type="date" name="tanggal" class="form-control date-picker mb-2"
                    value="<?= htmlspecialchars($tanggal) ?>" pattern="\d{4}-\d{2}-\d{2}" onchange="document.getElementById('filterForm').submit();" />
                <button class="btn btn-primary ml-2 mb-2">
                    <i class="fa-solid fa-filter"></i>
                </button>
            </form>
        </div>

        <!-- Tabel Data (kolom waktu dihapus) -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-primary">
                    <tr class="text-center">
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Jurnal</th>
                        <th>Catatan Pembimbing</th>
                    <tr>

                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php $no = $offset + 1; ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <?php
                            $id_jurnal = $row['id_jurnal'] ?? 0;
                            $id_siswa_row = $row['id_siswa']; // ambil id_siswa dari row
                            $catatan = !empty($row['catatan']) ? $row['catatan'] : '-';
                            $keterangan = !empty($row['keterangan_jurnal']) ? $row['keterangan_jurnal'] : 'Belum ada jurnal';
                            $keterangan_short = (strlen($keterangan) > 100) ? substr($keterangan, 0, 100) . '...' : $keterangan;

                            // Link tambah catatan (kirim id_jurnal + id_siswa)
                            $href = "index.php?page=tambahcatatan&id_jurnal=$id_jurnal&id_siswa=$id_siswa_row&tanggal=$tanggal";
                            ?>
                            <tr class="clickable-row" data-href="<?= $href ?>">
                                <td class="text-center"><?= $no ?></td>
                                <td><?= htmlspecialchars($row['nama_siswa']) ?></td>
                                <td class="journal-text" title="<?= htmlspecialchars($keterangan) ?>">
                                    <?= htmlspecialchars($keterangan_short) ?>
                                </td>
                                <td><?= htmlspecialchars($catatan) ?></td>
                            </tr>
                            <?php $no++; ?>
                        <?php endwhile; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data siswa ditemukan untuk tanggal
                                <?= htmlspecialchars(date('d-m-Y', strtotime($tanggal))) ?>.</td>
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
            
            // Untuk siswa, tampilkan alert jika mencoba klik tombol yang dinonaktifkan
            <?php if ($level === 'siswa'): ?>
            $('#btnJurnalDisabled').click(function(e) {
                e.preventDefault();
                <?php if (!$is_today): ?>
                    // Jika melihat tanggal selain hari ini
                    Swal.fire({
                        icon: 'info',
                        title: 'Aksi Tidak Diizinkan',
                        html: 'Hanya dapat menambah atau mengupdate jurnal untuk <strong>hari ini</strong>.',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Mengerti'
                    });
                <?php elseif ($absen_status === 'belum'): ?>
                    // Jika belum absen
                    Swal.fire({
                        icon: 'warning',
                        title: 'Aksi Tidak Diizinkan',
                        html: '<?php echo $nama_siswa; ?> <strong>belum absen</strong> hari ini. Silakan absen terlebih dahulu sebelum membuat jurnal.',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Mengerti'
                    });
                <?php elseif ($absen_status === 'pulang'): ?>
                    // Jika sudah absen pulang
                    Swal.fire({
                        icon: 'info',
                        title: 'Aksi Tidak Diizinkan',
                        html: '<?php echo $nama_siswa; ?> <strong>sudah absen pulang</strong>. Kamu tidak bisa menambah atau update jurnal.',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Mengerti'
                    });
                <?php endif; ?>
            });
            <?php endif; ?>
        });
    </script>

    <!-- SweetAlert Flash Notifications untuk Jurnal -->
    <?php
    if (isset($_SESSION['flash_jurnal_tambah']) && $_SESSION['flash_jurnal_tambah'] == 'sukses') {
        echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Sukses!',
            text: 'Jurnal berhasil ditambahkan',
            position: 'top',
            showConfirmButton: false,
            timer: 3000,
            toast: true
        });
    });
    </script>";
        unset($_SESSION['flash_jurnal_tambah']);
    }

    if (isset($_SESSION['flash_jurnal_update']) && $_SESSION['flash_jurnal_update'] == 'sukses') {
        echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Sukses!',
            text: 'Jurnal berhasil diupdate',
            position: 'top',
            showConfirmButton: false,
            timer: 3000,
            toast: true
        });
    });
    </script>";
        unset($_SESSION['flash_jurnal_update']);
    }

    if (isset($_SESSION['flash_jurnal_error'])) {
        echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '" . addslashes($_SESSION['flash_jurnal_error']) . "',
            position: 'top',
            showConfirmButton: false,
            timer: 3000,
            toast: true
        });
    });
    </script>";
        unset($_SESSION['flash_jurnal_error']);
    }
    ?>

    <!-- SweetAlert Flash Notifications -->
    <?php
    if (isset($_SESSION['flash_hapus']) && $_SESSION['flash_hapus'] == 'sukses') {
        echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'info',
            title: 'Sukses!',
            text: 'Catatan berhasil dihapus',
            position: 'top',
            showConfirmButton: false,
            timer: 3000,
            toast: true
        });
    });
    </script>";
        unset($_SESSION['flash_hapus']);
    }

    if (isset($_SESSION['flash_update']) && $_SESSION['flash_update'] == 'sukses') {
        echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Sukses!',
            text: 'Catatan berhasil diupdate',
            position: 'top',
            showConfirmButton: false,
            timer: 3000,
            toast: true
        });
    });
    </script>";
        unset($_SESSION['flash_update']);
    }

    if (isset($_SESSION['flash_tambah']) && $_SESSION['flash_tambah'] == 'sukses') {
        echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Sukses!',
            text: 'Catatan berhasil ditambahkan',
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
            text: 'Anda sudah membuat catatan untuk jurnal ini',
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