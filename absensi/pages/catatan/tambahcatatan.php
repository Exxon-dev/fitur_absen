<?php
include('koneksi.php');

if (isset($_SESSION['level']) && $_SESSION['level'] === 'pembimbing') {
    $id_pembimbing = $_SESSION['id_pembimbing'];
} else {
    $id_pembimbing = null;
}
$tanggal_hari_ini = date('Y-m-d');
$tanggal = $_GET['tanggal'] ?? date('Y-m-d');
$id_jurnal = $_GET['id_jurnal'] ?? null;
$id_siswa = $_GET['id_siswa'] ?? null;

<<<<<<< HEAD
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

// Get notes
$catatan_result = mysqli_query(
    $coneksi,
    "SELECT c.*, p.nama_pembimbing, c.tanggal AS tanggal_catatan 
     FROM catatan c
     LEFT JOIN pembimbing p ON c.id_pembimbing = p.id_pembimbing
     WHERE c.id_jurnal = '$id_jurnal'
     ORDER BY c.id_catatan ASC"
);

=======
// Inisialisasi variabel
$jurnal_data = null;
>>>>>>> 1ba93e3e1841f0db196d55408850db39c813b6be
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
<<<<<<< HEAD
$catatan_data = $catatan_list[0] ?? null;

$keterangan = $jurnal_data['keterangan'] ?? 'Tidak ada jurnal';
$level = $_SESSION['level'] ?? '';
$id_pembimbing = $_SESSION['id_pembimbing'] ?? null;
=======

// Jika ada id_jurnal, ambil data jurnal berdasarkan tanggal yang difilter
if ($id_jurnal) {
    $jurnal_result = mysqli_query($coneksi, "SELECT * FROM jurnal WHERE id_jurnal = '$id_jurnal'");
    $jurnal_data = mysqli_fetch_assoc($jurnal_result);

    if ($jurnal_data) {
        $keterangan = $jurnal_data['keterangan'] ?? 'Tidak ada jurnal';
        $id_siswa = $jurnal_data['id_siswa'] ?? $id_siswa;
    }
} elseif ($id_siswa) {
    // Jika tidak ada id_jurnal tetapi ada id_siswa, cari jurnal berdasarkan tanggal
    $jurnal_result = mysqli_query($coneksi, "SELECT * FROM jurnal WHERE id_siswa = '$id_siswa' AND DATE(tanggal) = '$tanggal'");
    if ($jurnal_result && mysqli_num_rows($jurnal_result) > 0) {
        $jurnal_data = mysqli_fetch_assoc($jurnal_result);
        $id_jurnal = $jurnal_data['id_jurnal'];
        $keterangan = $jurnal_data['keterangan'] ?? 'Tidak ada jurnal';
    }
}

