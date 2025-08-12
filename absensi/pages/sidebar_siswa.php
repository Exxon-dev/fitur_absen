<!-- SIDEBAR -->
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark" id="sidenav-main">
  <div class="sidenav-header">
    <a class="navbar-brand m-0" href="index.php?page=dashboard_siswa">
      <img src="assets/img/LOGOSMK-removebg-preview.png" class="navbar-brand-img h-100" alt="main_logo">
      <span class="ms-1 font-weight-bold text-white">ABSENSI SISWA</span>
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
            <i class="material-icons opacity-10">note</i>
          </div>
          <span class="nav-link-text ms-1">Catatan</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link text-white <?php echo (isset($_GET['page']) && $_GET['page'] == 'laporan') ? 'active' : ''; ?>" href="index.php?page=laporan">
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