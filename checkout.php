<?php
include 'includes/header.php';
include 'includes/db.php';
session_start();

// Kiểm tra giỏ hàng
$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    echo "<div class='container'><p>Giỏ hàng của bạn trống. <a href='index.php'>Mua sắm ngay</a></p></div>";
    include 'includes/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy thông tin từ form
    $customerName = $_POST['customer_name'];
    $customerPhone = $_POST['customer_phone'];
    $customerAddress = $_POST['customer_address'];
    $paymentMethod = $_POST['payment_method'];

    try {
        // Bắt đầu transaction
        $conn->beginTransaction();

        // Thêm thông tin vào bảng `orders`
        $query = "INSERT INTO orders (customer_name, contact, address, payment, OrderDate) 
                  VALUES (:name, :contact, :address, :payment, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->execute([
            'name' => $customerName,
            'contact' => $customerPhone,
            'address' => $customerAddress,
            'payment' => $paymentMethod
        ]);

        // Lấy OrderID vừa tạo
        $orderID = $conn->lastInsertId();

        // Thêm thông tin vào bảng `orderdetails`
        foreach ($cart as $productID => $quantity) {
            $query = "SELECT Price FROM products WHERE ProductID = :id";
            $stmt = $conn->prepare($query);
            $stmt->execute(['id' => $productID]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            $query = "INSERT INTO orderdetails (OrderID, ProductID, Quantity, Price) 
                      VALUES (:order_id, :product_id, :quantity, :price)";
            $stmt = $conn->prepare($query);
            $stmt->execute([
                'order_id' => $orderID,
                'product_id' => $productID,
                'quantity' => $quantity,
                'price' => $product['Price']
            ]);
        }

        // Xóa giỏ hàng sau khi đặt hàng
        unset($_SESSION['cart']);

        // Hoàn tất transaction
        $conn->commit();

        // Thông báo thành công
        echo "<div class='container'><p>Đơn hàng của bạn đã được đặt thành công. <a href='index.php'>Tiếp tục mua sắm</a></p></div>";
    } catch (PDOException $e) {
        // Rollback nếu có lỗi
        $conn->rollBack();
        echo "<div class='container'><p>Đã xảy ra lỗi: " . $e->getMessage() . "</p></div>";
    }
    include 'includes/footer.php';
    exit;
}
?>

<div style="max-width: 800px; margin: 50px auto; font-family: Arial, sans-serif;">
    <h1 style="text-align: center; margin-bottom: 20px;">Thanh Toán</h1>
    <form action="checkout.php" method="POST"
        style="background-color: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <h2 style="margin-bottom: 15px;">Thông tin khách hàng</h2>
        <label for="customer_name" style="display: block; margin-bottom: 5px; font-weight: bold;">Họ và tên:</label>
        <input type="text" id="customer_name" name="customer_name" required
            style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px;">

        <label for="customer_phone" style="display: block; margin-bottom: 5px; font-weight: bold;">Số điện
            thoại:</label>
        <input type="text" id="customer_phone" name="customer_phone" required
            style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px;">

        <label for="customer_address" style="display: block; margin-bottom: 5px; font-weight: bold;">Địa chỉ:</label>
        <textarea id="customer_address" name="customer_address" required
            style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px; resize: none;"></textarea>

        <h2 style="margin-bottom: 15px;">Giỏ hàng</h2>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <thead>
                <tr style="background-color: #f8f9fa;">
                    <th style="padding: 10px; border: 1px solid #ddd;">Sản phẩm</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Số lượng</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Giá</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Tổng</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($cart as $productID => $quantity):
                    $query = "SELECT ProductName, Price FROM products WHERE ProductID = :id";
                    $stmt = $conn->prepare($query);
                    $stmt->execute(['id' => $productID]);
                    $product = $stmt->fetch(PDO::FETCH_ASSOC);
                    $subtotal = $product['Price'] * $quantity;
                    $total += $subtotal;
                    ?>
                    <tr>
                        <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($product['ProductName']) ?>
                        </td>
                        <td style="padding: 10px; border: 1px solid #ddd; text-align: center;"><?= $quantity ?></td>
                        <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">
                            <?= number_format($product['Price'], 0, ',', '.') ?> VND</td>
                        <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">
                            <?= number_format($subtotal, 0, ',', '.') ?> VND</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="padding: 10px; border: 1px solid #ddd; text-align: right;"><strong>Tổng
                            cộng</strong></td>
                    <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">
                        <?= number_format($total, 0, ',', '.') ?> VND</td>
                </tr>
            </tfoot>
        </table>

        <h2 style="margin-bottom: 15px;">Phương thức thanh toán</h2>
        <label style="display: block; margin-bottom: 10px;">
            <input type="radio" name="payment_method" value="COD" checked style="margin-right: 10px;"> Thanh toán khi
            nhận hàng (COD)
        </label>
        <label style="display: block; margin-bottom: 20px;">
            <input type="radio" name="payment_method" value="Transfer" style="margin-right: 10px;"> Chuyển khoản ngân
            hàng
        </label>

        <button type="submit"
            style="width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">Đặt
            hàng</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>