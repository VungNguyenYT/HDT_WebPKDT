<?php
include 'includes/db.php';
include 'includes/header.php';

session_start();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $address = trim($_POST['address']);

    if ($password !== $confirmPassword) {
        $error = 'Mật khẩu không khớp.';
    } else {

        $query = "SELECT * FROM Users WHERE Username = :username OR Email = :email";
        $stmt = $conn->prepare($query);
        $stmt->execute(['username' => $username, 'email' => $email]);
        if ($stmt->rowCount() > 0) {
            $error = 'Tên đăng nhập hoặc email đã tồn tại.';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO Users (Username, Password, FullName, Email, Phone, Address, Role) 
            VALUES (:username, :password, :fullName, :email, :phone, :address, 'Customer')";

            $stmt = $conn->prepare($query);
            $stmt->execute([
                'username' => $username,
                'password' => $hashedPassword,
                'fullName' => $fullName,
                'email' => $email,
                'phone' => $phone,
                'address' => $address
            ]);
            echo "<p>Tài khoản đã được tạo thành công. <a href='login.php'>Đăng nhập ngay</a></p>";
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký Tài Khoản</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Đăng Ký Tài Khoản</h1>
        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
        <form action="register.php" method="POST">
            <label for="full_name">Họ Tên Đầy Đủ:</label>
            <input type="text" id="full_name" name="full_name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="phone">Số Điện Thoại:</label>
            <input type="text" id="phone" name="phone" required>

            <label for="username">Tên Đăng Nhập:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Mật Khẩu:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Nhập Lại Mật Khẩu:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <label for="address">Địa Chỉ:</label>
            <input type="text" id="address" name="address" required>

            <button type="submit">Đăng Ký</button>
        </form>
        <p>Bạn đã có tài khoản? <a href="login.php">Đăng nhập ngay</a></p>
    </div>
</body>

</html>