<?php
include('../../koneksi.php');
if (isset($_GET['id_guru'])) {
    $id_guru = $_GET['id_guru'];
    $select = mysqli_query($coneksi, "
        SELECT guru.*, sekolah.nama_sekolah 
        FROM guru 
        INNER JOIN sekolah ON guru.id_sekolah = sekolah.id_sekolah 
        WHERE guru.id_guru = '$id_guru'
    ") or die(mysqli_error($coneksi));

    if (mysqli_num_rows($select) == 0) {
        echo '<div class="alert alert-warning">id_guru tidak ada dalam database.</div>';
        exit();
    } else {
        $data = mysqli_fetch_assoc($select);
    }
}

if (isset($_POST['submit'])) {
    $id_guru = $_GET['id_guru'];

    // Ambil data guru lama dari database
    $getGuru = mysqli_query($coneksi, "
        SELECT guru.*, sekolah.nama_sekolah 
        FROM guru 
        INNER JOIN sekolah ON guru.id_sekolah = sekolah.id_sekolah 
        WHERE guru.id_guru = '$id_guru'
    ");
    $data = mysqli_fetch_assoc($getGuru);


    $nama_guru = $_POST['nama_guru'];
    $nip = $_POST['nip'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    $no_tlp = $_POST['no_tlp'];
    $id_sekolah = $_POST['id_sekolah'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Default profile lama
    $profile = $data['profile'] ?? 'default.jpg';

    // Jika ada upload foto baru
    if (!empty($_FILES['foto']['name'])) {
        $fotoName = $_FILES['foto']['name'];
        $fotoTmp = $_FILES['foto']['tmp_name'];
        $fotoExt = pathinfo($fotoName, PATHINFO_EXTENSION);
        $fotoBaru = uniqid('guru_') . '.' . $fotoExt;
        $uploadPath = __DIR__ . "/../image/" . $fotoBaru;

        if (move_uploaded_file($fotoTmp, $uploadPath)) {
            // Hapus foto lama jika bukan default
            $oldProfilePath = __DIR__ . "/../image/" . $data['profile'];
            if (!empty($data['profile']) && file_exists($oldProfilePath) && $data['profile'] !== 'default.jpg') {
                unlink($oldProfilePath);
            }
            $profile = $fotoBaru;
        }
    }

    // Update ke database
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
        WHERE id_guru='$id_guru'");

    if ($sql) {
        $_SESSION['flash_edit'] = 'sukses';
    }

    header("Location: ../../index.php?page=guru");
    exit();
} else {
    header("Location: ../../index.php?page=guru");
    exit();
}
