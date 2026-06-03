<?php
session_start();
require_once 'config/database.php';

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Your Cart</title></head>
<body>
    <h2>Your Shopping Cart</h2>
    <?php if(!empty($_SESSION['cart'])): ?>
        <ul>
            <?php 
            foreach($_SESSION['cart'] as $id => $qty) {
                $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
                $stmt->execute([$id]);
                $prod = $stmt->fetch();
                echo "<li>" . htmlspecialchars($prod['title']) . " (Qty: $qty) - Rs. " . ($prod['price'] * $qty) . "</li>";
            }
            ?>
        </ul>
        
        <?php if(isset($_SESSION['user_id'])): ?>
            <form action="actions/order_action.php" method="POST">
                <button type="submit" name="place_order">Place Order & Get Invoice</button>
            </form>
        <?php else: ?>
            <a href="login.php">Please login to place the order</a>
        <?php endif; ?>

    <?php else: ?>
        <p>Your cart is empty!</p>
    <?php endif; ?>
    <br><a href="index.php">Continue Shopping</a>
</body>
</html>