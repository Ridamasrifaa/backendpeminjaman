<?php
include 'db.php';

if(isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
    if(mysqli_query($conn, $sql)) {
        echo "Registrasi berhasil! Anda terdaftar sebagai " . $role;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<html>
<head>
    <title>Register Sistem Peminjaman</title>
    <style>
        body { font-family: Arial; margin: 50px; }
        form { border: 1px solid #ccc; padding: 20px; width: 300px; }
        input, select { width: 100%; padding: 8px; margin: 10px 0; box-sizing: border-box; }
        button { background-color: #4CAF50; color: white; padding: 10px; cursor: pointer; width: 100%; }
    </style>
</head>
<body>
<h2>Register Sistem Peminjaman</h2>
<form method="post">
    Name: <input type="text" name="name" required><br>
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    Role: 
    <select name="role" required>
        <option value="">-- Pilih Role --</option>
        <option value="anggota">Anggota</option>
        <option value="sekretaris">Sekretaris</option>
        <option value="ketua">Ketua</option>
    </select><br>
    <button type="submit" name="submit">Register</button>
</form>
<p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
</body>
</html>
