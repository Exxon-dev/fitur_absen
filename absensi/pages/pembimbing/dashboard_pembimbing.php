<?php 
include "koneksi.php";

// Mendapatkan ID perusahaan yang sedang login
$id_pembimbing = $_SESSION['id_pembimbing'] ?? null;
$nama_pembimbing = $_SESSION['nama_pembimbing'] ?? null;

// Jika tidak ada pembimbing yang login, arahkan ke halaman sign-in
if (!$id_pembimbing) {
  header("Location: sign-in.php");
  exit();
}

$stmt = mysqli_prepare($coneksi, "SELECT nama_pembimbing FROM pembimbing WHERE id_pembimbing = ?");
mysqli_stmt_bind_param($stmt, "i", $id_pembimbing);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$pembimbing = mysqli_fetch_assoc($result);
$nama_pembimbing = $pembimbing ? $pembimbing['nama_pembimbing'] : "Pembimbing";

// Query untuk mengambil pembimbing yang terkait dengan ID pembimbing yang sedang login
$query_pembimbing = mysqli_query($coneksi, "SELECT * FROM pembimbing WHERE id_pembimbing = '$id_pembimbing'") or die(mysqli_error($coneksi));
$pembimbing = mysqli_fetch_assoc($query_pembimbing);

// Mengambil data siswa yang terkait dengan pembimbing yang sedang login
$query_siswa = mysqli_query($coneksi, "SELECT * FROM siswa WHERE id_pembimbing = '$id_pembimbing' ORDER BY id_siswa ASC") or die(mysqli_error($coneksi));

// Mengambil data sekolah
$query_sekolah = mysqli_query($coneksi, "SELECT * FROM sekolah ORDER BY id_sekolah ASC") or die(mysqli_error($coneksi));

// Menampilkan jumlah siswa dan sekolah yang terkait dengan pembimbing
$jumlah_siswa = mysqli_num_rows($query_siswa);
$jumlah_sekolah = mysqli_num_rows($query_sekolah);
$jumlah_pembimbing = mysqli_num_rows($query_pembimbing);

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
  </style>
</head>

<body>

  <div class="container main-content" style="margin-top: 20px;">
    <hr>
    <form method="POST" action="">
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
                  <p class="text-sm mb-0 text-capitalize">Pembimbing</p>
                  <h4 class="mb-0"><?php echo $jumlah_pembimbing; ?></h4>
                </div>
              </div>
              <hr class="dark horizontal my-0">
            </div>
          </div>
        </div>
      </div>

      <h2 class="text-center my-4">Absensi Siswa</h2>
      
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Status</th>
            <th>Sakit</th>
            <th>Izin</th>
            <th>Alpa</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $dataSiswa = mysqli_query($coneksi, "SELECT * FROM siswa WHERE id_pembimbing = '$id_pembimbing' ORDER BY id_siswa ASC") or die(mysqli_error($coneksi));
          $index = 1;
          $today = date('Y-m-d');

          while ($siswa = mysqli_fetch_assoc($dataSiswa)) {
            $attendanceQuery = mysqli_query($coneksi, "SELECT keterangan FROM absen WHERE id_siswa = {$siswa['id_siswa']} AND tanggal = '$today'") or die(mysqli_error($coneksi));
            $attendance = mysqli_fetch_assoc($attendanceQuery);

            $statusClass = 'status-belum';
            $keterangan = '-';
            $isReadOnly = false;
            $badgeClass = '';
            $statusText = 'Belum Absen';

            if ($attendance) {
              $keterangan = $attendance['keterangan'];
              $isReadOnly = true;

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
                  <td>
                      <button type="submit" name="simpan_' . $siswa['id_siswa'] . '" class="btn btn-primary btn-sm" ' . ($isReadOnly ? 'disabled' : '') . '>Simpan</button>
                  </td>
              </tr>
            ';
            $index++;
          }
          ?>
        </tbody>
      </table>
    </form>

    <?php
    // Proses penyimpanan data absensi
    foreach ($_POST as $key => $value) {
      if (strpos($key, 'simpan_') === 0) {
        $id_siswa = str_replace('simpan_', '', $key);
        $keterangan = mysqli_real_escape_string($coneksi, $_POST['absen_' . $id_siswa]);
        $tanggal = date('Y-m-d');
        $jamKeluar = date('H:i:s');
        $jamMasuk = date('H:i:s');

        // Cek jika data sudah ada di database
        $checkQuery = mysqli_query($coneksi, "SELECT * FROM absen WHERE id_siswa = '$id_siswa' AND tanggal = '$tanggal'");

        if (mysqli_num_rows($checkQuery) > 0) {
          // Jika ada, update data
          $updateQuery = "UPDATE absen SET keterangan = '$keterangan', jam_keluar = '$jamKeluar' WHERE id_siswa = '$id_siswa' AND tanggal = '$tanggal'";
          $result = mysqli_query($coneksi, $updateQuery);

          if ($result) {
            $_SESSION['flash_edit'] = 'sukses';
            echo '<script>
              document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                  icon: "success",
                  title: "Sukses!",
                  text: "Absen siswa berhasil diupdate",
                  position: "top",
                  showConfirmButton: false,
                  timer: 3000,
                  toast: true
                });
              });
            </script>';
          } else {
            echo '<div class="alert alert-warning">Gagal memperbarui data.</div>';
          }
        } else {
          // Jika tidak ada, insert data baru
          $insertQuery = "INSERT INTO absen (id_siswa, tanggal, keterangan, jam_masuk) VALUES ('$id_siswa', '$tanggal', '$keterangan', '$jamMasuk')";
          $result = mysqli_query($coneksi, $insertQuery);

          if ($result) {
            $_SESSION['flash_edit'] = 'sukses';
            echo '<script>
              document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                  icon: "success",
                  title: "Sukses!",
                  text: "Absen siswa berhasil disimpan",
                  position: "top",
                  showConfirmButton: false,
                  timer: 3000,
                  toast: true
                });
              });
            </script>';
          } else {
            echo '<div class="alert alert-warning">Gagal menyimpan data.</div>';
          }
        }
      }
    }
    ?>
  </div>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const namaPembimbing = "<?php echo !empty($nama_pembimbing) ? htmlspecialchars($nama_pembimbing, ENT_QUOTES) : 'Pembimbing'; ?>";
      
      // Cek apakah alert sudah pernah ditampilkan
      if (!localStorage.getItem('pembimbingAlertShown')) {
        Swal.fire({
          title: `Selamat datang ${namaPembimbing}!`,
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
        
        // Set flag di localStorage bahwa alert sudah ditampilkan
        localStorage.setItem('pembimbingAlertShown', 'true');
      }
    });
  </script>
</body>
</html>