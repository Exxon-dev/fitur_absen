<?php
// Set header JSON pertama kali
header('Content-Type: application/json');

// Aktifkan error reporting (hanya untuk debugging)
error_reporting(0); // Matikan di production
ini_set('display_errors', 0);

try {
    // 1. Validasi method request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method tidak diizinkan', 405);
    }

    // 2. Validasi field yang diperlukan
    if (empty($_POST['wa']) || empty($_POST['pesan'])) {
        throw new Exception('Nomor WA dan pesan harus diisi', 400);
    }

    // 3. Sanitasi input
    $nomor = preg_replace('/[^0-9]/', '', $_POST['wa']);
    $pesan = $_POST['pesan'];

    // 4. Validasi format nomor WA (62xxxxxxxxxx)
    if (!preg_match('/^62\d{9,13}$/', $nomor)) {
        throw new Exception('Format nomor WA tidak valid. Gunakan format 62xxxxxxxxxx', 400);
    }

    // 5. Persiapkan request API
    $token = 'VnNbhmFAwwLUAum3v9RV'; // Seharusnya disimpan di file config
    $data = [
        'target' => $nomor,
        'message' => $pesan,
        'delay' => '2-5',
        'countryCode' => '62'
    ];

    $ch = curl_init('https://api.fonnte.com/send');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            "Authorization: $token",
            "Content-Type: application/json"
        ],
        CURLOPT_TIMEOUT => 15,
        CURLOPT_SSL_VERIFYPEER => true
    ]);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($error) {
        throw new Exception("Gagal terhubung ke server: $error", 500);
    }

    $responseData = json_decode($response, true);

    // 6. Validasi respons API
    if ($httpCode !== 200 || !isset($responseData['status'])) {
        throw new Exception('Respon tidak valid dari server WhatsApp', 500);
    }

    // 7. Kembalikan respons sukses
    echo json_encode([
        'success' => true,
        'message' => "Pesan berhasil dikirim ke $nomor",
        'data' => $responseData
    ]);

} catch (Exception $e) {
    // Kembalikan respons error
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'code' => $e->getCode()
    ]);
}
?>