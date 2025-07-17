<?php
include('koneksi.php');
session_start();

// Cek apakah siswa sudah login
if (!isset($_SESSION['id_siswa'])) {
    header("Location: sign-in.php");
    exit();
}

$id_siswa = $_SESSION['id_siswa'];
$tanggal = date('Y-m-d');

// Ambil nama siswa dari database (tidak perlu simpan di session, agar selalu update)
$stmt = mysqli_prepare($coneksi, "SELECT nama_siswa FROM siswa WHERE id_siswa = ?");
mysqli_stmt_bind_param($stmt, "i", $id_siswa);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$siswa = mysqli_fetch_assoc($result);
$nama_siswa = $siswa ? $siswa['nama_siswa'] : "Siswa";

// Cek status absensi dengan prepared statement
$stmt = mysqli_prepare($coneksi, "SELECT jam_masuk, jam_keluar FROM absen WHERE id_siswa=? AND tanggal=?");
mysqli_stmt_bind_param($stmt, "is", $id_siswa, $tanggal);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$absen = mysqli_fetch_assoc($result);

$status = 'belum';
if ($absen) {
    if ($absen['jam_masuk'] && !$absen['jam_keluar']) {
        $status = 'masuk';
    } elseif ($absen['jam_masuk'] && $absen['jam_keluar']) {
        $status = 'selesai';
    }
}
$_SESSION['status_absen'] = $status;
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Siswa - Sistem Absensi</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <style>
        /* CSS styles tetap sama seperti sebelumnya */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
        }

        #btnAbsensi {
            padding: 25px 40px;
            font-size: 24px;
            font-weight: 600;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
        }

        #btnAbsensi.belum {
            background: linear-gradient(135deg, #ff4757 0%, #ff6b81 100%);
        }

        #btnAbsensi.masuk {
            background: linear-gradient(135deg, #2ed573 0%, #7bed9f 100%);
        }

        #btnAbsensi.selesai {
            background: linear-gradient(135deg, #2f3542 0%, #57606f 100%);
            cursor: not-allowed;
        }

        .container-tengah {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        .info-status {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            transform: scale(0);
            animation: ripple 0.6s linear;
        }

        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    </style>
</head>

<body>
    <div class="container-tengah">
        <button id="btnAbsensi" class="<?= $status ?>" <?= $status === 'selesai' ? 'disabled' : '' ?>
            onclick="prosesAbsensi()">
            <?= $status === 'belum' ? 'ABSEN MASUK' : ($status === 'masuk' ? 'ABSEN PULANG' : 'SUDAH ABSEN') ?>
        </button>
        <div class="info-status">
            Status:
            <?= $status === 'belum' ? 'Belum absen' : ($status === 'masuk' ? 'Sudah absen masuk' : 'Sudah absen pulang') ?>
        </div>
    </div>

    <script>
        let statusSaatIni = "<?= $status ?>";
        let sudahAbsenPulang = <?= ($status === 'selesai') ? 'true' : 'false' ?>;

        function prosesAbsensi() {
            if (statusSaatIni === 'selesai') return;

            // Efek ripple
            const btn = document.getElementById('btnAbsensi');
            const ripple = document.createElement('span');
            ripple.classList.add('ripple');
            btn.appendChild(ripple);
            const rect = btn.getBoundingClientRect();
            ripple.style.left = `${event.clientX - rect.left}px`;
            ripple.style.top = `${event.clientY - rect.top}px`;
            setTimeout(() => ripple.remove(), 600);

            // Konfirmasi
            Swal.fire({
                title: 'Konfirmasi',
                text: statusSaatIni === 'belum' ? 'Absen masuk sekarang?' : 'Absen pulang sekarang?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    kirimDataAbsensi(statusSaatIni === 'belum' ? 'simpan_masuk' : 'simpan_keluar');
                }
            });
        }

        function kirimDataAbsensi(aksi) {
            fetch('pages/siswa/proses_absen.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'Cache-Control': 'no-cache'
                    },
                    body: `action=${aksi}`
                })
                .then(async res => {
                    const text = await res.text();
                    try {
                        const data = JSON.parse(text);
                        if (!res.ok) throw new Error(data.message || 'Error');
                        return data;
                    } catch {
                        throw new Error(text || 'Invalid response');
                    }
                })
                .then(data => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: data.message
                    });

                    const btn = document.getElementById('btnAbsensi');
                    if (aksi === 'simpan_masuk') {
                        btn.className = 'masuk';
                        btn.textContent = 'ABSEN PULANG';
                        statusSaatIni = 'masuk';
                        document.querySelector('.info-status').textContent = 'Status: Sudah absen masuk';
                    } else {
                        btn.className = 'selesai';
                        btn.textContent = 'SUDAH ABSEN';
                        btn.disabled = true;
                        statusSaatIni = 'selesai';
                        sudahAbsenPulang = true;
                        document.querySelector('.info-status').textContent = 'Status: Sudah absen pulang';
                    }
                })
                .catch(err => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: err.message
                    });
                });
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Notifikasi SweetAlert2 dari proses tambah siswa
            <?php if (isset($_GET['pesan'])): ?>
                <?php if ($_GET['pesan'] == 'sukses'): ?>
                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses!',
                        text: 'Data siswa berhasil ditambahkan',
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2000,
                        toast: true
                    });
                <?php elseif ($_GET['pesan'] == 'gagal'): ?>
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: '<?php echo isset($_GET['error']) ? htmlspecialchars(urldecode($_GET['error']), ENT_QUOTES) : 'Terjadi kesalahan'; ?>',
                        position: 'top',
                        showConfirmButton: false,
                        timer: 3000,
                        toast: true
                    });
                <?php elseif ($_GET['pesan'] == 'duplikat'): ?>
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan!',
                        text: 'ID siswa atau Username sudah terdaftar',
                        position: 'top',
                        showConfirmButton: false,
                        timer: 3000,
                        toast: true
                    });
                <?php endif; ?>
            <?php else: ?>
                // Notifikasi login sukses (default) - hanya muncul sekali
                if (!localStorage.getItem('welcomeAlertShown')) {
                    const namaSiswa = "<?php echo !empty($nama_siswa) ? htmlspecialchars($nama_siswa, ENT_QUOTES) : 'Siswa'; ?>";
                    Swal.fire({
                        title: `Selamat datang ${namaSiswa}!`,
                        text: "Anda berhasil login ke sistem",
                        icon: 'success',
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        toast: true,
                        background: '#f8f9fa',
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer);
                            toast.addEventListener('mouseleave', Swal.resumeTimer);
                        }
                    });
                    localStorage.setItem('welcomeAlertShown', 'true');
                }
            <?php endif; ?>
        });

        // Tambahkan ini untuk menghapus localStorage saat logout
        // Pastikan ini ada di halaman logout Anda
        // localStorage.removeItem('welcomeAlertShown');
    </script>

</body>

</html>