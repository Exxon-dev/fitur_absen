<?php
session_start();
include('koneksi.php');

// Ambil data dari session
$level = $_SESSION['level'] ?? '';
$id_siswa = $_SESSION['id_siswa'] ?? null;
$id_perusahaan = $_SESSION['id_perusahaan'] ?? null;
$id_sekolah = $_SESSION['id_sekolah'] ?? null;

// Parameter dari URL
$tanggal = isset($_GET['tanggal']) ? mysqli_real_escape_string($coneksi, $_GET['tanggal']) : date('Y-m-d');
$limit = 6;
$page_no = isset($_GET['page_no']) ? (int)$_GET['page_no'] : 1;
$offset = ($page_no - 1) * $limit;
$search = isset($_GET['search']) ? mysqli_real_escape_string($coneksi, $_GET['search']) : '';

// Membangun kondisi WHERE berdasarkan level pengguna
$where_conditions = ["siswa.nama_siswa LIKE '%$search%'"];

if ($level === 'siswa') {
    $where_conditions[] = "siswa.id_siswa = '$id_siswa'";
} elseif ($level === 'pembimbing') {
    $where_conditions[] = "siswa.id_perusahaan = '$id_perusahaan'";
} elseif ($level === 'guru' || $level === 'sekolah') {
    $where_conditions[] = "siswa.id_sekolah = '$id_sekolah'";
}

$where_clause = implode(' AND ', $where_conditions);

// Hitung total data untuk pagination
$count_sql = "
    SELECT COUNT(*) AS total
    FROM siswa
    LEFT JOIN jurnal ON siswa.id_siswa = jurnal.id_siswa AND DATE(jurnal.tanggal) = '$tanggal'
    WHERE $where_clause
";
$count_result = mysqli_query($coneksi, $count_sql);
$total_rows = mysqli_fetch_assoc($count_result)['total'] ?? 0;
$total_pages = max(1, ceil($total_rows / $limit));

// Query untuk mendapatkan data jurnal
$sql = "
    SELECT
        siswa.id_siswa,
        siswa.nama_siswa,
        jurnal.id_jurnal,
        jurnal.keterangan AS keterangan_jurnal,
        jurnal.tanggal AS tanggal_jurnal,
        catatan.catatan,
        catatan.tanggal AS waktu_catatan
    FROM siswa
    LEFT JOIN jurnal ON siswa.id_siswa = jurnal.id_siswa AND DATE(jurnal.tanggal) = '$tanggal'
    LEFT JOIN catatan ON jurnal.id_jurnal = catatan.id_jurnal
    WHERE $where_clause
    ORDER BY siswa.nama_siswa ASC
    LIMIT $limit OFFSET $offset
";

