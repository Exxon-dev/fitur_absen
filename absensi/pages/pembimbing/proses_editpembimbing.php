<?php 
include('../../koneksi.php');

if (isset($_POST['submit'])) {
    $id_pembimbing    = $_POST['id_pembimbing'];
    $id_perusahaan    = $_POST['id_perusahaan'];
    $nama_pembimbing  = $_POST['nama_pembimbing'];
    $no_tlp           = $_POST['no_tlp'];
    $alamat           = $_POST['alamat'];
    $jenis_kelamin    = $_POST['jenis_kelamin'];
    $username         = $_POST['username'];
    $password         = $_POST['password'];
    $foto_lama        = $_POST['foto_lama'] ?? 'default.jpg';

    $profile = $foto_lama;

    // Jika ada upload foto baru
    if (!empty($_FILES['foto']['name'])) {
        $fotoName   = $_FILES['foto']['name'];
        $fotoTmp    = $_FILES['foto']['tmp_name'];
        $fotoExt    = pathinfo($fotoName, PATHINFO_EXTENSION);
        $fotoBaru   = uniqid('guru_') . '.' . $fotoExt;
        $uploadPath = __DIR__ . "/../image/" . $fotoBaru;

        if (move_uploaded_file($fotoTmp, $uploadPath)) {
            // Hapus foto lama jika bukan default
            $oldProfilePath = __DIR__ . "/../image/" . $foto_lama;
            if (!empty($foto_lama) && file_exists($oldProfilePath) && $foto_lama !== 'default.jpg') {
                unlink($oldProfilePath);
            }
            $profile = $fotoBaru;
        }
    }

    $sql = mysqli_query($coneksi, "UPDATE pembimbing SET 
        id_perusahaan = '$id_perusahaan',
        nama_pembimbing = '$nama_pembimbing',
        no_tlp          = '$no_tlp',
        alamat          = '$alamat',
        jenis_kelamin   = '$jenis_kelamin',
        username        = '$username', 
        profile         = '$profile', 
        password        = '$password'
        WHERE id_pembimbing = '$id_pembimbing'");

    if ($sql) {
        $_SESSION['flash_edit'] = 'sukses';
    }

    header("Location: ../../index.php?page=pembimbing");
    exit();
}
else {
    header("Location: ../../index.php?page=pembimbing");
    exit();
}
?>