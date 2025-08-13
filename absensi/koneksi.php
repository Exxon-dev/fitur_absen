<?php
if (session_status() == PHP_SESSION_NONE) {
session_start();
}
$host     = "localhost";
$username = 'root';
$pass 	  = '';
$db 	  = 'absensi';

$coneksi = mysqli_connect("localhost", "root", "", "absensi");

if(!$coneksi){
	echo "Koneksi failded";
}else{
	 echo " ";
}
date_default_timezone_set('Asia/Jakarta');

 ?>