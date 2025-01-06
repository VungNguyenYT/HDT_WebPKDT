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

<div style="max-width: 800px; margin: 50px auto; font-family: Arial, sans-serif;">
    <h1 style="text-align: center; margin-bottom: 20px;">Giỏ hàng của bạn</h1>
    <?php if (empty($cart)): ?>
        <p style="text-align: center;">Giỏ hàng trống. <a href="index.php"
                style="color: #007bff; text-decoration: none;">Tiếp tục mua sắm</a></p>
    <?php else: ?>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <thead>
                <tr style="background-color: #f8f9fa;">
                    <th style="padding: 10px; border: 1px solid #ddd;">Sản phẩm</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Số lượng</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Giá</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Tổng</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Thao tác</th>
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
                            <td style="padding: 10px; border: 1px solid #ddd; display: flex; align-items: center;">
                                <img src="assets/images/<?= htmlspecialchars($product['ImageURL']) ?>" alt="Product"
                                    style="width: 50px; height: auto; margin-right: 10px;">
                                <?= htmlspecialchars($product['ProductName']) ?>
                            </td>
                            <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">
                                <form action="update_cart.php" method="post" style="display: inline;">
                                    <input type="hidden" name="product_id" value="<?= $productID ?>">
                                    <input type="number" name="quantity" value="<?= $quantity ?>" min="1"
                                        style="width: 50px; text-align: center;">
                                    <button type="submit" name="action" value="update"
                                        style="margin-left: 5px; padding: 5px 10px; background-color: #007bff; color: white; border: none; border-radius: 3px; cursor: pointer;">Cập
                                        nhật</button>
                                </form>
                            </td>
                            <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">
                                <?= number_format($product['Price'], 0, ',', '.') ?> VND</td>
                            <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">
                                <?= number_format($subtotal, 0, ',', '.') ?> VND</td>
                            <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">
                                <form action="update_cart.php" method="post" style="display: inline;">
                                    <input type="hidden" name="product_id" value="<?= $productID ?>">
                                    <button type="submit" name="action" value="delete"
                                        style="padding: 5px 10px; background-color: #dc3545; color: white; border: none; border-radius: 3px; cursor: pointer;">Xóa</button>
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
                    <td colspan="3" style="padding: 10px; border: 1px solid #ddd; text-align: right;"><strong>Tổng
                            cộng</strong></td>
                    <td colspan="2" style="padding: 10px; border: 1px solid #ddd; text-align: center;">
                        <?= number_format($total, 0, ',', '.') ?> VND</td>
                </tr>
            </tfoot>
        </table>
        <div style="text-align: right;">
            <a href="checkout.php"
                style="padding: 10px 20px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px;">Thanh
                toán</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>