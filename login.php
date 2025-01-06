<?php
include 'includes/db.php';
include 'includes/header.php';

session_start();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM Users WHERE Username = :username";
    $stmt = $conn->prepare($query);
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['Password'])) {
        $_SESSION['user'] = [
            'UserID' => $user['UserID'],
            'Username' => $user['Username'],
            'Role' => $user['Role']
        ];
        header('Location: index.php');
        exit;
    } else {
        $error = 'Tên đăng nhập hoặc mật khẩu không đúng.';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f5f5f5; margin: 0; padding: 0;">
    <div class="container"
        style="max-width: 400px; margin: 50px auto; padding: 20px; background-color: #fff; border: 1px solid #ddd; border-radius: 5px;">
        <h1 style="text-align: center; margin-bottom: 20px;">Đăng Nhập</h1>
        <?php if ($error): ?>
            <p class="error" style="color: red; text-align: center;"><?= $error ?></p>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <label for="username" style="display: block; margin-bottom: 5px; font-weight: bold;">Tên Đăng Nhập:</label>
            <input type="text" id="username" name="username" required
                style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px;">

            <label for="password" style="display: block; margin-bottom: 5px; font-weight: bold;">Mật Khẩu:</label>
            <input type="password" id="password" name="password" required
                style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px;">

            <button type="submit"
                style="width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">Đăng
                Nhập</button>
        </form>
        <p style="text-align: center; margin-top: 15px;">Bạn chưa có tài khoản? <a href="register.php"
                style="color: #007bff; text-decoration: none;">Đăng ký ngay</a></p>
    </div>
</body>

</html>