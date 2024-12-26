<?php
header('Content-Type: application/json');

// Kết nối đến cơ sở dữ liệu
require_once '../db_connection.php';

// Kiểm tra tham số `query`
if (!isset($_GET['query']) || empty(trim($_GET['query']))) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng cung cấp từ khóa tìm kiếm']);
    exit;
}

$query = htmlspecialchars(trim($_GET['query']), ENT_QUOTES, 'UTF-8');


try {
    // Truy vấn tìm kiếm sản phẩm
    $stmt = $conn->prepare("
        SELECT ProductID, ProductName, Price, ImageURL, Description 
        FROM products 
        WHERE ProductName LIKE :query OR Description LIKE :query
    ");
    $stmt->execute(['query' => '%' . $query . '%']);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Trả về kết quả
    if ($products) {
        echo json_encode(['success' => true, 'products' => $products]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy sản phẩm']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
}
?>