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
            <img src="assets/img/bg-pricing.jpg" alt="Profile" class="avatar avatar-sm rounded-circle">
          </a>
          <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
            <li>
              <?php
              $profile_link = '#'; // default

              if (isset($_SESSION['role'])) {
                switch ($_SESSION['role']) {
                  case 'guru':
                    $id = $_SESSION['id_guru'] ?? '';
                    $profile_link = "index.php?page=editguru&id_guru=$id";
                    break;
                  case 'siswa':
                    $id = $_SESSION['id_siswa'] ?? '';
                    $profile_link = "index.php?page=editsiswa&id_siswa=$id";
                    break;
                  case 'pembimbing':
                    $id = $_SESSION['id_pembimbing'] ?? '';
                    $profile_link = "index.php?page=editpembimbing&id_pembimbing=$id";
                    break;
                  // tambahkan role lain jika perlu
                  default:
                    $profile_link = "#";
                }
              }
              ?>
              <!-- bagian dropdown profile -->
              <a class="dropdown-item border-radius-md" href="<?= htmlspecialchars($profile_link) ?>">
                  <i class="fas fa-user-circle me-2"></i> Logout
              </a>

            </li>
            <li>
              <a class="dropdown-item border-radius-md" href="./pages/sign-up_aksi.php">
                  <i class="fas fa-sign-out-alt me-2"></i> Logout
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>