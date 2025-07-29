<style>
  /* Sembunyikan sidebar di HP/tablet saat awal */
  @media (max-width: 1199.98px) {
    #sidenav-main {
      transform: translateX(-250px);
      transition: all 0.3s ease;
      z-index: 1099;
    }

    #sidenav-main.show {
      transform: translateX(0);
    }
  }

  /* Saat desktop, tetap muncul */
  @media (min-width: 1200px) {
    #sidenav-main {
      transform: none !important;
    }
  }
</style>

<!-- Tombol ☰ khusus HP/Tablet -->
<button id="menu-toggle" class="btn btn-dark d-xl-none position-fixed top-2 start-0 ms-4" style="z-index: 1100;">
  ☰
</button>

<!-- sidebar.php -->
<aside id="sidenav-main" class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark" data-color="dark">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-white d-xl-none" id="iconSidenav"></i>
    <a class="navbar-brand m-0" href="#">
      <span class="ms-1 font-weight-bold text-white">ABSENS ADMIN</span>
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

<script>
  const menuToggle = document.getElementById('menu-toggle');
  const sidebar = document.getElementById('sidenav-main');
  const closeBtn = document.getElementById('iconSidenav');

  menuToggle.addEventListener('click', () => {
    sidebar.classList.toggle('show');
  });

  closeBtn.addEventListener('click', () => {
    sidebar.classList.remove('show');
  });
</script>