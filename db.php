<?php
$conn = mysqli_connect("localhost", "root", "", "backendpeminjaman");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
