<?php
include "koneksi.php";

// Cek apakah guru sudah login
if (!isset($_SESSION['id_guru'])) {
  header("Location: sign-in.php");
  exit();
}

$id_guru = $_SESSION['id_guru'];

// Ambil data guru (termasuk nama) dengan prepared statement
$stmt = mysqli_prepare($coneksi, "SELECT id_sekolah, nama_guru FROM guru WHERE id_guru = ?");
mysqli_stmt_bind_param($stmt, "i", $id_guru);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$dataGuru = mysqli_fetch_assoc($result);

if (!$dataGuru) {
  echo "<script>
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Data guru tidak ditemukan',
      }).then(() => {
        window.location.href = 'sign-in.php';
      });
    </script>";
  exit();
}

$id_sekolah = $dataGuru['id_sekolah'];
$nama_guru = $dataGuru['nama_guru'];

$tanggal = date('Y-m-d');

// Ambil nama sekolah
$query_sekolah = mysqli_query($coneksi, "SELECT nama_sekolah FROM sekolah WHERE id_sekolah = '$id_sekolah'");
$sekolah = mysqli_fetch_assoc($query_sekolah);
$nama_sekolah = $sekolah['nama_sekolah'] ?? 'Sekolah';

// Query untuk mendapatkan data siswa dengan prepared statement
$stmt = mysqli_prepare($coneksi, "SELECT * FROM siswa WHERE id_sekolah = ? ORDER BY id_siswa ASC");
mysqli_stmt_bind_param($stmt, "i", $id_sekolah);
mysqli_stmt_execute($stmt);
$query_siswa = mysqli_stmt_get_result($stmt);

// Mengambil data sekolah
$query_sekolah = mysqli_query($coneksi, "SELECT * FROM sekolah ORDER BY id_sekolah ASC") or die(mysqli_error($coneksi));

// Query perusahaan
$query_perusahaan = mysqli_query($coneksi, "SELECT * FROM perusahaan ORDER BY id_perusahaan ASC") or die(mysqli_error($coneksi));

