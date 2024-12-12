<?php
header('Content-Type: application/json');

// Kết nối đến cơ sở dữ liệu
require_once '../db_connection.php'; // Đảm bảo bạn đã có tệp này để kết nối DB

// Kiểm tra tham số `query`
if (!isset($_GET['query']) || empty($_GET['query'])) {
    echo json_encode(['error' => 'Vui lòng cung cấp từ khóa tìm kiếm']);
    exit;
}

$query = htmlspecialchars($_GET['query']); // Xử lý input để tránh SQL Injection

try {
    // Truy vấn tìm kiếm sản phẩm
    $stmt = $conn->prepare("
        SELECT id, name, price, image, description 
        FROM products 
        WHERE name LIKE :query OR description LIKE :query
    ");
    $stmt->execute(['query' => '%' . $query . '%']);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Kiểm tra kết quả
    if ($products) {
        echo json_encode(['success' => true, 'products' => $products]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy sản phẩm']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>