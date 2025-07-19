<?php
include('koneksi.php');
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pembimbing dan Guru</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 20px;
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

        .btn-warning {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <div class="container">
        <a href="index.php?page=tambahpembimbing" class="btn btn-primary">Tambah Pembimbing</a>
        <h2 class="text-center">Data Pembimbing</h2>

        <hr>

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Password</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = mysqli_query($coneksi, "SELECT * FROM pembimbing ORDER BY id_pembimbing ASC") or die(mysqli_error($coneksi));
                if (mysqli_num_rows($sql) > 0) {
                    $no = 1;
                    while ($data = mysqli_fetch_assoc($sql)) {
                        echo '
                    <tr style="text-align:center; cursor:pointer;" onclick="window.location=\'index.php?page=editpembimbing1&id_pembimbing=' . $data['id_pembimbing'] . '\'">
                        <td>' . $no . '</td>
                        <td>' . $data['nama_pembimbing'] . '</td>
                        <td>' . $data['username'] . '</td>
                        <td>' . $data['password'] . '</td>
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