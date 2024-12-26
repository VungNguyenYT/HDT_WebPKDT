<?php
header('Content-Type: application/json');

// Kết nối cơ sở dữ liệu
require_once '../db_connection.php';

$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case 'GET':
        // Lấy danh sách sản phẩm
        $stmt = $conn->prepare("SELECT ProductID, ProductName, Price FROM products");
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $products]);
        break;

    case 'POST':
        // Thêm sản phẩm mới
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $conn->prepare("INSERT INTO products (ProductName, Price) VALUES (:name, :price)");
        $stmt->execute(['name' => $data['name'], 'price' => $data['price']]);
        echo json_encode(['success' => true, 'message' => 'Thêm sản phẩm thành công']);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
        break;
}
?>