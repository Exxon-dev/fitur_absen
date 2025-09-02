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

$tanggal_filter = isset($_GET['tanggal']) ? $_GET['tanggal'] : null;

// Ambil data siswa yang dibimbing guru ini
$query_siswa = mysqli_query($coneksi, "SELECT * FROM siswa WHERE id_guru = '$id_guru' ORDER BY id_siswa ASC") or die(mysqli_error($coneksi));

// Hitung statistik
$query_jumlah_siswa = mysqli_query($coneksi, "SELECT COUNT(*) as total FROM siswa WHERE id_guru = '$id_guru'") or die(mysqli_error($coneksi));
$data_jumlah_siswa = mysqli_fetch_assoc($query_jumlah_siswa);
$jumlah_siswa = $data_jumlah_siswa['total'];

// Fungsi untuk menghitung jumlah absen berdasarkan jenis
function hitungAbsensi($coneksi, $id_siswa, $jenis, $tanggal_filter = null)
{
    if ($tanggal_filter) {
        // Jika ada filter tanggal → ambil data hanya untuk hari itu
        $query = mysqli_query($coneksi, 
            "SELECT COUNT(*) as total 
             FROM absen  
             WHERE id_siswa = '$id_siswa' 
             AND keterangan = '$jenis' 
             AND tanggal = '$tanggal_filter'");
    } else {
        // Jika tidak ada filter tanggal → ambil total keseluruhan
        $query = mysqli_query($coneksi, 
            "SELECT COUNT(*) as total 
             FROM absen  
             WHERE id_siswa = '$id_siswa' 
             AND keterangan = '$jenis'");
    }

    $data = mysqli_fetch_assoc($query);
    return $data['total'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Absensi Siswa - <?php echo htmlspecialchars($nama_guru); ?></title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

    .body-card {
      background-color: #fff;
      border-radius: 10px;
      padding: 5px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
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

<body class="body">
  <h2 class="text-primary">Data Jurnal dan Catatan Harian <?= date('d-m-Y') ?> </h2>
  <div class="body-card">
    <div class="container-fluid my-4">
      <div class="d-flex justify-content-between align-items-center">
        <!-- Tombol Tambah Siswa -->
        <a href="index.php?page=tambahsiswa_guru" class="btn btn-primary">
          <i class="fas fa-plus"></i> Tambah Siswa
        </a>
        <!-- Form Filter Tanggal -->
        <form id="filterForm" method="GET" class="form-inline">
          <input type="hidden" name="page" value="absensi_siswa" />
          <label for="filter" class="mr-2"></label>
          <?php
          $tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
          ?>
          <input type="date" name="tanggal"
            class="form-control date-picker mb-2"
            value="<?= htmlspecialchars($tanggal) ?>"
            pattern="\d{4}-\d{2}-\d{2}"
            onchange="document.getElementById('filterForm').submit();" />
        </form>
      </div>

      <div class="table-responsive mt-3">
        <table class="table table-hover table-bordered">
          <thead class="thead-primary bg-primary text-white">
            <tr class="text-center">
              <th>No</th>
              <th>Nama Siswa</th>
              <th>Hadir</th>
              <th>Sakit</th>
              <th>Izin</th>
              <th>Alpa</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            while ($data_siswa = mysqli_fetch_array($query_siswa)) {
              $id_siswa = $data_siswa['id_siswa'];

              $hadir = hitungAbsensi($coneksi, $id_siswa, 'Hadir', $tanggal_filter);
              $sakit = hitungAbsensi($coneksi, $id_siswa, 'Sakit', $tanggal_filter);
              $izin  = hitungAbsensi($coneksi, $id_siswa, 'Izin', $tanggal_filter);
              $alpa  = hitungAbsensi($coneksi, $id_siswa, 'Alpa', $tanggal_filter);
            ?>
              <tr class="text-center">
                <td><?php echo $no++; ?></td>
                <td class="text-left"><?php echo htmlspecialchars($data_siswa['nama_siswa']); ?></td>
                <td><?php echo $hadir; ?></td>
                <td><?php echo $sakit; ?></td>
                <td><?php echo $izin; ?></td>
                <td><?php echo $alpa; ?></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>