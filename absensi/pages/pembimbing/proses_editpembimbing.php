<?php 
include('../../koneksi.php');

if (isset($_POST['submit'])) {
    $id_pembimbing    = $_POST['id_pembimbing'];
    $id_perusahaan    = $_POST['id_perusahaan'];
    $nama_pembimbing  = $_POST['nama_pembimbing'];
    $no_tlp           = $_POST['no_tlp'];
    $alamat           = $_POST['alamat'];
    $jenis_kelamin    = $_POST['jenis_kelamin'];
   
    $sql = mysqli_query($coneksi, "UPDATE pembimbing SET 
        id_perusahaan = '$id_perusahaan',
        nama_pembimbing = '$nama_pembimbing',
        no_tlp = '$no_tlp',
        alamat = '$alamat',
        jenis_kelamin = '$jenis_kelamin'
        WHERE id_pembimbing = '$id_pembimbing'");

    if ($sql) {
        $_SESSION['flash_edit'] = 'sukses';
    } else {
        $_SESSION['flash_error'] = mysqli_error($coneksi);
    }

    header("Location: ../../index.php?page=pembimbing");
    exit();
} else {
    header("Location: ../../index.php?page=pembimbing");
    exit();
}
?>