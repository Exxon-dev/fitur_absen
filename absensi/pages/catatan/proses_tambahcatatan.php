<?php
session_start();
include('../../koneksi.php');
session_start();

<<<<<<< HEAD
$id_jurnal = $_POST['id_jurnal'] ?? '';
$catatan = $_POST['catatan'] ?? '';
$id_pembimbing = $_SESSION['id_pembimbing'] ?? ($_POST['id_pembimbing'] ?? '');
$role = $_SESSION['role'] ?? '';
$tanggal_hari_ini = date('Y-m-d');

if ($role !== 'pembimbing') {
    header('Location: ../../index.php?page=catatan&pesan=gagal&error=' . urlencode('Akses ditolak'));
    exit();
}

if (isset($_POST['submit'])) {

    // Validasi input wajib
    if (empty($id_jurnal) || empty($catatan)) {
        $_SESSION['flash_error'] = 'ID Jurnal dan Catatan wajib diisi!';
        header('Location: ../../index.php?page=catatan');
        exit();
    }

    // Cek duplikat
    $cek = mysqli_query($coneksi, "SELECT 1 FROM catatan 
                                   WHERE id_jurnal='$id_jurnal' 
                                   AND id_pembimbing='$id_pembimbing'");
    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['flash_duplikat'] = true;
        header('Location: ../../index.php?page=catatan');
        exit();
    }

    // Insert
    $sql = mysqli_query($coneksi, "INSERT INTO catatan (id_jurnal, tanggal, catatan, id_pembimbing) 
                                   VALUES ('$id_jurnal', '$tanggal_hari_ini', '$catatan', '$id_pembimbing')");

    if ($sql) {
        $_SESSION['flash_tambah'] = 'sukses';
    } else {
        $_SESSION['flash_error'] = 'Gagal menambahkan catatan: ' . mysqli_error($coneksi);
=======
// Cek session
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'pembimbing') {
    $_SESSION['flash_error'] = 'Akses ditolak. Hanya pembimbing yang dapat menambahkan catatan.';
    header('Location: ../../index.php?page=catatan');
    exit();
}

// Ambil data dari form
$mode = $_POST['mode'] ?? 'tambah';
$id_catatan = $_POST['id_catatan'] ?? null;
$id_jurnal = $_POST['id_jurnal'] ?? null;
$id_siswa = $_POST['id_siswa'] ?? null;
$catatan = $_POST['catatan'] ?? '';
$tanggal = $_POST['tanggal'] ?? date('Y-m-d'); // Gunakan tanggal dari form, default hari ini
$id_pembimbing = $_SESSION['id_pembimbing'] ?? null;

// Validasi input wajib
if (empty($catatan)) {
    $_SESSION['flash_error'] = 'Catatan wajib diisi!';
    header("Location: ../../index.php?page=tambahcatatan&id_jurnal=$id_jurnal&id_siswa=$id_siswa&tanggal=$tanggal");
    exit();
}

if (empty($id_pembimbing)) {
    $_SESSION['flash_error'] = 'ID Pembimbing tidak valid!';
    header("Location: ../../index.php?page=tambahcatatan&id_jurnal=$id_jurnal&id_siswa=$id_siswa&tanggal=$tanggal");
    exit();
}

// Pastikan id_siswa selalu ada
if (empty($id_siswa)) {
    $_SESSION['flash_error'] = 'Data tidak valid! ID Siswa harus ada.';
    header("Location: ../../index.php?page=catatan&tanggal=$tanggal");
    exit();
}

if (isset($_POST['submit'])) {
    if ($mode === 'update' && !empty($id_catatan)) {
        // Update catatan yang sudah ada
        $sql = "UPDATE catatan 
                SET catatan = '" . mysqli_real_escape_string($coneksi, $catatan) . "', 
                    tanggal = '$tanggal'"; // Gunakan tanggal dari form
        
        // Update id_jurnal jika ada
        if (!empty($id_jurnal)) {
            $sql .= ", id_jurnal = '$id_jurnal'";
        }
        
        $sql .= " WHERE id_catatan = '$id_catatan' 
                 AND id_pembimbing = '$id_pembimbing'";
        
        if (mysqli_query($coneksi, $sql)) {
            $_SESSION['flash_update'] = 'sukses'; // Sesuai dengan yang diharapkan di halaman tampil
        } else {
            $_SESSION['flash_error'] = 'Gagal mengupdate catatan: ' . mysqli_error($coneksi);
        }
    } else {
        // Mode tambah - cek duplikat (catatan untuk tanggal, pembimbing dan siswa yang sama)
        $cek_query = "SELECT 1 FROM catatan 
                      WHERE id_pembimbing = '$id_pembimbing' 
                      AND id_siswa = '$id_siswa'
                      AND DATE(tanggal) = '$tanggal'"; // Gunakan tanggal dari form, bukan CURDATE()
        
        $cek = mysqli_query($coneksi, $cek_query);
        
        if (mysqli_num_rows($cek) > 0) {
            $_SESSION['flash_error'] = 'Anda sudah memberikan catatan untuk siswa ini pada tanggal ' . date('d-m-Y', strtotime($tanggal)) . '!';
            header("Location: ../../index.php?page=tambahcatatan&id_jurnal=$id_jurnal&id_siswa=$id_siswa&tanggal=$tanggal");
            exit();
        }

        // Insert catatan baru
        $escaped_catatan = mysqli_real_escape_string($coneksi, $catatan);
        
        // Tentukan kolom dan nilai
        $columns = ['tanggal', 'catatan', 'id_pembimbing', 'id_siswa'];
        $values = ["'$tanggal'", "'$escaped_catatan'", "'$id_pembimbing'", "'$id_siswa'"]; // Gunakan tanggal dari form
        
        // Tambahkan id_jurnal jika ada (opsional)
        if (!empty($id_jurnal)) {
            $columns[] = 'id_jurnal';
            $values[] = "'$id_jurnal'";
        }
        
        $sql = "INSERT INTO catatan (" . implode(', ', $columns) . ") 
                VALUES (" . implode(', ', $values) . ")";

        if (mysqli_query($coneksi, $sql)) {
            $_SESSION['flash_tambah'] = 'sukses'; // Sesuai dengan yang diharapkan di halaman tampil
        } else {
            $_SESSION['flash_error'] = 'Gagal menambahkan catatan: ' . mysqli_error($coneksi);
        }
>>>>>>> 1ba93e3e1841f0db196d55408850db39c813b6be
    }

    // Redirect kembali ke halaman yang sesuai dengan parameter tanggal
    header("Location: ../../index.php?page=tambahcatatan&id_jurnal=$id_jurnal&id_siswa=$id_siswa&tanggal=$tanggal");
    exit();

} else {
    // Kalau akses tanpa submit
    header("Location: ../../index.php?page=catatan&tanggal=$tanggal");
    exit();
}
?>