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

<!-- Overlay -->
<div id="sidebar-overlay" onclick="toggleSidebar()"></div>

<!-- sidebar.php -->
<aside id="sidenav-main" class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark" data-color="dark">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white d-xl-none" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="#">
            <span class="ms-1 font-weight-bold text-white">ABSENSI SEKOLAH</span>
        </a>
    </div>

    <hr class="horizontal light mt-0 mb-2" />

    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link text-white" href="index.php?page=dashboard_sekolah">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">dashboard</i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>

            <!-- Profile Sekolah -->
            <li class="nav-item">
                <a class="nav-link text-white <?= ($page == 'profile_sekolah') ? 'active' : ''; ?>" href="index.php?page=profile_sekolah&id_sekolah=<?php echo $_SESSION['id_sekolah'] ?>">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">school</i>
                    </div>
                    <span class="nav-link-text ms-1">Profile Sekolah</span>
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

            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Account Pages</h6>
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
    function toggleSidebar() {
        const sidebar = document.getElementById('sidenav-main');
        const overlay = document.getElementById('sidebar-overlay');

        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
    }
</script>