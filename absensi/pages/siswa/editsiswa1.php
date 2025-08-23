<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('koneksi.php');

// Mengambil data dari session jika ada (setelah redirect dari proses)
$error_nis = $_SESSION['error_nis'] ?? '';
$error_nisn = $_SESSION['error_nisn'] ?? '';
$success = $_SESSION['success'] ?? '';
$form_data = $_SESSION['form_data'] ?? array();

// Hapus data session setelah digunakan
unset($_SESSION['error_nis']);
unset($_SESSION['error_nisn']);
unset($_SESSION['success']);
unset($_SESSION['form_data']);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Siswa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        /* Style asli */
        .container-custom {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 20px;
            color: #007bff;
        }

        .table-responsive {
            margin-top: 20px;
        }

        .form-control {
            border: none;
            border-bottom: 2px solid #007bff;
            border-radius: 0;
            box-shadow: none;
        }

        .form-control:focus {
            border-color: #0056b3;
            box-shadow: none;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            transform: translateY(-1px);
            /* Sedikit efek angkat */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
            /* Shadow lebih besar saat hover */
        }

        .hapusSiswa {
            color: white;
            /* Text putih */
            background-color: #344767;
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

        .hapusSiswa:hover {
            background-color: #5a6268;
            /* Warna lebih gelap saat hover */
            color: white;
            /* Tetap putih saat hover */
            transform: translateY(-1px);
            /* Sedikit efek angkat */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
            /* Shadow lebih besar saat hover */
        }

        .form-row {
            margin-bottom: 15px;
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
    <h2 class="text-left">Data Siswa</h2>
    <div class="main-container container-custom">
        <?php
        if (isset($_GET['id_siswa'])) {
            $id_siswa = $_GET['id_siswa'];
            $select = mysqli_query($coneksi, "SELECT * FROM siswa WHERE id_siswa='$id_siswa'");
            if (mysqli_num_rows($select) == 0) {
                // Jika user akses langsung atau klik back ke halaman ini setelah hapus, redirect ke siswa.php
                echo '<script>window.location.replace("index.php?page=siswa");</script>';
                exit();
            } else {
                $data = mysqli_fetch_assoc($select);
            }
        }
        // Notifikasi update dari proses_editsiswa.php
        if (isset($_GET['pesan'])) {
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            echo '<script>document.addEventListener("DOMContentLoaded",function(){';
            if ($_GET['pesan'] == 'sukses') {
                echo 'Swal.fire({icon:"success",title:"Sukses!",text:"Data siswa berhasil diupdate",position:"top",showConfirmButton:false,timer:2000,toast:true});';
            } elseif ($_GET['pesan'] == 'gagal') {
                $err = isset($_GET['error']) ? htmlspecialchars(urldecode($_GET['error']), ENT_QUOTES) : 'Terjadi kesalahan';
                echo 'Swal.fire({icon:"error",title:"Gagal!",text:"' . $err . '",position:"top",showConfirmButton:false,timer:3000,toast:true});';
            } elseif ($_GET['pesan'] == 'duplikat') {
                echo 'Swal.fire({icon:"warning",title:"Peringatan!",text:"ID siswa atau Username sudah terdaftar",position:"top",showConfirmButton:false,timer:3000,toast:true});';
            }
            echo '});</script>';
        }
        ?>

        <form action="pages/siswa/proses_editsiswa.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id_siswa" value="<?php echo $id_siswa; ?>">

            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>NIS</label>
                    <input type="text" name="nis" class="form-control"
                        value="<?php echo htmlspecialchars($data['nis']); ?>" required>
                    <div id="nisError" class="error-message"><?php echo $error_nis; ?>
                    </div>
                </div>
                <div class="form-group col-md-3">
                    <label>NISN</label>
                    <input type="text" name="nisn" class="form-control"
                        value="<?php echo htmlspecialchars($data['nisn']); ?>" required>
                    <div id="nisError" class="error-message"><?php echo $error_nis; ?>
                </div>
                </div>
                <div class="form-group col-md-3">
                    <label>Nama Siswa</label>
                    <input type="text" name="nama_siswa" class="form-control"
                        value="<?php echo htmlspecialchars($data['nama_siswa']); ?>" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Program Keahlian</label>
                    <select name="pro_keahlian" class="form-control" required>
                        <option value="<?php echo htmlspecialchars($data['pro_keahlian']); ?>">
                            <?php echo htmlspecialchars($data['pro_keahlian']); ?></option>
                        <option value="Multimedia">Multimedia"</option>
                        <option value="Rekayasa Perangkat Lunak">Rekayasa Perangkat Lunak</option>
                        <option value="Perkantoran">Perkantoran</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Tempat Lahir</label>
                    <input type="text" name="TL" class="form-control" value="<?php echo htmlspecialchars($data['TL']); ?>" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="TTGL" class="form-control" value="<?php echo htmlspecialchars($data['TTGL']); ?>" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Sekolah</label>
                    <select name="id_sekolah" class="form-control" required>
                        <?php
                        $data_sekolah = mysqli_query($coneksi, "SELECT * FROM sekolah");
                        while ($row = mysqli_fetch_array($data_sekolah)) {
                        ?>
                            <option value="<?php echo htmlspecialchars($row['id_sekolah']); ?>" <?php echo ($row['id_sekolah'] == $data['id_sekolah']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($row['nama_sekolah']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Perusahaan</label>
                    <select name="id_perusahaan" class="form-control" required>
                        <?php
                        $data_perusahaan = mysqli_query($coneksi, "SELECT * FROM perusahaan");
                        while ($row = mysqli_fetch_array($data_perusahaan)) {
                        ?>
                            <option value="<?php echo htmlspecialchars($row['id_perusahaan']); ?>" <?php echo ($row['id_perusahaan'] == $data['id_perusahaan']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($row['nama_perusahaan']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control"
                        value="<?php echo htmlspecialchars($data['tanggal_mulai']); ?>" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control"
                        value="<?php echo htmlspecialchars($data['tanggal_selesai']); ?>" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Pembimbing</label>
                    <select name="id_pembimbing" class="form-control" required>
                        <?php
                        $data_pembimbing = mysqli_query($coneksi, "SELECT * FROM pembimbing");
                        while ($row = mysqli_fetch_array($data_pembimbing)) {
                        ?>
                            <option value="<?php echo htmlspecialchars($row['id_pembimbing']); ?>" <?php echo ($row['id_pembimbing'] == $data['id_pembimbing']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($row['nama_pembimbing']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Guru</label>
                    <select name="id_guru" class="form-control" required>
                        <?php
                        $data_guru = mysqli_query($coneksi, "SELECT * FROM guru");
                        while ($row = mysqli_fetch_array($data_guru)) {
                        ?>
                            <option value="<?php echo htmlspecialchars($row['id_guru']); ?>" <?php echo ($row['id_guru'] == $data['id_guru']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($row['nama_guru']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control"
                        value="<?php echo htmlspecialchars($data['username']); ?>" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control"
                        value="<?php echo htmlspecialchars($data['password']); ?>" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Nomor WhatsApp:</label>
                    <input type="text" name="no_wa" class="form-control" value="<?php echo htmlspecialchars($data['no_wa']); ?>" required>
                </div>
            </div>

            <div class="form-group">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <!-- Tombol Hapus di kiri -->
                    <button type="button" class="btn btn-danger hapusSiswa"
                        id="btnHapus" data-id="<?php echo $data['id_siswa']; ?>">
                        HAPUS
                    </button>

                    <!-- Tombol Kembali dan Simpan di kanan (tapi berdampingan) -->
                    <div class="d-flex flex-wrap justify-content-end gap-2">
                        <a href="index.php?page=siswa" class="btn btn-warning mr-2">KEMBALI</a>
                        <input type="submit" name="submit" class="btn btn-primary" value="Update">
                    </div>
                </div>
            </div>
        </form>
    </div>

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
                        cancelButtonColor: "#3085d6",
                        confirmButtonColor: "#344767",
                        cancelButtonText: "Batal",
                        confirmButtonText: "Ya, hapus!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = `index.php?page=hapussiswa&id_siswa=${id}`;
                        }
                    });
                });
            }
        });
    </script>
    </div>

    <!-- Script lainnya tetap sama -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script>
        function validateNIS() {
            const nisInput = document.getElementById('nis');
            const nisError = document.getElementById('nisError');
            const nisValue = nisInput.value.trim();

            if (nisValue.length < 8 || nisValue.length > 12) {
                nisError.textContent = 'NIS harus terdiri dari 8-12 karakter';
                nisInput.classList.add('is-invalid');
                return false;
            } else if (!/^\d+$/.test(nisValue)) {
                nisError.textContent = 'NIS harus berupa angka';
                nisInput.classList.add('is-invalid');
                return false;
            } else {
                nisError.textContent = '';
                nisInput.classList.remove('is-invalid');
                return true;
            }
        }

        function validateNISN() {
            const nisnInput = document.getElementById('nisn');
            const nisnError = document.getElementById('nisnError');
            const nisnValue = nisnInput.value.trim();

            if (nisnValue.length !== 10) {
                nisnError.textContent = 'NISN harus terdiri dari 10 karakter';
                nisnInput.classList.add('is-invalid');
                return false;
            } else if (!/^\d+$/.test(nisnValue)) {
                nisnError.textContent = 'NISN harus berupa angka';
                nisnInput.classList.add('is-invalid');
                return false;
            } else {
                nisnError.textContent = '';
                nisnInput.classList.remove('is-invalid');
                return true;
            }
        }

        function validateForm() {
            const isNISValid = validateNIS();
            const isNISNValid = validateNISN();

            if (!isNISValid || !isNISNValid) {
                if (!isNISValid) {
                    document.getElementById('nis').focus();
                } else {
                    document.getElementById('nisn').focus();
                }
                return false;
            }
            return true;
        }

        // Validasi real-time saat pengguna mengetik
        document.getElementById('nis').addEventListener('input', validateNIS);
        document.getElementById('nisn').addEventListener('input', validateNISN);
    </script>
</body>

</html>