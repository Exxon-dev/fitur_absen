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
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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

        /* Select2 Custom Styling - Mempertahankan tampilan asli */
        .select2-container--default .select2-selection--single {
            border: none;
            border-bottom: 2px solid #007bff;
            border-radius: 0;
            height: auto;
            padding: 6px 12px;
            background-color: transparent;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #495057;
            padding-left: 0;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #6c757d;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid #007bff;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #007bff;
        }

        .select2-dropdown {
            border: 1px solid #007bff;
            border-radius: 0;
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
                <select name="page" class="js-example-placeholder-single js-states form-control" required>
                    <option value="" selected disabled>-- Pilih Laporan --</option>
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
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(".js-example-placeholder-single").select2({
            placeholder: "-- Pilih Laporan --",
            allowClear: true
        });
        // Handle submit form
        $('#myForm').on('submit', function(e) {
            e.preventDefault();

            // Validasi pilihan laporan
            if ($('#reportSelect').val() === null || $('#reportSelect').val() === '') {
                alert('Silakan pilih laporan terlebih dahulu');
                return false;
            }

            // Buka laporan di tab baru
            const formData = $(this).serialize();
            const url = $(this).attr('action') + '?' + formData;
            window.open(url, '_blank');

            // Reset form setelah 100ms
            setTimeout(() => {
                $('#reportSelect').val(null).trigger('change');
            }, 100);

            // Kirim data via POST
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                success: function() {
                    // Tidak perlu action tambahan
                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseText);
                }
            });
        });
    </script>
</body>

</html>