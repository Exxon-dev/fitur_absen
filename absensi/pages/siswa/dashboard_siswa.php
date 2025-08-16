<?php
include('koneksi.php');

// Cek apakah siswa sudah login
if (!isset($_SESSION['id_siswa'])) {
    header("Location: sign-in.php");
    exit();
}

$id_siswa = $_SESSION['id_siswa'];
$tanggal = date('Y-m-d');

// Ambil data siswa
$stmt = mysqli_prepare($coneksi, "SELECT nama_siswa, id_perusahaan FROM siswa WHERE id_siswa = ?");
mysqli_stmt_bind_param($stmt, "i", $id_siswa);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$siswa = mysqli_fetch_assoc($result);
$nama_siswa = $siswa ? $siswa['nama_siswa'] : "Siswa";
$id_perusahaan = $siswa['id_perusahaan'] ?? null;

// Cek status absensi
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

// Ambil catatan pembimbing
$catatan_pembimbing = [];
if ($id_perusahaan) {
    $sql_catatan = "
        SELECT 
            c.catatan,
            c.tanggal,
            p.nama_pembimbing
        FROM catatan c
        JOIN pembimbing p ON c.id_pembimbing = p.id_pembimbing
        JOIN jurnal j ON c.id_jurnal = j.id_jurnal
        WHERE j.id_siswa = '$id_siswa'
        ORDER BY c.tanggal DESC
        LIMIT 5
    ";
    $result_catatan = mysqli_query($coneksi, $sql_catatan);
    if ($result_catatan) {
        $catatan_pembimbing = mysqli_fetch_all($result_catatan, MYSQLI_ASSOC);
    }
}

// Format tanggal function
function formatTanggal($dateString)
{
    $date = new DateTime($dateString);
    return $date->format('m-d-Y');
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Siswa - Sistem Absensi</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <style>
        :root {
            --primary: #3498db;
            --success: #2ecc71;
            --warning: #f39c12;
            --danger: #e74c3c;
            --light: #f8f9fa;
            --dark: #343a40;
        }

        body {
            padding-left: 270px;
            transition: padding-left 0.3s;
            background-color: #f8f9fa;
            
        }

        .main-container {
            margin-top: 20px;
            margin-right: 20px;
            margin-left: 0;
            width: auto;
            max-width: none;
            zoom: 0.85;
        }

        .container-custom {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .dashboard-wrapper {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .dashboard-header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        h2 {
            margin-bottom: 20px;
            color: #007bff;
        }

        .content-container {
            display: flex;
            flex: 1;
            gap: 20px;
        }

        .attendance-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 30px;
        }

        .notes-section {
            width: 350px;
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            overflow-y: auto;
        }

        #btnAbsensi {
            padding: 25px 30px;
            font-size: 20px;
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
            margin-bottom: 20px;
            width: 40%;
            max-width: 250px;
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

        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #007bff;
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 10px;
        }

        .note-card {
            background-color: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .note-header {
            font-weight: 600;
            color: #007bff;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .note-header i {
            margin-right: 8px;
            font-size: 14px;
        }

        .note-body {
            margin-bottom: 10px;
            color: #333;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
            border-left: 3px solid #007bff;
        }

        .note-footer {
            font-size: 12px;
            color: #6c757d;
            text-align: right;
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .note-footer i {
            margin-right: 5px;
        }

        .empty-notes {
            color: #6c757d;
            text-align: center;
            margin-top: 20px;
            font-style: italic;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
        }

        @media (max-width: 991px) {
            body {
                padding-left: 0;
            }

            .main-container {
                margin: 10px;
                height: auto;
            }

            .content-container {
                flex-direction: column;
            }

            .attendance-section {
                margin-bottom: 20px;
                padding: 20px;
            }

            .notes-section {
                width: 100%;
                height: auto;
                max-height: 300px;
            }
        }
    </style>
</head>



<body>
    <div class="main-container">
        <div class="dashboard-wrapper">
            <div class="content-container">
                <div class="attendance-section">
                    <button id="btnAbsensi" class="<?= $status ?>" <?= $status === 'selesai' ? 'disabled' : '' ?>
                        onclick="prosesAbsensi()">
                        <?= $status === 'belum' ? 'ABSEN MASUK' : ($status === 'masuk' ? 'ABSEN PULANG' : 'SUDAH ABSEN') ?>
                    </button>
                    <div class="info-status"> Status:
                        <?= $status === 'belum' ? 'Belum absen' : ($status === 'masuk' ? 'Sudah absen masuk' : 'Sudah absen pulang') ?>
                    </div>
                </div>

                <div class="notes-section">
                    <h2 class="section-title">
                        <i class="fas fa-clipboard-list"></i> Catatan Pembimbing
                    </h2>

                    <?php if (!empty($catatan_pembimbing)): ?>
                        <?php foreach ($catatan_pembimbing as $catatan): ?>
                            <div class="note-card">
                                <div class="note-header">
                                    <i class="fas fa-user-tie"></i>
                                    <?= htmlspecialchars($catatan['nama_pembimbing']) ?>
                                </div>
                                <div class="note-body">
                                    <?= htmlspecialchars($catatan['catatan']) ?>
                                </div>
                                <div class="note-footer">
                                    <i class="far fa-clock"></i>
                                    <?= formatTanggal($catatan['tanggal']) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-notes">
                            <i class="far fa-folder-open"></i> Belum ada catatan dari pembimbing
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

            if (statusSaatIni === 'belum') {
                kirimDataAbsensi('simpan_masuk');
            } else {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Absen pulang sekarang?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        kirimDataAbsensi('simpan_keluar');
                    }
                });
            }
        }

        function kirimDataAbsensi(aksi) {
            fetch('./pages/siswa/proses_absen.php', {
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
                        text: data.message,
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2000
                    });

                    const btn = document.getElementById('btnAbsensi');
                    if (aksi === 'simpan_masuk') {
                        btn.className = 'masuk';
                        btn.textContent = 'ABSEN PULANG';
                        statusSaatIni = 'masuk';
                        document.querySelector('.info-status').innerHTML = '<i class="fas fa-info-circle"></i> Status: Sudah absen masuk';
                    } else {
                        btn.className = 'selesai';
                        btn.textContent = 'SUDAH ABSEN';
                        btn.disabled = true;
                        statusSaatIni = 'selesai';
                        sudahAbsenPulang = true;
                        document.querySelector('.info-status').innerHTML = '<i class="fas fa-info-circle"></i> Status: Sudah absen pulang';
                    }
                })
                .catch(err => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: err.message,
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
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
                if (!localStorage.getItem('siswaWelcomeShown')) {
                    const namaSiswa = "<?php echo !empty($nama_siswa) ? htmlspecialchars($nama_siswa, ENT_QUOTES) : 'Siswa'; ?>";

                    setTimeout(() => {
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
                    }, 300);

                    localStorage.setItem('siswaWelcomeShown', 'true');
                }
            <?php endif; ?>
        });
    </script>
</body>

</html>