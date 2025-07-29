<<<<<<< HEAD
<!-- Tombol burger (mobile) -->
=======
<style>
  /* Tombol Burger */
  .menu-toggle {
    position: fixed;
    top: 15px;
    right: 30px;
    background: #344767;
    border: none;
    z-index: 1100;
    color: white;
    font-size: 24px;
    padding: 5px 10px;
    border-radius: 5px;
    display: none;
    cursor: pointer;
  }

  /* Overlay (muncul pas sidebar aktif di mobile) */
  #sidebar-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.4);
    z-index: 1049;
  }

  /* Responsive behavior */
  @media (max-width: 991px) {
    .menu-toggle {
      display: block;
    }

    #sidenav-main {
      transform: translateX(-100%);
      transition: transform 0.3s ease;
      z-index: 1051;
      position: fixed;
    }

    #sidenav-main.active {
      transform: translateX(0);
    }

    #sidebar-overlay.active {
      display: block;
    }
  }
</style>

<!-- Tombol burger -->
>>>>>>> 5e2c68c (mengubah tataletak tombol burger, memperbaiki fitur absen menggunakan ip, lokasi dan koordinat)
<button class="menu-toggle" onclick="toggleSidebar()">☰</button>

<!-- Overlay untuk mobile -->
<div id="sidebar-overlay" onclick="toggleSidebar()"></div>

<!-- SIDEBAR -->
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark" id="sidenav-main">
  <div class="sidenav-header">
    <a class="navbar-brand m-0" href="index.php?page=dashboard_siswa">
      <img src="assets/img/LOGOSMK-removebg-preview.png" class="navbar-brand-img h-100" alt="main_logo">
      <span class="ms-1 font-weight-bold text-white">ABSENSI</span>
    </a>
  </div>
  <hr class="horizontal light mt-0 mb-2">
  <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav">
      <!-- Menu utama -->
      <li class="nav-item">
        <a class="nav-link text-white <?php echo ($_GET['page'] == 'dashboard_siswa') ? 'active' : ''; ?>" href="index.php?page=dashboard_siswa">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">dashboard</i>
          </div>
          <span class="nav-link-text ms-1">Dashboard</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link text-white <?php echo (isset($_GET['page']) && $_GET['page'] == 'editsiswa') ? 'active' : ''; ?>" href="index.php?page=editsiswa&id_siswa=<?php echo $_SESSION['id_siswa'] ?>">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">person</i>
          </div>
          <span class="nav-link-text ms-1">Profile Siswa</span>
        </a>
      </li>

      <li class="nav-item">
        <a href="index.php?page=catatan" class="nav-link text-white <?php echo (isset($_GET['page']) && $_GET['page'] === 'catatan') ? 'active' : ''; ?>">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">assignment</i>
          </div>
          <span class="nav-link-text ms-1">Catatan</span>
        </a>
      </li>

      <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Account Pages</h6>
      </li>

      <li class="nav-item">
        <a class="nav-link text-white <?php echo (isset($_GET['page']) && $_GET['page'] == 'laporan') ? 'active' : ''; ?>" href="index.php?page=laporan">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">table_view</i>
          </div>
          <span class="nav-link-text ms-1">Laporan</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link text-white" href="pages/sign-in.php">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">logout</i>
          </div>
          <span class="nav-link-text ms-1">Sign Out</span>
        </a>
      </li>
    </ul>
  </div>
</aside>

<style>
  .menu-toggle {
    position: fixed;
    top: 15px;
    left: 15px;
    background: #344767;
    border: none;
    z-index: 1100;
    color: white;
    font-size: 24px;
    padding: 5px 10px;
    border-radius: 5px;
    display: none;
    cursor: pointer;
  }

  #sidebar-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.4);
    z-index: 1049;
  }

  #sidenav-main {
    width: 260px;
  }

  @media (min-width: 992px) {
    .main-content {
      margin-left: 270px;
      transition: margin-left 0.3s ease;
    }
  }

  @media (max-width: 991px) {
    .menu-toggle {
      display: block;
    }

    #sidenav-main {
      transform: translateX(-100%);
      transition: transform 0.3s ease;
      z-index: 1051;
      position: fixed;
    }

    #sidenav-main.active {
      transform: translateX(0);
    }

    #sidebar-overlay.active {
      display: block;
    }

    .main-content {
      margin-left: 0 !important;
    }
  }
</style>

<script>
  function toggleSidebar() {
    const sidebar = document.getElementById('sidenav-main');
    const overlay = document.getElementById('sidebar-overlay');

    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
  }
</script>