// Get notes - tampilkan semua catatan untuk siswa ini, terlepas dari ada jurnal atau tidak
if ($id_siswa) {
    $catatan_query = "SELECT c.*, p.nama_pembimbing, c.tanggal AS tanggal_catatan 
                     FROM catatan c
                     LEFT JOIN pembimbing p ON c.id_pembimbing = p.id_pembimbing
                     WHERE c.id_siswa = '$id_siswa' AND DATE(c.tanggal) = '$tanggal'
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

// Cek apakah pembimbing sudah punya catatan untuk siswa ini pada tanggal yang dipilih
if ($level === 'pembimbing' && $id_pembimbing && $id_siswa) {
    $cek_catatan_query = "SELECT * FROM catatan 
                         WHERE id_siswa = '$id_siswa' 
                         AND id_pembimbing = '$id_pembimbing'
                         AND DATE(tanggal) = '$tanggal'
                         ORDER BY id_catatan DESC LIMIT 1";
    $cek_catatan_result = mysqli_query($coneksi, $cek_catatan_query);

    if ($cek_catatan_result && mysqli_num_rows($cek_catatan_result) > 0) {
        $catatan_pembimbing = mysqli_fetch_assoc($cek_catatan_result);
    }
}

// Tentukan mode dan teks tombol
$mode = ($level === 'pembimbing' && $catatan_pembimbing) ? 'update' : 'tambah';
$teks_tombol = ($level === 'pembimbing') ? (($mode === 'update') ? 'UPDATE' : 'SIMPAN') : '';
>>>>>>> 1ba93e3e1841f0db196d55408850db39c813b6be
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
    <div class="main-container container-custom">
        <h2 class="text-center text-primary"><?= ($level === 'pembimbing') ? 'Tambah Catatan' : 'Lihat Catatan' ?></h2>
        <hr>
        <form id="formTambahCatatan" action="pages/catatan/proses_tambahcatatan.php" method="post">
            <?php if ($catatan_data): ?>
                <input type="hidden" name="id_catatan" value="<?= $catatan_data['id_catatan'] ?>">
            <?php endif; ?>
            <input type="hidden" name="id_jurnal" value="<?= htmlspecialchars($id_jurnal) ?>">
            <input type="hidden" name="id_siswa" value="<?= htmlspecialchars($id_siswa) ?>">
            <input type="hidden" name="tanggal" value="<?= htmlspecialchars($tanggal) ?>">

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Tanggal</label>
                <div class="col-sm-15">
                    <input type="text" class="form-control" value="<?= htmlspecialchars(date('d-m-Y', strtotime($tanggal))) ?>" readonly>
                </div>
            </div>

            <?php if ($id_siswa): ?>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Nama Siswa</label>
                    <div class="col-sm-15">
                        <input type="text" class="form-control" value="<?= htmlspecialchars($nama_siswa) ?>" readonly>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($jurnal_data): ?>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Jurnal</label>
                    <div class="col-sm-15">
                        <textarea class="form-control" rows="2" readonly><?= htmlspecialchars($keterangan) ?></textarea>
                    </div>
                </div>
            <?php endif; ?>

<<<<<<< HEAD
            <?php if ($catatan_data): ?>
                <input type="hidden" name="id_catatan" value="<?= htmlspecialchars($catatan_data['id_catatan']) ?>">
            <?php endif; ?>

            <?php if ($level === 'pembimbing'): ?>
                <textarea name="catatan" class="form-control mb-3" rows="4" placeholder="Tulis catatan..." required></textarea>
            <?php endif; ?>

            <?php foreach ($catatan_list as $row): ?>
                <div class="note-container">
                    <strong><?= htmlspecialchars($row['nama_pembimbing'] ?? 'Tidak diketahui') ?>:</strong>
                    <?= nl2br(htmlspecialchars($row['catatan'] ?? '')) ?>
                    <br>
                    <small><em><?= htmlspecialchars($row['tanggal_catatan'] ?? '') ?></em></small>
                </div>
            <?php endforeach; ?>
=======
            <?php if ($level === 'pembimbing'): ?>
                <div class="form-group">
                    <label for="catatan">Catatan Pembimbing</label>
                    <textarea name="catatan" class="form-control mb-3" rows="4" placeholder="Tulis catatan..." required><?= htmlspecialchars($catatan_pembimbing['catatan'] ?? '') ?></textarea>
                </div>
            <?php endif; ?>

            <?php if (!empty($catatan_list)): ?>
                <h5>Catatan Pembimbing:</h5>
                <?php foreach ($catatan_list as $row): ?>
                    <div class="note-container">
                        <strong><?= htmlspecialchars($row['nama_pembimbing'] ?? 'Tidak diketahui') ?>:</strong>
                        <?= nl2br(htmlspecialchars($row['catatan'] ?? '')) ?>
                        <br>
                        <small><em><?= htmlspecialchars(date('d-m-Y H:i', strtotime($row['tanggal_catatan'] ?? ''))) ?></em></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">Belum ada catatan.</p>
            <?php endif; ?>

>>>>>>> 1ba93e3e1841f0db196d55408850db39c813b6be
            <br>

            <?php if ($level === 'pembimbing'): ?>
                <div class="form-group row">
                    <div class="col text-left">
<<<<<<< HEAD
                        <?php if ($catatan_data): ?>
                            <a href="pages/catatan/hapuscatatan.php?id_catatan=<?= $catatan_data['id_catatan'] ?>" class="hapusCatatan" id="btnHapusCatatan">Hapus</a>
                        <?php endif; ?>
                    </div>
                    <div class="col text-right">
                        <a href="index.php?page=catatan" class="btn btn-warning">KEMBALI</a>
                        <input type="submit" name="submit" class="btn btn-primary" value="SIMPAN">
=======
                        <?php if ($catatan_pembimbing): ?>
                            <a href="pages/catatan/hapuscatatan.php?id_catatan=<?= $catatan_pembimbing['id_catatan'] ?>&id_siswa=<?= $id_siswa ?>&tanggal=<?= $tanggal ?>" class="hapusCatatan" id="btnHapusCatatan">Hapus</a>
                        <?php endif; ?>
                    </div>
                    <div class="col text-right">
                        <a href="index.php?page=catatan&tanggal=<?= $tanggal ?>" class="btn btn-warning">KEMBALI</a>
                        <input type="submit" name="submit" class="btn btn-primary" value="<?= $teks_tombol ?>">
>>>>>>> 1ba93e3e1841f0db196d55408850db39c813b6be
                    </div>
                </div>
            <?php else: ?>
                <div class="form-group row">
                    <div class="col text-right">
                        <a href="index.php?page=catatan&tanggal=<?= $tanggal ?>" class="btn btn-warning">KEMBALI</a>
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

            // Tampilkan SweetAlert berdasarkan status flash message
            <?php if (isset($_SESSION['flash_tambah']) && $_SESSION['flash_tambah'] == 'sukses'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: 'Catatan berhasil ditambahkan',
                    position: 'top',
                    showConfirmButton: false,
                    timer: 3000,
                    toast: true
                });
                <?php unset($_SESSION['flash_tambah']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['flash_update']) && $_SESSION['flash_update'] == 'sukses'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: 'Catatan berhasil diupdate',
                    position: 'top',
                    showConfirmButton: false,
                    timer: 3000,
                    toast: true
                });
                <?php unset($_SESSION['flash_update']); ?>
            <?php endif; ?>
        });
    </script>
</body>

</html>