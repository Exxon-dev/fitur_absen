<div class="konten">
    <?php
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
        switch ($page) {
            case 'dashboard':
                include "pages/dashboard.php";
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
            case 'rekap_ip':
                include "pages/rekap_ip.php";
                break;
            default:
                echo "Maaf halaman yang anda tuju tidak ada";
                break;
        }
    }
    ?>
</div>