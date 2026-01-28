<?php
session_start();
include 'db.php';

// Cek apakah user sudah login
if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_role = $_SESSION['user_role'];
$user_name = $_SESSION['user_name'];
$user_id = $_SESSION['user_id'];

// Hanya anggota yang bisa akses
if($user_role !== 'anggota') {
    header('Location: dashboard.php');
    exit();
}

// Function untuk menyensor nama
function censorName($name) {
    $parts = explode(' ', $name);
    $result = array();
    
    foreach($parts as $part) {
        if(strlen($part) <= 2) {
            $result[] = $part;
        } else {
            $result[] = substr($part, 0, 1) . str_repeat('*', strlen($part) - 2) . substr($part, -1);
        }
    }
    
    return implode(' ', $result);
}

// Function untuk menyensor uang (tampilkan 3 digit pertama dan 2 digit terakhir)
function censorAmount($amount) {
    $amount_str = (string)(int)$amount;
    $length = strlen($amount_str);
    
    if($length <= 5) {
        return str_repeat('*', $length - 1) . substr($amount_str, -1);
    }
    
    $first_3 = substr($amount_str, 0, 3);
    $last_2 = substr($amount_str, -2);
    $middle = str_repeat('*', $length - 5);
    
    return $first_3 . $middle . $last_2;
}
?>

<html>
<head>
    <title>Lihat Data Peminjaman</title>
    <style>
        body { font-family: Arial; margin: 20px; background-color: #f4f4f4; }
        .container { max-width: 1000px; margin: 0 auto; }
        .header { background-color: #333; color: white; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .header h1 { margin: 0; }
        .header a { float: right; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 3px; }
        .content { background-color: white; padding: 20px; border-radius: 5px; }
        .info-box { background-color: #e3f2fd; padding: 15px; border-left: 4px solid #2196F3; margin-bottom: 20px; border-radius: 3px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #4CAF50; color: white; }
        tr:hover { background-color: #f5f5f5; }
        .status-lunas { background-color: #d4edda; color: #155724; padding: 5px 10px; border-radius: 3px; font-weight: bold; }
        .status-belum { background-color: #fff3cd; color: #856404; padding: 5px 10px; border-radius: 3px; font-weight: bold; }
        .amount { text-align: right; font-family: monospace; font-weight: bold; }
        .note { font-size: 0.9em; color: #666; margin-top: 5px; }
        .read-only-notice { background-color: #fff9c4; padding: 12px; border-radius: 4px; margin-bottom: 20px; border-left: 4px solid #ff9800; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Data Peminjaman Koperasi</h1>
        <p>Selamat datang, <strong><?php echo $user_name; ?></strong></p>
        <a href="dashboard.php">← Kembali ke Dashboard</a>
    </div>

    <div class="read-only-notice">
        <strong>⚠️ Mode Tampilan Saja</strong> - Anda dapat melihat data peminjaman namun tidak dapat mengeditnya. 
        Beberapa informasi disensor untuk keamanan privasi.
    </div>

    <div class="content">
        <h2>Daftar Peminjaman Anggota</h2>
        
        <div class="info-box">
            <strong>Keterangan:</strong><br>
            • Nama peminjam ditampilkan dalam inisial untuk privasi<br>
            • Jumlah uang ditampilkan sebagian (3 digit awal dan 2 digit akhir, sisanya disensor)<br>
            • Status peminjaman ditampilkan dengan jelas<br>
            • Anda tidak memiliki akses untuk mengedit data
        </div>

        <table>
            <tr>
                <th>No</th>
                <th>Nama Peminjam <span style="font-size: 0.8em;">(Inisial)</span></th>
                <th>Jumlah Uang (Rp) <span style="font-size: 0.8em;">(Disensor)</span></th>
                <th>Tanggal</th>
                <th>Status</th>
            </tr>
            <?php
            $sql = "SELECT * FROM loans ORDER BY created_at DESC";
            $result = mysqli_query($conn, $sql);
            $no = 1;

            if(mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    $status_class = $row['status'] === 'lunas' ? 'status-lunas' : 'status-belum';
                    $censored_name = censorName($row['borrower_name']);
                    $censored_amount = censorAmount($row['amount']);
                    
                    echo "<tr>";
                    echo "<td>" . $no . "</td>";
                    echo "<td>" . $censored_name . "</td>";
                    echo "<td class='amount'>" . $censored_amount . "</td>";
                    echo "<td>" . date('d-m-Y', strtotime($row['loan_date'])) . "</td>";
                    echo "<td><span class='" . $status_class . "'>" . ucfirst($row['status']) . "</span></td>";
                    echo "</tr>";
                    $no++;
                }
            } else {
                echo "<tr><td colspan='5' style='text-align: center;'>Belum ada data peminjaman</td></tr>";
            }
            ?>
        </table>

        <div class="note" style="margin-top: 20px;">
            <strong>📌 Catatan:</strong> Data di atas adalah informasi peminjaman dari seluruh anggota koperasi. 
            Untuk detail peminjaman pribadi Anda atau untuk mengajukan peminjaman baru, silakan hubungi bagian administrasi.
        </div>
    </div>
</div>
</body>
</html>
