<!-- sidebar.php -->
<aside id="sidenav-main" class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark" data-color="dark">
  <div class="sidenav-header">
    <a class="navbar-brand m-0" href="#">
      <span class="ms-1 font-weight-bold text-white">ADMIN ABSENSI</span>
    </a>
  </div>

  <hr class="horizontal light mt-0 mb-2" />

  <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link text-white" href="index.php?page=dashboard">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">dashboard</i>
          </div>
          <span class="nav-link-text ms-1">Dashboard</span>
        </a>
      </li>
      <!-- Basis Data -->
      <li class="nav-item">
        <a class="nav-link text-white" href="index.php?page=siswa">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">local_library</i>
          </div>
          <span class="nav-link-text ms-1">Data Siswa</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="index.php?page=guru">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">person</i>
          </div>
          <span class="nav-link-text ms-1">Data Guru</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="index.php?page=pembimbing">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">supervisor_account</i>
          </div>
          <span class="nav-link-text ms-1">Data Pembimbing</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="index.php?page=perusahaan">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">business</i>
          </div>
          <span class="nav-link-text ms-1">Data Perusahaan</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="index.php?page=sekolah">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">school</i>
          </div>
          <span class="nav-link-text ms-1">Data Sekolah</span>
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