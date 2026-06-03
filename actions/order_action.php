<?php
session_start();
require_once '../config/database.php';

if (isset($_POST['place_order']) && !empty($_SESSION['cart'])) {
    
    try {
        $pdo->beginTransaction();
        
        foreach ($_SESSION['cart'] as $product_id => $qty) {
            
            $stmt = $pdo->prepare("SELECT stock FROM products WHERE id = ? FOR UPDATE");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch();
            
            if ($product['stock'] >= $qty) {
                $updateStmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
                $updateStmt->execute([$qty, $product_id]);
            } else {
                throw new Exception("සමාවන්න, අවශ්‍ය ප්‍රමාණයට තොග නොමැත!");
            }
        }
        
        $pdo->commit();
        
        $_SESSION['cart'] = [];
        
        echo "<h2>Order Placed Successfully! Inventory Updated.</h2>";
        echo "<p>Next Step: Dynamic PDF Invoice එක Composer හරහා generate කිරීම.</p>";
        echo "<a href='../index.php'>Home</a>";
        
    } catch (Exception $e) {
        $pdo->rollBack(); 
        echo "Error: " . $e->getMessage();
    }
}
?>