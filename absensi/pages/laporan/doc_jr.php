<?php
include('../../koneksi.php');
$filter_params = $_SESSION['filter_params'] ?? [];
$id_siswa = $filter_params['id_siswa'] ?? $_GET['id_siswa'] ?? null;
$filter_type = $filter_params['filter_type'] ?? 'daily';

if (!$id_siswa) {
    echo "ID siswa tidak ditemukan.";
    exit();
}

// Set default values untuk filter
$start_date = $filter_params['start_date'] ?? date('Y-m-d');
$end_date = $filter_params['end_date'] ?? date('Y-m-d');
$month = $filter_params['month'] ?? date('m');
$year = $filter_params['year'] ?? date('Y');

// ambil data siswa + pembimbing + tanggal PKL
$query_siswa = "
    SELECT 
        s.nama_siswa, 
        s.nisn, 
        s.pro_keahlian AS jurusan, 
        p.nama_pembimbing,
        s.tanggal_mulai,
        s.tanggal_selesai
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
$tanggal_mulai   = $data_siswa['tanggal_mulai'] ?? '';
$tanggal_selesai = $data_siswa['tanggal_selesai'] ?? '';

// Fungsi untuk format periode PKL (bulan dan tahun saja)
function formatPeriodePKL($tanggal_mulai, $tanggal_selesai) {
    if (empty($tanggal_mulai) || empty($tanggal_selesai)) {
        return "Periode PKL Belum Ditentukan";
    }
    
    $bulan_indonesia = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
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

// Query jurnal dengan filter
$query_jurnal = "
    SELECT j.tanggal, j.keterangan, c.catatan
    FROM jurnal j
    LEFT JOIN catatan c ON j.id_jurnal = c.id_jurnal
    WHERE j.id_siswa = ? 
";

// Tambahkan kondisi WHERE berdasarkan jenis filter
if ($filter_type == 'daily') {
    $query_jurnal .= " AND j.tanggal BETWEEN ? AND ? ";
} elseif ($filter_type == 'monthly') {
    $query_jurnal .= " AND MONTH(j.tanggal) = ? AND YEAR(j.tanggal) = ? ";
} elseif ($filter_type == 'yearly') {
    $query_jurnal .= " AND YEAR(j.tanggal) = ? ";
}

$query_jurnal .= " ORDER BY j.tanggal ASC";

$stmt_jurnal = $coneksi->prepare($query_jurnal);

// Bind parameter berdasarkan jenis filter
if ($filter_type == 'daily') {
    $stmt_jurnal->bind_param("iss", $id_siswa, $start_date, $end_date);
} elseif ($filter_type == 'monthly') {
    $stmt_jurnal->bind_param("iis", $id_siswa, $month, $year);
} elseif ($filter_type == 'yearly') {
    $stmt_jurnal->bind_param("ii", $id_siswa, $year);
}

$stmt_jurnal->execute();
$result_jurnal = $stmt_jurnal->get_result();

$jurnal_data = [];
while ($row = $result_jurnal->fetch_assoc()) {
    $jurnal_data[] = $row;
}

// Buat judul berdasarkan jenis filter
if ($filter_type == 'daily') {
    $judul_periode = "Tanggal: " . date('d-m-Y', strtotime($start_date)) . " - " . date('d-m-Y', strtotime($end_date));
} elseif ($filter_type == 'monthly') {
    $month_names = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $judul_periode = "Bulan: " . $month_names[$month - 1] . " " . $year;
} elseif ($filter_type == 'yearly') {
    $judul_periode = "Tahun: " . $year;
}

// group per bulan untuk multi-page
$grouped_by_month = [];
foreach ($jurnal_data as $row) {
    $bulan = date("Y-m", strtotime($row['tanggal']));
    $grouped_by_month[$bulan][] = $row;
}

// Jika tidak ada data, tampilkan pesan
if (empty($jurnal_data)) {
    echo "<p>Tidak ada data jurnal untuk periode yang dipilih.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Jurnal Prakerin - <?= $judul_periode ?></title>
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

        .periode-pkl {
            text-align: center;
            font-size: 11px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .periode-filter {
            text-align: center;
            font-size: 11px;
            margin-bottom: 10px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 11px;
        }

        .ttd {
            margin-top: 100px;
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

        .no-print {
            margin-top: 20px;
            text-align: center;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <?php foreach ($grouped_by_month as $bulan => $records): ?>
        <div class="judul">
            <div>JURNAL PRAKERIN</div>
            <div>SMA N 1 Magelang</div>
            <div class="periode-pkl" style="font-size: 11px;"><?= formatPeriodePKL($tanggal_mulai, $tanggal_selesai) ?></div>
            <div class="periode-filter"><?= $judul_periode ?></div>
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
            <?php $no = 1;
            foreach ($records as $row): ?>
                <tr>
                    <td style="text-align: center;"><?= $no++ ?></td>
                    <td style="text-align: center;"><?= date("d-m-Y", strtotime($row['tanggal'])) ?></td>
                    <td><?= htmlspecialchars($row['keterangan']) ?></td>
                    <td><?= htmlspecialchars($row['catatan'] ?? '') ?></td>
                    <td></td>
                </tr>
            <?php endforeach; ?>

            <?php
            // tambahin baris kosong biar 7 per halaman
            $remaining = 7 - count($records);
            for ($i = 0; $i < $remaining; $i++): ?>
                <tr>
                    <td>&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            <?php endfor; ?>
        </table>

        <!-- tanda tangan -->
        <div class="ttd">
            <div>
                <div>................., .......... <?= date('Y') ?></div>
                <div style="margin-top:20px;">PEMBIMBING DUDI</div>
                <br><br><br><br><br><br>
                <div>(<?= htmlspecialchars($nama_pembimbing) ?>)</div>
            </div>
        </div>

        <?php if (count($grouped_by_month) > 1): ?>
            <div class="page-break"></div>
        <?php endif; ?>
    <?php endforeach; ?>
</body>
</html>