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

// Xử lý đơn hàng sau khi người dùng nhấn "Đặt hàng"
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerName = $_POST['customer_name'];
    $customerPhone = $_POST['customer_phone'];
    $customerAddress = $_POST['customer_address'];
    $paymentMethod = $_POST['payment_method'];

    // Thêm thông tin vào bảng Orders
    $query = "INSERT INTO Orders (CustomerName, Phone, Address, PaymentMethod, OrderDate) 
              VALUES (:name, :phone, :address, :payment)";
    $stmt = $conn->prepare($query);
    $stmt->execute([
        'name' => $customerName,
        'phone' => $customerPhone,
        'address' => $customerAddress,
        'payment' => $paymentMethod
    ]);

    // Lấy OrderID vừa tạo
    $orderID = $conn->lastInsertId();

    // Thêm thông tin vào bảng OrderDetails
    foreach ($cart as $productID => $quantity) {
        $query = "SELECT Price FROM Products WHERE ProductID = :id";
        $stmt = $conn->prepare($query);
        $stmt->execute(['id' => $productID]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        $query = "INSERT INTO OrderDetails (OrderID, ProductID, Quantity, Price) 
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
    echo "<div class='container'><p>Đơn hàng của bạn đã được đặt thành công. <a href='index.php'>Tiếp tục mua sắm</a></p></div>";
    include 'includes/footer.php';
    exit;
}
?>

<div class="container">
    <h1>Thanh Toán</h1>
    <form action="checkout.php" method="POST">
        <h2>Thông tin khách hàng</h2>
        <label for="customer_name">Họ và tên:</label>
        <input type="text" id="customer_name" name="customer_name" required>
        <label for="customer_phone">Số điện thoại:</label>
        <input type="text" id="customer_phone" name="customer_phone" required>
        <label for="customer_address">Địa chỉ:</label>
        <textarea id="customer_address" name="customer_address" required></textarea>

        <h2>Giỏ hàng</h2>
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
                    $query = "SELECT ProductName, Price FROM Products WHERE ProductID = :id";
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

        <h2>Phương thức thanh toán</h2>
        <label><input type="radio" name="payment_method" value="COD" checked> Thanh toán khi nhận hàng (COD)</label><br>
        <label><input type="radio" name="payment_method" value="Transfer"> Chuyển khoản ngân hàng</label>

        <button type="submit" class="btn">Đặt hàng</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>