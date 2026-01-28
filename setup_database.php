<?php
include 'db.php';

// SQL untuk membuat tabel users jika belum ada
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('anggota', 'sekretaris', 'ketua') DEFAULT 'anggota',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if(mysqli_query($conn, $sql)) {
    echo "✓ Tabel users berhasil dibuat/diperbarui<br>";
} else {
    echo "✗ Error tabel users: " . mysqli_error($conn) . "<br>";
}

// SQL untuk membuat tabel loans (peminjaman koperasi)
$sql2 = "CREATE TABLE IF NOT EXISTS loans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    borrower_name VARCHAR(100) NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    status ENUM('belum lunas', 'lunas') DEFAULT 'belum lunas',
    loan_date DATE NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

if(mysqli_query($conn, $sql2)) {
    echo "✓ Tabel loans berhasil dibuat/diperbarui<br>";
} else {
    echo "✗ Error tabel loans: " . mysqli_error($conn) . "<br>";
}

echo "<br><strong>Database setup selesai!</strong><br>";
echo "<a href='login.php'>Ke halaman login</a>";
?>
