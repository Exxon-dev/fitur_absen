<!-- SIDEBAR -->
<aside id="sidenav-main"
  class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark"
  style="width: 250px;">
  <div class="sidenav-header">
    <a class="navbar-brand m-0" href="index.php?page=dashboard_pembimbing">
      <img src="assets/img/LOGOSMK-removebg-preview.png" class="navbar-brand-img h-100" alt="main_logo">
      <span class="ms-1 font-weight-bold text-white">ABSENS</span>
    </a>
  </div>
  <hr class="horizontal light mt-0 mb-2">
  <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav">
      <!-- MENU -->
      <li class="nav-item">
        <a class="nav-link text-white <?= ($_GET['page'] == 'dashboard_pembimbing') ? 'active' : ''; ?>"
          href="index.php?page=dashboard_pembimbing">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">dashboard</i>
          </div>
          <span class="nav-link-text ms-1">Dashboard</span>
        </a>
      </li>
            <li class="nav-item">
        <a class='nav-link text-white <?php echo ($_GET['page'] == 'editpembimbing') ? 'active' : ''; ?>' href='index.php?page=editpembimbing&id_pembimbing=<?php echo $_SESSION['id_pembimbing'] ?>'>
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">person</i>
          </div>
          <span class="nav-link-text ms-1">Data Pembimbing</span>
        </a>
      </li>
      <li class="nav-item">
        <a class='nav-link text-white <?php echo ($_GET['page'] == 'jurnal') ? 'active' : ''; ?>' href='index.php?page=jurnal'>
          <div class='text-white text-center me-2 d-flex align-items-center justify-content-center'>
            <i class='material-icons opacity-10'>assignment</i>
          </div>
          <span class="nav-link-text ms-1">Data Jurnal</span>
        </a>
      </li>
      <li class="nav-item">
        <a class='nav-link text-white <?php echo ($_GET['page'] == 'catatan') ? 'active' : ''; ?>' href='index.php?page=catatan'>
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">receipt_long</i>
          </div>
          <span class="nav-link-text ms-1">Data Catatan</span>
        </a>
      </li>
      <li class="nav-item">
        <a class='nav-link text-white <?php echo ($_GET['page'] == 'rekap_absen') ? 'active' : ''; ?>' href='index.php?page=rekap_absen'>
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">table_view</i>
          </div>
          <span class="nav-link-text ms-1">Rekap Absen</span>
        </a>
      </li>
      <li class="nav-item">
        <a class='nav-link text-white <?php echo ($_GET['page'] == 'rekap_ip') ? 'active' : ''; ?>' href='index.php?page=rekap_ip'>
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">table_view</i>
          </div>
          <span class="nav-link-text ms-1">Rekap IP</span>
        </a>
      </li>
      <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Account pages</h6>
      </li>
      <li class="nav-item">
        <a class='nav-link text-white <?php echo ($_GET['page'] == 'laporan2') ? 'active' : ''; ?>' href='index.php?page=laporan2'>
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">table_view</i>
          </div>
          <span class="nav-link-text ms-1">Laporan</span>
        </a>
      </li>
      <li class="nav-item">
        <a class='nav-link text-white <?php echo ($_GET['page'] == 'sign-up_aksi') ? 'active' : ''; ?>' href='./pages/sign-up_aksi.php'>
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">login</i>
          </div>
          <span class="nav-link-text ms-1">Sign Out</span>
        </a>
      </li>
    </ul>
  </div>
</aside>

