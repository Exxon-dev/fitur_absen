<?php include('koneksi.php'); ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Siswa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 20px;
            padding: 20px;
            border-radius: 8px;
            background-color: white;
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

        .error-message {
            color: red;
            font-size: 0.8em;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Tambah Siswa</h2>
        <hr>
        <form action="pages/siswa/proses_tambahsiswa.php" method="POST" onsubmit="return validateForm()">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>NIS</label>
<<<<<<< HEAD
                    <input type="text" name="nis" id="nis" class="form-control" required maxlength="8" minlength="8" oninput="validateNIS()">
=======
                    <input type="text" name="nis" id="nis" class="form-control" required minlength="8" maxlength="12" oninput="validateNIS()">
>>>>>>> 347503aa207cbac013f6347c147123e78b578449
                    <div id="nisError" class="error-message"></div>
                </div>
                <div class="form-group col-md-3">
                    <label>NISN</label>
                    <input type="text" name="nisn" id="nisn" class="form-control" required maxlength="10" minlength="10" oninput="validateNISN()">
                    <div id="nisnError" class="error-message"></div>
                </div>
                <div class="form-group col-md-3">
                    <label>Nama Siswa</label>
                    <input type="text" name="nama_siswa" class="form-control" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Kelas</label>
                    <select name="kelas" class="form-control" required>
                        <option value="">Pilih Kelas</option>
                        <option value="12 RPL A">12 RPL A</option>
                        <option value="12 RPL B">12 RPL B</option>
                        <option value="12 RPL C">12 RPL C</option>
                        <option value="12 ELIND A">12 ELIND A</option>
                        <option value="12 ELIND B">12 ELIND B</option>
                        <option value="12 ELIND C">12 ELIND C</option>
                        <option value="12 MEKA A">12 MEKA A</option>
                        <option value="12 MEKA B">12 MEKA B</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Program Keahlian</label>
                    <select name="pro_keahlian" class="form-control" required>
                        <option value="">Pilih Program Keahlian</option>
                        <option value="Elektronika">Elektronika</option>
                        <option value="Perangkat Lunak">Perangkat Lunak</option>
                        <option value="Mekatronika">Mekatronika</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Tempat Tanggal Lahir</label>
                    <input type="text" name="TTL" class="form-control" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Sekolah</label>
                    <select name="id_sekolah" class="form-control" required>
                        <option value="">Pilih Sekolah</option>
                        <?php
                        $data_sekolah = mysqli_query($coneksi, "SELECT * FROM sekolah");
                        while ($row = mysqli_fetch_array($data_sekolah)) {
                        ?>
                            <option value="<?php echo htmlspecialchars($row['id_sekolah']); ?>"><?php echo htmlspecialchars($row['nama_sekolah']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Perusahaan</label>
                    <select name="id_perusahaan" class="form-control" required>
                        <option value="">Pilih Perusahaan</option>
                        <?php
                        $data_perusahaan = mysqli_query($coneksi, "SELECT * FROM perusahaan");
                        while ($row = mysqli_fetch_array($data_perusahaan)) {
                        ?>
                            <option value="<?php echo htmlspecialchars($row['id_perusahaan']); ?>"><?php echo htmlspecialchars($row['nama_perusahaan']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Pembimbing</label>
                    <select name="id_pembimbing" class="form-control" required>
                        <option value="">Pilih Pembimbing</option>
                        <?php
                        $data_pembimbing = mysqli_query($coneksi, "SELECT * FROM pembimbing");
                        while ($row = mysqli_fetch_array($data_pembimbing)) {
                        ?>
                            <option value="<?php echo htmlspecialchars($row['id_pembimbing']); ?>"><?php echo htmlspecialchars($row['nama_pembimbing']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Guru</label>
                    <select name="id_guru" class="form-control" required>
                        <option value="">Pilih Guru</option>
                        <?php
                        $data_guru = mysqli_query($coneksi, "SELECT * FROM guru");
                        while ($row = mysqli_fetch_array($data_guru)) {
                        ?>
                            <option value="<?php echo htmlspecialchars($row['id_guru']); ?>"><?php echo htmlspecialchars($row['nama_guru']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Nomor WhatsApp:</label>
                    <input type="text" name="no_wa" class="form-control" placeholder="628xxx" required>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-3">
                    <input type="submit" name="submit" class="btn btn-primary" value="SIMPAN">
                </div>
                <div class="col-md-3 offset-md-6 text-right">
                    <a href="index.php?page=siswa" class="btn btn-warning">KEMBALI</a>
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

    <script>
        function validateNIS() {
            const nisInput = document.getElementById('nis');
            const nisError = document.getElementById('nisError');
            const nisValue = nisInput.value.trim();
            
<<<<<<< HEAD
            if (nisValue.length !== 8) {
                nisError.textContent = 'NIS harus terdiri dari 8-8 karakter';
=======
            if (nisValue.length < 8 || nisValue.length > 12) {
                nisError.textContent = 'NIS harus terdiri dari 8-12 karakter';
>>>>>>> 347503aa207cbac013f6347c147123e78b578449
                return false;
            } else {
                nisError.textContent = '';
                return true;
            }
        }

        function validateNISN() {
            const nisnInput = document.getElementById('nisn');
            const nisnError = document.getElementById('nisnError');
            const nisnValue = nisnInput.value.trim();
            
            if (nisnValue.length !== 10) {
                nisnError.textContent = 'NISN harus terdiri dari 10 karakter';
                return false;
            } else {
                nisnError.textContent = '';
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
    </script>
</body>
</html>