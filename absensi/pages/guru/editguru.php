<?php
include('koneksi.php');

// Validasi ID guru
if (!isset($_GET['id_guru'])) {
    header("Location: index.php?page=guru");
    exit();
}

$id_guru = $_GET['id_guru'];
$select = mysqli_query($coneksi, "SELECT guru.*, sekolah.nama_sekolah 
                                 FROM guru 
                                 JOIN sekolah ON guru.id_sekolah = sekolah.id_sekolah 
                                 WHERE guru.id_guru='$id_guru'")
    or die(mysqli_error($coneksi));

if (mysqli_num_rows($select) == 0) {
    echo '<div class="alert alert-warning">ID guru tidak ada dalam database.</div>';
    exit();
} else {
    $data = mysqli_fetch_assoc($select);
}

if (isset($_POST['update_user'])) {
    $id_guru = $_GET['id_guru'];
    $username = $_POST['username'];
    $password = $_POST['password'] ? $_POST['password'] : $data['password']; // kalau kosong pakai lama
    $profile  = $data['profile'];

    // Upload foto jika ada
    if ($_FILES['profile']['name']) {
        $target_dir = "../uploads/profiles/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);

        $imageFileType = strtolower(pathinfo($_FILES['profile']['name'], PATHINFO_EXTENSION));
        $new_filename  = "guru_" . $id_guru . "_" . time() . "." . $imageFileType;
        $target_file   = $target_dir . $new_filename;

        if (move_uploaded_file($_FILES['profile']['tmp_name'], $target_file)) {
            if ($data['profile'] && file_exists("../" . $data['profile'])) {
                unlink("../" . $data['profile']); // hapus lama
            }
            $profile = "uploads/profiles/" . $new_filename;
        }
    }

    $sql = mysqli_query($coneksi, "UPDATE guru SET 
        username='$username',
        password='$password',
        profile='$profile'
        WHERE id_guru='$id_guru'");
    if ($sql) {
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '<script>
            Swal.fire({
                icon: "success",
                title: "Sukses!",
                text: "User berhasil diupdate",
                position: "top",
                showConfirmButton: false,
                timer: 1500,
                toast: true
            }).then(() => {
                window.location.href = "index.php?page=editguru&id_guru=' . $id_guru . '";
            });
        </script>';
        exit();
    }
}

// Handle update DATA GURU
if (isset($_POST['update_guru'])) {
    $id_guru = $_GET['id_guru'];
    $nama_guru = $_POST['nama_guru'];
    $nip = $_POST['nip'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    $no_tlp = $_POST['no_tlp'];
    $id_sekolah = $_POST['id_sekolah'];
    $id_perusahaan = $_POST['id_perusahaan'];

    $sql = mysqli_query($coneksi, "UPDATE guru SET 
        nama_guru='$nama_guru',
        nip='$nip',
        jenis_kelamin='$jenis_kelamin',
        alamat='$alamat',
        no_tlp='$no_tlp',
        id_sekolah='$id_sekolah',
        id_perusahaan='$id_perusahaan'
        WHERE id_guru='$id_guru'");

    if (isset($_SESSION['pesan'])) {
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '<script>
            Swal.fire({
                icon:"success",
                title:"Sukses!",
                text:"' . $_SESSION['pesan'] . '",
                position:"top",
                showConfirmButton:false,
                timer:1500,
                toast:true
            });
        </script>';
        unset($_SESSION['pesan']); // supaya tidak muncul lagi saat reload
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0,user-scalable=no">
    <title>Profil Guru - <?php echo htmlspecialchars($data['nama_guru']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <style>
        body {
            padding-left: 270px;
            background-color: #f8f9fa;
        }

        .container-custom {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .profile-container {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 20px;
        }

        @media (max-width: 768px) {
            .profile-container {
                grid-template-columns: 1fr;
            }
        }

        .profile-card {
            background-color: white;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #3498db;
            margin: 0 auto 15px;
        }

        @media (max-width: 768px) {
            body {
                padding-left: 0;
            }
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: white;
            padding: 15px 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
        }

        .profile-container {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 20px;
        }

        .profile-card {
            background-color: white;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid var(--primary);
            margin: 0 auto 15px;
            display: block;
        }

        .profile-info {
            background-color: white;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }

        .form-col {
            flex: 0 0 50%;
            max-width: 50%;
            padding-right: 15px;
            padding-left: 15px;
            box-sizing: border-box;
        }

        .edit-mode {
            display: none;
        }

        .file-upload {
            display: inline-block;
            padding: 8px 15px;
            background-color: #3498db;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }

        .file-upload:hover {
            background-color: #2980b9;
        }

        #file-input {
            display: none;
        }

        select.form-control:disabled {
            color: #495057;
            height: calc(1.5em + .75rem + 2px);
            padding: .375rem .75rem;
        }

        .btn-left {
            margin-left: 600px;
        }
    </style>
</head>

<body>
    <div>
        <h3 class="text-primary text-center text-md-left">Profile Guru</h3>
        <form action="" method="post" enctype="multipart/form-data" id="profile-form">
            <input type="hidden" name="id_guru" value="<?php echo $id_guru; ?>">
            <div class="profile-container">
                <div class="profile-card">
                    <br>
                    <div class="profile-picture-container">
                        <img src="<?php echo $data['profile'] ? '../' . htmlspecialchars($data['profile']) : '../image/default.png'; ?>"
                            alt="Foto Profil"
                            id="profile-picture"
                            class="profile-picture">
                        <input type="file" id="file-input" name="profile" accept="image/*">
                        <br>
                        <label for="file-input" class="file-upload">
                            <i class="fas fa-camera"></i> Ganti Foto
                        </label>
                    </div>

                    <div id="view-mode">
                        <h4><?php echo htmlspecialchars($data['nama_guru']); ?></h4>
                        <p><?php echo htmlspecialchars($data['nip']); ?></p>
                        <p><?php echo htmlspecialchars($data['nama_sekolah']); ?></p>

                        <button type="button" class="btn btn-warning" onclick="enableEdit()">
                            <i class="fas fa-edit"></i> Edit User
                        </button>
                    </div>

                    <div id="edit-mode" class="edit-mode">

                        <div class="form-group text-left">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username"
                                value="<?php echo htmlspecialchars($data['username']); ?>" required>
                        </div>

                        <div class="form-group text-left">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($data['password']); ?>">
                        </div>
                        <button type="button" class="btn btn-danger" onclick="disableEdit()">Batal</button>
                        <button type="submit" name="update_user" class="btn btn-primary">Update</button>
                    </div>
                </div>

                <div class="profile-info">
                    <h3 class="text-center">Data Guru</h3>
                    <br>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="nama_guru">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama_guru" name="nama_guru"
                                    value="<?php echo htmlspecialchars($data['nama_guru']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label>No. Telepon</label>
                                <input type="text" name="no_tlp" class="form-control"
                                    value="<?php echo htmlspecialchars($data['no_tlp']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-control" required>
                                    <option value="Laki-laki" <?php if ($data['jenis_kelamin'] == 'Laki-laki') echo 'selected'; ?>>Laki-laki</option>
                                    <option value="Perempuan" <?php if ($data['jenis_kelamin'] == 'Perempuan') echo 'selected'; ?>>Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="form-col">

                            <div class="form-group">
                                <label>NIP</label>
                                <input type="text" name="nip" class="form-control"
                                    value="<?php echo htmlspecialchars($data['nip']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Alamat</label>
                                <input type="text" name="alamat" class="form-control"
                                    value="<?php echo htmlspecialchars($data['alamat']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="">Sekolah</label>
                                <select class="form-control" disabled>
                                    <?php
                                    $sekolah_query = mysqli_query($coneksi, "SELECT * FROM sekolah");
                                    while ($sekolah = mysqli_fetch_assoc($sekolah_query)) {
                                        $selected = ($sekolah['id_sekolah'] == $data['id_sekolah']) ? 'selected' : '';
                                        echo '<option value="' . $sekolah['id_sekolah'] . '" ' . $selected . '>' . htmlspecialchars($sekolah['nama_sekolah']) . '</option>';
                                    }
                                    ?>
                                </select>

                                <!-- Hidden input agar tetap dikirim -->
                                <input type="hidden" name="id_sekolah" value="<?= $data['id_sekolah'] ?>">
                            </div>

                            <div class="form-group">
                                <label for="">Perusahaan</label>
                                <select class="form-control" disabled>
                                    <?php
                                    $perusahaan_query = mysqli_query($coneksi, "SELECT * FROM perusahaan");
                                    while ($perusahaan = mysqli_fetch_assoc($perusahaan_query)) {
                                        $selected = ($perusahaan['id_perusahaan'] == $data['id_perusahaan']) ? 'selected' : '';
                                        echo '<option value="' . $perusahaan['id_perusahaan'] . '" ' . $selected . '>' . htmlspecialchars($perusahaan['nama_perusahaan']) . '</option>';
                                    }
                                    ?>
                                </select>

                                <!-- Hidden input agar tetap dikirim -->
                                <input type="hidden" name="id_perusahaan" value="<?= $data['id_perusahaan'] ?>">
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="update_guru" class="btn btn-primary btn-left">Update</button>
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Fungsi untuk preview gambar sebelum upload
        document.getElementById('file-input').addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    document.getElementById('profile-picture').src = e.target.result;

                    // Tampilkan notifikasi
                    Swal.fire({
                        title: 'Foto Profil Diubah',
                        text: 'Foto akan disimpan saat Anda klik Simpan',
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2000,
                        toast: true
                    });
                }

                reader.readAsDataURL(this.files[0]);
            }
        });

        // Fungsi untuk mengaktifkan mode edit
        function enableEdit() {
            document.getElementById('view-mode').style.display = 'none';
            document.getElementById('edit-mode').style.display = 'block';
        }

        // Fungsi untuk menonaktifkan mode edit
        function disableEdit() {
            document.getElementById('view-mode').style.display = 'block';
            document.getElementById('edit-mode').style.display = 'none';
            document.getElementById('profile-form').reset();

            // Reset gambar ke yang sebelumnya
            document.getElementById('profile-picture').src = '<?php echo $data['profile'] ? "../" . $data['profile'] : "../image/default.png"; ?>';
        }

        // Auto-hide alert setelah 5 detik
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    </script>
</body>

</html>