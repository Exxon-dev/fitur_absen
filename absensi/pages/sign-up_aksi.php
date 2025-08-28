<?php

session_start(); // Pastikan session_start() ada di awal
unset($_SESSION['username']);
unset($_SESSION['login']);
session_destroy();

// Hapus localStorage dan redirect
echo '<script>
    localStorage.removeItem("siswaWelcomeShown");
    localStorage.removeItem("pembimbingAlertShown");
    localStorage.removeItem("adminWelcomeShown");
    localStorage.removeItem("guruWelcomeShown");
    window.location.href = "sign-in.php";
</script>';
exit();
?>
