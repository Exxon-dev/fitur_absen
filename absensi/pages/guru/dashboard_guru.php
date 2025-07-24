<?php
include "koneksi.php";

// Cek apakah guru udah login
if (!isset($_SESSION['id_guru'])) {
  header("Location: sign-in.php");
  exit();
}

$id_guru = $_SESSION['id_guru'];

// Ambil data guru (termasuk nama)
$getGuru = mysqli_query($coneksi, "SELECT id_sekolah, nama_guru FROM guru WHERE id_guru = '$id_guru'") or die(mysqli_error($coneksi));
$dataGuru = mysqli_fetch_assoc($getGuru);

if (!$dataGuru) {
  echo "Data guru tidak ditemukan.";
  exit();
}

$id_sekolah = $dataGuru['id_sekolah'];
$nama_guru = $dataGuru['nama_guru'];

// Query untuk mendapatkan data siswa berdasarkan id_sekolah
$query_siswa = mysqli_query($coneksi, "SELECT * FROM siswa WHERE id_sekolah = '$id_sekolah' ORDER BY id_siswa ASC") or die(mysqli_error($coneksi));

// Hanya ada satu sekolah yang relevan, jadi kita query satu sekolah saja
$query_sekolah = mysqli_query($coneksi, "SELECT * FROM sekolah WHERE id_sekolah = '$id_sekolah' LIMIT 1") or die(mysqli_error($coneksi));

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
  <title>Absensi Siswa</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <style>
    .absent {
      color: red;
    }

    .present {
      color: green;
    }

    .readonly {
      background-color: #f8f9fa;
    }

    .container {
      margin-top: 20px;
      background-color: #ffffff;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    input[type="radio"] {
      transform: scale(1.3);
      margin-right: 6px;
    }

    .status-hadir {
      color: green;
      font-weight: bold;
    }

    .status-sakit {
      color: orange;
      font-weight: bold;
    }

    .status-izin {
      color: blue;
      font-weight: bold;
    }

    .status-alpa {
      color: red;
      font-weight: bold;
    }

    .status-belum {
      color: #6c757d;
    }

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

    .radio-label {
      display: inline-flex;
      align-items: center;
      margin-right: 15px;
      cursor: pointer;
    }

    .radio-label.disabled {
      opacity: 0.7;
      cursor: not-allowed;
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
      .container {
        margin-top: 10px;
        padding: 10px;
        border-radius: 5px;
      }

      h2 {
        font-size: 1.5rem;
        margin: 20px 0 !important;
      }

      /* Card responsif */
      .col-xl-4 {
        margin-bottom: 15px;
      }

      .card {
        margin-bottom: 10px;
      }

      .card-header {
        padding: 15px !important;
      }

      .card-header h4 {
        font-size: 1.2rem;
      }

      /* Tabel responsif - hide table dan show card layout */
      .table-responsive-stack {
        display: block;
      }

      .table-responsive-stack .table {
        display: none;
      }

      .student-card {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        margin-bottom: 15px;
        padding: 15px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      }

      .student-card .student-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        flex-wrap: wrap;
      }

      .student-card .student-name {
        font-weight: bold;
        font-size: 1.1rem;
        color: #333;
        margin-bottom: 5px;
      }

      .student-card .student-number {
        color: #666;
        font-size: 0.9rem;
      }

      .student-card .status-section {
        margin-bottom: 15px;
      }

      .student-card .radio-section {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
      }

      .student-card .radio-label {
        margin-right: 0;
        margin-bottom: 8px;
        font-size: 0.9rem;
        min-width: 70px;
      }

      .student-card .radio-label input[type="radio"] {
        transform: scale(1.2);
        margin-right: 5px;
      }

      /* Badge responsif */
      .badge-status {
        font-size: 0.8rem;
        padding: 4px 8px;
        min-width: 60px;
      }

      /* Input radio lebih kecil di mobile */
      input[type="radio"] {
        transform: scale(1.1);
        margin-right: 4px;
      }
    }

    @media (min-width: 769px) {
      .student-cards {
        display: none;
      }
    }

    @media (max-width: 576px) {
      .container {
        padding: 8px;
      }

      .student-card {
        padding: 12px;
      }

      .student-card .radio-section {
        flex-direction: column;
      }

      .student-card .radio-label {
        margin-bottom: 10px;
        justify-content: flex-start;
      }

      /* Stats cards stack vertically */
      .col-xl-4 {
        margin-bottom: 10px;
      }

      h2 {
        font-size: 1.3rem;
      }
    }
  </style>
</head>

