<!-- SIDEBAR GURU -->
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark" id="sidenav-main">
  <div class="sidenav-header">
    <a class="navbar-brand m-0" href="index.php?page=dashboard_guru">
      <img src="assets/img/LOGOSMK-removebg-preview.png" class="navbar-brand-img h-100" alt="main_logo">
      <span class="ms-1 font-weight-bold text-white">ABSENSI GURU</span>
    </a>
  </div>
  <hr class="horizontal light mt-0 mb-2">
  <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link text-white <?php echo (isset($_GET['page']) && $_GET['page'] == 'dashboard_guru') ? 'active' : ''; ?>" href="index.php?page=dashboard_guru">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">dashboard</i>
          </div>
          <span class="nav-link-text ms-1">Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
          <a class="nav-link text-white <?php echo (isset($_GET['page']) && $_GET['page'] == 'absensi_siswa') ? 'active' : ''; ?>" href="index.php?page=absensi_siswa">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">assignment</i>
          </div>
          <span class="nav-link-text ms-1">Absensi Siswa</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white <?php echo (isset($_GET['page']) && strpos($_GET['page'], 'editguru') !== false) ? 'active' : ''; ?>" href="index.php?page=editguru&id_guru=<?php echo $_SESSION['id_guru'] ?>">

          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">person</i>
          </div>
          <span class="nav-link-text ms-1">Profile Guru</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white <?php echo (isset($_GET['page']) && strpos($_GET['page'], 'catatan') !== false) ? 'active' : ''; ?>" href="index.php?page=catatan">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">note</i>
          </div>
          <span class="nav-link-text ms-1">Catatan</span>
        </a>
      </li>
      <li class="nav-item">
          <a class="nav-link text-white <?php echo (isset($_GET['page']) && $_GET['page'] == 'laporan1') ? 'active' : ''; ?>" href="index.php?page=laporan1">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">insert_chart</i>
          </div>
          <span class="nav-link-text ms-1">Laporan</span>
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