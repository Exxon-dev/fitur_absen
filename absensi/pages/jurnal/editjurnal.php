<?php
include('koneksi.php');

// Check if user is logged in as student
$is_student = (isset($_SESSION['level']) && $_SESSION['level'] === 'siswa');
$current_user_id = $_SESSION['id_siswa'] ?? null;

// Get journal ID from URL
$id_jurnal = $_GET['id_jurnal'] ?? null;
if (!$id_jurnal) {
    header('Location: index.php?page=jurnal&pesan=gagal&error=' . urlencode('ID Jurnal tidak ditemukan'));
    exit();
}

// Fetch journal data
$jurnal_result = mysqli_query($coneksi, "SELECT * FROM jurnal WHERE id_jurnal = '$id_jurnal'");
$jurnal_data = mysqli_fetch_assoc($jurnal_result);

if (!$jurnal_data) {
    header('Location: index.php?page=jurnal&pesan=gagal&error=' . urlencode('Data jurnal tidak ditemukan'));
    exit();
}

// Check if current user is the owner of the journal
$is_owner = ($is_student && ($current_user_id == $jurnal_data['id_siswa']));
$can_edit = ($is_owner || (isset($_SESSION['level']) && $_SESSION['level'] === 'id_siswa')); // Allow siswa to edit too if needed
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Jurnal</title>
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

        .small-date-input {
            width: 150px;
        }

        .readonly-field {
            background-color: #f8f9fa;
            cursor: not-allowed;
        }

        .btn-container {
            margin-top: 20px;
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
        <h2>Detail Jurnal</h2>
        <hr>
        <form id="jurnalForm" action="pages/jurnal/proses_editjurnal.php" method="POST">
            <input type="hidden" name="id_jurnal" value="<?php echo $jurnal_data['id_jurnal']; ?>">
            <div class="form-group">
                <label>Tanggal</label>
                <input type="date" name="tanggal" class="form-control small-date-input <?php echo !$can_edit ? 'readonly-field' : ''; ?>"
                    value="<?php echo htmlspecialchars($jurnal_data['tanggal']); ?>" <?php echo !$can_edit ? 'readonly-field' : ''; ?> required>
            </div>

            <div class="form-group">
                <label>Keterangan</label>
                <textarea class="form-control <?php echo !$can_edit ? 'readonly-field' : ''; ?>" rows="4"
                    name="keterangan" <?php echo !$can_edit ? 'readonly' : ''; ?> required><?php echo htmlspecialchars($jurnal_data['keterangan']); ?></textarea>
            </div>

            <div class="row btn-container">
                <?php if ($can_edit): ?>
                    <div class="col text-left">
                        <button type="submit" class="btn btn-primary" id="btnSimpan">Simpan</button>
                    </div>
                    <div class="col text-center">
                        <button type="button" class="btn btn-danger" id="btnHapus">Hapus</button>
                    </div>
                <?php endif; ?>
                <div class="col text-right">
                    <a href="index.php?page=jurnal" class="btn btn-warning">Kembali</a>
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // SweetAlert for delete confirmation
            $('#btnHapus').on('click', function(e) {
                e.preventDefault();
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
                        window.location.href = 'index.php?page=hapusjurnal&id_jurnal=<?php echo $jurnal_data['id_jurnal']; ?>';
                    }
                });
            });
        });
    </script>
</body>

</html>