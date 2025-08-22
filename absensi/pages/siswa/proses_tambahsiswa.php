<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include('../../koneksi.php');

// Fungsi untuk mengecek apakah NIS sudah ada di database
function cekNisExist($nis, $exclude_id = null) {
    global $coneksi;
    $query = "SELECT * FROM siswa WHERE nis = '$nis'";
    if ($exclude_id) {
        $query .= " AND id_siswa != $exclude_id";
    }
    $result = mysqli_query($coneksi, $query);
    return mysqli_num_rows($result) > 0;
}

// Fungsi untuk mengecek apakah NISN sudah ada di database
function cekNisnExist($nisn, $exclude_id = null) {
    global $coneksi;
    $query = "SELECT * FROM siswa WHERE nisn = '$nisn'";
    if ($exclude_id) {
        $query .= " AND id_siswa != $exclude_id";
    }
    $result = mysqli_query($coneksi, $query);
    return mysqli_num_rows($result) > 0;
}

if (isset($_POST['submit'])) {
    // Escape semua input dengan nilai default jika tidak ada
    $nis = mysqli_real_escape_string($coneksi, $_POST['nis']);
    $nisn = mysqli_real_escape_string($coneksi, $_POST['nisn']);
    $nama_siswa = mysqli_real_escape_string($coneksi, $_POST['nama_siswa']);
    $id_sekolah = mysqli_real_escape_string($coneksi, $_POST['id_sekolah'] ?? '');
    $id_perusahaan = mysqli_real_escape_string($coneksi, $_POST['id_perusahaan'] ?? '');
    $id_pembimbing = mysqli_real_escape_string($coneksi, $_POST['id_pembimbing'] ?? '');
    $id_guru = mysqli_real_escape_string($coneksi, $_POST['id_guru'] ?? '');
    $username = mysqli_real_escape_string($coneksi, $_POST['username']);
    $password = mysqli_real_escape_string($coneksi, $_POST['password']);
    
    // Field tambahan yang diperlukan oleh database
    $pro_keahlian = mysqli_real_escape_string($coneksi, $_POST['pro_keahlian'] ?? 'Multimedia');
    $TL = mysqli_real_escape_string($coneksi, $_POST['TL'] ?? '');
    $TTGL = mysqli_real_escape_string($coneksi, $_POST['TTGL'] ?? date('Y-m-d'));
    $tanggal_mulai = mysqli_real_escape_string($coneksi, $_POST['tanggal_mulai'] ?? date('Y-m-d'));
    $tanggal_selesai = mysqli_real_escape_string($coneksi, $_POST['tanggal_selesai'] ?? date('Y-m-d', strtotime('+3 months')));
    $no_wa = mysqli_real_escape_string($coneksi, $_POST['no_wa'] ?? '0000000000');
    
    // Validasi format NIS dan NISN
    $nis_valid = (strlen($nis) >= 8 && strlen($nis) <= 12 && is_numeric($nis));
    $nisn_valid = (strlen($nisn) == 10 && is_numeric($nisn));
    
    if (!$nis_valid) {
        $_SESSION['error_nis'] = 'NIS harus terdiri dari 8-12 digit angka';
        $_SESSION['form_data'] = $_POST;
        header('Location: ../../index.php?page=tambahsiswa');
        exit();
    } elseif (!$nisn_valid) {
        $_SESSION['error_nisn'] = 'NISN harus terdiri dari 10 digit angka';
        $_SESSION['form_data'] = $_POST;
        header('Location: ../../index.php?page=tambahsiswa');
        exit();
    } else {
        // Validasi NIS dan NISN di database
        $nis_exist = cekNisExist($nis);
        $nisn_exist = cekNisnExist($nisn);
        
        if ($nis_exist) {
            $_SESSION['error_nis'] = 'NIS sudah digunakan';
            $_SESSION['form_data'] = $_POST;
            header('Location: ../../index.php?page=tambahsiswa');
            exit();
        } elseif ($nisn_exist) {
            $_SESSION['error_nisn'] = 'NISN sudah digunakan';
            $_SESSION['form_data'] = $_POST;
            header('Location: ../../index.php?page=tambahsiswa');
            exit();
        } else {
            // Jika NIS dan NISN belum ada, simpan data dengan semua field yang diperlukan
            $query = "INSERT INTO siswa (nis, nisn, nama_siswa, id_sekolah, id_perusahaan, id_pembimbing, id_guru, username, password, no_wa, pro_keahlian, TL, TTGL, tanggal_mulai, tanggal_selesai) 
                      VALUES ('$nis', '$nisn', '$nama_siswa', '$id_sekolah', '$id_perusahaan', '$id_pembimbing', '$id_guru', '$username', '$password', '$no_wa', '$pro_keahlian', '$TL', '$TTGL', '$tanggal_mulai', '$tanggal_selesai')";
            
            if (mysqli_query($coneksi, $query)) {
                $_SESSION['flash_tambah'] = 'sukses';
                header('Location: ../../index.php?page=siswa');
                exit();
            } else {
                $_SESSION['flash_error'] = "Error: " . mysqli_error($coneksi);
                $_SESSION['form_data'] = $_POST;
                header('Location: ../../index.php?page=tambahsiswa');
                exit();
            }
        }
    }
} else {
    // Jika tidak ada submit, redirect ke form
    header('Location: ../../index.php?page=tambahsiswa');
    exit();
}
?>