<?php
// koneksi ke database
$conn = mysqli_connect("localhost", "root", "12345", "backendpeminjaman");

// cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
