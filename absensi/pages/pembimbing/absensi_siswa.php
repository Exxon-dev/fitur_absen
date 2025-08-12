<?php
include "koneksi.php";

if (!isset($_SESSION['id_pembimbing'])) {
  header("Location: sign-in.php");
  exit();
}

$id_pembimbing = $_SESSION['id_pembimbing'];

$stmt = mysqli_prepare($coneksi, "SELECT p.nama_pembimbing, p.id_perusahaan, pr.nama_perusahaan 
                                FROM pembimbing p
                                LEFT JOIN perusahaan pr ON p.id_perusahaan = pr.id_perusahaan
                                WHERE p.id_pembimbing = ?");
mysqli_stmt_bind_param($stmt, "i", $id_pembimbing);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$pembimbing = mysqli_fetch_assoc($result);

if (!isset($_SESSION['id_pembimbing'])) {
  header("Location: sign-in.php");
  exit();
}

$tanggal = date('Y-m-d');
$nama_pembimbing = $pembimbing['nama_pembimbing'];
$id_perusahaan = $pembimbing['id_perusahaan'];
$nama_perusahaan = $pembimbing['nama_perusahaan'];

// Proses simpan absensi
if (isset($_POST['simpan_semua'])) {
  foreach ($_POST as $key => $value) {
    if (strpos($key, 'absen_') === 0) {
      $id_siswa = str_replace('absen_', '', $key);
      $keterangan = mysqli_real_escape_string($coneksi, $value);
      $tanggal = date('Y-m-d');
      $jamMasuk = date('H:i:s');
      $jamKeluar = date('H:i:s');

      $checkQuery = mysqli_query($coneksi, "SELECT * FROM absen WHERE id_siswa = '$id_siswa' AND tanggal = '$tanggal'");

      if (mysqli_num_rows($checkQuery) > 0) {
        $updateQuery = "UPDATE absen SET keterangan = '$keterangan', jam_keluar = '$jamKeluar' WHERE id_siswa = '$id_siswa' AND tanggal = '$tanggal'";
        mysqli_query($coneksi, $updateQuery);
      } else {
        $insertQuery = "INSERT INTO absen (id_siswa, tanggal, keterangan, jam_masuk, jam_keluar) VALUES ('$id_siswa', '$tanggal', '$keterangan', '$jamMasuk', '$jamKeluar')";
        mysqli_query($coneksi, $insertQuery);
      }
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <title>Absensi Siswa Pembimbing</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />
  <style>
    body {
      padding-left: 270px;
      /* tetap untuk desktop */
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      /* hilangkan margin default */
    }

    .body-card {
      background-color: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
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

    .badge-belum {
      background-color: #E0E0E0;
      color: #424242;
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

    @media (max-width: 768px) {
      body {
        padding-left: 0;
      }

      .body-card {
        padding: 15px;
        margin-bottom: 15px;
      }

      .table-responsive {
        overflow-x: scroll;
      }

      .table td,
      .table th {
        font-size: 14px;
        padding: 8px;
      }

      .badge-sakit,
      .badge-izin,
      .badge-alpa,
      .badge-belum {
        font-size: 0.8rem;
        padding: 4px 8px;
      }
    }
  </style>
</head>

<body class="row">
  <div class="body">
    <h2 class="text-primary">Absesi Siswa</h2>
    <div class="body-card">
      <div class="container my-4">
        <div class="table-responsive">
          <table class="table table-hover table-bordered">
            <thead class="thead-primary bg-primary text-white">
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
              $dataSiswa = mysqli_query($coneksi, "SELECT * FROM siswa WHERE id_perusahaan = '$id_perusahaan' ORDER BY id_siswa ASC");

              if (mysqli_num_rows($dataSiswa) > 0) {
                $index = 1;
                $today = date('Y-m-d');

                while ($siswa = mysqli_fetch_assoc($dataSiswa)) {
                  $attendanceQuery = mysqli_query($coneksi, "SELECT keterangan FROM absen WHERE id_siswa = {$siswa['id_siswa']} AND tanggal = '$today'");
                  $attendance = mysqli_fetch_assoc($attendanceQuery);

                  $statusClass = 'status-belum';
                  $keterangan = '-';
                  $isReadOnly = false;
                  $badgeClass = '';
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
                          <td>' . $index . '</td>
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
                      </tr>';
                  $index++;
                }
              } else {
                echo '<tr><td colspan="6" class="text-center">Tidak ada siswa di perusahaan ini</td></tr>';
              }
              ?>
            </tbody>
          </table>

          <?php if (mysqli_num_rows($dataSiswa) > 0) : ?>
            <div class="mt-3 text-right">
              <button type="submit" name="simpan_semua" class="btn btn-primary">Simpan Semua</button>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  </form>

  <?php if (isset($_SESSION['show_alert'])) : ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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
    </script>
    <?php unset($_SESSION['show_alert']); ?>
  <?php endif; ?>
  </div>
</body>

</html>