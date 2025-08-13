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

$query_siswa = mysqli_query($coneksi, "SELECT COUNT(*) as total FROM siswa WHERE id_perusahaan = '$id_perusahaan'");
$jumlah_siswa = mysqli_fetch_assoc($query_siswa)['total'];

$query_sekolah = mysqli_query($coneksi, "SELECT COUNT(*) as total FROM sekolah");
$jumlah_sekolah = mysqli_fetch_assoc($query_sekolah)['total'];

$query_perusahaan = mysqli_query($coneksi, "SELECT COUNT(*) as total FROM perusahaan WHERE id_perusahaan = '$id_perusahaan'");
$jumlah_perusahaan = mysqli_fetch_assoc($query_perusahaan)['total'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Dashboard Pembimbing</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" />
  <style>
    body {
      padding-left: 250px;
      background-color: #f8f9fa;
      margin: 0;
      overflow: auto;
    }

    .main-container {
      margin: 20px;
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

    /* === Media Query untuk layar kecil (mobile) === */
    @media (max-width: 991px) {
      body {
        padding-left: 0;
        /* hilangkan padding kiri agar sidebar tidak mengganggu */
      }

      .main-container {
        margin: 10px;
        /* kurangi margin agar muat */
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

      <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
          <div class="card-header p-3 pt-2">
            <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
              <i class="material-icons opacity-10">school</i>
            </div>
            <div class="text-end pt-1">
              <p class="text-sm mb-0 text-capitalize">Sekolah</p>
              <h4 class="mb-0"><?= $jumlah_sekolah ?></h4>
            </div>
          </div>
          <hr class="dark horizontal my-0" />
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
              <h4 class="mb-0"><?= $jumlah_perusahaan ?></h4>
            </div>
          </div>
          <hr class="dark horizontal my-0" />
        </div>
      </div>
    </div>
  </div>
</body>

</html>