<?php
$conn = new mysqli("localhost", "root", "", "db_peminjaman");

if ($conn->connect_error) {
    die("Koneksi database gagal");
}
?>
