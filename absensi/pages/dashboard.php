<!--
=========================================================
* Material Dashboard 2 - v3.1.0
=========================================================

* Product Page: https://www.creative-tim.com/product/material-dashboard
* Copyright 2023 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<?php

include 'koneksi.php';

$query_siswa = mysqli_query($coneksi, "SELECT * FROM siswa ORDER BY id_siswa ASC") or die(mysqli_error($coneksi));
$query_sekolah = mysqli_query($coneksi, "SELECT * FROM sekolah ORDER BY id_sekolah ASC") or die(mysqli_error($coneksi));
$query_perusahaan = mysqli_query($coneksi, "SELECT * FROM perusahaan ORDER BY id_perusahaan ASC") or die(mysqli_error($coneksi));
$query_pembimbing = mysqli_query($coneksi, "SELECT * FROM pembimbing ORDER BY id_pembimbing ASC") or die(mysqli_error($coneksi));
$query_guru = mysqli_query($coneksi, "SELECT * FROM guru ORDER BY id_guru ASC") or die(mysqli_error($coneksi));
$jumlah_siswa = mysqli_num_rows($query_siswa);
$jumlah_sekolah = mysqli_num_rows($query_sekolah);
$jumlah_perusahaan = mysqli_num_rows($query_perusahaan);
$jumlah_pembimbing = mysqli_num_rows($query_pembimbing);
$jumlah_guru = mysqli_num_rows($query_guru);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>
    ABSENSI | PRAKERIN
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.1.0" rel="stylesheet" />
  <!-- Nepcha Analytics (nepcha.com) -->
  <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
  <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
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

    h2 {
      margin-bottom: 20px;
      color: #007bff;
    }

    /* Card Styles */
    .card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s;
      margin-bottom: 30px;
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
  </style>
</head>

<body class="g-sidenav-show  bg-gray-200">
  <form method="POST" action="">
    <div class="row">
      <div class="col-xl-4 col-sm-6 mb-xl-0 mb-0">
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
      <div class="col-xl-4 col-sm-6 mb-xl-0 mb-0">
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
      <div class="col-xl-4 col-sm-6 mb-xl-0 mb-0">
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
      <div class="col-xl-4 col-sm-6 mb-xl-0 mb-0">
        <div class="card">
          <div class="card-header p-3 pt-2">
            <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
              <i class="material-icons opacity-10">person</i>
            </div>
            <div class="text-end pt-1">
              <p class="text-sm mb-0 text-capitalize">Guru</p>
              <h4 class="mb-0"><?php echo $jumlah_guru; ?></h4>
            </div>
          </div>
          <hr class="dark horizontal my-0">
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 mb-xl-0 mb-0">
        <div class="card">
          <div class="card-header p-3 pt-2">
            <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
              <i class="material-icons opacity-10">supervisor_account</i>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // Cek apakah alert welcome sudah pernah ditampilkan
        if (!localStorage.getItem('adminWelcomeShown')) {
          const nama = "<?php echo !empty($nama) ? htmlspecialchars($nama, ENT_QUOTES) : 'Admin'; ?>";

          Swal.fire({
            title: `Selamat datang ${nama}!`,
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
          localStorage.setItem('adminWelcomeShown', 'true');
        }

        // Untuk notifikasi lainnya (jika ada) bisa ditambahkan di sini
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
      // Tambahkan ini di halaman logout admin Anda
    </script>
    </script>
</body>

</html>