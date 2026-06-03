<?php 
session_start();
require_once 'config/database.php';

// Database items get
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Advanced PHP E-Commerce</title>
    <style>
        .product-card { border: 1px solid #ccc; padding: 20px; margin: 10px; display: inline-block; width: 250px; text-align: center; }
        .out-of-stock { color: red; font-weight: bold; }
        .in-stock { color: green; }
    </style>
</head>
<body>
    <h2>Advanced PHP Store</h2>
    <p style="text-align: right;">
        <?php if(isset($_SESSION['user_id'])): ?>
            Welcome, <?php echo $_SESSION['user_name']; ?> | <a href="cart.php">View Cart</a>
        <?php else: ?>
            <a href="login.php">Login to Checkout</a>
        <?php endif; ?>
    </p>

    <div class="products-container">
        <?php foreach($products as $product): ?>
            <div class="product-card">
                <h3><?php echo htmlspecialchars($product['title']); ?></h3>
                <p>Rs. <?php echo number_format($product['price'], 2); ?></p>
                
                <!-- Automated Inventory UI Logic -->
                <?php if($product['stock'] > 0): ?>
                    <p class="in-stock">Available Stock: <?php echo $product['stock']; ?></p>
                    <form action="cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <button type="submit" name="add_to_cart">Add to Cart</button>
                    </form>
                <?php else: ?>
                    <p class="out-of-stock">Out of Stock</p>
                    <button disabled>Add to Cart</button>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>