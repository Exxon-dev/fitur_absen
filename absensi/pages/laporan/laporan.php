<?php
include('koneksi.php');

// Pastikan ID siswa ada dalam session
if (!isset($_SESSION['id_siswa'])) {
    header("Location: sign-in.php"); // Arahkan ke halaman login jika belum login
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buka Laporan Siswa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
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

        h1 {
            text-align: center;
            color: #007bff;
        }

        .form-control {
            border: none;
            border-bottom: 2px solid #007bff;
            border-radius: 0;
            box-shadow: none;
        }

        .form-control:focus {
            border-color: #0056b3;
            box-shadow: none;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            color: #777;
        }

        .btn-warning {
            background-color: #ffc107;
            border: none;
        }

        .btn-warning:hover {
            background-color: #e0a800;
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
    <script>
        function openTab(event) {
            event.preventDefault();
            const form = document.getElementById('myForm');
            const formData = new FormData(form);

            const params = new URLSearchParams();
            for (const pair of formData.entries()) {
                params.append(pair[0], pair[1]);
            }

            const url = form.action + '?' + params.toString();
            window.open(url, '_blank');

            // Kirim data POST menggunakan fetch
            fetch(url, {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text(); // Ambil respons sebagai teks
                })
                .then(data => {
                    // Menuliskan respons ke tab baru
                    newTab.document.write(data);
                    newTab.document.close(); // Menutup dokumen untuk memproses konten
                })
                .catch(error => {
                    console.error('Terjadi kesalahan saat mengirim data:', error);
                    newTab.document.write("<h1>Gagal memuat laporan</h1><p>" + error.message + "</p>");
                    newTab.document.close();
                });
        }
    </script>
</head>

<body>

    <div class="main-container container-custom">
        <h1>Buka Laporan Siswa</h1>
        <hr>

        <form id="myForm" action="pages/laporan/preview.php" method="POST" onsubmit="openTab(event)">
            <label for="id_siswa"></label>
            <input type="hidden" id="id_siswa" name="id_siswa" value="<?php echo htmlspecialchars($_SESSION['id_siswa']); ?>" class="form-control" readonly required>

            <label for="reportSelect">Pilih Laporan:</label>
            <select id="reportSelect" name="page" class="form-control" required>
                <option value="">-- Pilih Laporan --</option>
                <option value="cover">Cover</option>
                <option value="df">Daftar Hadir</option>
                <option value="jr">Laporan Jurnal</option>
                <option value="catatan">Lembar Catatan Kegiatan</option>
                <option value="dn">Lembar Daftar Nilai</option>
                <option value="sk">Lembar Surat Keterangan</option>
                <option value="nkp">Lembar Nilai Kepuasan Pelanggan</option>
                <option value="lp">Lembar Pengesahan</option>
                <option value="bl">Lembar Bimbingan Laporan</option>
            </select>

            <button type="submit" class="btn btn-primary btn-block mt-4">Unduh Laporan</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

</body>

</html>