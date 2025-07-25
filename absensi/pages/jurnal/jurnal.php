<?php
include('koneksi.php');

$search = isset($_GET['search']) ? mysqli_real_escape_string($coneksi, $_GET['search']) : '';

$limit = 4; // Jumlah data per halaman
$page = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$start = ($page - 1) * $limit;

// Query menghitung total data (untuk pagination)
$totalQuery = "
    SELECT COUNT(*) as total 
    FROM jurnal 
    LEFT JOIN siswa ON jurnal.id_siswa = siswa.id_siswa
";
if ($search) {
    $totalQuery .= " WHERE jurnal.tanggal LIKE '%$search%'
                     OR jurnal.keterangan LIKE '%$search%' 
                     OR siswa.nama_siswa LIKE '%$search%'";
}
$totalResult = mysqli_query($coneksi, $totalQuery);
$totalData = mysqli_fetch_assoc($totalResult)['total'];
$totalPages = ceil($totalData / $limit);

// Query ambil data jurnal + siswa
$query = "
    SELECT jurnal.*, siswa.nama_siswa 
    FROM jurnal 
    LEFT JOIN siswa ON jurnal.id_siswa = siswa.id_siswa
";
if ($search) {
    $query .= " WHERE jurnal.tanggal LIKE '%$search%'
                OR jurnal.keterangan LIKE '%$search%' 
                OR siswa.nama_siswa LIKE '%$search%'";
}
$query .= " ORDER BY jurnal.id_jurnal DESC LIMIT $start, $limit";

$result = mysqli_query($coneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Jurnal</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 20px;
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
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

        .search-bar {
            margin-bottom: 20px;
            display: flex;
            justify-content: flex-end;
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="index.php?page=tambahjurnal" class="btn btn-primary">Tambah Jurnal</a>
        <h2 class="text-center">Data Jurnal</h2>
        <hr>

        <!-- Form pencarian -->
        <form method="GET" class="search-bar" action="index.php">
            <input type="hidden" name="page" value="jurnal" />
            <input type="text" name="search" class="form-control w-25" placeholder="Cari..." value="<?php echo htmlspecialchars($search); ?>" />
            <button type="submit" class="btn btn-primary ml-2">Cari</button>
        </form>

        <!-- Tabel Jurnal -->
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>Siswa</th>
                    <th>Keterangan</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = $start + 1;
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr style="text-align:center; cursor:pointer;" onclick="window.location=\'index.php?page=editjurnal&id_jurnal=' . $row['id_jurnal'] . '\'">';
                        echo '<td>' . $no++ . '</td>';
                        echo '<td>' . htmlspecialchars($row['nama_siswa'] ?? '') . '</td>';
                        echo '<td>' . htmlspecialchars($row['keterangan']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['tanggal']) . '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="4" class="text-center">Tidak ada data</td></tr>';
                }
                ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <nav>
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=jurnal&search=<?= urlencode($search) ?>&halaman=<?= $page - 1 ?>"><-</a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=jurnal&search=<?= urlencode($search) ?>&halaman=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=jurnal&search=<?= urlencode($search) ?>&halaman=<?= $page + 1 ?>">-></a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php
    // Notifikasi flash message hapus
    if (isset($_SESSION['flash_hapus']) && $_SESSION['flash_hapus'] == 'sukses') {
        echo "
    <script>
    document.addEventListener('DOMContentLoaded',function()
    {Swal.fire
        ({icon:'info',
            title:'Sukses!',
            text:'Data jurnal berhasil dihapus',
            position:'top',
            showConfirmButton:false,
            timer:3000,
            toast:true
        });
    });</script>";
        unset($_SESSION['flash_hapus']);
    }
    ?>
    <?php
    if (isset($_SESSION['flash_edit']) && $_SESSION['flash_edit'] == 'sukses') {
        echo "
    <script>document.addEventListener('DOMContentLoaded',function()
    {Swal.fire
        ({icon:'success',
            title:'Sukses!',
            text:'Data jurnal berhasil di update',
            position:'top',
            showConfirmButton:false,
            timer:3000,
            toast:true
        });
    });</script>";
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
        text: 'Data jurnal berhasil ditambahkan',
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
        text: 'Jurnal sudah sama',
        position: 'top',
        showConfirmButton: false,
        timer: 3000,
        toast: true
    });
});</script>";
        unset($_SESSION['flash_duplikat']);
    }
    ?>

</body>

</html>