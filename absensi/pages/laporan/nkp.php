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
        s.pro_keahlian,
        s.tanggal_mulai,
        s.tanggal_selesai,
        s.TTL,
        s.NISN,
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
    <title>KUESIONER KEPUASAN PELANGGAN</title>
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
        <div class="text-center mb-4">
            <h1 class="text-lg font-semibold style9">KUESIONER KEPUASAN PELANGGAN</h1>
            <h2 class="text-lg font-semibold style9">TERHADAP PESERTA PKL <?php echo htmlspecialchars($data['nama_sekolah']); ?></h2>
        </div>
        <div class="mb-4">
            <label class="block mb-2 style9">Nama Industri / Perusahaan : <?php echo htmlspecialchars($data['id_perusahaan']); ?></label>
            <div class="border-b border-black w-full"></div>
        </div>
        <table class="w-full border-collapse border border-black text-center style9">
            <thead>
                <tr>
                    <th class="border border-black px-2 py-1 style9">No</th>
                    <th class="border border-black px-2 py-1 style9">Aspek</th>
                    <th colspan="4" class="border border-black px-2 py-1 style9">Kepuasan</th>
                    <th colspan="4" class="border border-black px-2 py-1 style9">Kepentingan</th>
                </tr>
                <tr>
                    <th class="border border-black px-2 py-1"></th>
                    <th class="border border-black px-2 py-1"></th>
                    <th class="border border-black px-2 py-1 style9">1</th>
                    <th class="border border-black px-2 py-1 style9">2</th>
                    <th class="border border-black px-2 py-1 style9">3</th>
                    <th class="border border-black px-2 py-1 style9">4</th>
                    <th class="border border-black px-2 py-1 style9">1</th>
                    <th class="border border-black px-2 py-1 style9">2</th>
                    <th class="border border-black px-2 py-1 style9">3</th>
                    <th class="border border-black px-2 py-1 style9">4</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border border-black px-2 py-1 style9">1</td>
                    <td class="border border-black px-2 py-1 text-left style9">Tanggung Jawab</td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                </tr>
                <tr>
                    <td class="border border-black px-2 py-1 style9">2</td>
                    <td class="border border-black px-2 py-1 text-left style9">Kerja Sama</td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                </tr>
                <tr>
                    <td class="border border-black px-2 py-1 style9">3</td>
                    <td class="border border-black px-2 py-1 text-left style9">Kemandirian</td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                </tr>
                <tr>
                    <td class="border border-black px-2 py-1 style9">4</td>
                    <td class="border border-black px-2 py-1 text-left style9">Kemampuan Adaptasi</td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                </tr>
                <tr>
                    <td class="border border-black px-2 py-1 style9">5</td>
                    <td class="border border-black px-2 py-1 text-left style9">Kompetensi</td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                </tr>
                <tr>
                    <td class="border border-black px-2 py-1 style9">6</td>
                    <td class="border border-black px-2 py-1 text-left style9">Kreatifitas</td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                </tr>
                <tr>
                    <td class="border border-black px-2 py-1 style9">7</td>
                    <td class="border border-black px-2 py-1 text-left style9">Inisiatif</td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                </tr>
                <tr>
                    <td class="border border-black px-2 py-1 style9">8</td>
                    <td class="border border-black px-2 py-1 text-left style9">Disiplin</td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                    <td class="border border-black px-2 py-1"></td>
                </tr>
            </tbody>
        </table>
        <div class="mt-4">
            <h3 class="font-semibold style9">Keterangan :</h3>
            <div class="flex mt-2 style9">
                <div class="w-1/2 ">
                    <p>Skoring Kepuasan :</p>
                    <p>1. Sangat Tidak Memuaskan</p>
                    <p>2. Tidak memuaskan</p>
                    <p>3. Memuaskan</p>
                    <p>4. Sangat Memuaskan</p>
                </div>
                <div class="w-1/2">
                    <p>Skoring Kepentingan :</p>
                    <p>1. Sangat Tidak penting</p>
                    <p>2. Tidak penting</p>
                    <p>3. Penting</p>
                    <p>4. Sangat Penting</p>
                </div>
            </div>
        </div>
        <div class="mt-4 style9">
            <h3 class="font-semibold">Kritik dan Saran :</h3><br>
            <div class="border-b border-black w-full mb-2"></div><br>
            <div class="border-b border-black w-full mb-2"></div><br>
            <div class="border-b border-black w-full mb-2"></div><br>
        </div>
        <div class="flex justify-between mt-8">
            <div class="w-1/2">
                <div class="border-b border-black w-1/2 mb-2 style9"></div>
                <p class="style9">Dunia Kerja</p>
            </div>
            <div class="w-1/2 text-right">
                <div class="border-b border-black w-1/2 mb-2 ml-auto"></div>
            </div>
        </div>
    </div>
</body>
</html>