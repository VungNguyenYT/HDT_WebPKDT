<?php
include 'includes/header.php';
session_start();

include 'includes/db.php'; // Đường dẫn tới file db.php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Xử lý giỏ hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productID = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $_SESSION['cart'][$productID] = $quantity;
}

// Hiển thị giỏ hàng
$cart = $_SESSION['cart'] ?? [];
?>

<div class="container">
    <h1>Giỏ hàng</h1>
    <?php if (empty($cart)): ?>
        <p>Giỏ hàng trống.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Tổng</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($cart as $productID => $quantity):
                    $query = "SELECT * FROM Products WHERE ProductID = :id";
                    $stmt = $conn->prepare($query);
                    $stmt->execute(['id' => $productID]);
                    $product = $stmt->fetch(PDO::FETCH_ASSOC);
                    $subtotal = $product['Price'] * $quantity;
                    $total += $subtotal;
                    ?>
                    <tr>
                        <td><?= $product['ProductName'] ?></td>
                        <td><?= $quantity ?></td>
                        <td><?= number_format($product['Price'], 0, ',', '.') ?> VND</td>
                        <td><?= number_format($subtotal, 0, ',', '.') ?> VND</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Tổng cộng</td>
                    <td><?= number_format($total, 0, ',', '.') ?> VND</td>
                </tr>
            </tfoot>
        </table>
        <a href="checkout.php" class="btn">Thanh toán</a>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>