<style>
  #iconNavbarSidenav {
    display: block;
  }

  @media (min-width: 992px) {
    #iconNavbarSidenav {
      display: none;
    }
  }
</style>
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 border-radius-xl shadow-none fixed" id="navbarBlur" data-scroll="true">
  <div class="container-fluid py-1 px-3">
    <nav aria-label="breadcrumb" class="ps-0 ms-0">
      <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Material</a></li>
        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Dashboard</li>
      </ol>
    </nav>
    <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
      <div class="ms-md-auto pe-md-3 d-flex align-items-center"></div>
      <ul class="navbar-nav d-flex align-items-center justify-content-end">
        <li class="nav-item px-3 d-flex align-items-center">
          <a href="javascript:;" class="nav-link p-0 text-body" id="iconNavbarSidenav">
            <div class="sidenav-toggler-inner">
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
            </div>
          </a>
        </li>

        <!-- Ganti Notifications dengan Profile (avatar) -->
        <li class="nav-item dropdown pe-3 d-flex align-items-center">
          <a href="javascript:;" class="nav-link p-0 text-body" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            <?php
            include('koneksi.php');
            $profile_image = 'assets/img/bg-pricing.jpg'; // default

            if (isset($_SESSION['level'])) {
              // Query untuk mendapatkan gambar profil berdasarkan level user
              switch ($_SESSION['level']) {
                case 'guru':
                  $id_guru = $_SESSION['id_guru'] ?? '';
                  if (!empty($id_guru)) {
                    $query = mysqli_query($coneksi, "SELECT profile FROM guru WHERE id_guru='$id_guru'");
                    if ($query && mysqli_num_rows($query) > 0) {
                      $data = mysqli_fetch_assoc($query);
                      if (!empty($data['profile'])) {
                        $profile_image = '../' . $data['profile'];
                      }
                    }
                  }
                  break;
                case 'siswa':
                  $id_siswa = $_SESSION['id_siswa'] ?? '';
                  if (!empty($id_siswa)) {
                    $query = mysqli_query($coneksi, "SELECT profile FROM siswa WHERE id_siswa='$id_siswa'");
                    if ($query && mysqli_num_rows($query) > 0) {
                      $data = mysqli_fetch_assoc($query);
                      if (!empty($data['profile'])) {
                        $profile_image = '../' . $data['profile'];
                      }
                    }
                  }
                  break;
                  // Tambahkan case lain untuk level user lainnya
              }
            }
            ?>
            <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile" class="avatar avatar-sm rounded-circle" onerror="this.src='assets/img/bg-pricing.jpg'">
          </a>
          <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
            <li>
              <?php
              $profile_link = '#'; // default

              if (isset($_SESSION['level'])) {
                switch ($_SESSION['level']) {
                  case 'guru':
                    $id_guru = $_SESSION['id_guru'] ?? '';
                    $profile_link = "index.php?page=editguru&id_guru=$id_guru";
                    break;
                  case 'siswa':
                    $id_siswa = $_SESSION['id_siswa'] ?? '';
                    $profile_link = "index.php?page=editsiswa&id_siswa=$id_siswa";
                    break;
                  case 'pembimbing':
                    $id_pembimbing = $_SESSION['id_pembimbing'] ?? '';
                    $profile_link = "index.php?page=editpembimbing&id_pembimbing=$id_pembimbing";
                    break;
                  case 'admin':
                    $id = $_SESSION['id'] ?? '';
                    $profile_link = "index.php?page=editsiswa&id=$id";
                    break;
                  // tambahkan role lain jika perlu
                  default:
                    $profile_link = "image/default.png";
                }
              }
              ?>
              <!-- bagian dropdown profile -->
              <a class="dropdown-item border-radius-md" href="<?= htmlspecialchars($profile_link) ?>">
                <i class="fas fa-user-circle me-2"></i> Profile
              </a>

            </li>
            <li>
              <a id="logoutBtn" class="dropdown-item border-radius-md" href="./pages/sign-up_aksi.php">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.getElementById('logoutBtn').addEventListener('click', function(event) {
    event.preventDefault(); // Jangan langsung logout

    Swal.fire({
      title: 'Yakin mau logout?',
      text: "Anda akan keluar dari aplikasi!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Logout',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = './pages/sign-up_aksi.php'; // arahkan logout
      }
    });
  });
</script>