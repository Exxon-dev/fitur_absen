<?php
include('koneksi.php');

// Pastikan ID siswa ada dalam session
if (!isset($_SESSION['id_siswa'])) {
    header("Location: sign-in.php");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
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

        .container-custom {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px;
            font-size: 16px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        /* Style untuk Choices.js */
        .choices__inner {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 6px 12px;
            min-height: auto;
            background-color: white;
        }

        .choices__list--dropdown {
            border: 1px solid #ddd;
            border-radius: 0 0 4px 4px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .choices__input {
            margin-bottom: 0;
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
    <div class="main-container container-custom">
        <h1>Laporan Siswa</h1>
        <hr>

        <form id="myForm" action="pages/laporan/preview.php" method="POST">
            <input type="hidden" id="id_siswa" name="id_siswa" value="<?php echo htmlspecialchars($_SESSION['id_siswa']); ?>" readonly>

            <div class="form-group">
                <label for="reportSelect">Pilih Laporan:</label>
                <select id="reportSelect" name="page" class="form-control">
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
            </div>

            <button type="submit" class="btn btn-primary btn-block mt-4">Buka Laporan</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    <script>
        // Inisialisasi Choices.js
        document.addEventListener('DOMContentLoaded', function() {
            const reportSelect = document.getElementById('reportSelect');
            if (reportSelect) {
                const choices = new Choices(reportSelect, {
                    searchEnabled: true,
                    itemSelectText: '',
                    shouldSort: false,
                    position: 'auto',
                    placeholder: true,
                    searchPlaceholderValue: 'Cari laporan...',
                    noResultsText: 'Tidak ditemukan',
                    noChoicesText: 'Tidak ada pilihan',
                });
            }

            // Handle submit form
            $('#myForm').on('submit', function(e) {
                e.preventDefault();

                // Validasi pilihan laporan
                if (!$('#reportSelect').val()) {
                    alert('Silakan pilih laporan terlebih dahulu');
                    return false;
                }

                // Buka laporan di tab baru
                const formData = $(this).serialize();
                const url = $(this).attr('action') + '?' + formData;
                window.open(url, '_blank');

                // Reset form setelah submit
                setTimeout(() => {
                    const choices = $('#reportSelect').data('choices');
                    if (choices) {
                        choices.setChoiceByValue('');
                    } else {
                        $('#reportSelect').val('');
                    }
                }, 100);
            });
        });
    </script>
</body>

</html>