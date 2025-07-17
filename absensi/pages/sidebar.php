<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    <a class="navbar-brand m-0" href="index.php?page=dashboard">
      <img src="assets/img/LOGOSMK-removebg-preview.png" class="navbar-brand-img h-100" alt="main_logo">
      <span class="ms-1 font-weight-bold text-white">ABSENS</span>
    </a>
  </div>

  <hr class="horizontal light mt-0 mb-2">
  <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link text-white " href="index.php?page=dashboard">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">dashboard</i>
          </div>
          <span class="nav-link-text ms-1">Dashboard</span>
        </a>
      </li>
      <a class="nav-link text-white dropdown-toggle" data-bs-toggle="dropdown"><i class="material-icons opacity-10">dashboard</i>Basis Data </a>
      <div class="dropdown-menu bg-transparent border-0">
        <a href="index.php?page=siswa" class="dropdown-item text-white">Data Siswa</a>
        <a href="index.php?page=guru" class="dropdown-item text-white">Data Guru</a>
        <a href="index.php?page=pembimbing" class="dropdown-item text-white">Data Pembimbing</a>
        <a href="index.php?page=perusahaan" class="dropdown-item text-white">Data Perusahaan</a>
        <a href="index.php?page=sekolah" class="dropdown-item text-white">Data Sekolah</a>

      </div>
      <a class="nav-link text-white dropdown-toggle" data-bs-toggle="dropdown"><i class="material-icons opacity-10">assignment</i>Jurnal </a>
      <div class="dropdown-menu bg-transparent border-0">
        <a href="index.php?page=jurnal" class="dropdown-item text-white">Data Jurnal</a>
        
      </div>
      <a class="nav-link text-white dropdown-toggle" data-bs-toggle="dropdown"><i class="material-icons opacity-10">receipt_long</i>Catatan </a>
      <div class="dropdown-menu bg-transparent border-0">
        <a href="index.php?page=catatan" class="dropdown-item text-white">Data Catatan</a>
        
      </div>
      <!-- Rekap Absen -->
      <li class="nav-item">
        <a class="nav-link text-white <?php echo ($_GET['page'] == 'rekap_absen') ? 'active' : ''; ?>" href="index.php?page=rekap_absen">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">table_view</i>
          </div>
          <span class="nav-link-text ms-1">Rekap Absen</span>
        </a>
      </li>
      <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Account pages</h6>
      </li>
      <!-- Laporan -->
      <li class="nav-item">
        <a class="nav-link text-white <?php echo ($_GET['page'] == 'laporan3') ? 'active' : ''; ?>" href="index.php?page=laporan3">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">table_view</i>
          </div>
          <span class="nav-link-text ms-1">Laporan</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white " href="./pages/sign-up_aksi.php">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">assignment</i>
          </div>
          <span class="nav-link-text ms-1">Sign Out</span>
        </a>
      </li>
    </ul>
  </div>
</aside>