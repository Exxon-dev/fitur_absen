<style>
  /* Tombol Burger */
    .menu-toggle {
        position: fixed;
        top: 15px;
        right: 30px;
        /* ini gantiin left: 15px */
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
<button class="menu-toggle" onclick="toggleSidebar()">☰</button>

<!-- Overlay untuk mobile -->
<div id="sidebar-overlay" onclick="toggleSidebar()"></div>

<!-- SIDEBAR GURU -->
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark" id="sidenav-main">
  <div class="sidenav-header">
    <a class="navbar-brand m-0" href="index.php?page=dashboard_guru">
      <img src="assets/img/LOGOSMK-removebg-preview.png" class="navbar-brand-img h-100" alt="main_logo">
      <span class="ms-1 font-weight-bold text-white">ABSENSI</span>
    </a>
  </div>
  <hr class="horizontal light mt-0 mb-2">
  <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link text-white <?php echo ($_GET['page'] == 'dashboard_guru') ? 'active' : ''; ?>" href="index.php?page=dashboard_guru">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">dashboard</i>
          </div>
          <span class="nav-link-text ms-1">Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white <?php echo (strpos($_GET['page'], 'editguru') !== false) ? 'active' : ''; ?>" href="index.php?page=editguru&id_guru=<?php echo $_SESSION['id_guru'] ?>">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">person</i>
          </div>
          <span class="nav-link-text ms-1">Profile Guru</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white <?php echo (strpos($_GET['page'], 'catatan') !== false) ? 'active' : ''; ?>" href="index.php?page=catatan">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">receipt_long</i>
          </div>
          <span class="nav-link-text ms-1">Data Catatan</span>
        </a>
      </li>
      <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Account pages</h6>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white <?php echo ($_GET['page'] == 'laporan1') ? 'active' : ''; ?>" href="index.php?page=laporan1">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">table_view</i>
          </div>
          <span class="nav-link-text ms-1">Laporan</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="./pages/sign-up_aksi.php">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">login</i>
          </div>
          <span class="nav-link-text ms-1">Sign Out</span>
        </a>
      </li>
    </ul>
  </div>
</aside>

<script>
  function toggleSidebar() {
    const sidebar = document.getElementById('sidenav-main');
    const overlay = document.getElementById('sidebar-overlay');

    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
  }
</script>