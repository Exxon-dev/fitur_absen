<?php

// Fungsi untuk memeriksa hak akses
function checkAccess($requiredLevel)
{
    if (!isset($_SESSION['level'])) {
        header("Location: login.php");
        exit();
    }

    // Jika level pengguna tidak sesuai dengan yang diperlukan
    if ($_SESSION['level'] != $requiredLevel) {
        // Redirect ke halaman dashboard sesuai level
        switch ($_SESSION['level']) {
            case 'admin':
                header("Location: index.php?page=dashboard");
                break;
            case 'pembimbing':
                header("Location: index.php?page=dashboard_pembimbing");
                break;
            case 'siswa':
                header("Location: index.php?page=dashboard_siswa");
                break;
            case 'guru':
                header("Location: index.php?page=dashboard_guru");
                break;
            case 'sekolah':
                header("Location: index.php?page=dashboard_sekolah");
                break;
            default:
                header("Location: login.php");
                break;
        }
        exit();
    }
}

$accessControl = [
    'dashboard' => ['admin'],
    'profile_admin' => ['admin'],
    'rekap_absen' => ['pembimbing'],
    'dashboard_pembimbing' => ['pembimbing'],
    'dashboard_siswa' => ['siswa'],
    'dashboard_guru' => ['guru'],
    'dashboard_sekolah' => ['sekolah'],
    'siswa' => ['admin'],
    'tambahsiswa' => ['admin'],
    'editsiswa' => ['siswa'],
    'editsiswa1' => ['admin'],
    'hapussiswa' => ['admin'],
    'absenmasuk' => ['siswa'],
    'guru' => ['admin'],
    'absensi_siswa' => ['guru'],
    'tambahsiswa_guru' => ['guru'],
    'tambahguru' => ['admin'],
    'editguru' => ['guru'],
    'editguru1' => ['admin'],
    'hapusguru' => ['admin'],
    'pembimbing' => ['admin'],
    'pembimbing_absen' => ['pembimbing'],
    'tambahpembimbing' => ['admin'],
    'editpembimbing' => ['pembimbing'],
    'editpembimbing1' => ['admin'],
    'hapuspembimbing' => ['admin'],
    'perusahaan' => ['admin'],
    'tambahperusahaan' => ['admin'],
    'editperusahaan' => ['admin'],
    'hapusperusahaan' => ['admin'],
    'sekolah' => ['admin'],
    'tambahsekolah' => ['admin'],
    'editsekolah' => ['admin'],
    'hapussekolah' => ['admin'],
    'jurnal' => ['siswa', 'pembimbing', 'guru'],
    'tambahjurnal' => ['siswa'],
    'editjurnal' => ['siswa'],
    'hapusjurnal' => ['siswa'],
    'catatan' => ['siswa', 'pembimbing', 'guru'],
    'tambahcatatan' => ['siswa', 'guru', 'pembimbing'],
    'editcatatan' => ['siswa'],
    'hapuscatatan' => ['siswa'],
    'laporan' => ['pembimbing', 'guru', 'siswa'],
    'laporan1' => ['admin', 'pembimbing', 'guru'],
    'laporan2' => ['admin', 'pembimbing', 'guru'],
    'laporan3' => ['admin', 'pembimbing', 'guru']
];

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['level'])) {
    header("Location: login.php");
    exit();
}

