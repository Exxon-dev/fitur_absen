<?php
include('koneksi.php');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Perusahaan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
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


        .editPerusahaan {
            color: white;
            /* Text putih */
            background-color: goldenrod;
            /* Warna abu-abu Bootstrap */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            /* Shadow */
            border: none;
            /* Hilangkan border */
            padding: 8px 16px;
            /* Padding yang sesuai */
            border-radius: 4px;
            /* Sedikit rounded corners */
            transition: all 0.3s ease;
            /* Efek transisi halus */
        }

        .editPerusahaan:hover {
            background-color: goldenrod;
            /* Warna lebih gelap saat hover */
            color: white;
            /* Tetap putih saat hover */
            transform: translateY(-1px);
            /* Sedikit efek angkat */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
            /* Shadow lebih besar saat hover */
        }

        .hapusPerusahaan {
            color: white;
            /* Text putih */
            background-color: #344767;
            /* Warna abu-abu Bootstrap */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            /* Shadow */
            border: none;
            /* Hilangkan border */
            padding: 8px 16px;
            /* Padding yang sesuai */
            border-radius: 4px;
            /* Sedikit rounded corners */
            transition: all 0.3s ease;
            /* Efek transisi halus */
        }

        .hapusPerusahaan:hover {
            background-color: #5a6268;
            /* Warna lebih gelap saat hover */
            color: white;
            /* Tetap putih saat hover */
            transform: translateY(-1px);
            /* Sedikit efek angkat */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
            /* Shadow lebih besar saat hover */
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
<h2 class="text-left">Data Perusahaan</h2>
    <div class="main-container container-custom">
        <a href="index.php?page=tambahperusahaan" class="btn btn-primary">Tambah Perusahaan</a>
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Derektur</th>
                        <th class="text-center">Alamat</th>
                        <th class="text-center">No Telepon</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Mengubah query untuk mengurutkan berdasarkan id_perusahaan DESC
                    // agar data terbaru (dengan ID tertinggi) muncul di paling atas
                    $sql = mysqli_query($coneksi, "SELECT * FROM perusahaan ORDER BY id_perusahaan DESC") or die(mysqli_error($coneksi));
                    if (mysqli_num_rows($sql) > 0) {
                        $no = 1;
                        while ($data = mysqli_fetch_assoc($sql)) {
                            $editUrl = "index.php?page=editperusahaan&id_perusahaan=" . $data['id_perusahaan'];
                            $deleteUrl = "index.php?page=hapusperusahaan&id_perusahaan=" . $data['id_perusahaan'];
                            echo '
                                <tr style="text-align:center; cursor:pointer;" onclick="window.location=\'' . $editUrl . '\'">
                                    <td>' . $no . '</td>
                                    <td class="text-left">' . $data['nama_perusahaan'] . '</td>
                                    <td class="text-left">' . $data['pimpinan'] . '</td>
                                    <td class="text-left">' . $data['alamat_perusahaan'] . '</td>
                                    <td>' . $data['no_tlp'] . '</td>
                                </tr>';
                            $no++;
                        }
                    } else {
                        echo '
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data.</td>
                        </tr>';
                    }
                    ?>
                </tbody>

            </table>
        </div>


        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <?php
        // Notifikasi flash message hapus
        if (isset($_SESSION['flash_hapus']) && $_SESSION['flash_hapus'] == 'sukses') {
            echo "<script>document.addEventListener('DOMContentLoaded',function(){Swal.fire({icon:'info',title:'Sukses!',text:'Data perusahaan berhasil dihapus',position:'top',showConfirmButton:false,timer:3000,toast:true});});</script>";
            unset($_SESSION['flash_hapus']);
        }
        ?>
        <?php
        if (isset($_SESSION['flash_edit']) && $_SESSION['flash_edit'] == 'sukses') {
            echo "<script>document.addEventListener('DOMContentLoaded',function(){Swal.fire({icon:'success',title:'Sukses!',text:'Data perusahaan berhasil di update',position:'top',showConfirmButton:false,timer:3000,toast:true});});</script>";
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
            text: 'Data perusahaan berhasil ditambahkan',
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
            text: 'ID perusahaan sudah terdaftar',
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