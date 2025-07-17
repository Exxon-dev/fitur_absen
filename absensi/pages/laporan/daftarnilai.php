<?php
include('../../koneksi.php');

$id_siswa = $_GET['id_siswa'] ?? null;
if (!$id_siswa) {
    echo "ID siswa tidak ditemukan.";
    exit();
}

$query = " 
    SELECT 
        s.nama_siswa, 
        s.kelas, 
        s.pro_keahlian,
        s.tanggal_mulai,
        s.tanggal_selesai,
        p.nama_perusahaan AS id_perusahaan, 
        b.nama_pembimbing AS id_pembimbing,
        sk.nama_sekolah,
        sk.alamat_sekolah
    FROM 
        siswa s
    LEFT JOIN 
        perusahaan p ON s.id_perusahaan = p.id_perusahaan
    LEFT JOIN 
        pembimbing b ON s.id_pembimbing = b.id_pembimbing
    LEFT JOIN 
        sekolah sk ON s.id_sekolah = sk.id_sekolah
    WHERE 
        s.id_siswa = ? ";

$stmt = $coneksi->prepare($query);
$stmt->bind_param("i", $id_siswa);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    echo "Data tidak ditemukan.";
    exit();
}

$stmt->close();
$coneksi->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Nilai Praktik Kerja Lapangan</title>
    <style type="text/css">
        @page {
            size: A4;
            margin: 20mm;
        }
        .printable {
            margin: 20px;
        }
        @media print {
            .no-print {
                display: none; 
            }
        }
        .style6 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 16px;  }
        .style9 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; }
        .style10 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; }
        .top	{border-top: 1px solid #000000; }
        .bottom	{border-bottom: 1px solid #000000; }
        .left	{border-left: 1px solid #000000; }
        .right	{border-right: 1px solid #000000; }
        .all	{border: 1px solid #000000; }
        .style26 {font-family: Verdana, Arial, Helvetica, sans-serif}
        .style27 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: bold; }
        .align-justify {text-align:justify; }
        .align-center {text-align:center; }
        .align-right {text-align:right; }
    </style>
    <script>
        function printReport() {
            window.print();
        }
        
        window.onload = function() {
            printReport();
        };
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="printable">
        <h1 class="text-center text-xl font-bold mb-4 style9">DAFTAR NILAI PRAKTIK KERJA LAPANGAN</h1>
        <h2 class="text-center text-lg mb-4 style9">TAHUN 202...../202.....</h2>
        
        <div class="mb-2 style9">
            <div class="flex mb-2">
                <span class="w-1/5">Nama Peserta Didik</span>
                <span class="w-2/5">: <?php echo htmlspecialchars($data['nama_siswa']); ?></span>
            </div>
            <div class="flex mb-2 style9">
                <span class="w-1/5">Kelas</span>
                <span class="w-2/5">: <?php echo htmlspecialchars($data['kelas']); ?></span>
            </div>
            <div class="flex mb-2 style9">
                <span class="w-1/5">Program Keahlian</span>
                <span class="w-2/5">: <?php echo htmlspecialchars($data['pro_keahlian']); ?></span>
            </div>
            <div class="flex mb-2 style9">
                <span class="w-1/5">Tempat PKL</span>
                <span class="w-2/5">: <?php echo htmlspecialchars($data['id_perusahaan']); ?></span>
            </div>
            <div class="flex mb-2 style9">
                <span class="w-1/5">Tanggal PKL</span>
                <span class="w-2/5">: Mulai : <?php echo htmlspecialchars($data['tanggal_mulai']); ?> Selesai : <?php echo htmlspecialchars($data['tanggal_selesai']); ?></span>
            </div>
        </div>

        <table class="w-full border-collapse border border-gray-400 mb-4">
            <thead>
                <tr>
                    <th class="border border-gray-400 p-2 style9">Tujuan Pembelajaran</th>
                    <th class="border border-gray-400 p-2 style9">Skor</th>
                    <th class="border border-gray-400 p-2 style9">Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border border-gray-400 p-2 style9">
                        1. Menerapkan <i>soft skills</i> yang dibutuhkan dalam dunia kerja (tempat PKL)
                        <ul class="list-disc ml-6">
                            <li>Disiplin</li>
                            <li>Kerjasama</li>
                            <li>Tanggung Jawab</li>
                            <li>Inisiatif dan Kreatifitas</li>
                        </ul>
                    </td>
                    <td class="border border-gray-400 p-2"></td>
                    <td class="border border-gray-400 p-2"></td>
                </tr>
                <tr>
                    <td class="border border-gray-400 p-2 style9">
                        2. Menerapkan norma, POS dan K3LH yang ada pada dunia kerja (tempat PKL)
                    </td>
                    <td class="border border-gray-400 p-2"></td>
                    <td class="border border-gray-400 p-2"></td>
                </tr>
                <tr>
                    <td class="border border-gray-400 p-2 style9">
                        3. Menerapkan kompetensi teknis yang sudah dipelajari di sekolah dan/atau baru dipelajari pada dunia kerja (tempat PKL)
                        <ul class="list-disc ml-6">
                            <li>.......................................................</li>
                            <li>.......................................................</li>
                            <li>.......................................................</li>
                            <li>.......................................................</li>
                        </ul>
                    </td>
                    <td class="border border-gray-400 p-2"></td>
                    <td class="border border-gray-400 p-2"></td>
                </tr>
                <tr>
                    <td class="border border-gray-400 p-2 style9">
                        4. Memahami alur bisnis dunia kerja tempat PKL
                    </td>
                    <td class="border border-gray-400 p-2"></td>
                    <td class="border border-gray-400 p-2"></td>
                </tr>
            </tbody>
        </table>

        <div class="mb-4 style9">
            <span>Catatan :</span>
            <div class="border border-gray-400 h-24 mt-2"></div>
        </div>
        <div class="mb-4">
    <table class="border-collapse border border-gray-400">
        <thead>
            <tr>
                <th class="p-2 style9">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="border border-gray-400 p-2 style9">Kehadiran</td>
                <td class="border border-gray-400 p-2 style9">: ................ hari</td>
            </tr>
            <tr>
                <td class="border border-gray-400 p-2 style9">Sakit</td>
                <td class="border border-gray-400 p-2 style9">: ................ hari</td>
            </tr>
            <tr>
                <td class="border border-gray-400 p-2 style9">Ijin</td>
                <td class="border border-gray-400 p-2 style9">: ................ hari</td>
            </tr>
            <tr>
                <td class="border border-gray-400 p-2 style9">Tanpa Keterangan</td>
                <td class="border border-gray-400 p-2 style9">: ................ hari</td>
            </tr>
        </tbody>
    </table>
</div>

        <div class="flex justify-between mt-8">
            <div>
                <p class="mt-8 style9">Pembimbing Sekolah</p>
                <p class="mt-16 style9">.......................................................</p>
            </div>
            <div>
                <p class="style9">.............., .......................... 202.....</p>
                <p class="style9">Pimpinan/Pembimbing DUDI</p>
                <p class="mt-16 style9">.......................................................</p>
            </div>
        </div>
    </div>
</body>
</html>