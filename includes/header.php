<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">

    <title>Website Bán Phụ Kiện Điện Thoại</title>

</head>

<body>
    <header
        style="background: linear-gradient(90deg, #007bff, #0056b3); color: #fff; padding: 20px 0; position: sticky; top: 0; z-index: 1000; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <div style="width: 80%; margin: 0 auto; display: flex; justify-content: space-between; align-items: center;">
            <!-- Logo -->
            <div class="logo">
                <a href="index.php"
                    style="text-decoration: none; font-size: 28px; font-weight: bold; color: #fff;">GEEHES <span
                        style="color: #ffcc00;">STORE</span></a>
            </div>

            <!-- Thanh tìm kiếm -->
            <div class="search-bar" style="display: flex; align-items: center;">
                <form action="search_results.php" method="GET" style="display: flex;">
                    <input type="text" name="query" placeholder="Tìm kiếm sản phẩm..." required
                        style="padding: 10px; border: none; border-radius: 5px 0 0 5px; outline: none; width: 200px;">
                    <button type="submit"
                        style="padding: 10px; border: none; background-color: #ffcc00; border-radius: 0 5px 5px 0; cursor: pointer; font-size: 16px;">🔍</button>
                </form>
            </div>

            <!-- Menu điều hướng -->
            <nav>
                <ul style="list-style: none; display: flex; gap: 20px; margin: 0; padding: 0;">
                    <li><a href="index.php"
                            style="text-decoration: none; color: #fff; font-size: 16px; transition: color 0.3s ease;">Trang
                            chủ</a></li>
                    <li><a href="cart.php"
                            style="text-decoration: none; color: #fff; font-size: 16px; transition: color 0.3s ease;">Giỏ
                            hàng</a></li>
                    <li><a href="login.php"
                            style="text-decoration: none; color: #fff; font-size: 16px; transition: color 0.3s ease;">Đăng
                            nhập</a></li>
                    <li><a href="register.php"
                            style="text-decoration: none; color: #fff; font-size: 16px; transition: color 0.3s ease;">Đăng
                            ký</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>

        <main>