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

if (!$pembimbing) {
  session_destroy();
  header("Location: sign-in.php");
  exit();
}

$nama_pembimbing = $pembimbing['nama_pembimbing'];
$id_perusahaan = $pembimbing['id_perusahaan'];
$nama_perusahaan = $pembimbing['nama_perusahaan'];

// Query untuk jumlah siswa di perusahaan ini
$query_siswa = mysqli_query($coneksi, "SELECT COUNT(*) as total FROM siswa WHERE id_perusahaan = '$id_perusahaan'");
$jumlah_siswa = mysqli_fetch_assoc($query_siswa)['total'];

// Query untuk jumlah jurnal dari siswa di perusahaan ini HARI INI
$today = date('Y-m-d');
$query_jurnal = mysqli_query($coneksi, "SELECT COUNT(*) as total 
                                        FROM jurnal j
                                        JOIN siswa s ON j.id_siswa = s.id_siswa
                                        WHERE s.id_perusahaan = '$id_perusahaan'
                                        AND j.tanggal = '$today'");
$jumlah_jurnal = mysqli_fetch_assoc($query_jurnal)['total'];

// Query untuk siswa yang belum absen hari ini
$query_belum_hadir = mysqli_query($coneksi, "SELECT COUNT(*) as total 
                                            FROM siswa s
                                            WHERE s.id_perusahaan = '$id_perusahaan'
                                            AND s.id_siswa NOT IN (
                                                SELECT id_siswa FROM absen WHERE tanggal = '$today'
                                            )");
$jumlah_belum_hadir = mysqli_fetch_assoc($query_belum_hadir)['total'];

// Query untuk jumlah jurnal total (opsional - jika ingin menampilkan juga)
$query_jurnal_total = mysqli_query($coneksi, "SELECT COUNT(*) as total 
                                             FROM jurnal j
                                             JOIN siswa s ON j.id_siswa = s.id_siswa
                                             WHERE s.id_perusahaan = '$id_perusahaan'");
$jumlah_jurnal_total = mysqli_fetch_assoc($query_jurnal_total)['total'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard Pembimbing</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      padding-left: 270px;
      transition: padding-left 0.3s;
      background-color: #f8f9fa;
    }

    .main-container {
      margin-top: 20px;
      margin-right: 20px;
      margin-left: 0;
      max-width: none;
    }

    .container-custom {
      background-color: #ffffff;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .card {
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
    }

    .card:hover {
      transform: translateY(-4px);
    }

    .icon-circle {
      width: 50px;
      height: 50px;
      background: #007bff;
      border-radius: 50%;
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 28px;
      margin-right: 15px;
    }

    .card-title {
      font-weight: 700;
      font-size: 1.2rem;
    }

    .card-number {
      font-weight: 600;
      font-size: 1.5rem;
      color: #333;
    }

    .card-subtitle {
      font-size: 0.8rem;
      color: #6c757d;
    }

    /* === Media Query untuk layar kecil (mobile) === */
    @media (max-width: 991px) {
      body {
        padding-left: 0;
      }

      .main-container {
        margin: 10px;
      }

      .icon-circle {
        width: 40px;
        height: 40px;
        font-size: 22px;
        margin-right: 10px;
      }

      .card-title {
        font-size: 1rem;
      }

      .card-number {
        font-size: 1.2rem;
      }

      .card-subtitle {
        font-size: 0.7rem;
      }

      .body-card {
        background-color: #fff;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgb(0 0 0 / 0.1);
        margin-bottom: 20px;
      }

      .chart {
        height: 170px;
        position: relative;
      }
    }
  </style>

</head>

<body>
  <div class="main-container container">
    <div class="row">
      <!-- Card Siswa -->
      <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
          <div class="card-header p-3 pt-2">
            <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
              <i class="material-icons opacity-10">group</i>
            </div>
            <div class="text-end pt-1">
              <p class="text-sm mb-0 text-capitalize">Siswa</p>
              <h4 class="mb-0"><?= $jumlah_siswa ?></h4>
            </div>
          </div>
          <hr class="dark horizontal my-0" />
        </div>
      </div>

      <!-- Card Jurnal (HARI INI) -->
      <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
          <div class="card-header p-3 pt-2">
            <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
              <i class="material-icons opacity-10">book</i>
            </div>
            <div class="text-end pt-1">
              <p class="text-sm mb-0 text-capitalize">Jurnal</p>
              <h4 class="mb-0"><?= $jumlah_jurnal ?></h4>
            </div>
          </div>
          <hr class="dark horizontal my-0" />
        </div>
      </div>

      <!-- Card Belum Hadir -->
      <div class="col-xl-4 col-sm-6">
        <div class="card">
          <div class="card-header p-3 pt-2">
            <div class="icon icon-lg icon-shape bg-gradient-danger shadow-danger text-center border-radius-xl mt-n4 position-absolute">
              <i class="material-icons opacity-10">schedule</i>
            </div>
            <div class="text-end pt-1">
              <p class="text-sm mb-0 text-capitalize">Belum Hadir</p>
              <h4 class="mb-0"><?= $jumlah_belum_hadir ?></h4>
            </div>
          </div>
          <hr class="dark horizontal my-0" />
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    if (!localStorage.getItem('pembimbingAlertShown')) {
      const namaPembimbing = "<?php echo !empty($nama_pembimbing) ? htmlspecialchars($nama_pembimbing, ENT_QUOTES) : ' Pembimbing'; ?>";

      setTimeout(() => {
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
      }, 300);

      localStorage.setItem('pembimbingAlertShown', 'true');
    }
  </script>
</body>

</html>