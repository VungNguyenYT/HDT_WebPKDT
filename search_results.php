<?php
include 'includes/header.php';

$query = $_GET['query'] ?? '';

if (!$query) {
    echo "<p>Vui lòng nhập từ khóa để tìm kiếm</p>";
    include 'includes/footer.php';
    exit;
}

// URL API với cổng 8888
$api_url = 'http://localhost:8888/HDT_WebPKDT/api/search.php?query=' . urlencode($query);
$response = file_get_contents($api_url);
$data = json_decode($response, true);

echo "<div class='container'>";
if (isset($data['success']) && $data['success'] === true) {
    echo "<h2>Kết quả tìm kiếm cho: " . htmlspecialchars($query) . "</h2>";
    echo "<div class='product-grid'>";
    foreach ($data['products'] as $product) {
        echo "<div class='product-card'>";
        echo "<img src='assets/images/" . htmlspecialchars($product['ImageURL']) . "' alt='" . htmlspecialchars($product['ProductName']) . "'>";
        echo "<h3>" . htmlspecialchars($product['ProductName']) . "</h3>";
        echo "<p>Giá: " . number_format($product['Price'], 0, ',', '.') . " VNĐ</p>";
        echo "<p>" . htmlspecialchars($product['Description']) . "</p>";
        echo "<a href='product.php?id=" . htmlspecialchars($product['ProductID']) . "' class='btn'>Xem chi tiết</a>";
        echo "</div>";
    }
    echo "</div>";
} else {
    echo "<p>" . htmlspecialchars($data['message'] ?? 'Không tìm thấy sản phẩm') . "</p>";
}
echo "</div>";

include 'includes/footer.php';
?>