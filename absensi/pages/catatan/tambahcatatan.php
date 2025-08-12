<?php
include('koneksi.php');

if (isset($_SESSION['level']) && $_SESSION['level'] === 'pembimbing') {
    $id_pembimbing = $_SESSION['id_pembimbing'];
} else {
    $id_pembimbing = null; // Atau bisa redirect ke halaman login jika perlu
}
$tanggal_hari_ini = date('Y-m-d');
$id_jurnal = $_GET['id_jurnal'] ?? null;
if (!$id_jurnal) {
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: "ID Jurnal tidak ditemukan",
                        toast: true,
                        position: "top",
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                    setTimeout(function () {
                        window.location.href = "index.php?page=catatan&pesan=gagal&error=' . urlencode("ID Jurnal tidak ditemukan") . '";
                    }, 3000);
                });
            </script>';
    exit();
}
$jurnal_result = mysqli_query($coneksi, "SELECT * FROM jurnal WHERE id_jurnal = '$id_jurnal'");
$jurnal_data = mysqli_fetch_assoc($jurnal_result);
if (!$jurnal_data) {
    header('Location: index.php?page=catatan&pesan=gagal&error=' . urlencode('Data jurnal tidak ditemukan'));
    exit();
}
// ambil catatan (letakkan sebelum HTML)
$catatan_result = mysqli_query(
    $coneksi,
    "SELECT c.*, p.nama_pembimbing, c.tanggal AS tanggal_catatan 
     FROM catatan c
     LEFT JOIN pembimbing p ON c.id_pembimbing = p.id_pembimbing
     WHERE c.id_jurnal = '$id_jurnal'
     ORDER BY c.id_catatan ASC"
);

// masukkan ke array supaya tidak kehilangan baris saat looping
$catatan_list = [];
if ($catatan_result) {
    while ($r = mysqli_fetch_assoc($catatan_result)) {
        $catatan_list[] = $r;
    }
}
$catatan_data = $catatan_list[0] ?? null; // baris pertama, kalau ada



$keterangan = $jurnal_data['keterangan'] ?? 'Tidak ada jurnal';
$level = $_SESSION['level'] ?? '';
$id_pembimbing = $_SESSION['id_pembimbing'] ?? null;
?>
<!-- HTML Form -->
<!DOCTYPE html>
<html>

<head>
    <title>Tambah Catatan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
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

        .hapusCatatan {
            color: white;
            /* Text putih */
            background-color: #6c757d;
            /* Warna abu-abu Bootstrap */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            /* Shadow */
            border: none;
            /* Hilangkan border */
            padding: 8px 16px;
            /* Padding yang sesuai */
            border-radius: 4px;
            /* Sedikit rounded corners */
            transition: all 0.3s ease;
            /* Efek transisi halus */
        }

        .hapusCatatan:hover {
            background-color: #5a6268;
            /* Warna lebih gelap saat hover */
            color: white;
            /* Tetap putih saat hover */
            transform: translateY(-1px);
            /* Sedikit efek angkat */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
            /* Shadow lebih besar saat hover */
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
        <h2 class="text-center text-primary">Tambah Catatan</h2>
        <hr>
        <form id="formTambahCatatan" action="pages/catatan/proses_tambahcatatan.php" method="post">
            <?php if ($catatan_data): ?>
                <input type="hidden" name="id_catatan" value="<?= $catatan_data['id_catatan'] ?>">
            <?php endif; ?>
            <input type="hidden" name="id_jurnal" value="<?= htmlspecialchars($id_jurnal) ?>">

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Tanggal</label>
                <div class="col-sm-15">
                    <input type="text" class="form-control" value="<?= htmlspecialchars($tanggal_hari_ini) ?>" readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Jurnal</label>
                <div class="col-sm-15">
                    <textarea class="form-control" rows="2" readonly><?= htmlspecialchars($keterangan) ?></textarea>
                </div>
            </div>

            <?php if ($catatan_data): ?>
                <input type="hidden" name="id_catatan" value="<?= htmlspecialchars($catatan_data['id_catatan']) ?>">
            <?php endif; ?>

            <?php if ($level === 'pembimbing'): ?>
                <textarea name="catatan" class="form-control mb-3" rows="4" placeholder="Tulis catatan..." required></textarea>
            <?php endif; ?>

            <?php foreach ($catatan_list as $row): ?>
                <div style="background:#f1f1f1;padding:10px;margin-bottom:8px;border-radius:5px;">
                    <strong><?= htmlspecialchars($row['nama_pembimbing'] ?? 'Tidak diketahui') ?>:</strong>
                    <?= nl2br(htmlspecialchars($row['catatan'] ?? '')) ?>
                    <br>
                    <small><em><?= htmlspecialchars($row['tanggal_catatan'] ?? '') ?></em></small>
                </div>
            <?php endforeach; ?>
                <br>
            <?php if ($level === 'pembimbing'): ?>
                <div class="form-group row">
                    <div class="col text-left">

                        <?php if ($catatan_data): ?>
                            <a href="pages/catatan/hapuscatatan.php?id_catatan=<?= $catatan_data['id_catatan'] ?>" class="hapusCatatan" id="btnHapusCatatan">Hapus</a>
                        <?php endif; ?>
                    </div>

                    <div class="col text-right">
                        <a href="index.php?page=catatan" class="btn btn-warning">KEMBALI</a>
                        <input type="submit" name="submit" class="btn btn-primary" value="SIMPAN">
                    </div>
                </div>

            <?php else: ?>
                <div class="form-group row">
                    <div class="col text-right">
                        <a href="index.php?page=catatan" class="btn btn-warning">KEMBALI</a>
                    </div>
                </div>
            <?php endif; ?>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var btnHapus = document.getElementById("btnHapusCatatan");
            if (btnHapus) {
                btnHapus.addEventListener("click", function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Yakin ingin menghapus catatan ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = btnHapus.getAttribute('href');
                        }
                    });
                });
            }
        });
    </script>
</body>

</html>