<?php
session_start();
include 'db.php';

if(isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id,name,email,password,role FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if(password_verify($password, $user['password'])) {
            // Simpan data user di session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            
            header('Location: dashboard.php');
            exit();
        } else {
            echo "Password salah!";
        }
    } else {
        echo "Email tidak ditemukan!";
    }
}
?>

<html>
<head>
    <title>Login Sistem peminjaman</title>
    <style>
        body { font-family: Arial; margin: 50px; }
        form { border: 1px solid #ccc; padding: 20px; width: 300px; }
        input { width: 100%; padding: 8px; margin: 10px 0; box-sizing: border-box; }
        button { background-color: #4CAF50; color: white; padding: 10px; cursor: pointer; width: 100%; }
        .error { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>
<h2>Login Sistem peminjaman</h2>
<form method="post">
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit" name="submit">Login</button>
</form>
<p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
</body>
</html>
