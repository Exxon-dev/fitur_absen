<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include('../../koneksi.php');

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
    header("Location: ../../index.php?page=tambahcatatan&id_jurnal=" . urlencode($id_jurnal) . "&id_siswa=" . urlencode($id_siswa) . "&tanggal=" . urlencode($tanggal));
    exit();
}

if (empty($id_pembimbing)) {
    $_SESSION['flash_error'] = 'ID Pembimbing tidak valid!';
    header("Location: ../../index.php?page=tambahcatatan&id_jurnal=" . urlencode($id_jurnal) . "&id_siswa=" . urlencode($id_siswa) . "&tanggal=" . urlencode($tanggal));
    exit();
}

// Pastikan id_siswa selalu ada
if (empty($id_siswa)) {
    $_SESSION['flash_error'] = 'Data tidak valid! ID Siswa harus ada.';
    header("Location: ../../index.php?page=catatan&tanggal=" . urlencode($tanggal));
    exit();
}

if (isset($_POST['submit'])) {
    if ($mode === 'update' && !empty($id_catatan)) {
        // Update catatan yang sudah ada
        $sql = "UPDATE catatan 
                SET catatan = '" . mysqli_real_escape_string($coneksi, $catatan) . "', 
                    tanggal = '" . mysqli_real_escape_string($coneksi, $tanggal) . "'"; // Gunakan tanggal dari form
        
        // Update id_jurnal jika ada
        if (!empty($id_jurnal)) {
            $sql .= ", id_jurnal = '" . mysqli_real_escape_string($coneksi, $id_jurnal) . "'";
        }
        
        $sql .= " WHERE id_catatan = '" . mysqli_real_escape_string($coneksi, $id_catatan) . "' 
                 AND id_pembimbing = '" . mysqli_real_escape_string($coneksi, $id_pembimbing) . "'";
        
        if (mysqli_query($coneksi, $sql)) {
            $_SESSION['flash_update'] = 'sukses'; // Sesuai dengan yang diharapkan di halaman tampil
        } else {
            $_SESSION['flash_error'] = 'Gagal mengupdate catatan: ' . mysqli_error($coneksi);
        }
    } else {
        // Mode tambah - cek duplikat (catatan untuk tanggal, pembimbing dan siswa yang sama)
        $cek_query = "SELECT 1 FROM catatan 
                      WHERE id_pembimbing = '" . mysqli_real_escape_string($coneksi, $id_pembimbing) . "' 
                      AND id_siswa = '" . mysqli_real_escape_string($coneksi, $id_siswa) . "'
                      AND DATE(tanggal) = '" . mysqli_real_escape_string($coneksi, $tanggal) . "'";
        
        $cek = mysqli_query($coneksi, $cek_query);
        
        if (mysqli_num_rows($cek) > 0) {
            $_SESSION['flash_error'] = 'Anda sudah memberikan catatan untuk siswa ini pada tanggal ' . date('d-m-Y', strtotime($tanggal)) . '!';
            header("Location: ../../index.php?page=tambahcatatan&id_jurnal=" . urlencode($id_jurnal) . "&id_siswa=" . urlencode($id_siswa) . "&tanggal=" . urlencode($tanggal));
            exit();
        }

        // Insert catatan baru
        $escaped_catatan = mysqli_real_escape_string($coneksi, $catatan);
        $escaped_tanggal = mysqli_real_escape_string($coneksi, $tanggal);
        $escaped_id_pembimbing = mysqli_real_escape_string($coneksi, $id_pembimbing);
        $escaped_id_siswa = mysqli_real_escape_string($coneksi, $id_siswa);
        $escaped_id_jurnal = mysqli_real_escape_string($coneksi, $id_jurnal);

        // Tentukan kolom dan nilai
        $columns = ['tanggal', 'catatan', 'id_pembimbing', 'id_siswa', 'id_jurnal'];
        $values = ["'$escaped_tanggal'", "'$escaped_catatan'", "'$escaped_id_pembimbing'", "'$escaped_id_siswa'", "'$escaped_id_jurnal'"];
        
        // // Tambahkan id_jurnal jika ada (opsional)
        // if (!empty($id_jurnal)) {

        //     $columns[] = 'id_jurnal';
        //     $values[] = "'$escaped_id_jurnal'";
        // }
        
        $sql = "INSERT INTO catatan (" . implode(', ', $columns) . ") 
                VALUES (" . implode(', ', $values) . ")";

        if (mysqli_query($coneksi, $sql)) {
            $_SESSION['flash_tambah'] = 'sukses'; // Sesuai dengan yang diharapkan di halaman tampil
        } else {
            $_SESSION['flash_error'] = 'Gagal menambahkan catatan: ' . mysqli_error($coneksi);
        }
    }

    // Redirect kembali ke halaman yang sesuai dengan parameter tanggal
    header("Location: ../../index.php?page=tambahcatatan&id_jurnal=" . urlencode($id_jurnal) . "&id_siswa=" . urlencode($id_siswa) . "&tanggal=" . urlencode($tanggal));
    exit();
} else {
    // Kalau akses tanpa submit
    header("Location: ../../index.php?page=catatan&tanggal=" . urlencode($tanggal));
    exit();
}
?>