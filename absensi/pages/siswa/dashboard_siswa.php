<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include('koneksi.php');

// Cek apakah siswa sudah login
if (!isset($_SESSION['id_siswa'])) {
    header("Location: sign-in.php");
    exit();
}

$id_siswa = $_SESSION['id_siswa'];
$tanggal = date('Y-m-d');

// Ambil data siswa termasuk status password
$stmt = mysqli_prepare($coneksi, "SELECT nama_siswa, id_perusahaan, password, nis FROM siswa WHERE id_siswa = ?");
mysqli_stmt_bind_param($stmt, "i", $id_siswa);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$siswa = mysqli_fetch_assoc($result);
$nama_siswa = $siswa ? $siswa['nama_siswa'] : "Siswa";
$id_perusahaan = $siswa['id_perusahaan'] ?? null;

// Cek apakah password masih default (sama dengan NIS)
$password_default = false;
if ($siswa && $siswa['password'] === $siswa['nis']) {
    $password_default = true;
}

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

// Konfigurasi pagination untuk catatan pembimbing
$limit = 5; 
$page = isset($_GET['page_catatan']) ? (int)$_GET['page_catatan'] : 1;
$offset = ($page - 1) * $limit;

// Filter tanggal jika ada
$filter_tanggal = isset($_GET['filter_tanggal']) ? $_GET['filter_tanggal'] : '';

// Ambil catatan pembimbing - DIPERBAIKI
$catatan_pembimbing = [];
$total_catatan = 0;
if ($id_perusahaan) {
    // Query untuk total catatan - DIPERBAIKI
    $sql_count = "
        SELECT COUNT(*) as total
        FROM catatan c
        JOIN pembimbing p ON c.id_pembimbing = p.id_pembimbing
        LEFT JOIN jurnal j ON c.id_jurnal = j.id_jurnal
        LEFT JOIN siswa s ON j.id_siswa = s.id_siswa
        WHERE (p.id_perusahaan = ? OR s.id_perusahaan = ?)
    ";
    
    // Query untuk data catatan - DIPERBAIKI
    $sql_catatan = "
        SELECT 
            c.catatan,
            c.tanggal,
            p.nama_pembimbing,
            j.keterangan,
            j.tanggal as tanggal_jurnal,
            CASE 
                WHEN j.id_jurnal IS NOT NULL THEN 'Jurnal'
                ELSE 'Catatan Umum'
            END as tipe_catatan
        FROM catatan c
        JOIN pembimbing p ON c.id_pembimbing = p.id_pembimbing
        LEFT JOIN jurnal j ON c.id_jurnal = j.id_jurnal
        LEFT JOIN siswa s ON j.id_siswa = s.id_siswa
        WHERE (p.id_perusahaan = ? OR s.id_perusahaan = ?)
    ";
    
    // Tambahkan filter tanggal jika ada
    $params_count = [$id_perusahaan, $id_perusahaan];
    $params_catatan = [$id_perusahaan, $id_perusahaan];
    $param_types_count = "ii";
    $param_types_catatan = "ii";
    
    if (!empty($filter_tanggal)) {
        $sql_count .= " AND DATE(c.tanggal) = ?";
        $sql_catatan .= " AND DATE(c.tanggal) = ?";
        $params_count[] = $filter_tanggal;
        $params_catatan[] = $filter_tanggal;
        $param_types_count .= "s";
        $param_types_catatan .= "s";
    }
    
    // Hitung total catatan
    $stmt_count = mysqli_prepare($coneksi, $sql_count);
    if ($stmt_count) {
        mysqli_stmt_bind_param($stmt_count, $param_types_count, ...$params_count);
        mysqli_stmt_execute($stmt_count);
        $result_count = mysqli_stmt_get_result($stmt_count);
        $total_data = mysqli_fetch_assoc($result_count);
        $total_catatan = $total_data['total'];
    }
    
    // Query untuk data catatan dengan pagination
    $sql_catatan .= " ORDER BY c.tanggal DESC LIMIT ? OFFSET ?";
    $params_catatan[] = $limit;
    $params_catatan[] = $offset;
    $param_types_catatan .= "ii";
    
    // Ambil data catatan
    $stmt_catatan = mysqli_prepare($coneksi, $sql_catatan);
    if ($stmt_catatan) {
        mysqli_stmt_bind_param($stmt_catatan, $param_types_catatan, ...$params_catatan);
        mysqli_stmt_execute($stmt_catatan);
        $result_catatan = mysqli_stmt_get_result($stmt_catatan);
        
        if ($result_catatan) {
            $catatan_pembimbing = mysqli_fetch_all($result_catatan, MYSQLI_ASSOC);
        }
    }
}

