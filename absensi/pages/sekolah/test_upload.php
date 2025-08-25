<?php
// test_upload.php
if ($_FILES) {
    echo "<h2>Hasil Upload:</h2>";
    echo "<pre>";
    print_r($_FILES);
    echo "</pre>";
    
    // Coba upload file
    $target_file = 'test_upload_' . time() . '.txt';
    if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
        echo "<p style='color: green;'>Upload berhasil! File disimpan sebagai: " . $target_file . "</p>";
        
        // Tampilkan isi file jika berhasil diupload
        if (file_exists($target_file)) {
            echo "<h3>Isi File:</h3>";
            echo "<pre>" . htmlspecialchars(file_get_contents($target_file)) . "</pre>";
        }
    } else {
        echo "<p style='color: red;'>Upload gagal: " . (error_get_last()['message'] ?? 'Unknown error') . "</p>";
        
        // Tampilkan informasi error lebih detail
        echo "<h3>Informasi Error:</h3>";
        echo "<pre>";
        print_r(error_get_last());
        echo "</pre>";
        
        // Cek izin direktori
        echo "<h3>Informasi Direktori:</h3>";
        echo "Current directory: " . getcwd() . "<br>";
        echo "Is writable: " . (is_writable('.') ? 'Yes' : 'No') . "<br>";
    }
    
    // Tampilkan informasi konfigurasi PHP
    echo "<h3>Konfigurasi PHP:</h3>";
    echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "<br>";
    echo "post_max_size: " . ini_get('post_max_size') . "<br>";
    echo "file_uploads: " . (ini_get('file_uploads') ? 'On' : 'Off') . "<br>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Test Upload File</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .form-container { background: #f5f5f5; padding: 20px; border-radius: 5px; }
        .info { background: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Test Upload File</h1>
    
    <div class="info">
        <strong>Informasi Server:</strong><br>
        PHP Version: <?php echo phpversion(); ?><br>
        Server: <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?><br>
        Current Directory: <?php echo getcwd(); ?><br>
        Directory Writable: <?php echo is_writable('.') ? 'Yes' : 'No'; ?>
    </div>
    
    <div class="form-container">
        <form method="post" enctype="multipart/form-data">
            <p>
                <label>Pilih file untuk diupload: </label><br>
                <input type="file" name="file">
            </p>
            <p>
                <input type="submit" value="Test Upload">
            </p>
        </form>
    </div>
    
    <?php if (!$_FILES): ?>
    <div style="margin-top: 20px; padding: 15px; background: #fff3cd; border-radius: 5px;">
        <strong>Petunj Testing:</strong><br>
        1. Klik "Browse" untuk memilih file<br>
        2. Klik "Test Upload" untuk mengupload file<br>
        3. Lihat hasilnya di bagian atas halaman
    </div>
    <?php endif; ?>
</body>
</html>