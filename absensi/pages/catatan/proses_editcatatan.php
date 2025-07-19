<?php 
session_start();
include('../../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_catatan = mysqli_real_escape_string($coneksi, $_POST['id_catatan']);
    $id_pembimbing = mysqli_real_escape_string($coneksi, $_POST['id_pembimbing']);
    $catatan = mysqli_real_escape_string($coneksi, $_POST['catatan']);
    $id_jurnal = mysqli_real_escape_string($coneksi, $_POST['id_jurnal']);

    // Validasi data
    if (empty($id_catatan) || empty($id_pembimbing) || empty($catatan) || empty($id_jurnal)) {
        $_SESSION['flash_error'] = "Semua field harus diisi";
        header("Location: ../../index.php?page=catatan");
        exit();
    }

    // Cek apakah data benar-benar berubah
    $check_query = "SELECT * FROM catatan WHERE id_catatan='$id_catatan'";
    $result = mysqli_query($coneksi, $check_query);
    $old_data = mysqli_fetch_assoc($result);

    if ($old_data['id_pembimbing'] == $id_pembimbing && 
        $old_data['catatan'] == $catatan && 
        $old_data['id_jurnal'] == $id_jurnal) {
        $_SESSION['flash_error'] = "Tidak ada perubahan data";
        header("Location: ../../index.php?page=catatan");
        exit();
    }

    // Lakukan update
    $sql = "UPDATE catatan SET 
            id_pembimbing='$id_pembimbing',
            catatan='$catatan', 
            id_jurnal='$id_jurnal' 
            WHERE id_catatan='$id_catatan'";

    if (mysqli_query($coneksi, $sql)) {
        $_SESSION['flash_edit'] = 'sukses';
    } else {
        $_SESSION['flash_error'] = "Gagal update: " . mysqli_error($coneksi);
    }

    header("Location: ../../index.php?page=catatan");
    exit();
} else {
    $_SESSION['flash_duplikat'] = "duplikat";
    header("Location: ../../index.php?page=catatan");
    exit();
}
?>