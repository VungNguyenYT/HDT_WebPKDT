<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productID = $_POST['product_id'] ?? null;
    $action = $_POST['action'] ?? null;

    if (!$productID || !isset($_SESSION['cart'][$productID])) {
        header('Location: cart.php');
        exit;
    }

    if ($action === 'update') {
        $quantity = $_POST['quantity'] ?? 1;
        if ($quantity > 0) {
            $_SESSION['cart'][$productID] = $quantity;
        }
    } elseif ($action === 'delete') {
        unset($_SESSION['cart'][$productID]);
    }

    header('Location: cart.php');
    exit;
}
?>