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

$tanggal = date('Y-m-d');

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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Absensi Siswa - <?php echo htmlspecialchars($nama_guru); ?></title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" />
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

<body class="row">
        <h2 class="text-primary">Data Jurnal dan Catatan Harian <?= date('d-m-Y') ?> </h2>
  <div class="body">
    <div class="body-card p-3">
      <div class="container-fluid my-4">
        <a href="index.php?page=tambahsiswa_guru" class="btn btn-primary"><i class="fas fa-plus"></i>tambah</a>
        <div class="table-responsive">
          <table class="table table-hover table-bordered">
            <thead class="thead-primary bg-primary text-white">
              <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Status</th>
                <th>Sakit</th>
                <th>Izin</th>
                <th>Alpa</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $index = 1;
              // Reset pointer result set ke awal
              mysqli_data_seek($query_siswa, 0);

              while ($siswa = mysqli_fetch_assoc($query_siswa)) {
                // Pastikan format tanggal sesuai database (YYYY-MM-DD)
                $tanggal = date('Y-m-d'); // Contoh, sesuaikan dengan kebutuhan

                // Query untuk mendapatkan data absen
                $query_absen = mysqli_query(
                  $coneksi,
                  "SELECT keterangan FROM absen 
             WHERE id_siswa = '" . $siswa['id_siswa'] . "' 
             AND tanggal = '" . $tanggal . "'"
                );

                $absen = mysqli_fetch_assoc($query_absen);
                $keterangan = isset($absen['keterangan']) ? $absen['keterangan'] : null;

                // Tentukan kelas badge dan teks status
                $badgeClass = 'badge-secondary'; // Default: Belum absen
                $statusText = 'Belum Absen';

                if ($keterangan) {
                  switch (strtolower($keterangan)) {
                    case 'hadir':
                      $badgeClass = 'badge-success';
                      $statusText = 'Hadir';
                      break;
                    case 'sakit':
                      $badgeClass = 'badge-warning';
                      $statusText = 'Sakit';
                      break;
                    case 'izin':
                      $badgeClass = 'badge-info';
                      $statusText = 'Izin';
                      break;
                    case 'alpa':
                      $badgeClass = 'badge-danger';
                      $statusText = 'Alpa';
                      break;
                  }
                }
              ?>
                <tr>
                  <td><?= $index; ?></td>
                  <td><?= htmlspecialchars($siswa['nama_siswa']); ?></td>
                  <td>
                    <span class="badge <?= $badgeClass; ?>">
                      <?= $statusText; ?>
                    </span>
                  </td>
                  <td><input type="radio" name="absen_<?= $siswa['id_siswa']; ?>" value="sakit" <?= ($keterangan === 'sakit') ? 'checked' : ''; ?> disabled></td>
                  <td><input type="radio" name="absen_<?= $siswa['id_siswa']; ?>" value="izin" <?= ($keterangan === 'izin') ? 'checked' : ''; ?> disabled></td>
                  <td><input type="radio" name="absen_<?= $siswa['id_siswa']; ?>" value="alpa" <?= ($keterangan === 'alpa') ? 'checked' : ''; ?> disabled></td>
                </tr>
              <?php
                $index++;
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>