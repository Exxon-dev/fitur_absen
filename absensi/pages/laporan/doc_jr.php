<?php
include('../../koneksi.php');

$id_siswa = $_GET['id_siswa'] ?? null;
if (!$id_siswa) {
    echo "ID siswa tidak ditemukan.";
    exit();
}

// ambil data siswa + pembimbing
$query_siswa = "
    SELECT 
        s.nama_siswa, 
        s.nisn, 
        s.pro_keahlian AS jurusan, 
        p.nama_pembimbing
    FROM siswa s
    LEFT JOIN pembimbing p ON s.id_pembimbing = p.id_pembimbing
    WHERE s.id_siswa = ?
";
$stmt_siswa = $coneksi->prepare($query_siswa);
$stmt_siswa->bind_param("i", $id_siswa);
$stmt_siswa->execute();
$result_siswa = $stmt_siswa->get_result();
$data_siswa = $result_siswa->fetch_assoc();

$nama_siswa      = $data_siswa['nama_siswa'] ?? '';
$nisn            = $data_siswa['nisn'] ?? '';
$jurusan         = $data_siswa['jurusan'] ?? '';
$nama_pembimbing = $data_siswa['nama_pembimbing'] ?? '........................';

<<<<<<< HEAD
// ambil data jurnal + catatan
=======
// Fungsi untuk format periode PKL (bulan dan tahun saja)
function formatPeriodePKL($tanggal_mulai, $tanggal_selesai)
{
    if (empty($tanggal_mulai) || empty($tanggal_selesai)) {
        return "Periode PKL Belum Ditentukan";
    }

    $bulan_indonesia = [
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    ];

    $bulan_mulai = (int)date('m', strtotime($tanggal_mulai));
    $tahun_mulai = date('Y', strtotime($tanggal_mulai));
    $bulan_selesai = (int)date('m', strtotime($tanggal_selesai));
    $tahun_selesai = date('Y', strtotime($tanggal_selesai));

    $nama_bulan_mulai = $bulan_indonesia[$bulan_mulai];
    $nama_bulan_selesai = $bulan_indonesia[$bulan_selesai];

    if ($tahun_mulai === $tahun_selesai) {
        return "$nama_bulan_mulai $tahun_mulai / $nama_bulan_selesai $tahun_selesai";
    } else {
        return "$nama_bulan_mulai $tahun_mulai / $nama_bulan_selesai $tahun_selesai";
    }
}

// Query untuk mengambil data jurnal
>>>>>>> 1ba93e3e1841f0db196d55408850db39c813b6be
$query_jurnal = "
    SELECT j.id_jurnal, j.tanggal, j.keterangan
    FROM jurnal j
    WHERE j.id_siswa = ? 
    ORDER BY j.tanggal ASC
";
$stmt_jurnal = $coneksi->prepare($query_jurnal);
$stmt_jurnal->bind_param("i", $id_siswa);
$stmt_jurnal->execute();
$result_jurnal = $stmt_jurnal->get_result();

$jurnal_data = [];
while ($row = $result_jurnal->fetch_assoc()) {
    $jurnal_data[] = $row;
}

<<<<<<< HEAD
// group per bulan
=======
// Ambil semua catatan untuk jurnal-jurnal ini
if (!empty($jurnal_data)) {
    $jurnal_ids = array_column($jurnal_data, 'id_jurnal');
    $placeholders = implode(',', array_fill(0, count($jurnal_ids), '?'));

    $query_catatan = "
        SELECT c.id_jurnal, c.catatan
        FROM catatan c
        WHERE c.id_jurnal IN ($placeholders)
        ORDER BY c.id_catatan ASC
    ";

    $stmt_catatan = $coneksi->prepare($query_catatan);
    $stmt_catatan->bind_param(str_repeat('i', count($jurnal_ids)), ...$jurnal_ids);
    $stmt_catatan->execute();
    $result_catatan = $stmt_catatan->get_result();

    $catatan_data = [];
    while ($row = $result_catatan->fetch_assoc()) {
        $catatan_data[$row['id_jurnal']][] = $row['catatan'];
    }

    // Gabungkan data jurnal dengan catatan
    foreach ($jurnal_data as &$jurnal) {
        $id_jurnal = $jurnal['id_jurnal'];
        $jurnal['catatan_list'] = $catatan_data[$id_jurnal] ?? [];
    }
}