$result = mysqli_query($coneksi, $sql) or die(mysqli_error($coneksi));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Jurnal dan Catatan Harian</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .clickable-row {
            cursor: pointer;
        }

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

        .pagination {
            justify-content: center;
        }

        .date-picker {
            max-width: 200px;
        }

        .badge-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: bold;
        }

        .badge-belum {
            background-color: #E0E0E0;
            color: #424242;
        }

        .badge-ada {
            background-color: #C8E6C9;
            color: #1B5E20;
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
        
        <?php if ($level === 'siswa'): ?>
            <div class="form-row mb-3">
                <div class="col text-left">
                    <a href="index.php?page=tambahjurnal&id_siswa=<?= $id_siswa ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Jurnal
                    </a>
                </div>
            </div>
        <?php endif; ?>
        
        <hr>

        <!-- Form Filter dan Pencarian -->
        <div class="d-flex justify-content-between flex-wrap align-items-center mb-3">
            <!-- Form Pencarian -->
            <form method="GET" class="form-inline">
                <div class="form-row">
                    <input type="hidden" name="page" value="catatan" />
                    <input type="hidden" name="tanggal" value="<?= htmlspecialchars($tanggal) ?>" />
                    <input type="text" name="search" class="form-control mr-2" placeholder="Cari nama siswa..." value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </form>

            <!-- Form Filter Tanggal -->
            <form method="GET" class="form-inline">
                <input type="hidden" name="page" value="catatan" />
                <div class="form-row">
                    <label for="tanggal" class="mr-2">Tanggal:</label>
                    <input type="date" name="tanggal" class="form-control date-picker" value="<?= htmlspecialchars($tanggal) ?>">
                    <button type="submit" class="btn btn-primary ml-2">Filter</button>
                    <?php if ($tanggal != date('Y-m-d')): ?>
                        <a href="index.php?page=catatan" class="btn btn-secondary ml-2">Hari Ini</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Tabel Data -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-primary">
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Status Jurnal</th>
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
                            $waktu = !empty($row['waktu_catatan']) ? $row['waktu_catatan'] : (!empty($row['tanggal_jurnal']) ? $row['tanggal_jurnal'] : '-');

                            // Tentukan status dan badge
                            $status = empty($row['keterangan_jurnal']) ? 'Belum ada jurnal' : 'Sudah membuat jurnal';
                            $badge_class = empty($row['keterangan_jurnal']) ? 'badge-belum' : 'badge-ada';

                            // Tentukan link berdasarkan level pengguna
                            $href = ($level === 'pembimbing') ? 
                                "index.php?page=tambahcatatan&id_jurnal=$id_jurnal" : 
                                "index.php?page=tambahcatatan&id_jurnal=$id_jurnal";
                            ?>
                            <tr class="clickable-row" data-href="<?= $href ?>">
                                <td><?= $no ?></td>
                                <td><?= htmlspecialchars($row['nama_siswa']) ?></td>
                                <td><span class="badge-status <?= $badge_class ?>"><?= $status ?></span></td>
                                <td><?= htmlspecialchars($keterangan) ?></td>
                                <td><?= htmlspecialchars($catatan) ?></td>
                                <td><?= htmlspecialchars($waktu) ?></td>
                            </tr>
                            <?php $no++; ?>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data siswa ditemukan.</td>
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
                        <a class="page-link" href="?page=catatan&tanggal=<?= urlencode($tanggal) ?>&search=<?= urlencode($search) ?>&page_no=<?= $page_no - 1 ?>">
                            &laquo; Sebelumnya
                        </a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= ($i == $page_no) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=catatan&tanggal=<?= urlencode($tanggal) ?>&search=<?= urlencode($search) ?>&page_no=<?= $i ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <?php if ($page_no < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=catatan&tanggal=<?= urlencode($tanggal) ?>&search=<?= urlencode($search) ?>&page_no=<?= $page_no + 1 ?>">
                            Selanjutnya &raquo;
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php 
    if (isset($_SESSION['flash_hapus']) && $_SESSION['flash_hapus'] == 'sukses') {
        echo "<script>document.addEventListener('DOMContentLoaded',function(){Swal.fire({icon:'info',title:'Sukses!',text:'Data catatan berhasil dihapus',position:'top',showConfirmButton:false,timer:3000,toast:true});});</script>";
        unset($_SESSION['flash_hapus']);
    }
    ?>
    <?php
    if (isset($_SESSION['flash_edit']) && $_SESSION['flash_edit'] == 'sukses') {
        echo "<script>document.addEventListener('DOMContentLoaded',function(){Swal.fire({icon:'success',title:'Sukses!',text:'Data catatan berhasil di update',position:'top',showConfirmButton:false,timer:3000,toast:true});});</script>";
        unset($_SESSION['flash_edit']);
    }
    ?>
    <?php
    // Notifikasi flash message tambah
    if (isset($_SESSION['flash_tambah']) && $_SESSION['flash_tambah'] == 'sukses') {
        echo "<script>document.addEventListener('DOMContentLoaded',function(){
        Swal.fire({
            icon: 'success',
            title: 'Sukses!',
            text: 'Data catatan berhasil ditambahkan',
            position: 'top',
            showConfirmButton: false,
            timer: 3000,
            toast: true
        });
    });</script>";
        unset($_SESSION['flash_tambah']);
    }

    // Notifikasi error
    if (isset($_SESSION['flash_error'])) {
        echo "<script>document.addEventListener('DOMContentLoaded',function(){
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '" . addslashes($_SESSION['flash_error']) . "',
            position: 'top',
            showConfirmButton: false,
            timer: 3000,
            toast: true
        });
    });</script>";
        unset($_SESSION['flash_error']);
    }

    // Notifikasi duplikat
    if (isset($_SESSION['flash_duplikat'])) {
        echo "<script>document.addEventListener('DOMContentLoaded',function(){
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan!',
            text: Catatan sudah terdaftar',
            position: 'top',
            showConfirmButton: false,
            timer: 3000,
            toast: true
        });
    });</script>";
        unset($_SESSION['flash_duplikat']);
    }
    ?>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle clickable row
            $(".clickable-row").click(function() {
                if ($(this).data("href")) {
                    window.location = $(this).data("href");
                }
            });

            // Tambahkan efek hover untuk row
            $(".clickable-row").hover(
                function() {
                    $(this).css('background-color', '#f1f1f1');
                },
                function() {
                    $(this).css('background-color', '');
                }
            );
        });
    </script>
</body>
</html>