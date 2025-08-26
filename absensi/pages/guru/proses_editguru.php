<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_guru = $_POST['id_guru'];
    $nama_guru = $_POST['nama_guru'];
    $nip = $_POST['nip'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    $no_tlp = $_POST['no_tlp'];
    $id_sekolah = $_POST['id_sekolah'];
    $id_perusahaan = $_POST['id_perusahaan'];

    // Update ke database TANPA menyentuh kolom profile
    // PERBAIKAN: Hapus koma (,) sebelum WHERE
    $sql = mysqli_query($coneksi, "UPDATE guru SET 
        nama_guru='$nama_guru', 
        nip='$nip', 
        jenis_kelamin='$jenis_kelamin', 
        alamat='$alamat', 
        no_tlp='$no_tlp', 
        id_sekolah='$id_sekolah' 
        WHERE id_guru='$id_guru'");

    if ($sql) {
        $_SESSION['flash_edit'] = 'sukses';
    } else {
        $_SESSION['flash_edit'] = 'gagal';
        // Tambahkan informasi error untuk debugging
        $_SESSION['error_message'] = mysqli_error($coneksi);
    }

    header("Location: ../../index.php?page=guru");
    exit();
} else {
    header("Location: ../../index.php?page=guru");
    exit();
}
?>