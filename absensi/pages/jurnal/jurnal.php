<?php
include('koneksi.php');

$search = isset($_GET['search']) ? mysqli_real_escape_string($coneksi, $_GET['search']) : '';

// Query dengan JOIN tabel siswa untuk mendapatkan nama_siswa
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

$query .= " ORDER BY jurnal.id_jurnal";

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
        <h2 class="text-center">Jurnal</h2>
        <hr />

        <!-- Form pencarian -->
        <form method="get" class="search-bar">
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
                $no = 1;
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr style="text-align:center; cursor:pointer;" onclick="window.location=\'index.php?page=editjurnal&id_jurnal=' . $row['id_jurnal'] . '\'">';
                        echo '<td>' . $no++ . '</td>';
                        echo '<td>' . htmlspecialchars($row['nama_siswa']) . '</td>';
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
    </div>

    <script src="https://code.jquery.com/jquery-3.5.2.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if (isset($_GET['pesan'])): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                <?php if ($_GET['pesan'] == 'sukses_hapus'): ?>
                    Swal.fire({
                        icon: 'info',
                        title: 'Sukses!',
                        text: 'Data jurnal berhasil dihapus',
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2000,
                        toast: true
                    });
                <?php elseif ($_GET['pesan'] == 'gagal'): ?>
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: '<?php echo isset($_GET['error']) ? htmlspecialchars(urldecode($_GET['error']), ENT_QUOTES) : 'Terjadi kesalahan'; ?>',
                        position: 'top',
                        showConfirmButton: false,
                        timer: 3000,
                        toast: true
                    });
                <?php endif; ?>
            });
            <?php endif; ?>
        </script>
</body>

</html>