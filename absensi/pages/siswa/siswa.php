<?php
include('koneksi.php');
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 20px;
            background-color: #ffffff;
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

        .btn-warning {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <div class="container">
        <a href="index.php?page=tambahsiswa" class="btn btn-primary">Tambah Siswa</a>
        <h2 class="text-center">Data SISWA</h2>

        <hr>

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NISN</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Sekolah</th>
                    <th>Tempat Prakerin</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = mysqli_query($coneksi, "
    SELECT 
        s.id_siswa, s.nisn, s.nama_siswa, s.kelas, 
        s.tanggal_mulai, s.tanggal_selesai,
        sk.nama_sekolah, 
        p.nama_perusahaan
    FROM siswa s
    LEFT JOIN sekolah sk ON s.id_sekolah = sk.id_sekolah
    LEFT JOIN perusahaan p ON s.id_perusahaan = p.id_perusahaan
    ORDER BY sk.nama_sekolah ASC, p.nama_perusahaan ASC
") or die(mysqli_error($coneksi));

                if (mysqli_num_rows($sql) > 0) {
                    $no = 1;
                    while ($data = mysqli_fetch_assoc($sql)) {
                        echo '
        <tr style="text-align:center; cursor:pointer;" onclick="window.location=\'index.php?page=editsiswa1&id_siswa=' . $data['id_siswa'] . '\'">
            <td>' . $no . '</td>
            <td>' . htmlspecialchars($data['nisn']) . '</td>
            <td>' . htmlspecialchars($data['nama_siswa']) . '</td>
            <td>' . htmlspecialchars($data['kelas']) . '</td>
            <td>' . htmlspecialchars($data['nama_sekolah']) . '</td>
            <td>' . htmlspecialchars($data['nama_perusahaan']) . '</td>
            <td>' . $data['tanggal_mulai'] . '</td>
            <td>' . $data['tanggal_selesai'] . '</td>
        </tr>';
                        $no++;
                    }
                } else {
                    echo '
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data.</td>
                </tr>
                ';
                }
                ?>

            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php
    // Notifikasi flash message hapus
    if (isset($_SESSION['flash_hapus']) && $_SESSION['flash_hapus'] == 'sukses') {
        echo "<script>document.addEventListener('DOMContentLoaded',function(){Swal.fire({icon:'info',title:'Sukses!',text:'Data siswa berhasil dihapus',position:'top',showConfirmButton:false,timer:3000,toast:true});});</script>";
        unset($_SESSION['flash_hapus']);
    }
    ?>

    <?php if (isset($_GET['pesan'])): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'sukses'): ?>
                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses!',
                        text: <?php
                                if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'editsiswa1') !== false) {
                                    echo "'Data siswa berhasil diupdate'";
                                } else {
                                    echo "'Data siswa berhasil ditambahkan'";
                                }
                                ?>,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2000,
                        toast: true
                    });
                <?php elseif (isset($_GET['pesan']) && $_GET['pesan'] == 'gagal'): ?>
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: '<?php echo isset($_GET['error']) ? htmlspecialchars(urldecode($_GET['error']), ENT_QUOTES) : 'Terjadi kesalahan'; ?>',
                        position: 'top',
                        showConfirmButton: false,
                        timer: 3000,
                        toast: true
                    });
                <?php elseif (isset($_GET['pesan']) && $_GET['pesan'] == 'duplikat'): ?>
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan!',
                        text: 'ID siswa atau Username sudah terdaftar',
                        position: 'top',
                        showConfirmButton: false,
                        timer: 3000,
                        toast: true
                    });
                <?php endif; ?>
            });
        </script>
    <?php endif; ?>

</body>

</html>