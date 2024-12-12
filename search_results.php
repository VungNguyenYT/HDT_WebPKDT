
<?php
include 'includes/db.php';
include 'includes/header.php';

// Kết nối đến API
if (!isset($_GET['query']) || empty($_GET['query'])) {
    echo "<p>Vui lòng nhập từ khóa để tìm kiếm</p>";
    exit;
}

$query = htmlspecialchars($_GET['query']);

// Gửi yêu cầu đến API
$api_url = 'http://localhost:8888/HDT_WebPKDT/api/search.php?query=' . urlencode($query);
$response = file_get_contents($api_url);
$data = json_decode($response, true);

// Hiển thị kết quả tìm kiếm
echo "<pre>";
print_r($data);
echo "</pre>";

if (isset($data['success']) && $data['success']) {
    echo "<h2>Kết quả tìm kiếm cho: " . htmlspecialchars($query) . "</h2>";
    foreach ($data['products'] as $products) {
        echo "<div class='products'>";
        echo "<img src='assets/images/" . htmlspecialchars($products['image']) . "' alt='" . htmlspecialchars($products['ProductName']) . "'>";
        echo "<h3>" . htmlspecialchars($products['ProductName']) . "</h3>";
        echo "<p>Giá: " . number_format($products['price'], 0, ',', '.') . " VNĐ</p>";
        echo "<p>" . htmlspecialchars($products['description']) . "</p>";
        echo "</div>";
    }
} else {
    echo "<p>" . htmlspecialchars($data['message'] ?? 'Lỗi khi tìm kiếm sản phẩm') . "</p>";
}
?>