// Buat judul berdasarkan jenis filter
if ($filter_type == 'daily') {
    $judul_periode = "Tanggal: " . date('d-m-Y', strtotime($start_date)) . " - " . date('d-m-Y', strtotime($end_date));
} elseif ($filter_type == 'monthly') {
    $month_names = [
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    ];
    $judul_periode = "Bulan: " . $month_names[$month - 1] . " " . $year;
} elseif ($filter_type == 'yearly') {
    $judul_periode = "Tahun: " . $year;
}

// group per bulan untuk multi-page
>>>>>>> 1ba93e3e1841f0db196d55408850db39c813b6be
$grouped_by_month = [];
foreach ($jurnal_data as $row) {
    $bulan = date("Y-m", strtotime($row['tanggal']));
    $grouped_by_month[$bulan][] = $row;
}
<<<<<<< HEAD
=======

// Jika tidak ada data, tampilkan pesan
if (empty($jurnal_data)) {
    echo "<p>Tidak ada data jurnal untuk periode yang dipilih.</p>";
    exit();
}
>>>>>>> 1ba93e3e1841f0db196d55408850db39c813b6be
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jurnal Prakerin</title>
    <style>
        body {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 11px;
        }
        .judul {
            text-align: center;
            font-weight: bold;
            font-size: 11px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 11px;
        }
<<<<<<< HEAD
=======

        .catatan-list {
            margin: 0;
            padding-left: 15px;
        }

>>>>>>> 1ba93e3e1841f0db196d55408850db39c813b6be
        .ttd {
            margin-top: 100px; /* kasih jarak lebih luas */
            width: 100%;
            text-align: right;
            font-size: 11px;
        }
        .ttd div {
            text-align: center;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
    <script>
        function printReport() {
            window.print();
        }
        window.onload = function() {
            printReport();
        };
    </script>
</head>
<body>

<?php foreach ($grouped_by_month as $bulan => $records): ?>
    <div class="judul">
        <div>JURNAL PRAKERIN</div>
        <div>SMA N 1 Magelang</div>
        <div>TAHUN AJARAN 2025/2026</div>
    </div>

    <br>
    <p>Nama : <?= htmlspecialchars($nama_siswa) ?></p>
    <p>NISN : <?= htmlspecialchars($nisn) ?></p>
    <p>Jurusan : <?= htmlspecialchars($jurusan) ?></p>

    <table>
        <tr>
            <th>No</th>
            <th>Hari/Tanggal</th>
            <th>Uraian Kegiatan/Uraian Materi</th>
            <th>Catatan</th>
            <th>Tanda Tangan</th>
        </tr>
        <?php $no=1; foreach ($records as $row): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= date("d-m-Y", strtotime($row['tanggal'])) ?></td>
                <td><?= htmlspecialchars($row['keterangan']) ?></td>
                <td><?= htmlspecialchars($row['catatan'] ?? '') ?></td>
                <td></td>
            </tr>
<<<<<<< HEAD
        <?php endforeach; ?>
=======
            <?php $no = 1;
            foreach ($records as $row): ?>
                <tr>
                    <td style="text-align: center;"><?= $no++ ?></td>
                    <td style="text-align: center;"><?= date("d-m-Y", strtotime($row['tanggal'])) ?></td>
                    <td><?= htmlspecialchars($row['keterangan']) ?></td>
                    <td>
                        <?php if (!empty($row['catatan_list'])): ?>
                            <ul class="catatan-list">
                                <?php foreach ($row['catatan_list'] as $catatan): ?>
                                    <li><?= htmlspecialchars($catatan) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td></td>
                </tr>
            <?php endforeach; ?>
>>>>>>> 1ba93e3e1841f0db196d55408850db39c813b6be

        <?php 
        // tambahin baris kosong biar 7 per halaman
        $remaining = 7 - count($records);
        for ($i=0; $i < $remaining; $i++): ?>
            <tr>
                <td>&nbsp;</td><td></td><td></td><td></td><td></td>
            </tr>
        <?php endfor; ?>
    </table>

    <!-- tanda tangan -->
    <div class="ttd">
        <div>
            <div>....................., ..................... 202...</div>
            <div style="margin-top:20px;">PEMBIMBING DUDI</div>
            <br><br><br><br><br><br>
            <div>(<?= htmlspecialchars($nama_pembimbing) ?>)</div>
        </div>
    </div>

    <div class="page-break"></div>
<?php endforeach; ?>

</body>
<<<<<<< HEAD
</html>
=======

</html>
>>>>>>> 1ba93e3e1841f0db196d55408850db39c813b6be
