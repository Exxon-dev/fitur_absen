<?php
include '../koneksi.php'; // Ubah sesuai lokasi file koneksi kamu

// Tanggal awal dan akhir minggu yang ingin dilihat
$tanggal_awal = '2025-07-21'; // Senin
$tanggal_akhir = '2025-07-26'; // Sabtu

// Ambil semua siswa
$siswa_q = mysqli_query($conn, "SELECT * FROM siswa ORDER BY nama_siswa");
$no = 1;

function formatJam($jam) {
    return $jam ? substr($jam, 0, 5) : '-';
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Absensi Mingguan</title>
    <style>
        table { border-collapse: collapse; width: 100%; font-size: 12px; }
        th, td { border: 1px solid black; text-align: center; padding: 5px; }
        th.rotate {
            height: 140px;
            white-space: nowrap;
        }
        th.rotate > div {
            transform: 
                translate(10px, 60px)
                rotate(-90deg);
            width: 20px;
        }
    </style>
</head>
<body>

<h2>LAPORAN DAFTAR HADIR MINGGUAN SISWA</h2>
<p>Periode: <?= $tanggal_awal ?> s.d <?= $tanggal_akhir ?></p>

<table>
    <tr>
        <th rowspan="2">No</th>
        <th rowspan="2">Nama</th>
        <?php 
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at", 'Sabtu'];
        foreach ($days as $day): ?>
            <th colspan="2"><?= $day ?></th>
        <?php endforeach; ?>
        <th colspan="3">Jumlah Kehadiran</th>
        <th rowspan="2">Keterangan</th>
    </tr>
    <tr>
        <?php foreach ($days as $d): ?>
            <th>Masuk</th><th>Pulang</th>
        <?php endforeach; ?>
        <th>S</th><th>I</th><th>A</th>
    </tr>

    <?php while($s = mysqli_fetch_assoc($siswa_q)): ?>
        <?php
        $id_siswa = $s['id_siswa'];
        $nama = $s['nama_siswa'];

        $hadir_data = [];
        $sakit = $izin = $alfa = 0;

        for ($i = 0; $i < 6; $i++) {
            $tanggal = date('Y-m-d', strtotime("$tanggal_awal +$i days"));

            $absen = mysqli_query($conn, "SELECT * FROM absen WHERE id_siswa='$id_siswa' AND tanggal='$tanggal'");
            $data = mysqli_fetch_assoc($absen);

            if ($data) {
                $hadir_data[$tanggal] = [
                    'masuk' => $data['jam_masuk'],
                    'pulang' => $data['jam_keluar'],
                    'ket' => strtolower($data['keterangan'])
                ];

                if ($data['keterangan'] == 'Sakit') $sakit++;
                if ($data['keterangan'] == 'Izin') $izin++;
                if ($data['keterangan'] == 'Alfa') $alfa++;
            } else {
                $hadir_data[$tanggal] = ['masuk' => null, 'pulang' => null, 'ket' => 'alfa'];
                $alfa++;
            }
        }
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $nama ?></td>
            <td><?= $kelas ?></td>
            <?php for ($i = 0; $i < 6; $i++): 
                $tanggal = date('Y-m-d', strtotime("$tanggal_awal +$i days"));
                $abs = $hadir_data[$tanggal];
            ?>
                <td><?= formatJam($abs['masuk']) ?></td>
                <td><?= formatJam($abs['pulang']) ?></td>
            <?php endfor; ?>
            <td><?= $sakit ?></td>
            <td><?= $izin ?></td>
            <td><?= $alfa ?></td>
            <td></td>
        </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
