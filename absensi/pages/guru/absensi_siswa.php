<?php
include "koneksi.php";

// Cek login guru
if (!isset($_SESSION['id_guru'])) {
  header("Location: sign-in.php");
  exit();
}

$id_guru = $_SESSION['id_guru'];

// Ambil data guru & sekolah
$stmt = mysqli_prepare($coneksi, "SELECT id_sekolah, nama_guru FROM guru WHERE id_guru = ?");
mysqli_stmt_bind_param($stmt, "i", $id_guru);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$dataGuru = mysqli_fetch_assoc($result);

if (!$dataGuru) {
  header("Location: sign-in.php");
  exit();
}

$id_sekolah = $dataGuru['id_sekolah'];
$nama_guru = $dataGuru['nama_guru'];

$tanggal = date('m-d-Y');

// Ambil data siswa yang dibimbing guru ini
$query_siswa = mysqli_query($coneksi, "SELECT * FROM siswa WHERE id_guru = '$id_guru' ORDER BY id_siswa ASC") or die(mysqli_error($coneksi));

// Hitung statistik
$query_jumlah_siswa = mysqli_query($coneksi, "SELECT COUNT(*) as total FROM siswa WHERE id_guru = '$id_guru'") or die(mysqli_error($coneksi));
$data_jumlah_siswa = mysqli_fetch_assoc($query_jumlah_siswa);
$jumlah_siswa = $data_jumlah_siswa['total'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Absensi Siswa - <?php echo htmlspecialchars($nama_guru); ?></title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <style>
    /* Penyesuaian posisi */
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
    }

    /* Style asli */
    .container-custom {
      background-color: #ffffff;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    h3 {
      color: #007bff
    }

    h2 {
      color: #007bff
    }

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

    .table-responsive {
      margin-top: 20px;
    }

    .absent {
      color: red;
    }

    .present {
      color: green;
    }

    .readonly {
      background-color: #f8f9fa;
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

    .btn-wa {
      background-color: #25D366;
      color: white;
    }

    .btn-wa:hover {
      background-color: #128C7E;
      color: white;
    }

    .table-light th {
      background-color: #007bff;
      color: white;
    }

    .tabletbody tr:hover {
      background-color: #e9ecef;
    }

    .table-responsive {
      border: none !important;
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
    }

    .table td,
    .table th {
      border: 1px solid #dee2e6 !important;
      vertical-align: middle;
    }

    /* ===== Responsif untuk layar kecil (mobile/tablet) ===== */
    @media (max-width: 768px) {
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

    @media (max-width: 991px) {
      body {
        padding-left: 0;
        /* hilangkan padding kiri agar konten muat penuh */
      }
      }
    }
  </style>
</head>

<body>
  <h2 class="text-left my-4">Absensi Siswa <?= htmlspecialchars($tanggal) ?></h2>
  <!-- Main content -->
  <div class="main-container container-custom">
    <form method="POST" action="">
      <div class="container-fluid py-4">
        <div class="row">
          <div class="table-responsive">
            <table class="table table-hover table-bordered">
              <thead class="table-light">
                <tr>
                  <th class="text-center">No</th>
                  <th class="text-center">Nama</th>
                  <th class="text-center">Status</th>
                  <th class="text-center">Sakit</th>
                  <th class="text-center">Izin</th>
                  <th class="text-center">Alpa</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if (mysqli_num_rows($query_siswa) > 0) {
                  $index = 1;
                  $today = date('Y-m-d');

                  while ($siswa = mysqli_fetch_assoc($query_siswa)) {
                    $attendanceQuery = mysqli_query($coneksi, "SELECT keterangan FROM absen WHERE id_siswa = {$siswa['id_siswa']} AND tanggal = '$today'") or die(mysqli_error($coneksi));
                    $attendance = mysqli_fetch_assoc($attendanceQuery);

                    $statusClass = 'status-belum';
                    $keterangan = '-';
                    $isReadOnly = false;
                    $badgeClass = 'badge-belum';
                    $statusText = 'Belum Absen';

                    if ($attendance) {
                      $keterangan = $attendance['keterangan'];
                      $isReadOnly = true;

                      switch ($keterangan) {
                        case 'sakit':
                          $statusClass = 'status-sakit';
                          $badgeClass = 'badge-sakit';
                          $statusText = 'Sakit';
                          break;
                        case 'izin':
                          $statusClass = 'status-izin';
                          $badgeClass = 'badge-izin';
                          $statusText = 'Izin';
                          break;
                        case 'alpa':
                          $statusClass = 'status-alpa';
                          $badgeClass = 'badge-alpa';
                          $statusText = 'Alpa';
                          break;
                        default:
                          $statusClass = 'status-hadir';
                          $badgeClass = 'badge-hadir';
                          $statusText = 'Hadir';
                      }
                    }

                    echo '
                <tr class="' . ($isReadOnly ? 'readonly' : '') . '">
                    <td class="text-center">' . $index . '</td>
                    <td>' . htmlspecialchars($siswa['nama_siswa']) . '</td>
                    <td><span class="badge-status ' . $badgeClass . '">' . $statusText . '</span></td>
                    <td>
                        <label class="radio-label ' . ($isReadOnly ? 'disabled' : '') . '">
                          <input type="radio" id="Sakit_' . $siswa['id_siswa'] . '" name="absen_' . $siswa['id_siswa'] . '" value="sakit" ' . ($keterangan === 'sakit' ? 'checked' : '') . ($isReadOnly ? ' disabled' : '') . '>
                          <span>Sakit</span>
                        </label>
                    </td>
                    <td>
                        <label class="radio-label ' . ($isReadOnly ? 'disabled' : '') . '">
                          <input type="radio" id="Izin_' . $siswa['id_siswa'] . '" name="absen_' . $siswa['id_siswa'] . '" value="izin" ' . ($keterangan === 'izin' ? 'checked' : '') . ($isReadOnly ? ' disabled' : '') . '>
                          <span>Izin</span>
                        </label>
                    </td>
                    <td>
                        <label class="radio-label ' . ($isReadOnly ? 'disabled' : '') . '">
                          <input type="radio" id="Alpa_' . $siswa['id_siswa'] . '" name="absen_' . $siswa['id_siswa'] . '" value="alpa" ' . ($keterangan === 'alpa' ? 'checked' : '') . ($isReadOnly ? ' disabled' : '') . '>
                          <span>Alpa</span>
                        </label>
                    </td>
                </tr>
                ';
                    $index++;
                  }
                } else {
                  echo '<tr><td colspan="6" class="text-center">Tidak ada siswa yang dibimbing</td></tr>';
                }
                ?>
              </tbody>
            </table>

            <?php if (mysqli_num_rows($query_siswa) > 0) : ?>
              <div class="mt-3 text-right">
                <button type="submit" name="simpan_semua" class="btn btn-primary">Simpan Semua</button>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </form>

    <?php
    if (isset($_POST['simpan_semua'])) {
      foreach ($_POST as $key => $value) {
        if (strpos($key, 'absen_') === 0) {
          $id_siswa = str_replace('absen_', '', $key);
          $keterangan = mysqli_real_escape_string($coneksi, $value);
          $tanggal = date('Y-m-d');
          $jam_masuk = date('H:i:s');
          $jam_keluar = date('H:i:s');

          // Cek jika data sudah ada di database
          $checkQuery = mysqli_query($coneksi, "SELECT * FROM absen WHERE id_siswa = '$id_siswa' AND tanggal = '$tanggal'");

          if (mysqli_num_rows($checkQuery) > 0) {
            // Update data jika sudah ada
            $updateQuery = "UPDATE absen SET keterangan = '$keterangan', jam_keluar = '$jam_keluar' WHERE id_siswa = '$id_siswa' AND tanggal = '$tanggal'";
            $result = mysqli_query($coneksi, $updateQuery);
          } else {
            // Insert data baru
            $insertQuery = "INSERT INTO absen (id_siswa, tanggal, keterangan, jam_masuk, jam_keluar) VALUES ('$id_siswa', '$tanggal', '$keterangan', '$jam_masuk', '$jam_keluar')";
            $result = mysqli_query($coneksi, $insertQuery);
          }
        }
      }

      $_SESSION['show_alert'] = true;
      echo '<script>window.location.href = "index.php?page=absensi_siswa";</script>';
      exit();
    }

    if (isset($_SESSION['show_alert'])) {
      echo '<script>
      document.addEventListener("DOMContentLoaded", function() {
          Swal.fire({
              icon: "success",
              title: "Sukses!",
              text: "Absensi berhasil disimpan untuk semua siswa",
              position: "top",
              showConfirmButton: false,
              timer: 3000,
              toast: true
          });
      });
      </script>';
      unset($_SESSION['show_alert']);
    }
    ?>
  </div>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>