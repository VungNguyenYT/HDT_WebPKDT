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
            echo "<p style='color: green;'>Tài khoản đã được tạo thành công. <a href='login.php'>Đăng nhập ngay</a></p>";
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
</head>

<body style="font-family: Arial, sans-serif; background-color: #f5f5f5; margin: 0; padding: 0;">
    <div class="container"
        style="max-width: 400px; margin: 50px auto; padding: 20px; background-color: #fff; border: 1px solid #ddd; border-radius: 5px;">
        <h1 style="text-align: center; margin-bottom: 20px;">Đăng Ký Tài Khoản</h1>
        <?php if ($error): ?>
            <p class="error" style="color: red; text-align: center;"><?= $error ?></p>
        <?php endif; ?>
        <form action="register.php" method="POST">
            <label for="full_name" style="display: block; margin-bottom: 5px; font-weight: bold;">Họ Tên Đầy Đủ:</label>
            <input type="text" id="full_name" name="full_name" required
                style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px;">

            <label for="email" style="display: block; margin-bottom: 5px; font-weight: bold;">Email:</label>
            <input type="email" id="email" name="email" required
                style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px;">

            <label for="phone" style="display: block; margin-bottom: 5px; font-weight: bold;">Số Điện Thoại:</label>
            <input type="text" id="phone" name="phone" required
                style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px;">

            <label for="username" style="display: block; margin-bottom: 5px; font-weight: bold;">Tên Đăng Nhập:</label>
            <input type="text" id="username" name="username" required
                style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px;">

            <label for="password" style="display: block; margin-bottom: 5px; font-weight: bold;">Mật Khẩu:</label>
            <input type="password" id="password" name="password" required
                style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px;">

            <label for="confirm_password" style="display: block; margin-bottom: 5px; font-weight: bold;">Nhập Lại Mật
                Khẩu:</label>
            <input type="password" id="confirm_password" name="confirm_password" required
                style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px;">

            <label for="address" style="display: block; margin-bottom: 5px; font-weight: bold;">Địa Chỉ:</label>
            <input type="text" id="address" name="address" required
                style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px;">

            <button type="submit"
                style="width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">Đăng
                Ký</button>
        </form>
        <p style="text-align: center; margin-top: 15px;">Bạn đã có tài khoản? <a href="login.php"
                style="color: #007bff; text-decoration: none;">Đăng nhập ngay</a></p>
    </div>
</body>

</html>