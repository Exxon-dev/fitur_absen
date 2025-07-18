<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark" id="sidenav-main">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" id="iconSidenav"></i>
    <a class="navbar-brand m-0" href="index.php?page=dashboard">
      <img src="assets/img/LOGOSMK-removebg-preview.png" class="navbar-brand-img h-100" alt="main_logo">
      <span class="ms-1 font-weight-bold text-white">ABSENS</span>
    </a>
  </div>

  <hr class="horizontal light mt-0 mb-3">
  <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link text-white" href="index.php?page=dashboard">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">home</i>
          </div>
          <span class="nav-link-text ms-1">Dashboard</span>
        </a>
      </li>

      <!-- Basis Data -->
      <li class="nav-item">
        <a class="nav-link text-white" href="index.php?page=siswa">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">people</i>
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

      <!-- Jurnal -->
      <li class="nav-item">
        <a class="nav-link text-white" href="index.php?page=jurnal">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">assignment</i>
          </div>
          <span class="nav-link-text ms-1">Jurnal</span>
        </a>
      </li>

      <!-- Catatan -->
      <li class="nav-item">
        <a class="nav-link text-white" href="index.php?page=catatan">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">note</i>
          </div>
          <span class="nav-link-text ms-1">Catatan</span>
        </a>
      </li>

      <!-- Rekap Absen -->
      <li class="nav-item">
        <a class="nav-link text-white <?= ($page == 'rekap_absen') ? 'active' : ''; ?>" href="index.php?page=rekap_absen">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">table_view</i>
          </div>
          <span class="nav-link-text ms-1">Rekap Absen</span>
        </a>
      </li>

      <!-- Laporan -->
      <li class="nav-item">
        <a class="nav-link text-white <?= ($page == 'laporan3') ? 'active' : ''; ?>" href="index.php?page=laporan3">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">insert_chart</i>
          </div>
          <span class="nav-link-text ms-1">Laporan</span>
        </a>
      </li>

      <!-- Sign Out -->
      <li class="nav-item">
        <a class="nav-link text-white" href="./pages/sign-up_aksi.php">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">logout</i>
          </div>
          <span class="nav-link-text ms-1">Sign Out</span>
        </a>
      </li>
    </ul>
  </div>
</aside>