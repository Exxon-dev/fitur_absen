<?php
include('koneksi.php');

$id = isset($_GET['id_jurnal']) ? mysqli_real_escape_string($coneksi, $_GET['id_jurnal']) : '';

// Ambil data jurnal berdasarkan ID
$query = "SELECT * FROM jurnal WHERE id_jurnal = '$id'";
$result = mysqli_query($coneksi, $query);
$row = mysqli_fetch_assoc($result);
$role = $_SESSION['role'] ?? '';
$id_siswa = $_SESSION['id_siswa'] ?? null;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Jurnal</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <style>
        .form-container {
            margin-top: 20px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .small-date-input {
            width: 150px;
            /* Adjust this value to make the input smaller */
        }
    </style>
</head>

<body>
    <div class="container form-container">
        <h2>Detail Jurnal</h2>
        <hr>
        <div class="form-group">
            <label>Tanggal</label>
            <input type="text" class="form-control small-date-input"
                value="<?php echo htmlspecialchars($row['tanggal']); ?>" readonly>
        </div>
        <div class="form-group">
            <label>Keterangan</label>
            <textarea class="form-control" rows="4"
                readonly><?php echo htmlspecialchars($row['keterangan']); ?></textarea>
        </div>
        <div class="row">
            <div class="col text-left">
                <button type="button" class="btn btn-danger" id="btnHapus"
                    data-id="<?php echo $row['id_jurnal']; ?>">Hapus</button>
            </div>
            <div class="col text-right">
                <a href="index.php?page=jurnal" class="btn btn-warning">KEMBALI</a>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        // SweetAlert untuk konfirmasi hapus
        document.addEventListener('DOMContentLoaded', function() {
            const deleteBtn = document.getElementById('btnHapus');
            if (deleteBtn) {
                deleteBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const id = this.getAttribute('data-id');
                    Swal.fire({
                        title: "Apakah Anda yakin?",
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Ya, hapus!",
                        cancelButtonText: "Batal"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'index.php?page=hapusjurnal&id_jurnal=<?php echo $id; ?>';
                        }
                    });
                });
            }
        });
    </script>
</body>

</html>