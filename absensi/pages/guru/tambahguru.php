<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Guru dan Siswa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="assets/css/custom.css">

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

        select.form-control {
            border-radius: 2px;
            padding: 6px 12px;
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
    <h2 class="text-left">Tambah Guru</h2>
    <div class="main-container container-custom">
        <form action="pages/guru/proses_tambahguru.php" method="post">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Nama Guru</label>
                    <input type="text" name="nama_guru" class="form-control" required>
                </div>
                <div class="form-group col-md-4">
                    <label>Nip</label>
                    <input type="text" name="nip" class="form-control" required>
                </div>
                <div class="form-group col-md-4">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control">
                        <option value="">Jenis Kelamin</option>
                        <option value="Laki-laki" <?php if (($data['jenis_kelamin'] ?? '') == 'Laki-laki') echo 'selected'; ?>>Laki-laki</option>
                        <option value="Perempuan" <?php if (($data['jenis_kelamin'] ?? '') == 'Perempuan') echo 'selected'; ?>>Perempuan</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label>Alamat</label>
                    <input type="text" name="alamat" class="form-control" value="<?php echo htmlspecialchars($data['alamat'] ?? ''); ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>No. Telepon / HP</label>
                    <input type="text" name="no_tlp" class="form-control" value="<?php echo htmlspecialchars($data['no_tlp'] ?? ''); ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>Sekolah</label>
                    <select name="id_sekolah" class="form-control" required>
                        <option value="">Pilih Sekolah</option>
                        <?php
                        $querySekolah = mysqli_query($coneksi, "SELECT * FROM sekolah");
                        while ($sekolah = mysqli_fetch_assoc($querySekolah)) {
                            $selected = ($data['id_sekolah'] == $sekolah['id_sekolah']) ? 'selected' : '';
                            echo "<option value='{$sekolah['id_sekolah']}' $selected>{$sekolah['nama_sekolah']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label>Perusahaan</label>
                    <select name="id_perusahaan" class="form-control" required>
                        <option value="">Pilih Perusahaan</option>
                        <?php
                        $queryperusahaan = mysqli_query($coneksi, "SELECT * FROM perusahaan");
                        while ($perusahaan = mysqli_fetch_assoc($queryperusahaan)) {
                            $selected = ($data['id_perusahaan'] == $perusahaan['id_perusahaan']) ? 'selected' : '';
                            echo "<option value='{$perusahaan['id_perusahaan']}' $selected>{$perusahaan['nama_perusahaan']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="form-group col-md-4">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
            </div>
            <div class="form-row">
                <div class="col text-right">
                    <a href="index.php?page=guru" class="btn btn-warning">KEMBALI</a>
                    <input type="submit" name="submit" class="btn btn-primary" value="SIMPAN">

                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>