// Memeriksa parameter page
if (isset($_GET['page'])) {
    $page = $_GET['page'];

    // Memeriksa apakah halaman yang diminta ada dalam daftar kontrol akses
    if (array_key_exists($page, $accessControl)) {
        // Memeriksa apakah level pengguna diizinkan mengakses halaman
        if (in_array($_SESSION['level'], $accessControl[$page])) {
            // Jika diizinkan, include halaman yang diminta
            switch ($page) {
                case 'dashboard':
                    include "pages/dashboard.php";
                    break;
                case 'profile_admin':
                    include "pages/profile_admin.php";
                    break;
                case 'rekap_absen':
                    include "pages/admin_rekap_absen.php";
                    break;
                case 'dashboard_pembimbing':
                    include "pages/pembimbing/dashboard_pembimbing.php";
                    break;
                case 'dashboard_siswa':
                    include "pages/siswa/dashboard_siswa.php";
                    break;
                case 'dashboard_guru':
                    include "pages/guru/dashboard_guru.php";
                    break;
                case 'dashboard_sekolah':
                    include "pages/sekolah/dashboard_sekolah.php";
                    break;
                case 'siswa':
                    include "pages/siswa/siswa.php";
                    break;
                case 'tambahsiswa':
                    include "pages/siswa/tambahsiswa.php";
                    break;
                case 'editsiswa':
                    include "pages/siswa/editsiswa.php";
                    break;
                case 'editsiswa1':
                    include "pages/siswa/editsiswa1.php";
                    break;
                case 'hapussiswa':
                    include "pages/siswa/hapussiswa.php";
                    break;
                case 'absenmasuk':
                    include "pages/siswa/absenmasuk.php";
                    break;
                case 'guru':
                    include "pages/guru/guru.php";
                    break;
                case 'absensi_siswa':
                    include "pages/guru/absensi_siswa.php";
                    break;
                case 'tambahsiswa_guru':
                    include "pages/guru/tambahsiswa_guru.php";
                    break;
                case 'tambahguru':
                    include "pages/guru/tambahguru.php";
                    break;
                case 'editguru':
                    include "pages/guru/editguru.php";
                    break;
                case 'editguru1':
                    include "pages/guru/editguru1.php";
                    break;
                case 'hapusguru':
                    include "pages/guru/hapusguru.php";
                    break;
                case 'pembimbing':
                    include "pages/pembimbing/pembimbing.php";
                    break;
                case 'pembimbing_absen':
                    include "pages/pembimbing/absensi_siswa.php";
                    break;
                case 'tambahpembimbing':
                    include "pages/pembimbing/tambahpembimbing.php";
                    break;
                case 'editpembimbing':
                    include "pages/pembimbing/editpembimbing.php";
                    break;
                case 'editpembimbing1':
                    include "pages/pembimbing/editpembimbing1.php";
                    break;
                case 'hapuspembimbing':
                    include "pages/pembimbing/hapuspembimbing.php";
                    break;
                case 'perusahaan':
                    include "pages/perusahaan/perusahaan.php";
                    break;
                case 'tambahperusahaan':
                    include "pages/perusahaan/tambahperusahaan.php";
                    break;
                case 'editperusahaan':
                    include "pages/perusahaan/editperusahaan.php";
                    break;
                case 'hapusperusahaan':
                    include "pages/perusahaan/hapusperusahaan.php";
                    break;
                case 'sekolah':
                    include "pages/sekolah/sekolah.php";
                    break;
                case 'profile_sekolah':
                    include "pages/sekolah/profile_sekolah.php";
                    break;
                case 'tambahsekolah':
                    include "pages/sekolah/tambahsekolah.php";
                    break;
                case 'editsekolah':
                    include "pages/sekolah/editsekolah.php";
                    break;
                case 'hapussekolah':
                    include "pages/sekolah/hapussekolah.php";
                    break;
                case 'jurnal':
                    include "pages/jurnal/jurnal.php";
                    break;
                case 'tambahjurnal':
                    include "pages/jurnal/tambahjurnal.php";
                    break;
                case 'editjurnal':
                    include "pages/jurnal/editjurnal.php";
                    break;
                case 'hapusjurnal':
                    include "pages/jurnal/hapusjurnal.php";
                    break;
                case 'catatan':
                    include "pages/catatan/catatan.php";
                    break;
                case 'tambahcatatan':
                    include "pages/catatan/tambahcatatan.php";
                    break;
                case 'editcatatan':
                    include "pages/catatan/editcatatan.php";
                    break;
                case 'hapuscatatan':
                    include "pages/catatan/hapuscatatan.php";
                    break;
                case 'laporan':
                    include "pages/laporan/laporan.php";
                    break;
                case 'laporan1':
                    include "pages/laporan/laporan1.php";
                    break;
                case 'laporan2':
                    include "pages/laporan/laporan2.php";
                    break;
                case 'laporan3':
                    include "pages/laporan/laporan3.php";
                    break;
                default:
                    echo "Maaf halaman yang anda tuju tidak ada";
                    break;
            }
        } else {
            // Jika tidak diizinkan, redirect ke halaman dashboard sesuai level

            // Tentukan URL redirect
            if ($_SESSION['level'] == 'admin') {
                $redirect_url = "index.php?page=dashboard";
            } else {
                $redirect_url = "index.php?page=dashboard_" . $_SESSION['level'];
            }

            // Tampilkan SweetAlert
            echo '
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Anda tidak memiliki akses ke halaman ini!"
            }).then(function() {
                window.location.href = "'.$redirect_url.'";
            });
            </script>
            ';


        }
    } else {
        // Jika halaman tidak ditemukan dalam daftar
        echo "Maaf halaman yang anda tuju tidak ada";
    }
}
