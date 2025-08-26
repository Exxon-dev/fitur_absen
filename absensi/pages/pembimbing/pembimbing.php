<?php
include('koneksi.php');
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Data Pembimbing dan Guru</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Penyesuaian posisi */
        body {
            padding-left: 270px;
            transition: padding-left 0.3s;
            background-color: #f8f9fa;
        }

        .main-container {
            margin-top: 20px;
            margin-right: 20px;
            margin-left: 0;
            width: auto;
            max-width: none;
        }

        /* Style asli */
        .container-custom {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #007bff;
        }

        .table thead th {
            background-color: #007bff;
            color: white;
        }

        .table tbody tr:hover {
            background-color: #e9ecef;
        }

        .table-responsive {
            border: none !important;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table td,
        .table th {
            border: 1px solid #dee2e6 !important;
            vertical-align: middle;
        }

        .btn-warning {
            margin-bottom: 20px;
        }

        @media (max-width: 991px) {
            body {
                padding-left: 0;
            }

            .main-container {
                margin-right: 15px;
                margin-left: 15px;
            }
        }
    </style>
</head>

<body>
 <h2 class="text-left">Data Pembimbing</h2>
    <div class="main-container container-custom">
        <a href="index.php?page=tambahpembimbing" class="btn btn-primary">Tambah Pembimbing</a>
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Perusahaan</th>
                        <th class="text-center">Alamat Perusahaan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Mengubah query untuk JOIN dengan tabel perusahaan
                    // Mengurutkan berdasarkan id_pembimbing DESC agar data terbaru muncul di atas
                    $sql = mysqli_query($coneksi, "SELECT p.*, pr.nama_perusahaan, pr.alamat_perusahaan 
                                                   FROM pembimbing p 
                                                   LEFT JOIN perusahaan pr ON p.id_perusahaan = pr.id_perusahaan 
                                                   ORDER BY p.id_pembimbing DESC") or die(mysqli_error($coneksi));
                    
                    if (mysqli_num_rows($sql) > 0) {
                        $no = 1;
                        while ($data = mysqli_fetch_assoc($sql)) {
                            echo '
                    <tr style="text-align:center; cursor:pointer;" onclick="window.location=\'index.php?page=editpembimbing1&id_pembimbing=' . $data['id_pembimbing'] . '\'">
                        <td>' . $no . '</td>
                        <td class="text-left">' . $data['nama_pembimbing'] . '</td>
                        <td class="text-left">' . $data['nama_perusahaan'] . '</td>
                        <td class="text-left">' . $data['alamat_perusahaan'] . '</td>
                    </tr>
                    ';
                            $no++;
                        }
                    } else {
                        echo '
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data.</td>
                </tr>
                ';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.2.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <?php
        // Notifikasi flash message hapus
        if (isset($_SESSION['flash_hapus']) && $_SESSION['flash_hapus'] == 'sukses') {
            echo "<script>document.addEventListener('DOMContentLoaded',function(){Swal.fire({icon:'info',title:'Sukses!',text:'Data pembimbing berhasil dihapus',position:'top',showConfirmButton:false,timer:3000,toast:true});});</script>";
            unset($_SESSION['flash_hapus']);
        }
        ?>
        <?php
        if (isset($_SESSION['flash_edit']) && $_SESSION['flash_edit'] == 'sukses') {
            echo "<script>document.addEventListener('DOMContentLoaded',function(){Swal.fire({icon:'success',title:'Sukses!',text:'Data pembimbing berhasil di update',position:'top',showConfirmButton:false,timer:3000,toast:true});});</script>";
            unset($_SESSION['flash_edit']);
        }
        ?>
        <?php
        // Notifikasi flash message tambah
        if (isset($_SESSION['flash_tambah']) && $_SESSION['flash_tambah'] == 'sukses') {
            echo "<script>document.addEventListener('DOMContentLoaded',function(){
        Swal.fire({
            icon: 'success',
            title: 'Sukses!',
            text: 'Data pembimbing berhasil ditambahkan',
            position: 'top',
            showConfirmButton: false,
            timer: 3000,
            toast: true
        });
    });</script>";
            unset($_SESSION['flash_tambah']);
        }

        // Notifikasi error
        if (isset($_SESSION['flash_error'])) {
            echo "<script>document.addEventListener('DOMContentLoaded',function(){
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '" . addslashes($_SESSION['flash_error']) . "',
            position: 'top',
            showConfirmButton: false,
            timer: 3000,
            toast: true
        });
    });</script>";
            unset($_SESSION['flash_error']);
        }

        // Notifikasi duplikat
        if (isset($_SESSION['flash_duplikat'])) {
            echo "<script>document.addEventListener('DOMContentLoaded',function(){
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan!',
            text: 'ID pembimbing atau Username sudah terdaftar',
            position: 'top',
            showConfirmButton: false,
            timer: 3000,
            toast: true
        });
    });</script>";
            unset($_SESSION['flash_duplikat']);
        }
        ?>
</body>

</html>