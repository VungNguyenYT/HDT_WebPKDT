<?php
header('Content-Type: application/json');

// Kết nối cơ sở dữ liệu
require_once '../db_connection.php';

$method = $_SERVER['REQUEST_METHOD'];
session_start();

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để thao tác']);
    exit;
}

$userId = $_SESSION['user']['UserID']; // Lấy UserID từ session

try {
    switch ($method) {
        case 'POST': // Thêm sản phẩm vào giỏ hàng
            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data['product_id']) || empty($data['quantity'])) {
                echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
                exit;
            }

            $stmt = $conn->prepare("
                INSERT INTO cart (UserID, ProductID, Quantity)
                VALUES (:user_id, :product_id, :quantity)
                ON DUPLICATE KEY UPDATE Quantity = Quantity + :quantity
            ");
            $stmt->execute([
                'user_id' => $userId,
                'product_id' => $data['product_id'],
                'quantity' => $data['quantity']
            ]);

            echo json_encode(['success' => true, 'message' => 'Thêm sản phẩm vào giỏ hàng thành công']);
            break;

        case 'PUT': // Sửa số lượng sản phẩm trong giỏ hàng
            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data['product_id']) || !isset($data['quantity'])) {
                echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
                exit;
            }

            $stmt = $conn->prepare("
                UPDATE cart 
                SET Quantity = :quantity 
                WHERE UserID = :user_id AND ProductID = :product_id
            ");
            $stmt->execute([
                'quantity' => $data['quantity'],
                'user_id' => $userId,
                'product_id' => $data['product_id']
            ]);

            echo json_encode(['success' => true, 'message' => 'Cập nhật số lượng thành công']);
            break;

        case 'DELETE': // Xóa sản phẩm khỏi giỏ hàng
            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data['product_id'])) {
                echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
                exit;
            }

            $stmt = $conn->prepare("
                DELETE FROM cart 
                WHERE UserID = :user_id AND ProductID = :product_id
            ");
            $stmt->execute([
                'user_id' => $userId,
                'product_id' => $data['product_id']
            ]);

            echo json_encode(['success' => true, 'message' => 'Xóa sản phẩm thành công']);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
            break;
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
}
?>