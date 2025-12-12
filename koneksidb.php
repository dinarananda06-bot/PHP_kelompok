<?php
session_start();

// Koneksi ke database......................................................
$host = "192.168.10.252";
$username = "a122407270_user_barang";
$password = "janganseragam";
$database = "a122407270_db_barang";

$koneksidb = mysqli_connect($host, $username, $password, $database);

// Cek koneksi..............................................................
if (!$koneksidb) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
