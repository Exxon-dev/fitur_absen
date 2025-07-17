<?php
include('koneksi.php');
session_start();
$tanggal_hari_ini = date('Y-m-d');
$id_jurnal = $_GET['id_jurnal'] ?? null;
if (!$id_jurnal) {
    header('Location: index.php?page=catatan&pesan=gagal&error='.urlencode('ID Jurnal tidak ditemukan'));
    exit();
}
$jurnal_result = mysqli_query($coneksi, "SELECT * FROM jurnal WHERE id_jurnal = '$id_jurnal'");
$jurnal_data = mysqli_fetch_assoc($jurnal_result);
if (!$jurnal_data) {
    header('Location: index.php?page=catatan&pesan=gagal&error='.urlencode('Data jurnal tidak ditemukan'));
    exit();
}
$catatan_result = mysqli_query($coneksi, "SELECT * FROM catatan WHERE id_jurnal = '$id_jurnal'");
$catatan_data = mysqli_fetch_assoc($catatan_result);
$keterangan = $jurnal_data['keterangan'] ?? 'Tidak ada jurnal';
$role = $_SESSION['role'] ?? '';
$id_pembimbing = $_SESSION['id_pembimbing'] ?? null;
?>
<!-- HTML Form -->
<!DOCTYPE html>
<html>

<head>
    <title>Tambah Catatan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 20px;
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Tambah Catatan</h2>
        <hr>
        <form id="formTambahCatatan" action="pages/catatan/proses_tambahcatatan.php" method="post">
            <?php if ($catatan_data): ?>
                <input type="hidden" name="id_catatan" value="<?= $catatan_data['id_catatan'] ?>">
            <?php endif; ?>
            <input type="hidden" name="id_jurnal" value="<?= htmlspecialchars($id_jurnal) ?>">

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Tanggal</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" value="<?= htmlspecialchars($tanggal_hari_ini) ?>" readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Jurnal</label>
                <div class="col-sm-10">
                    <textarea class="form-control" rows="2" readonly><?= htmlspecialchars($keterangan) ?></textarea>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Catatan</label>
                <div class="col-sm-10">
                    <?php if ($role === 'id_pembimbing'): ?>
                        <textarea name="catatan" class="form-control" rows="4"
                            required><?= htmlspecialchars($catatan_data['catatan'] ?? '') ?></textarea>
                    <?php else: ?>
                        <textarea class="form-control" rows="4"
                            readonly><?= htmlspecialchars($catatan_data['catatan'] ?? '') ?></textarea>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($role === 'id_pembimbing'): ?>
                <div class="form-group row">
                    <div class="col text-left">
                        <input type="submit" name="submit" class="btn btn-primary" value="SIMPAN">
                    </div>
                    <div class="col text-center">
                        <?php if ($catatan_data): ?>
                        <a href="pages/catatan/hapuscatatan.php?id_catatan=<?= $catatan_data['id_catatan'] ?>" class="btn btn-danger" id="btnHapusCatatan">Hapus</a>
                        <?php endif; ?>
                    </div>
                    <div class="col text-center">
                        <a href="index.php?page=catatan" class="btn btn-warning">KEMBALI</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="form-group row">
                    <div class="col text-left">
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