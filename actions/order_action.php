```php
<?php
session_start();
require_once '../config/database.php';
// Composer එකෙන් ආපු Autoloader එක සම්බන්ධ කිරීම (Dompdf වැඩ කරන්න මේක ඕනේ)
require_once '../vendor/autoload.php'; 

use Dompdf\Dompdf;

if (isset($_POST['place_order']) && !empty($_SESSION['cart'])) {
    
    try {
        $pdo->beginTransaction();
        
        $invoice_html = "
        <div style='font-family: Arial, sans-serif; padding: 20px;'>
            <h1 style='text-align: center; color: #333;'>ADVANCED PHP STORE</h1>
            <h3 style='text-align: center; color: #666;'>Official Invoice</h3>
            <hr>
            <p><strong>Date:</strong> " . date('Y-md H:i:s') . "</p>
            <p><strong>Customer Name:</strong> " . htmlspecialchars($_SESSION['user_name'] ?? 'Guest Customer') . "</p>
            <br>
            <table border='1' cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse;'>
                <thead>
                    <tr style='background-color: #f2f2f2;'>
                        <th>Product Title</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>";

        $grand_total = 0;

        foreach ($_SESSION['cart'] as $product_id => $qty) {
            
            // 1. Stock check කිරීම
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? FOR UPDATE");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch();
            
            if ($product['stock'] >= $qty) {
                // 2. Automated Inventory Logic (Stock අඩු කිරීම)
                $updateStmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
                $updateStmt->execute([$qty, $product_id]);
                
                $item_total = $product['price'] * $qty;
                $grand_total += $item_total;
                
                // HTML Invoice එකට item එක එකතු කිරීම
                $invoice_html .= "
                <tr>
                    <td>" . htmlspecialchars($product['title']) . "</td>
                    <td>Rs. " . number_format($product['price'], 2) . "</td>
                    <td align='center'>$qty</td>
                    <td>Rs. " . number_format($item_total, 2) . "</td>
                </tr>";

            } else {
                throw new Exception("සමාවන්න, " . $product['title'] . " සඳහා ප්‍රමාණවත් තොග නොමැත!");
            }
        }
        
        $invoice_html .= "
                    <tr>
                        <td colspan='3' align='right'><strong>Grand Total:</strong></td>
                        <td><strong>Rs. " . number_format($grand_total, 2) . "</strong></td>
                    </tr>
                </tbody>
            </table>
            <br><br>
            <p style='text-align: center; color: green;'>Thank you for shopping with us! 👋</p>
        </div>";

        // හැමදේම හරි නම් database එක ස්ථිරවම update කරනවා
        $pdo->commit();
        
        // Cart එක හිස් කරනවා
        $_SESSION['cart'] = [];
        
        // --- DOMPDF මඟින් PDF එක සාදා Download කිරීම ---
        $dompdf = new Dompdf();
        $dompdf->loadHtml($invoice_html); // අර හදපු HTML ටික PDF එකට දෙනවා
        $dompdf->setPaper('A4', 'portrait'); // A4 සයිස් එක දෙනවා
        $dompdf->render();
        
        // Browser එකට PDF එක download වෙන්න සලස්වනවා
        $dompdf->stream("Invoice-" . time() . ".pdf", array("Attachment" => true));
        exit;
        
    } catch (Exception $e) {
        $pdo->rollBack(); // අවුලක් වුනොත් ඔක්කොම rollback කරනවා
        echo "<h2 style='color:red;'>Error: " . $e->getMessage() . "</h2>";
        echo "<a href='../cart.php'>Back to Cart</a>";
    }
}
?>