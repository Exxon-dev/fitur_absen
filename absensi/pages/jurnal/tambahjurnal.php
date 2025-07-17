<?php
include('koneksi.php');

// Ambil data jurnal jika ada
$tanggal_hari_ini = date('Y-m-d');
$id_siswa = $_SESSION['id_siswa'] ?? null;

if ($id_siswa) {
    $result = mysqli_query($coneksi, "
        SELECT * FROM jurnal 
        WHERE id_siswa='$id_siswa' AND tanggal='$tanggal_hari_ini'
    ");
    $jurnal_hari_ini = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jurnal</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <!-- Tambahkan SweetAlert2 CSS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .container {
            margin-top: 20px;
            padding: 20px;
            border-radius: 8px;
            background-color: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .small-date-input {
            max-width: 200px;
        }
    </style>
</head>

<body>
    <div class="container">
        <form id="formJurnal" action="pages/jurnal/proses_tambahjurnal.php" method="POST">
            <div class="form-group">
                <label>Tanggal</label>
                <div class="col-sm-10">
                    <input type="text" id="tanggal" class="form-control small-date-input"
                        value="<?php echo htmlspecialchars($tanggal_hari_ini ?? '') ?>" readonly required>
                </div>
            </div>
            <div class="form-group">
                <label>Keterangan</label>
                <div class="col-sm-10">
                    <textarea id="keterangan" name="keterangan" class="form-control" rows="4"
                        required><?php echo htmlspecialchars($jurnal_hari_ini['keterangan'] ?? '') ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-10">
                    <button type="submit" name="submit" class="btn btn-primary">SIMPAN</button>
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

    <script>
        // Ganti script sebelumnya dengan ini:
        document.getElementById('formJurnal').addEventListener('submit', function (e) {
            e.preventDefault();

            // Tampilkan loading indicator
            const submitBtn = this.querySelector('[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...';
            submitBtn.disabled = true;

            fetch(this.action, {
                method: 'POST',
                body: new FormData(this)
            })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = 'index.php?page=jurnal';
                        });
                    } else {
                        throw new Error(data.message || 'Terjadi kesalahan');
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: error.message || 'Terjadi kesalahan saat menyimpan jurnal'
                    });
                })
                .finally(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
        });
    </script>
</body>

</html>