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

// Handle form submission
if (isset($_POST['submit'])) {
    $id_guru = $_GET['id_guru'];
    $nama_guru = $_POST['nama_guru'];
    $nip = $_POST['nip'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    $no_tlp = $_POST['no_tlp'];
    $id_sekolah = $_POST['id_sekolah'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Handle file upload
    $profile = $data['profile']; // Default to existing profile picture

    if ($_FILES['profile']['name']) {
        $target_dir = "../uploads/profiles/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name = basename($_FILES['profile']['name']);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image
        $check = getimagesize($_FILES['profile']['tmp_name']);
        if ($check === false) {
            echo '<script>alert("File yang diupload bukan gambar.");</script>';
        } else {
            // Generate unique filename
            $new_filename = "guru_" . $id_guru . "_" . time() . "." . $imageFileType;
            $target_file = $target_dir . $new_filename;

            // Check file size (max 2MB)
            if ($_FILES['profile']['size'] > 9000000) {
                echo '<script>alert("Ukuran file terlalu besar. Maksimal 2MB.");</script>';
            } else {
                // Allow certain file formats
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    echo '<script>alert("Hanya file JPG, JPEG, PNG & GIF yang diizinkan.");</script>';
                } else {
                    // Delete old file if exists
                    if ($data['profile'] && file_exists("../" . $data['profile'])) {
                        unlink("../" . $data['profile']);
                    }

                    // Try to upload file
                    if (move_uploaded_file($_FILES['profile']['tmp_name'], $target_file)) {
                        $profile = "uploads/profiles/" . $new_filename;
                    } else {
                        echo '<script>alert("Terjadi kesalahan saat mengupload file.");</script>';
                    }
                }
            }
        }
    }

    $sql = mysqli_query($coneksi, "UPDATE guru SET 
        nama_guru='$nama_guru', 
        nip='$nip', 
        jenis_kelamin='$jenis_kelamin', 
        alamat='$alamat', 
        no_tlp='$no_tlp', 
        id_sekolah='$id_sekolah', 
        username='$username', 
        password='$password',
        profile='$profile'
        WHERE id_guru='$id_guru'")
        or die(mysqli_error($coneksi));

    if ($sql) {
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '<script>Swal.fire({icon:"success",title:"Sukses!",text:"Data guru berhasil diupdate",position:"top",showConfirmButton:false,timer:1200,toast:true}); setTimeout(function(){window.location.href="index.php?page=editguru&id_guru=' . $id_guru . '&pesan=sukses";},1200);</script>';
        exit();
    } else {
        $err = htmlspecialchars(mysqli_error($coneksi), ENT_QUOTES);
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '<script>Swal.fire({icon:"error",title:"Gagal!",text:"' . $err . '",position:"top",showConfirmButton:false,timer:3000,toast:true});</script>';
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
                            <i class="fas fa-edit"></i> Edit data
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
                            <input type="password" class="form-control" id="password" name="password"
                                value="<?php echo htmlspecialchars($data['password']); ?>" required>
                        </div>
                        <button type="button" class="btn btn-danger" onclick="disableEdit()">Batal</button>
                        <button type="submit" name="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>

                <div class="profile-info">
                    <h3>Data Guru</h3>
                    <div class="form-row">
                        <!-- Left Column -->
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

                        </div>
                    </div>
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