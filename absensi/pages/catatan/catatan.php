<?php
include('koneksi.php');

$limit = 10;
$page_no = isset($_GET['page_no']) ? (int)$_GET['page_no'] : 1;
$offset = ($page_no - 1) * $limit;
$search = isset($_GET['search']) ? mysqli_real_escape_string($coneksi, $_GET['search']) : '';

// Hitung total data SESUAI query tampilan
$count_sql = "
    SELECT COUNT(*) AS total
    FROM siswa
    LEFT JOIN (
        SELECT * FROM jurnal
        WHERE id_jurnal IN (
            SELECT MAX(id_jurnal) FROM jurnal GROUP BY id_siswa
        )
    ) AS jurnal ON siswa.id_siswa = jurnal.id_siswa
    LEFT JOIN (
        SELECT id_jurnal, catatan
        FROM catatan
        WHERE id_catatan IN (
            SELECT MAX(id_catatan) FROM catatan GROUP BY id_jurnal
        )
    ) AS c ON jurnal.id_jurnal = c.id_jurnal
    WHERE siswa.nama_siswa LIKE '%$search%'
";
$count_result = mysqli_query($coneksi, $count_sql);
$total_rows = mysqli_fetch_assoc($count_result)['total'] ?? 0;
$total_pages = max(1, ceil($total_rows / $limit));
?>

<!DOCTYPE html>
<html>

<head>
    <title>Data Siswa, Jurnal, dan Catatan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <!-- Font Awesome CDN (versi 6.4.0) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .clickable-row {
            cursor: pointer;
        }

        .container {
            margin-top: 20px;
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

        .pagination {
            justify-content: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center text-primary">Data Jurnal, dan Catatan Terbaru</h2>
        <hr>

        <!-- Pencarian di kanan -->
        <div class="d-flex justify-content-between flex-wrap align-items-center mb-3">
            <!-- Tombol Tambah Jurnal (kiri) -->
            <?php if ($_SESSION['level'] === 'siswa'): ?>
                <a href="index.php?page=tambahjurnal" class="btn btn-success mb-2 d-flex align-items-center">
                    <span class="d-none d-sm-inline">Tambah Jurnal</span>
                    <i class="fas fa-plus d-inline d-sm-none"></i>
                </a>
            <?php endif; ?>

            <!-- Form Pencarian (kanan) -->
            <form method="GET" class="d-flex align-items-center mb-2" action="index.php">
                <input type="hidden" name="page" value="catatan" />
                <input type="text" name="search" class="form-control me-2" placeholder="Cari..." value="<?= htmlspecialchars($search) ?>" style="max-width: 160px; font-size: 0.9rem; padding: 4px 8px;" />
                <button type="submit" class="btn btn-primary sm-1 mt-3">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Jurnal</th>
                        <th>Catatan</th>
                        <th>Tanggal</th>
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
                                c.catatan,
                                c.tanggal
                            FROM siswa
                            LEFT JOIN (
                                SELECT * FROM jurnal
                                WHERE id_jurnal IN (
                                    SELECT MAX(id_jurnal) FROM jurnal GROUP BY id_siswa
                                )
                            ) AS jurnal ON siswa.id_siswa = jurnal.id_siswa
                            LEFT JOIN (
                                SELECT id_jurnal, catatan, tanggal
                                FROM catatan
                                WHERE id_catatan IN (
                                    SELECT MAX(id_catatan) FROM catatan GROUP BY id_jurnal
                                )
                            ) AS c ON jurnal.id_jurnal = c.id_jurnal
                            WHERE siswa.nama_siswa LIKE '%$search%'
                            ORDER BY siswa.nama_siswa ASC
                            LIMIT $limit OFFSET $offset ";


                    $result = mysqli_query($coneksi, $sql) or die(mysqli_error($coneksi));

                    if (mysqli_num_rows($result) > 0) {
                        $no = $offset + 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            $id_jurnal = $row['id_jurnal'] ?? 0;
                            $catatan = !empty($row['catatan']) ? $row['catatan'] : '-';
                            $keterangan = !empty($row['keterangan_jurnal']) ? $row['keterangan_jurnal'] : 'Tidak ada jurnal';

                            echo '<tr class="clickable-row" data-href="index.php?page=tambahcatatan&id_jurnal=' . $id_jurnal . '">';
                            echo '<td>' . $no . '</td>';
                            echo '<td>' . htmlspecialchars($row['nama_siswa']) . '</td>';
                            echo '<td>' . htmlspecialchars($keterangan) . '</td>';
                            echo '<td>' . htmlspecialchars($catatan) . '</td>';
                            echo '<td>' . htmlspecialchars($row['tanggal'] ?? '') . '</td>';
                            echo '</tr>';
                            $no++;
                        }
                    } else {
                        echo '<tr><td colspan="4" class="text-center">Tidak ada data ditemukan.</td></tr>';
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
                        <a class="page-link" href="?page=catatan&search=<?= urlencode($search) ?>&page_no=<?= $page_no - 1 ?>">&laquo;</a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= ($i == $page_no) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=catatan&search=<?= urlencode($search) ?>&page_no=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($page_no < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=catatan&search=<?= urlencode($search) ?>&page_no=<?= $page_no + 1 ?>">&raquo;</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php
    // Notifikasi flash message hapus
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
            $(".clickable-row").click(function() {
                window.location = $(this).data("href");
            });
        });
    </script>
</body>

</html>