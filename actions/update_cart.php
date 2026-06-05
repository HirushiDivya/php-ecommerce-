<?php
session_start();

// AJAX හරහා ආපු JSON data ටික ගන්නවා
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['product_id']) && isset($data['quantity'])) {
    $product_id = $data['product_id'];
    $quantity = (int)$data['quantity'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if ($quantity > 0) {
        // සෙශන් එකේ තියෙන quantity එක අලුත් අගයට update කරනවා
        $_SESSION['cart'][$product_id] = $quantity;
        echo json_encode(['status' => 'success', 'message' => 'Cart updated']);
    } else {
        // Quantity එක 0 වුනොත් cart එකෙන් අයින් කරනවා
        unset($_SESSION['cart'][$product_id]);
        echo json_encode(['status' => 'success', 'message' => 'Item removed']);
    }
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
?>