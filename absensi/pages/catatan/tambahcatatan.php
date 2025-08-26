<?php
include('koneksi.php');

// Cek session level
$level = $_SESSION['level'] ?? '';
$id_pembimbing = $_SESSION['id_pembimbing'] ?? null;

$tanggal_hari_ini = date('Y-m-d');
$id_jurnal = $_GET['id_jurnal'] ?? null;
$id_siswa = $_GET['id_siswa'] ?? null;

// Inisialisasi variabel
$jurnal_data = null;
$catatan_list = [];
$catatan_pembimbing = null;
$keterangan = 'Tidak ada jurnal';
$nama_siswa = '';

// Jika ada id_siswa, ambil nama siswa
if ($id_siswa) {
    $siswa_result = mysqli_query($coneksi, "SELECT nama_siswa FROM siswa WHERE id_siswa = '$id_siswa'");
    if ($siswa_result && mysqli_num_rows($siswa_result) > 0) {
        $siswa_data = mysqli_fetch_assoc($siswa_result);
        $nama_siswa = $siswa_data['nama_siswa'];
    }
}

// Jika ada id_jurnal, ambil data jurnal
if ($id_jurnal) {
    $jurnal_result = mysqli_query($coneksi, "SELECT * FROM jurnal WHERE id_jurnal = '$id_jurnal'");
    $jurnal_data = mysqli_fetch_assoc($jurnal_result);

    if ($jurnal_data) {
        $keterangan = $jurnal_data['keterangan'] ?? 'Tidak ada jurnal';
        $id_siswa = $jurnal_data['id_siswa'] ?? $id_siswa;
    }
}

// Get notes - tampilkan semua catatan untuk siswa ini, terlepas dari ada jurnal atau tidak
if ($id_siswa) {
    $catatan_query = "SELECT c.*, p.nama_pembimbing, c.tanggal AS tanggal_catatan 
                     FROM catatan c
                     LEFT JOIN pembimbing p ON c.id_pembimbing = p.id_pembimbing
                     WHERE c.id_siswa = '$id_siswa'
                     ORDER BY c.id_catatan DESC"; // Urutkan dari yang terbaru
}

if (!empty($catatan_query)) {
    $catatan_result = mysqli_query($coneksi, $catatan_query);
    if ($catatan_result) {
        while ($r = mysqli_fetch_assoc($catatan_result)) {
            $catatan_list[] = $r;
        }
    }
}

// Cek apakah pembimbing sudah punya catatan untuk siswa ini
if ($level === 'pembimbing' && $id_pembimbing && $id_siswa) {
    $cek_catatan_query = "SELECT * FROM catatan 
                         WHERE id_siswa = '$id_siswa' 
                         AND id_pembimbing = '$id_pembimbing'
                         ORDER BY id_catatan DESC LIMIT 1";
    $cek_catatan_result = mysqli_query($coneksi, $cek_catatan_query);
    
    if ($cek_catatan_result && mysqli_num_rows($cek_catatan_result) > 0) {
        $catatan_pembimbing = mysqli_fetch_assoc($cek_catatan_result);
    }
}

// Tentukan mode dan teks tombol
$mode = ($level === 'pembimbing' && $catatan_pembimbing) ? 'update' : 'tambah';
$teks_tombol = ($level === 'pembimbing') ? (($mode === 'update') ? 'UPDATE' : 'SIMPAN') : '';
?>

<!DOCTYPE html>
<html>

<head>
    <title><?= ($level === 'pembimbing') ? 'Tambah Catatan' : 'Lihat Catatan' ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <style>
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

        .hapusCatatan {
            color: white;
            background-color: #6c757d;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .hapusCatatan:hover {
            background-color: #5a6268;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        }

        .note-container {
            background: #f1f1f1;
            padding: 10px;
            margin-bottom: 8px;
            border-radius: 5px;
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
    <h2 class="text-left text-primary"><?= ($level === 'pembimbing') ? (($mode === 'update') ? 'Update Catatan' : 'Tambah Catatan') : 'Lihat Catatan' ?></h2>
    <div class="main-container container-custom">
        <form id="formTambahCatatan" action="pages/catatan/proses_tambahcatatan.php" method="post">
            <?php if ($catatan_pembimbing): ?>
                <input type="hidden" name="id_catatan" value="<?= $catatan_pembimbing['id_catatan'] ?>">
            <?php endif; ?>
            <input type="hidden" name="mode" value="<?= $mode ?>">
            <input type="hidden" name="id_jurnal" value="<?= htmlspecialchars($id_jurnal) ?>">
            <input type="hidden" name="id_siswa" value="<?= htmlspecialchars($id_siswa) ?>">

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Tanggal</label>
                <div class="col-sm-15">
                    <input type="text" class="form-control" value="<?= htmlspecialchars($tanggal_hari_ini) ?>" readonly>
                </div>
            </div>

            <?php if ($id_jurnal && $jurnal_data): ?>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Jurnal</label>
                    <div class="col-sm-15">
                        <textarea class="form-control" rows="2" readonly><?= htmlspecialchars($keterangan) ?></textarea>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($level === 'pembimbing'): ?>
                <div class="form-group">
                    <label for="catatan">Catatan Pembimbing</label>
                    <textarea name="catatan" class="form-control mb-3" rows="4" placeholder="Tulis catatan..." required><?= $catatan_pembimbing ? htmlspecialchars($catatan_pembimbing['catatan']) : '' ?></textarea>
                </div>
            <?php endif; ?>

            <?php if (!empty($catatan_list)): ?>
                <h5>Catatan Pembimbing:</h5>
                <?php foreach ($catatan_list as $row): ?>
                    <div class="note-container">
                        <strong><?= htmlspecialchars($row['nama_pembimbing'] ?? 'Tidak diketahui') ?>:</strong>
                        <?= nl2br(htmlspecialchars($row['catatan'] ?? '')) ?>
                        <br>
                        <small><em><?= htmlspecialchars($row['tanggal_catatan'] ?? '') ?></em></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">Belum ada catatan.</p>
            <?php endif; ?>

            <br>

            <?php if ($level === 'pembimbing'): ?>
                <div class="form-group row">
                    <div class="col text-left">
                        <?php if ($catatan_pembimbing): ?>
                            <a href="pages/catatan/hapuscatatan.php?id_catatan=<?= $catatan_pembimbing['id_catatan'] ?>&id_siswa=<?= $id_siswa ?>" class="hapusCatatan" id="btnHapusCatatan">Hapus</a>
                        <?php endif; ?>
                    </div>
                    <div class="col text-right">
                        <a href="index.php?page=catatan" class="btn btn-warning">KEMBALI</a>
                        <input type="submit" name="submit" class="btn btn-primary" value="<?= $teks_tombol ?>">
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