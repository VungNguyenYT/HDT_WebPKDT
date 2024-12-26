<?php
include 'includes/db.php';
include 'includes/header.php';

// Lấy thông tin sản phẩm
$productID = $_GET['id'] ?? null;
if (!$productID) {
    echo "<p>Không tìm thấy sản phẩm!</p>";
    include 'includes/footer.php';
    exit;
}

$query = "SELECT ProductID, ProductName, Description, Price, Stock, ImageURL 
          FROM products 
          WHERE ProductID = :id";
$stmt = $conn->prepare($query);
$stmt->execute(['id' => $productID]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "<p>Sản phẩm không tồn tại!</p>";
    include 'includes/footer.php';
    exit;
}
?>

<div class="container">
    <div class="product-detail">
        <img src="assets/images/<?= htmlspecialchars($product['ImageURL']) ?>"
            alt="<?= htmlspecialchars($product['ProductName']) ?>">
        <div class="info">
            <h1><?= htmlspecialchars($product['ProductName']) ?></h1>
            <p><?= htmlspecialchars($product['Description']) ?></p>
            <p>Giá: <?= number_format($product['Price'], 0, ',', '.') ?> VND</p>
            <form action="cart.php" method="post">
                <input type="hidden" name="product_id" value="<?= $product['ProductID'] ?>">
                <label for="quantity">Số lượng:</label>
                <input type="number" name="quantity" value="1" min="1" max="<?= $product['Stock'] ?>">
                <button type="submit" class="btn">Thêm vào giỏ hàng</button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>