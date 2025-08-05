    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    include('koneksi.php');
    $data = null;
    if (isset($_GET['id_guru'])) {
        $id_guru = $_GET['id_guru'];
        $select = mysqli_query($coneksi, "SELECT * FROM guru WHERE id_guru='$id_guru'");
        if (mysqli_num_rows($select) == 0) {
            // Jika data tidak ditemukan, redirect ke guru.php
            echo '<script>window.location.replace("index.php?page=guru");</script>';
            exit();
        } else {
            $data = mysqli_fetch_assoc($select);
        }
    }
    ?>

    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Guru</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

            h2 {
                margin-bottom: 20px;
                color: #007bff;
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
            }

            .btn-warning {
                background-color: #ffc107;
                border: none;
            }

            .btn-warning:hover {
                background-color: #e0a800;
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
            <h2 class="text-center">Data Guru</h2>
            <hr>


            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <?php if (isset($_GET['pesan'])): ?>
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        <?php if ($_GET['pesan'] == 'sukses'): ?>
                            Swal.fire({
                                icon: 'success',
                                title: 'Sukses!',
                                text: 'Data guru berhasil diupdate',
                                position: 'top',
                                showConfirmButton: false,
                                timer: 2000,
                                toast: true
                            });
                        <?php elseif ($_GET['pesan'] == 'gagal'): ?>
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: '<?php echo isset($_GET['error']) ? htmlspecialchars(urldecode($_GET['error']), ENT_QUOTES) : 'Terjadi kesalahan'; ?>',
                                position: 'top',
                                showConfirmButton: false,
                                timer: 3000,
                                toast: true
                            });
                        <?php elseif ($_GET['pesan'] == 'duplikat'): ?>
                            Swal.fire({
                                icon: 'warning',
                                title: 'Peringatan!',
                                text: 'Username sudah terdaftar',
                                position: 'top',
                                showConfirmButton: false,
                                timer: 3000,
                                toast: true
                            });
                        <?php endif; ?>
                    });
                </script>
            <?php endif; ?>

            <?php if ($data): ?>
                <form action="pages/guru/proses_editguru.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id_guru" value="<?php echo $id_guru; ?>">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Nama Guru</label>
                            <input type="text" name="nama_guru" class="form-control" value="<?php echo htmlspecialchars($data['nama_guru']); ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Nip</label>
                            <input type="text" name="nip" class="form-control" value="<?php echo htmlspecialchars($data['nip']); ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-control">
                                <option value="Laki-laki" <?php if (($data['jenis_kelamin'] ?? '') == 'Laki-laki') echo 'selected'; ?>>Laki-laki</option>
                                <option value="Perempuan" <?php if (($data['jenis_kelamin'] ?? '') == 'Perempuan') echo 'selected'; ?>>Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Alamat</label>
                            <input type="text" name="alamat" class="form-control" value="<?php echo htmlspecialchars($data['alamat']); ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>No. Telepon / HP</label>
                            <input type="text" name="no_tlp" class="form-control" value="<?php echo htmlspecialchars($data['no_tlp'] ?? ''); ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Nama Sekolah</label>
                            <select name="id_sekolah" class="form-control" required>
                                <?php
                                $querySekolah = mysqli_query($coneksi, "SELECT * FROM sekolah");
                                while ($sekolah = mysqli_fetch_assoc($querySekolah)) {
                                    $selected = ($data['id_sekolah'] == $sekolah['id_sekolah']) ? 'selected' : '';
                                    echo "<option value='{$sekolah['id_sekolah']}' $selected>{$sekolah['nama_sekolah']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($data['username']); ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" value="<?php echo htmlspecialchars($data['password']); ?>" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col text-left">
                            <button type="button" class="btn btn-danger" id="btnHapus"
                                data-id="<?php echo $data['id_guru']; ?>">Hapus</button>
                        </div>
                        <div class="col text-right">
                            <a href="index.php?page=guru" class="btn btn-warning">KEMBALI</a>
                            <input type="submit" name="submit" class="btn btn-primary" value="SIMPAN">
                        </div>
                </form>
            <?php else: ?>
                <div class="alert alert-warning">Data guru tidak ditemukan.</div>
            <?php endif; ?>
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
                                    window.location.href = `index.php?page=hapusguru&id_guru=${id}`;
                                }
                            });
                        });
                    }
                });
            </script>
        </div>

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    </body>

    </html>