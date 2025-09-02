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

// ambil data jurnal + catatan
$query_jurnal = "
    SELECT j.tanggal, j.keterangan, c.catatan
    FROM jurnal j
    LEFT JOIN catatan c ON j.id_jurnal = c.id_jurnal
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

// group per bulan
$grouped_by_month = [];
foreach ($jurnal_data as $row) {
    $bulan = date("Y-m", strtotime($row['tanggal']));
    $grouped_by_month[$bulan][] = $row;
}
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
        .ttd {
            margin-top: 100px; /* kasih jarak lebih luas */
            width: 100%;
            text-align: right;
            font-size: 11px;
        }
        .ttd div {
            text-align: right;
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
        <?php endforeach; ?>

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
            <div>................, .............. 202...</div>
            <div style="margin-top:20px;">PEMBIMBING DUDI</div>
            <br><br><br><br><br><br>
            <div>(<?= htmlspecialchars($nama_pembimbing) ?>)</div>
        </div>
    </div>

    <div class="page-break"></div>
<?php endforeach; ?>

</body>
</html>