<body>

  <div class="container" style="margin-top: 20px">
    <hr>
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

    <h2 class="text-center my-4">Absensi Siswa</h2>

    <!-- Desktop Table View -->
    <div class="table-responsive d-none d-md-block">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Status</th>
            <th>Sakit</th>
            <th>Izin</th>
            <th>Alpa</th>
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

            $statusClass = 'status-belum';
            $keterangan = '-';
            $isReadOnly = true; // Always read-only in this version
            $badgeClass = '';
            $statusText = 'Belum Absen';

            if ($attendance) {
              $keterangan = $attendance['keterangan'];

              // Set warna teks dan badge berdasarkan keterangan
              if ($keterangan === 'sakit') {
                $statusClass = 'status-sakit';
                $badgeClass = 'badge-sakit';
                $statusText = 'Sakit';
              } elseif ($keterangan === 'izin') {
                $statusClass = 'status-izin';
                $badgeClass = 'badge-izin';
                $statusText = 'Izin';
              } elseif ($keterangan === 'alpa') {
                $statusClass = 'status-alpa';
                $badgeClass = 'badge-alpa';
                $statusText = 'Alpa';
              } else {
                $statusClass = 'status-hadir';
                $badgeClass = 'badge-hadir';
                $statusText = 'Hadir';
              }
            }

            echo '
              <tr class="' . ($isReadOnly ? 'readonly' : '') . '">
                  <td>' . $index . '</td>
                  <td>' . htmlspecialchars($siswa['nama_siswa']) . '</td>
                  <td><span class="badge-status ' . $badgeClass . '">' . $statusText . '</span></td>
                  <td>
                      <label class="radio-label disabled">
                        <input type="radio" id="Sakit_' . $siswa['id_siswa'] . '" name="absen_' . $siswa['id_siswa'] . '" value="sakit" ' . ($keterangan === 'sakit' ? 'checked' : '') . ' disabled>
                        <span>Sakit</span>
                      </label>
                  </td>
                  <td>
                      <label class="radio-label disabled">
                        <input type="radio" id="Izin_' . $siswa['id_siswa'] . '" name="absen_' . $siswa['id_siswa'] . '" value="izin" ' . ($keterangan === 'izin' ? 'checked' : '') . ' disabled>
                        <span>Izin</span>
                      </label>
                  </td>
                  <td>
                      <label class="radio-label disabled">
                        <input type="radio" id="Alpa_' . $siswa['id_siswa'] . '" name="absen_' . $siswa['id_siswa'] . '" value="alpa" ' . ($keterangan === 'alpa' ? 'checked' : '') . ' disabled>
                        <span>Alpa</span>
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
      // Reset query untuk mobile view
      $dataSiswa = mysqli_query($coneksi, "SELECT * FROM siswa WHERE id_sekolah = '$id_sekolah' ORDER BY id_siswa ASC") or die(mysqli_error($coneksi));
      $index = 1;
      $today = date('Y-m-d');

      while ($siswa = mysqli_fetch_assoc($dataSiswa)) {
        $attendanceQuery = mysqli_query($coneksi, "SELECT keterangan FROM absen WHERE id_siswa = {$siswa['id_siswa']} AND tanggal = '$today'") or die(mysqli_error($coneksi));
        $attendance = mysqli_fetch_assoc($attendanceQuery);

        $statusClass = 'status-belum';
        $keterangan = '-';
        $isReadOnly = true; // Always read-only in this version
        $badgeClass = '';
        $statusText = 'Belum Absen';

        if ($attendance) {
          $keterangan = $attendance['keterangan'];

          // Set warna teks dan badge berdasarkan keterangan
          if ($keterangan === 'sakit') {
            $statusClass = 'status-sakit';
            $badgeClass = 'badge-sakit';
            $statusText = 'Sakit';
          } elseif ($keterangan === 'izin') {
            $statusClass = 'status-izin';
            $badgeClass = 'badge-izin';
            $statusText = 'Izin';
          } elseif ($keterangan === 'alpa') {
            $statusClass = 'status-alpa';
            $badgeClass = 'badge-alpa';
            $statusText = 'Alpa';
          } else {
            $statusClass = 'status-hadir';
            $badgeClass = 'badge-hadir';
            $statusText = 'Hadir';
          }
        }

        echo '
          <div class="student-card">
            <div class="student-header">
              <div>
                <div class="student-name">' . htmlspecialchars($siswa['nama_siswa']) . '</div>
              </div>
              <span class="badge-status ' . $badgeClass . '">' . $statusText . '</span>
            </div>
            
            <div class="radio-section">
              <label class="radio-label disabled">
                <input type="radio" id="Sakit_mobile_' . $siswa['id_siswa'] . '" name="absen_mobile_' . $siswa['id_siswa'] . '" value="sakit" ' . ($keterangan === 'sakit' ? 'checked' : '') . ' disabled>
                <span>Sakit</span>
              </label>
              <label class="radio-label disabled">
                <input type="radio" id="Izin_mobile_' . $siswa['id_siswa'] . '" name="absen_mobile_' . $siswa['id_siswa'] . '" value="izin" ' . ($keterangan === 'izin' ? 'checked' : '') . ' disabled>
                <span>Izin</span>
              </label>
              <label class="radio-label disabled">
                <input type="radio" id="Alpa_mobile_' . $siswa['id_siswa'] . '" name="absen_mobile_' . $siswa['id_siswa'] . '" value="alpa" ' . ($keterangan === 'alpa' ? 'checked' : '') . ' disabled>
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

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Notifikasi login sukses (hanya muncul sekali)
      if (!localStorage.getItem('guruWelcomeShowed')) {
        const namaGuru = "<?php echo !empty($nama_guru) ? htmlspecialchars($nama_guru, ENT_QUOTES) : 'Guru'; ?>";

        Swal.fire({
          title: `Selamat datang ${namaGuru}!`,
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

        // Set flag bahwa alert sudah ditampilkan
        localStorage.setItem('guruWelcomeShowed', 'true');
      }

      // Untuk notifikasi lainnya (jika ada)
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