$jumlah_siswa = mysqli_num_rows($query_siswa);
$jumlah_sekolah = mysqli_num_rows($query_sekolah);
$jumlah_perusahaan = mysqli_num_rows($query_perusahaan);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Absensi Siswa - <?php echo htmlspecialchars($nama_guru); ?></title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    /* Improved CSS with better organization */
    :root {
      --primary-color: #007bff;
      --success-color: #28a745;
      --info-color: #17a2b8;
      --warning-color: #ffc107;
      --danger-color: #dc3545;
      --light-color: #f8f9fa;
      --dark-color: #343a40;
    }

    body {
      padding-left: 270px;
      transition: padding-left 0.3s;
      background-color: var(--light-color);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .main-container {
      margin-top: 20px;
      margin-right: 20px;
      margin-left: 0;
      width: auto;
      max-width: none;
    }

    .container-custom {
      background-color: #ffffff;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    h3 {
      color: #007bff;
      font-weight: 600;
    }


    /* Status Badges */
    .badge-status {
      padding: 5px 10px;
      border-radius: 20px;
      font-size: 0.9em;
      font-weight: bold;
      display: inline-block;
      text-align: center;
      min-width: 70px;
    }

    .badge-sakit {
      background-color: #FFE0B2;
      color: #E65100;
    }

    .badge-izin {
      background-color: #BBDEFB;
      color: #0D47A1;
    }

    .badge-alpa {
      background-color: #FFCDD2;
      color: #B71C1C;
    }

    .badge-hadir {
      background-color: #C8E6C9;
      color: #1B5E20;
    }

    .badge-belum {
      background-color: #E0E0E0;
      color: #424242;
    }

    /* Radio Buttons */
    .radio-label {
      display: inline-flex;
      align-items: center;
      margin-right: 15px;
      cursor: pointer;
      transition: all 0.2s;
    }

    .radio-label:hover {
      opacity: 0.8;
    }

    .radio-label.disabled {
      opacity: 0.7;
      cursor: not-allowed;
    }

    input[type="radio"] {
      transform: scale(1.3);
      margin-right: 6px;
    }

    /* Table Styles */
    .table-responsive {
      margin-top: 20px;
    }

    .table thead th {
      background-color: var(--primary-color);
      color: white;
      border: none;
    }

    .table tbody tr:hover {
      background-color: rgba(0, 123, 255, 0.05);
    }

    /* Card Styles */
    .card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s;
    }

    .card:hover {
      transform: translateY(-2px);
    }

    .card-header {
      border-radius: 10px 10px 0 0 !important;
      background-color: white;
      border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    /* Mobile Card View */
    .student-cards {
      display: none;
    }

    .student-card {
      background: white;
      border-radius: 8px;
      padding: 15px;
      margin-bottom: 15px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .student-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;
    }

    .student-name {
      font-weight: bold;
    }

    .radio-section {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
    }

    /* Responsive Styles */
    @media (max-width: 991px) {
      body {
        padding-left: 0;
      }

      .main-container {
        margin-right: 15px;
        margin-left: 15px;
      }

      .student-cards {
        display: block;
      }
    }

    @media (max-width: 768px) {
      h2 {
        font-size: 1.5rem;
      }

      .card-header {
        padding: 15px !important;
      }

      .card-header h4 {
        font-size: 1.2rem;
      }

      .student-card {
        padding: 12px;
      }

      .radio-section {
        flex-direction: column;
      }

      .radio-label {
        margin-bottom: 10px;
      }

      input[type="radio"] {
        transform: scale(1.1);
      }
    }

    /* Refresh Indicator */
    .refresh-indicator {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background-color: var(--primary-color);
      color: white;
      padding: 10px 15px;
      border-radius: 50px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
      display: flex;
      align-items: center;
      z-index: 1000;
    }

    .refresh-indicator i {
      margin-right: 8px;
      animation: spin 2s linear infinite;
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }

    h2 {
      color: #007bff;
      font-weight: 550
    }
  </style>
</head>

<body>
  <div class="main-container container-custom">
    <div class="text-center">
      <h3>Dashboard Guru</h3>
      <h3><?php echo htmlspecialchars($nama_sekolah, ENT_QUOTES); ?></h3>
    </div>
    <hr>

    <!-- Stats Cards -->
    <div class="container-fluid py-4">
      <div class="container-fluid py-4">
        <div class="row">
          <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
              <div class="card-header p-3 pt-2">
                <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                  <i class="material-icons opacity-10">group</i>
                </div>
                <div class="text-end pt-1">
                  <p class="text-sm mb-0 text-capitalize">Siswa</p>
                  <h4 class="mb-0"><?php echo $jumlah_siswa; ?></h4>
                </div>
              </div>
              <hr class="dark horizontal my-0">
            </div>
          </div>
          <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
              <div class="card-header p-3 pt-2">
                <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                  <i class="material-icons opacity-10">school</i>
                </div>
                <div class="text-end pt-1">
                  <p class="text-sm mb-0 text-capitalize">Sekolah</p>
                  <h4 class="mb-0"><?php echo $jumlah_sekolah; ?></h4>
                </div>
              </div>
              <hr class="dark horizontal my-0">
            </div>
          </div>
          <div class="col-xl-4 col-sm-6">
            <div class="card">
              <div class="card-header p-3 pt-2">
                <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                  <i class="material-icons opacity-10">location_city</i>
                </div>
                <div class="text-end pt-1">
                  <p class="text-sm mb-0 text-capitalize">Perusahaan</p>
                  <h4 class="mb-0"><?php echo $jumlah_perusahaan; ?></h4>
                </div>
              </div>
              <hr class="dark horizontal my-0">
            </div>
          </div>
        </div>
      </div>
    </div>

    <h2 class="text-center my-4">Absensi Siswa <?= htmlspecialchars($tanggal) ?></h2>

    <!-- Desktop Table View -->
    <div class="table-responsive d-none d-md-block">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Siswa</th>
            <th>Status</th>
            <th>Sakit</th>
            <th>Izin</th>
            <th>Alpa</th>
            <th>Hadir</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $dataSiswa = mysqli_query($coneksi, "SELECT * FROM siswa WHERE id_sekolah = '$id_sekolah' ORDER BY id_siswa ASC") or die(mysqli_error($coneksi));
          $index = 1;
          $today = date('Y-m-d');

          while ($siswa = mysqli_fetch_assoc($dataSiswa)) {
            $attendanceQuery = mysqli_query($coneksi, "SELECT keterangan FROM absen WHERE id_siswa = {$siswa['id_siswa']} AND tanggal = '$today'") or die(mysqli_error($coneksi));
            $attendance = mysqli_fetch_assoc($attendanceQuery);

            $keterangan = $attendance['keterangan'] ?? null;
            $badgeClass = 'badge-belum';
            $statusText = 'Belum Absen';

            if ($keterangan) {
              switch ($keterangan) {
                case 'sakit':
                  $badgeClass = 'badge-sakit';
                  $statusText = 'Sakit';
                  break;
                case 'izin':
                  $badgeClass = 'badge-izin';
                  $statusText = 'Izin';
                  break;
                case 'alpa':
                  $badgeClass = 'badge-alpa';
                  $statusText = 'Alpa';
                  break;
                default:
                  $badgeClass = 'badge-hadir';
                  $statusText = 'Hadir';
              }
            }

            echo '
              <tr>
                <td>' . $index . '</td>
                <td>' . htmlspecialchars($siswa['nama_siswa']) . '</td>
                <td><span class="badge-status ' . $badgeClass . '">' . $statusText . '</span></td>
                <td>
                  <label class="radio-label disabled">
                    <input type="radio" name="absen_' . $siswa['id_siswa'] . '" value="sakit" ' . ($keterangan === 'sakit' ? 'checked' : '') . ' disabled>
                    <span>Sakit</span>
                  </label>
                </td>
                <td>
                  <label class="radio-label disabled">
                    <input type="radio" name="absen_' . $siswa['id_siswa'] . '" value="izin" ' . ($keterangan === 'izin' ? 'checked' : '') . ' disabled>
                    <span>Izin</span>
                  </label>
                </td>
                <td>
                  <label class="radio-label disabled">
                    <input type="radio" name="absen_' . $siswa['id_siswa'] . '" value="alpa" ' . ($keterangan === 'alpa' ? 'checked' : '') . ' disabled>
                    <span>Alpa</span>
                  </label>
                </td>
                <td>
                  <label class="radio-label disabled">
                    <input type="radio" name="absen_' . $siswa['id_siswa'] . '" value="hadir" ' . (!$keterangan || $keterangan === 'hadir' ? 'checked' : '') . ' disabled>
                    <span>Hadir</span>
                  </label>
                </td>
              </tr>
              ';
            $index++;
          }
          ?>
        </tbody>
      </table>
    </div>

    <!-- Mobile Card View -->
    <div class="student-cards d-block d-md-none">
      <?php
      $dataSiswa = mysqli_query($coneksi, "SELECT * FROM siswa WHERE id_guru = '$id_guru' ORDER BY id_siswa ASC") or die(mysqli_error($coneksi));
      $index = 1;
      $today = date('Y-m-d');

      while ($siswa = mysqli_fetch_assoc($dataSiswa)) {
        $attendanceQuery = mysqli_query($coneksi, "SELECT keterangan FROM absen WHERE id_siswa = {$siswa['id_siswa']} AND tanggal = '$today'") or die(mysqli_error($coneksi));
        $attendance = mysqli_fetch_assoc($attendanceQuery);

        $keterangan = $attendance['keterangan'] ?? null;
        $badgeClass = 'badge-belum';
        $statusText = 'Belum Absen';

        if ($keterangan) {
          switch ($keterangan) {
            case 'sakit':
              $badgeClass = 'badge-sakit';
              $statusText = 'Sakit';
              break;
            case 'izin':
              $badgeClass = 'badge-izin';
              $statusText = 'Izin';
              break;
            case 'alpa':
              $badgeClass = 'badge-alpa';
              $statusText = 'Alpa';
              break;
            default:
              $badgeClass = 'badge-hadir';
              $statusText = 'Hadir';
          }
        }

        echo '
          <div class="student-card">
            <div class="student-header">
              <div>
                <div class="student-name">' . $index . '. ' . htmlspecialchars($siswa['nama_siswa']) . '</div>
              </div>
              <span class="badge-status ' . $badgeClass . '">' . $statusText . '</span>
            </div>
            
            <div class="radio-section">
              <label class="radio-label disabled">
                <input type="radio" name="absen_mobile_' . $siswa['id_siswa'] . '" value="hadir" ' . (!$keterangan || $keterangan === 'hadir' ? 'checked' : '') . ' disabled>
                <span>Hadir</span>
              </label>
              <label class="radio-label disabled">
                <input type="radio" name="absen_mobile_' . $siswa['id_siswa'] . '" value="sakit" ' . ($keterangan === 'sakit' ? 'checked' : '') . ' disabled>
                <span>Sakit</span>
              </label>
              <label class="radio-label disabled">
                <input type="radio" name="absen_mobile_' . $siswa['id_siswa'] . '" value="izin" ' . ($keterangan === 'izin' ? 'checked' : '') . ' disabled>
                <span>Izin</span>
              </label>
              <label class="radio-label disabled">
                <input type="radio" name="absen_mobile_' . $siswa['id_siswa'] . '" value="alpa" ' . ($keterangan === 'alpa' ? 'checked' : '') . ' disabled>
                <span>Alpa</span>
              </label>
            </div>
          </div>
          ';
        $index++;
      }
      ?>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Welcome notification
      if (!localStorage.getItem('guruWelcomeShowed')) {
        const namaGuru = "<?php echo !empty($nama_guru) ? htmlspecialchars($nama_guru, ENT_QUOTES) : 'Guru'; ?>";

        Swal.fire({
          title: `Selamat datang, ${namaGuru}!`,
          text: "Anda berhasil login ke sistem",
          icon: 'success',
          position: 'top-end',
          showConfirmButton: false,
          timer: 2000,
          timerProgressBar: true,
          toast: true,
          background: '#f8f9fa'
        });

        localStorage.setItem('guruWelcomeShowed', 'true');
      }

      // Countdown for auto-refresh
      let seconds = 30;
      const countdownElement = document.getElementById('countdown');
      const countdownInterval = setInterval(() => {
        seconds--;
        countdownElement.textContent = seconds;

        if (seconds <= 0) {
          clearInterval(countdownInterval);
          location.reload();
        }
      }, 1000);

      // Show notification if any
      <?php if (isset($_GET['pesan'])): ?>
        <?php if ($_GET['pesan'] == 'sukses'): ?>
          Swal.fire({
            icon: 'success',
            title: 'Sukses!',
            text: 'Operasi berhasil dilakukan',
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
        <?php endif; ?>
      <?php endif; ?>
    });
  </script>
</body>

</html>