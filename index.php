<?php
include 'includes/db.php';
include 'includes/header.php';

// Lấy danh sách sản phẩm
$query = "SELECT * FROM Products";
$stmt = $conn->query($query);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <h1>Danh mục sản phẩm</h1>
    <div class="product-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <img src="assets/images/<?= $product['ImageURL'] ?>" alt="<?= $product['ProductName'] ?>">
                <h2><?= $product['ProductName'] ?></h2>
                <p><?= number_format($product['Price'], 0, ',', '.') ?> VND</p>
                <a href="product.php?id=<?= $product['ProductID'] ?>" class="btn">Xem chi tiết</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>