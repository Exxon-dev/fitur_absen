<?php
include('koneksi.php');

// Pagination dan search
$limit = 4;
$page = isset($_GET['page_no']) ? (int)$_GET['page_no'] : 1;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? mysqli_real_escape_string($coneksi, $_GET['search']) : '';

// Hitung total catatan (bukan siswa)
$count_sql = "
    SELECT COUNT(*) as total
    FROM catatan
    JOIN jurnal ON catatan.id_jurnal = jurnal.id_jurnal
    JOIN siswa ON jurnal.id_siswa = siswa.id_siswa
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        <h2 class="text-center text-primary">Data Siswa, Jurnal, dan Catatan Terbaru</h2>
        <hr>

        <form method="GET" class="d-flex justify-content-end align-items-center" action="index.php">
            <input type="hidden" name="page" value="catatan" />
            <input type="text" name="search" class="form-control w-25" placeholder="Cari..." value="<?= htmlspecialchars($search) ?>" />
            <button type="submit" class="btn btn-primary ms-2">Cari</button>
        </form>

        <br>

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Jurnal</th>
                    <th>Catatan</th>
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
                        catatan.catatan
                    FROM catatan
                    JOIN jurnal ON catatan.id_jurnal = jurnal.id_jurnal
                    JOIN siswa ON jurnal.id_siswa = siswa.id_siswa
                    WHERE siswa.nama_siswa LIKE '%$search%'
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

                        echo '<tr class="clickable-row" data-href="index.php?page=tambahcatatan&id_jurnal=' . $id_jurnal . '">';
                        echo '<td>' . $no . '</td>';
                        echo '<td>' . htmlspecialchars($row['nama_siswa']) . '</td>';
                        echo '<td>' . htmlspecialchars($keterangan) . '</td>';
                        echo '<td>' . htmlspecialchars($catatan) . '</td>';
                        echo '</tr>';
                        $no++;
                    }
                } else {
                    echo '<tr><td colspan="4" class="text-center">Tidak ada data ditemukan.</td></tr>';
                }
                ?>
            </tbody>
        </table>

        <!-- PAGINATION -->
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=catatan&search=<?= urlencode($search) ?>&page_no=<?= $page - 1 ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=catatan&search=<?= urlencode($search) ?>&page_no=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=catatan&search=<?= urlencode($search) ?>&page_no=<?= $page + 1 ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <?php
    // Notifikasi flash message
    if (isset($_SESSION['flash_hapus']) && $_SESSION['flash_hapus'] == 'sukses') {
        echo "<script>document.addEventListener('DOMContentLoaded',function(){Swal.fire({icon:'info',title:'Sukses!',text:'Data catatan berhasil dihapus',position:'top',showConfirmButton:false,timer:3000,toast:true});});</script>";
        unset($_SESSION['flash_hapus']);
    }
    if (isset($_SESSION['flash_edit']) && $_SESSION['flash_edit'] == 'sukses') {
        echo "<script>document.addEventListener('DOMContentLoaded',function(){Swal.fire({icon:'success',title:'Sukses!',text:'Data catatan berhasil di update',position:'top',showConfirmButton:false,timer:3000,toast:true});});</script>";
        unset($_SESSION['flash_edit']);
    }
    if (isset($_GET['pesan']) && $_GET['pesan'] == 'duplikat') {
        echo "<script>document.addEventListener('DOMContentLoaded',function(){Swal.fire({icon:'info',title:'Catatan sudah terdaftar',position:'top',showConfirmButton:false,timer:2000,toast:true});});</script>";
    }
    if (isset($_SESSION['flash_tambah']) && $_SESSION['flash_tambah'] == 'sukses') {
        echo "<script>document.addEventListener('DOMContentLoaded',function(){Swal.fire({icon:'success',title:'Sukses!',text:'Data catatan berhasil ditambahkan',position:'top',showConfirmButton:false,timer:3000,toast:true});});</script>";
        unset($_SESSION['flash_tambah']);
    }
    if (isset($_SESSION['flash_error'])) {
        echo "<script>document.addEventListener('DOMContentLoaded',function(){Swal.fire({icon:'error',title:'Gagal!',text:'" . addslashes($_SESSION['flash_error']) . "',position:'top',showConfirmButton:false,timer:3000,toast:true});});</script>";
        unset($_SESSION['flash_error']);
    }
    if (isset($_SESSION['flash_duplikat']) && $_SESSION['flash_duplikat'] == 'duplikat') {
        echo "<script>document.addEventListener('DOMContentLoaded',function(){Swal.fire({icon:'warning',title:'Peringatan!',text:'Catatan sudah terdaftar!',position:'top',showConfirmButton:false,timer:3000,toast:true});});</script>";
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
