<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];

echo "<h3>Login sebagai: $role</h3><hr>";

/* =======================
   ROLE KETUA
   ======================= */
if ($role == 'ketua') {

    $sql = "SELECT nama_peminjam, jumlah, status FROM peminjaman";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        echo "Nama : {$row['nama_peminjam']} <br>";
        echo "Jumlah : {$row['jumlah']} <br>";
        echo "Status : {$row['status']} <br><br>";
    }

/* =======================
   ROLE ANGGOTA
   ======================= */
} else if ($role == 'anggota') {

    $sql = "SELECT nama_peminjam, jumlah, status FROM peminjaman";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {

        // Nama → inisial saja
        $nama = substr($row['nama_peminjam'], 0, 1) . "***";

        // Jumlah → tampilkan 3 digit awal saja
        $jumlah = substr($row['jumlah'], 0, 3) . "xxx";

        echo "Nama : $nama <br>";
        echo "Jumlah : $jumlah <br>";
        echo "Status : {$row['status']} <br><br>";
    }
}
?>

<a href="logout.php">Logout</a>
