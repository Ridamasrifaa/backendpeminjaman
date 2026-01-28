<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_role = $_SESSION['user_role'];
$user_name = $_SESSION['user_name'];

if($user_role === 'anggota') {
    header('Location: dashboard.php');
    exit();
}

if(isset($_GET['delete']) && ($user_role === 'ketua' || $user_role === 'sekretaris')) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM loans WHERE id = $id";
    if(mysqli_query($conn, $sql)) {
        $msg = "Data berhasil dihapus!";
    } else {
        $msg = "Error: " . mysqli_error($conn);
    }
}

if(isset($_POST['update']) && ($user_role === 'ketua' || $user_role === 'sekretaris')) {
    $id = $_POST['id'];
    $borrower_name = $_POST['borrower_name'];
    $amount = $_POST['amount'];
    $status = $_POST['status'];
    $notes = $_POST['notes'];

    $sql = "UPDATE loans SET borrower_name='$borrower_name', amount=$amount, status='$status', notes='$notes' WHERE id=$id";
    if(mysqli_query($conn, $sql)) {
        $msg = "Data berhasil diupdate!";
    } else {
        $msg = "Error: " . mysqli_error($conn);
    }
}

if(isset($_POST['add']) && ($user_role === 'ketua' || $user_role === 'sekretaris')) {
    $borrower_name = $_POST['borrower_name'];
    $amount = $_POST['amount'];
    $loan_date = $_POST['loan_date'];
    $notes = $_POST['notes'];
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO loans (user_id, borrower_name, amount, loan_date, notes, status) 
            VALUES ($user_id, '$borrower_name', $amount, '$loan_date', '$notes', 'belum lunas')";
    if(mysqli_query($conn, $sql)) {
        $msg = "Data peminjaman berhasil ditambahkan!";
    } else {
        $msg = "Error: " . mysqli_error($conn);
    }
}

$edit_loan = null;
if(isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT * FROM loans WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $edit_loan = mysqli_fetch_assoc($result);
}
?>

<html>
<head>
    <title>Manajemen Peminjaman Koperasi</title>
    <style>
        body { font-family: Arial; margin: 20px; background-color: #f4f4f4; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { background-color: #333; color: white; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .header h1 { margin: 0; }
        .header a { float: right; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 3px; }
        .content { background-color: white; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .msg { padding: 12px; margin-bottom: 20px; border-radius: 4px; }
        .msg.success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .msg.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #4CAF50; color: white; }
        tr:hover { background-color: #f5f5f5; }
        .btn { padding: 8px 12px; text-decoration: none; border: none; border-radius: 3px; cursor: pointer; }
        .btn-edit { background-color: #2196F3; color: white; }
        .btn-delete { background-color: #f44336; color: white; }
        .btn-back { background-color: #666; color: white; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, textarea, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        textarea { resize: vertical; }
        .form-container { max-width: 600px; background-color: #f9f9f9; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .btn-submit { background-color: #4CAF50; color: white; padding: 12px 20px; }
        .btn-submit:hover { background-color: #45a049; }
        .amount { text-align: right; font-weight: bold; }
        .status-lunas { background-color: #d4edda; color: #155724; padding: 5px 10px; border-radius: 3px; }
        .status-belum { background-color: #fff3cd; color: #856404; padding: 5px 10px; border-radius: 3px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Manajemen Peminjaman Koperasi</h1>
        <p>Role: <strong><?php echo ucfirst($user_role); ?></strong></p>
        <a href="dashboard.php">← Kembali ke Dashboard</a>
    </div>

    <?php if(isset($msg)): ?>
        <div class="msg success"><?php echo $msg; ?></div>
    <?php endif; ?>

    <?php if($edit_loan): ?>
        <!-- FORM EDIT -->
        <div class="content">
            <h2>Edit Data Peminjaman</h2>
            <div class="form-container">
                <form method="post">
                    <input type="hidden" name="id" value="<?php echo $edit_loan['id']; ?>">
                    
                    <div class="form-group">
                        <label>Nama Peminjam:</label>
                        <input type="text" name="borrower_name" value="<?php echo $edit_loan['borrower_name']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Jumlah Uang (Rp):</label>
                        <input type="number" name="amount" step="0.01" value="<?php echo $edit_loan['amount']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Status:</label>
                        <select name="status" required>
                            <option value="belum lunas" <?php echo $edit_loan['status'] === 'belum lunas' ? 'selected' : ''; ?>>Belum Lunas</option>
                            <option value="lunas" <?php echo $edit_loan['status'] === 'lunas' ? 'selected' : ''; ?>>Lunas</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Catatan:</label>
                        <textarea name="notes"><?php echo $edit_loan['notes'] ?? ''; ?></textarea>
                    </div>

                    <button type="submit" name="update" class="btn btn-submit">Update Data</button>
                    <a href="loans.php" class="btn btn-back" style="display: inline-block; margin-left: 10px;">Batal</a>
                </form>
            </div>
        </div>
    <?php else: ?>
        <!-- FORM TAMBAH -->
        <div class="content">
            <h2>Tambah Data Peminjaman Baru</h2>
            <div class="form-container">
                <form method="post">
                    <div class="form-group">
                        <label>Nama Peminjam:</label>
                        <input type="text" name="borrower_name" required>
                    </div>

                    <div class="form-group">
                        <label>Jumlah Uang (Rp):</label>
                        <input type="number" name="amount" step="0.01" required>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Peminjaman:</label>
                        <input type="date" name="loan_date" required>
                    </div>

                    <div class="form-group">
                        <label>Catatan:</label>
                        <textarea name="notes" placeholder="Opsional"></textarea>
                    </div>

                    <button type="submit" name="add" class="btn btn-submit">Tambah Data</button>
                </form>
            </div>
        </div>

        <!-- DATA PEMINJAMAN -->
        <div class="content">
            <h2>Daftar Peminjaman</h2>
            <table>
                <tr>
                    <th>No</th>
                    <th>Nama Peminjam</th>
                    <th>Jumlah Uang (Rp)</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Catatan</th>
                    <th>Aksi</th>
                </tr>
                <?php
                $sql = "SELECT * FROM loans ORDER BY created_at DESC";
                $result = mysqli_query($conn, $sql);
                $no = 1;

                if(mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        $status_class = $row['status'] === 'lunas' ? 'status-lunas' : 'status-belum';
                        echo "<tr>";
                        echo "<td>" . $no . "</td>";
                        echo "<td>" . $row['borrower_name'] . "</td>";
                        echo "<td class='amount'>Rp " . number_format($row['amount'], 2, ',', '.') . "</td>";
                        echo "<td>" . date('d-m-Y', strtotime($row['loan_date'])) . "</td>";
                        echo "<td><span class='" . $status_class . "'>" . ucfirst($row['status']) . "</span></td>";
                        echo "<td>" . ($row['notes'] ? substr($row['notes'], 0, 30) . '...' : '-') . "</td>";
                        echo "<td>";
                        echo "<a href='loans.php?edit=" . $row['id'] . "' class='btn btn-edit'>Edit</a> ";
                        echo "<a href='loans.php?delete=" . $row['id'] . "' class='btn btn-delete' onclick='return confirm(\"Yakin ingin hapus?\")'>Hapus</a>";
                        echo "</td>";
                        echo "</tr>";
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='7' style='text-align: center;'>Belum ada data peminjaman</td></tr>";
                }
                ?>
            </table>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
