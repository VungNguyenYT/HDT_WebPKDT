<?php
include 'includes/header.php';
include 'includes/db.php';
session_start();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $fullName = trim($_POST['full_name']);
    $email = trim($_POST['email']);

    if ($password !== $confirmPassword) {
        $error = 'Mật khẩu không khớp.';
    } else {
        // Kiểm tra tài khoản hoặc email đã tồn tại
        $query = "SELECT * FROM Users WHERE Username = :username OR Email = :email";
        $stmt = $conn->prepare($query);
        $stmt->execute(['username' => $username, 'email' => $email]);
        if ($stmt->rowCount() > 0) {
            $error = 'Tên đăng nhập hoặc email đã tồn tại.';
        } else {
            // Mã hóa mật khẩu và lưu vào cơ sở dữ liệu
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO Users (Username, Password, FullName, Email, Role) 
                      VALUES (:username, :password, :fullName, :email, 'Customer')";
            $stmt = $conn->prepare($query);
            $stmt->execute([
                'username' => $username,
                'password' => $hashedPassword,
                'fullName' => $fullName,
                'email' => $email
            ]);
            echo "<div class='container'><p>Tài khoản đã được tạo thành công. <a href='login.php'>Đăng nhập ngay</a></p></div>";
            include 'includes/footer.php';
            exit;
        }
    }
}
?>