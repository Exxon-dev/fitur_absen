<?php
session_start();
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
$id_pembimbing = $_SESSION['id_pembimbing'] ?? null;
$tanggal_hari_ini = date('Y-m-d');

// Validasi input wajib
if (empty($catatan)) {
    $_SESSION['flash_error'] = 'Catatan wajib diisi!';
    header("Location: ../../index.php?page=tambahcatatan&id_jurnal=$id_jurnal&id_siswa=$id_siswa");
    exit();
}

if (empty($id_pembimbing)) {
    $_SESSION['flash_error'] = 'ID Pembimbing tidak valid!';
    header("Location: ../../index.php?page=tambahcatatan&id_jurnal=$id_jurnal&id_siswa=$id_siswa");
    exit();
}

// Pastikan id_siswa selalu ada
if (empty($id_siswa)) {
    $_SESSION['flash_error'] = 'Data tidak valid! ID Siswa harus ada.';
    header('Location: ../../index.php?page=catatan');
    exit();
}

if (isset($_POST['submit'])) {
    if ($mode === 'update' && !empty($id_catatan)) {
        // Update catatan yang sudah ada
        $sql = "UPDATE catatan 
                SET catatan = '" . mysqli_real_escape_string($coneksi, $catatan) . "', 
                    tanggal = '$tanggal_hari_ini'";
        
        // Update id_jurnal jika ada
        if (!empty($id_jurnal)) {
            $sql .= ", id_jurnal = '$id_jurnal'";
        }
        
        $sql .= " WHERE id_catatan = '$id_catatan' 
                 AND id_pembimbing = '$id_pembimbing'";
        
        if (mysqli_query($coneksi, $sql)) {
            $_SESSION['flash_success'] = 'Catatan berhasil diupdate!';
        } else {
            $_SESSION['flash_error'] = 'Gagal mengupdate catatan: ' . mysqli_error($coneksi);
        }
    } else {
        // Mode tambah - cek duplikat (catatan hari ini untuk pembimbing dan siswa yang sama)
        $cek_query = "SELECT 1 FROM catatan 
                      WHERE id_pembimbing = '$id_pembimbing' 
                      AND id_siswa = '$id_siswa'
                      AND DATE(tanggal) = CURDATE()";
        
        $cek = mysqli_query($coneksi, $cek_query);
        
        if (mysqli_num_rows($cek) > 0) {
            $_SESSION['flash_error'] = 'Anda sudah memberikan catatan untuk siswa ini hari ini!';
            header("Location: ../../index.php?page=tambahcatatan&id_jurnal=$id_jurnal&id_siswa=$id_siswa");
            exit();
        }

        // Insert catatan baru
        $escaped_catatan = mysqli_real_escape_string($coneksi, $catatan);
        
        // Tentukan kolom dan nilai
        $columns = ['tanggal', 'catatan', 'id_pembimbing', 'id_siswa'];
        $values = ["'$tanggal_hari_ini'", "'$escaped_catatan'", "'$id_pembimbing'", "'$id_siswa'"];
        
        // Tambahkan id_jurnal jika ada (opsional)
        if (!empty($id_jurnal)) {
            $columns[] = 'id_jurnal';
            $values[] = "'$id_jurnal'";
        }
        
        $sql = "INSERT INTO catatan (" . implode(', ', $columns) . ") 
                VALUES (" . implode(', ', $values) . ")";

        if (mysqli_query($coneksi, $sql)) {
            $_SESSION['flash_success'] = 'Catatan berhasil ditambahkan!';
        } else {
            $_SESSION['flash_error'] = 'Gagal menambahkan catatan: ' . mysqli_error($coneksi);
        }
    }

    // Redirect kembali ke halaman yang sesuai
    header("Location: ../../index.php?page=tambahcatatan&id_jurnal=$id_jurnal&id_siswa=$id_siswa");
    exit();
} else {
    // Kalau akses tanpa submit
    header('Location: ../../index.php?page=catatan');
    exit();
}
?>