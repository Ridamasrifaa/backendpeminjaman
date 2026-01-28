<?php
session_start();
include 'db.php';

// Cek apakah user sudah login
if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_name = $_SESSION['user_name'];
$user_role = $_SESSION['user_role'];
$user_id = $_SESSION['user_id'];
?>

<html>
<head>
    <title>Dashboard - <?php echo ucfirst($user_role); ?></title>
    <style>
        body { font-family: Arial; margin: 20px; background-color: #f4f4f4; }
        .container { max-width: 1000px; margin: 0 auto; }
        .header { background-color: #333; color: white; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .header h1 { margin: 0; }
        .header p { margin: 5px 0; }
        .menu { background-color: white; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .menu a { display: inline-block; padding: 10px 20px; margin-right: 10px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 3px; }
        .menu a:hover { background-color: #45a049; }
        .content { background-color: white; padding: 20px; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #4CAF50; color: white; }
        tr:hover { background-color: #f5f5f5; }
        .status { padding: 5px 10px; border-radius: 3px; color: white; }
        .approved { background-color: #4CAF50; }
        .pending { background-color: #ff9800; }
        .rejected { background-color: #f44336; }
        .info-box { background-color: #e3f2fd; padding: 15px; border-left: 4px solid #2196F3; margin-bottom: 20px; border-radius: 3px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Dashboard Sistem Peminjaman</h1>
        <p>Selamat datang, <strong><?php echo $user_name; ?></strong></p>
        <p>Role: <strong><?php echo ucfirst($user_role); ?></strong></p>
        <a href="logout.php" style="float: right; padding: 8px 15px; background-color: #f44336; text-decoration: none; color: white; border-radius: 3px;">Logout</a>
    </div>

    <div class="menu">
        <h3>Menu</h3>
        <?php if($user_role === 'anggota'): ?>
            <a href="view_loans.php">Lihat Data Peminjaman</a>
            <a href="dashboard.php?page=info">Informasi Anggota</a>
        <?php elseif($user_role === 'sekretaris'): ?>
            <a href="loans.php">Kelola Peminjaman</a>
            <a href="dashboard.php?page=reports">Laporan</a>
        <?php elseif($user_role === 'ketua'): ?>
            <a href="loans.php">Kelola Peminjaman</a>
            <a href="dashboard.php?page=reports">Laporan & Analisis</a>
            <a href="dashboard.php?page=users">Data User</a>
        <?php endif; ?>
    </div>

    <div class="content">
        <?php
        $page = isset($_GET['page']) ? $_GET['page'] : 'home';

        if($user_role === 'anggota'):
            if($page === 'info'):
        ?>
            <h2>Informasi Anggota</h2>
            <div class="info-box">
                Sebagai anggota, Anda dapat melihat daftar peminjaman koperasi dengan informasi yang terbatas untuk privasi.
            </div>
            <p>Untuk melihat daftar peminjaman, klik menu <strong>Lihat Data Peminjaman</strong> di atas.</p>

        <?php else: ?>
            <h2>Selamat Datang, <?php echo $user_name; ?>!</h2>
            <div class="info-box">
                Anda login sebagai <strong><?php echo ucfirst($user_role); ?></strong>.<br>
                Anda dapat melihat daftar peminjaman koperasi dengan informasi yang terbatas untuk privasi.
                <br><br>
                <a href="view_loans.php" style="color: #2196F3; text-decoration: none; font-weight: bold;">→ Lihat Data Peminjaman</a>
            </div>
        <?php endif;

        elseif($user_role === 'sekretaris'):
            if($page === 'reports'):
        ?>
            <h2>Laporan Peminjaman</h2>
            <div class="info-box">
                Laporan dan statistik peminjaman koperasi.
            </div>
            <table>
                <tr>
                    <th>Metrik</th>
                    <th>Jumlah</th>
                </tr>
                <?php
                $total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM loans"));
                $lunas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM loans WHERE status='lunas'"));
                $belum = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM loans WHERE status='belum lunas'"));
                $total_amount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) as sum FROM loans"));
                ?>
                <tr>
                    <td>Total Peminjam</td>
                    <td><?php echo $total['count']; ?></td>
                </tr>
                <tr>
                    <td>Peminjam Lunas</td>
                    <td><?php echo $lunas['count']; ?></td>
                </tr>
                <tr>
                    <td>Peminjam Belum Lunas</td>
                    <td><?php echo $belum['count']; ?></td>
                </tr>
                <tr>
                    <td>Total Jumlah Peminjaman</td>
                    <td>Rp <?php echo number_format($total_amount['sum'] ?? 0, 2, ',', '.'); ?></td>
                </tr>
            </table>

        <?php else: ?>
            <h2>Selamat Datang, <?php echo $user_name; ?>!</h2>
            <div class="info-box">
                Anda login sebagai <strong><?php echo ucfirst($user_role); ?></strong>.<br>
                Anda dapat mengelola semua data peminjaman koperasi, membuat laporan, dan mengelola data user.
                <br><br>
                <a href="loans.php" style="color: #2196F3; text-decoration: none; font-weight: bold;">→ Kelola Peminjaman</a>
            </div>
        <?php endif;

        elseif($user_role === 'ketua'):
            if($page === 'reports'):
        ?>
            <h2>Laporan & Analisis Peminjaman</h2>
            <div class="info-box">
                Laporan lengkap dan analisis peminjaman koperasi untuk pengambilan keputusan.
            </div>
            <table>
                <tr>
                    <th>Metrik</th>
                    <th>Jumlah</th>
                </tr>
                <?php
                $total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM loans"));
                $lunas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM loans WHERE status='lunas'"));
                $belum = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM loans WHERE status='belum lunas'"));
                $total_amount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) as sum FROM loans"));
                $lunas_amount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) as sum FROM loans WHERE status='lunas'"));
                $belum_amount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) as sum FROM loans WHERE status='belum lunas'"));
                $total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users"));
                ?>
                <tr>
                    <td>Total Peminjam</td>
                    <td><?php echo $total['count']; ?></td>
                </tr>
                <tr>
                    <td>Peminjam Lunas</td>
                    <td><?php echo $lunas['count']; ?></td>
                </tr>
                <tr>
                    <td>Peminjam Belum Lunas</td>
                    <td><?php echo $belum['count']; ?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Total Jumlah Peminjaman</td>
                    <td style="font-weight: bold;">Rp <?php echo number_format($total_amount['sum'] ?? 0, 2, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td>Total Peminjaman Lunas</td>
                    <td>Rp <?php echo number_format($lunas_amount['sum'] ?? 0, 2, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td>Total Peminjaman Belum Lunas</td>
                    <td>Rp <?php echo number_format($belum_amount['sum'] ?? 0, 2, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td>Total Pengguna Sistem</td>
                    <td><?php echo $total_users['count']; ?></td>
                </tr>
            </table>

        <?php elseif($page === 'users'): ?>
            <h2>Data User</h2>
            <div class="info-box">
                Menampilkan semua user yang terdaftar dalam sistem. Hanya Ketua yang dapat mengelola data user.
            </div>
            <table>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Tanggal Daftar</th>
                </tr>
                <?php
                $sql = "SELECT * FROM users ORDER BY created_at DESC";
                $result = mysqli_query($conn, $sql);
                $no = 1;
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $no . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td><span style='background-color: #e3f2fd; padding: 5px 10px; border-radius: 3px;'>" . ucfirst($row['role']) . "</span></td>";
                    echo "<td>" . date('d-m-Y', strtotime($row['created_at'])) . "</td>";
                    echo "</tr>";
                    $no++;
                }
                ?>
            </table>

        <?php else: ?>
            <h2>Selamat Datang, <?php echo $user_name; ?>!</h2>
            <div class="info-box">
                Anda login sebagai <strong><?php echo ucfirst($user_role); ?></strong>.<br>
                Sebagai Ketua, Anda memiliki akses penuh ke semua fitur sistem, dapat mengelola peminjaman, 
                melihat laporan lengkap, dan mengelola data user.
                <br><br>
                <a href="loans.php" style="color: #2196F3; text-decoration: none; font-weight: bold;">→ Kelola Peminjaman</a>
                &nbsp;&nbsp;&nbsp;
                <a href="dashboard.php?page=reports" style="color: #2196F3; text-decoration: none; font-weight: bold;">→ Lihat Laporan</a>
            </div>
        <?php endif;
        endif;
        ?>
    </div>
</div>
</body>
</html>
