<?php
include 'includes/header.php';
session_start();
include 'includes/db.php'; // Kết nối cơ sở dữ liệu

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }

    $productID = $_POST['product_id'] ?? null;
    $quantity = $_POST['quantity'] ?? 1;

    if (!$productID || $quantity <= 0) {
        header('Location: product.php');
        exit;
    }

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$productID])) {
        $_SESSION['cart'][$productID] += $quantity;
    } else {
        $_SESSION['cart'][$productID] = $quantity;
    }

    header('Location: cart.php');
    exit;
}

$cart = $_SESSION['cart'] ?? [];

?>

<div class="container">
    <h1>Giỏ hàng của bạn</h1>
    <?php if (empty($cart)): ?>
        <p>Giỏ hàng trống. <a href="index.php">Tiếp tục mua sắm</a></p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Tổng</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($cart as $productID => $quantity):
                    $stmt = $conn->prepare("SELECT ProductName, Price, ImageURL FROM products WHERE ProductID = :id");
                    $stmt->execute(['id' => $productID]);
                    $product = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($product) {
                        $subtotal = $product['Price'] * $quantity;
                        $total += $subtotal;
                        ?>
                        <tr>
                            <td>
                                <img src="assets/images/<?= htmlspecialchars($product['ImageURL']) ?>" style="width: 50px;">
                                <?= htmlspecialchars($product['ProductName']) ?>
                            </td>
                            <td>
                                <form action="update_cart.php" method="post" style="display: inline;">
                                    <input type="hidden" name="product_id" value="<?= $productID ?>">
                                    <input type="number" name="quantity" value="<?= $quantity ?>" min="1" style="width: 50px;">
                                    <button type="submit" name="action" value="update">Cập nhật</button>
                                </form>
                            </td>
                            <td><?= number_format($product['Price'], 0, ',', '.') ?> VND</td>
                            <td><?= number_format($subtotal, 0, ',', '.') ?> VND</td>
                            <td>
                                <form action="update_cart.php" method="post" style="display: inline;">
                                    <input type="hidden" name="product_id" value="<?= $productID ?>">
                                    <button type="submit" name="action" value="delete">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    <?php
                    }
                endforeach;
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"><strong>Tổng cộng</strong></td>
                    <td colspan="2"><?= number_format($total, 0, ',', '.') ?> VND</td>
                </tr>
            </tfoot>
        </table>
        <a href="checkout.php" class="btn">Thanh toán</a>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>