<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('koneksi.php');

// CEK LEVEL PENGGUNA DI AWAL
if (!isset($_SESSION['level'])) {
    die("<div class='container mt-5'><div class='alert alert-danger'>Anda harus login terlebih dahulu</div></div>");
}

if ($_SESSION['level'] !== 'siswa') {
    die("<div class='container mt-5'><div class='alert alert-warning'>Akses ditolak: Hanya siswa yang dapat menambah jurnal</div></div>");
}

if (!isset($_SESSION['id_siswa'])) {
    die("<div class='container mt-5'><div class='alert alert-danger'>Session siswa tidak valid</div></div>");
}

// Ambil data jurnal jika ada
$tanggal_hari_ini = date('Y-m-d');
$id_siswa = $_SESSION['id_siswa'];

$jurnal_hari_ini = null;
$result = mysqli_query($coneksi, "
    SELECT * FROM jurnal 
    WHERE id_siswa='$id_siswa' AND tanggal='$tanggal_hari_ini'
");
if ($result) {
    $jurnal_hari_ini = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($jurnal_hari_ini) ? 'Update' : 'Tambah' ?> Jurnal</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
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

        .small-date-input {
            max-width: 200px;
        }

        .alert-container {
            position: fixed;
            top: 20px;
            right: 20px;
            width: 300px;
            z-index: 1000;
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
    <!-- Container untuk alert -->
    <div class="alert-container">
        <?php if (isset($_SESSION['flash_error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= $_SESSION['flash_error'] ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>
    </div>

    <div class="main-container container-custom">
        <h2 class="mb-4"><?= isset($jurnal_hari_ini) ? 'Update' : 'Tambah' ?> Jurnal Harian</h2>

        <form id="jurnalForm" action="pages/jurnal/proses_tambahjurnal.php" method="POST">
            <div class="form-group">
                <label>Tanggal</label>
                <input type="text" class="form-control small-date-input"
                    value="<?= htmlspecialchars($tanggal_hari_ini) ?>" readonly>
            </div>

            <div class="form-group">
                <label>Keterangan Kegiatan</label>
                <textarea class="form-control" rows="6" name="keterangan" required><?=
                htmlspecialchars($jurnal_hari_ini['keterangan'] ?? '')
                ?></textarea>
            </div>

            <div class="form-group text-right">
                <a href="index.php?page=catatan" class="btn btn-warning ml-2">Kembali</a>
                <button type="submit" class="btn btn-primary">
                    <?= isset($jurnal_hari_ini) ? 'Update Jurnal' : 'Simpan Jurnal' ?>
                </button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Auto-hide alert setelah 5 detik
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);

            // Konfirmasi sebelum submit
            $('#jurnalForm').on('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Konfirmasi',
                    text: "Apakah Anda yakin ingin <?= isset($jurnal_hari_ini) ? 'mengupdate' : 'menyimpan' ?> jurnal ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '<?= isset($jurnal_hari_ini) ? 'Update' : 'Simpan' ?>',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    </script>
</body>

</html>