// Hitung total halaman
$total_pages = ceil($total_catatan / $limit);

// Format tanggal function
function formatTanggal($dateString)
{
    $date = new DateTime($dateString);
    return $date->format('m-d-Y');
}

// Format tanggal untuk input date
function formatTanggalInput($dateString)
{
    $date = new DateTime($dateString);
    return $date->format('Y-m-d');
}

// Fungsi untuk membuat parameter URL - DIPERBAIKI
function buildQueryString($params = []) {
    $currentParams = $_GET;
    unset($currentParams['page_catatan']); // Hapus parameter page_catatan yang lama
    
    // Gabungkan dengan parameter baru
    $allParams = array_merge($currentParams, $params);
    
    // Pastikan parameter filter tanggal tetap ada
    if (isset($_GET['filter_tanggal']) && !isset($allParams['filter_tanggal'])) {
        $allParams['filter_tanggal'] = $_GET['filter_tanggal'];
    }
    
    return http_build_query($allParams);
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
        /* CSS tetap sama seperti sebelumnya */
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
            width: 400px;
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .notes-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .filter-form {
            display: flex;
            gap: 10px;
        }

        .filter-input {
            padding: 5px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .filter-btn {
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 15px;
            gap: 5px;
        }

        .pagination-btn {
            padding: 5px 10px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            color: #007bff;
            cursor: pointer;
        }

        .pagination-btn.active {
            background-color: #007bff;
            color: white;
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
            justify-content: space-between;
        }

        .note-header i {
            margin-right: 8px;
            font-size: 14px;
        }

        .note-jurnal {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 8px;
            padding: 5px;
            background-color: #f8f9fa;
            border-radius: 4px;
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
                max-height: 400px;
            }

            .notes-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .filter-form {
                width: 100%;
            }

            .filter-input {
                flex: 1;
            }
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 15px;
            gap: 5px;
        }
        
        .pagination-btn {
            padding: 5px 10px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            color: #007bff;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .pagination-btn:hover {
            background-color: #e9ecef;
        }
        
        .pagination-btn.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
        
        .pagination-btn.disabled {
            color: #6c757d;
            cursor: not-allowed;
            background-color: #f8f9fa;
        }
        
        .badge-jurnal {
            background-color: #28a745;
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            margin-left: 10px;
        }
        
        .badge-catatan {
            background-color: #17a2b8;
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            margin-left: 10px;
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
                    <div class="notes-header">
                        <h2 class="section-title">
                            <i class="fas fa-clipboard-list"></i> Catatan Pembimbing
                        </h2>
                        
                        <!-- FORM FILTER YANG DIPERBAIKI -->
                        <form method="GET" class="filter-form">
                            <input type="hidden" name="page" value="dashboard_siswa">
                            <input type="date" name="filter_tanggal" value="<?= htmlspecialchars($filter_tanggal) ?>" class="filter-input">
                            <button type="submit" class="filter-btn">Filter</button>
                            <?php if (!empty($filter_tanggal)): ?>
                                <a href="?page=dashboard_siswa" class="filter-btn" style="background-color: #6c757d;">Reset</a>
                            <?php endif; ?>
                        </form>
                    </div>

                    <?php if (!empty($catatan_pembimbing)): ?>
                        <?php foreach ($catatan_pembimbing as $catatan): ?>
                            <div class="note-card">
                                <div class="note-header">
                                    <span>
                                        <i class="fas fa-user-tie"></i>
                                        <?= htmlspecialchars($catatan['nama_pembimbing']) ?>
                                    </span>
                                    <span><?= formatTanggal($catatan['tanggal']) ?></span>
                                </div>
                                <?php if (!empty($catatan['keterangan'])): ?>
                                    <div class="note-jurnal">
                                        <i class="fas fa-book"></i> Jurnal: <?= htmlspecialchars($catatan['keterangan']) ?>
                                        (<?= formatTanggal($catatan['tanggal_jurnal']) ?>)
                                    </div>
                                <?php endif; ?>
                                <div class="note-body">
                                    <?= nl2br(htmlspecialchars($catatan['catatan'])) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                        <!-- Pagination yang Diperbaiki -->
                        <?php if ($total_pages > 1): ?>
                            <div class="pagination">
                                <!-- Tombol Previous -->
                                <?php if ($page > 1): ?>
                                    <a href="?<?= buildQueryString(['page_catatan' => $page - 1]) ?>" class="pagination-btn">
                                        &laquo; Prev
                                    </a>
                                <?php else: ?>
                                    <span class="pagination-btn disabled">&laquo; Prev</span>
                                <?php endif; ?>
                                
                                <!-- Nomor Halaman -->
                                <?php 
                                // Tentukan rentang halaman yang akan ditampilkan
                                $start_page = max(1, $page - 2);
                                $end_page = min($total_pages, $start_page + 4);
                                $start_page = max(1, $end_page - 4);
                                
                                for ($i = $start_page; $i <= $end_page; $i++): ?>
                                    <a href="?<?= buildQueryString(['page_catatan' => $i]) ?>" 
                                       class="pagination-btn <?= $i == $page ? 'active' : '' ?>">
                                        <?= $i ?>
                                    </a>
                                <?php endfor; ?>
                                
                                <!-- Tombol Next -->
                                <?php if ($page < $total_pages): ?>
                                    <a href="?<?= buildQueryString(['page_catatan' => $page + 1]) ?>" class="pagination-btn">
                                        Next &raquo;
                                    </a>
                                <?php else: ?>
                                    <span class="pagination-btn disabled">Next &raquo;</span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="empty-notes">
                            <i class="far fa-folder-open"></i> 
                            <?= !empty($filter_tanggal) ? 'Tidak ada catatan pada tanggal yang dipilih' : 'Belum ada catatan dari pembimbing' ?>
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
            // Fungsi untuk menampilkan alert selamat datang
            function showWelcomeAlert() {
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
                }).then(() => {
                    // Setelah alert selamat datang selesai, tampilkan peringatan jika password masih default
                    <?php if ($password_default): ?>
                    showPasswordWarning();
                    <?php endif; ?>
                });
            }

            // Fungsi untuk menampilkan peringatan password default
            function showPasswordWarning() {
                Swal.fire({
                    title: 'Peringatan Keamanan',
                    html: 'Password Anda masih menggunakan password default (NIS).<br>Silakan ubah password Anda untuk keamanan akun.',
                    icon: 'warning',
                    confirmButtonText: 'Ubah Password Sekarang',
                    confirmButtonColor: '#3085d6',
                    showCancelButton: true,
                    cancelButtonText: 'Nanti Saja',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect ke halaman ubah password
                        window.location.href = 'index.php?page=editsiswa&id_siswa=<?php echo $id_siswa; ?>';
                    }
                });
            }

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
                    setTimeout(() => {
                        showWelcomeAlert();
                    }, 300);

                    localStorage.setItem('siswaWelcomeShown', 'true');
                } else {
                    // Jika alert selamat datang sudah pernah ditampilkan,
                    // langsung tampilkan peringatan password jika diperlukan
                    <?php if ($password_default): ?>
                    setTimeout(() => {
                        showPasswordWarning();
                    }, 500);
                    <?php endif; ?>
                }
            <?php endif; ?>
        });
    </script>
</body>

</html>