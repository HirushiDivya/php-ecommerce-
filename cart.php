<?php
session_start();
require_once 'config/database.php';

// --- 1. AJAX ADD TO CART LOGIC (මේක තියෙන්න ඕනේ පිටුවේ උඩමයි) ---
if (isset($_POST['ajax_add_to_cart'])) {
    $product_id = $_POST['product_id'];
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }
    
    $total_count = array_sum($_SESSION['cart']);
    echo json_encode(['status' => 'success', 'total_count' => $total_count]);
    exit; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .navbar { background: linear-gradient(135deg, #2b3445 0%, #171c24 100%); }
        .card-custom { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); background: #ffffff; }
        .img-placeholder { width: 50px; height: 50px; background-color: #f1f3f5; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; color: #adb5bd; }
        .btn-checkout { background-color: #febd69; color: #131921; font-weight: 600; border: none; transition: background 0.2s; }
        .btn-checkout:hover { background-color: #f3a847; color: #131921; }
        .qty-input { width: 50px; text-align: center; border: 1px solid #ced4da; font-weight: bold; }
        .form-check-input { width: 1.3em; height: 1.3em; cursor: pointer; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark py-3 mb-5">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3 text-warning" href="index.php">
                <i class="fa-solid fa-bag-shopping me-2"></i>MegaStore
            </a>
            <a href="index.php" class="btn btn-outline-light rounded-pill px-4">
                <i class="fa-solid fa-arrow-left me-2"></i>Continue Shopping
            </a>
        </div>
    </nav>

    <div class="container">
        <div class="row mb-4">
            <div class="col">
                <h2 class="fw-bold text-secondary text-uppercase"><i class="fa-solid fa-cart-shopping me-2 text-warning"></i>Your Shopping Cart</h2>
                <p class="text-muted">Adjusted quantities are automatically saved to your session!</p>
            </div>
        </div>

        <?php if(!empty($_SESSION['cart'])): ?>
            <form action="actions/order_action.php" method="POST">
                <div class="row g-4">
                    
                    <div class="col-lg-8">
                        <div class="card card-custom p-4">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle m-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" class="py-3" style="width: 50px;">Select</th>
                                            <th scope="col" class="py-3">Product</th>
                                            <th scope="col" class="py-3">Price</th>
                                            <th scope="col" class="py-3 text-center">Quantity</th>
                                            <th scope="col" class="py-3 text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        // සෙශන් එකේ තියෙන බඩු ටික ඩේටාබේස් එකෙන් අරන් පෙන්වන ලූප් එක
                                        foreach($_SESSION['cart'] as $id => $qty) {
                                            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
                                            $stmt->execute([$id]);
                                            $prod = $stmt->fetch();
                                            
                                            if ($prod) {
                                                $item_total = $prod['price'] * $qty;
                                                ?>
                                                <tr class="cart-item-row" data-id="<?php echo $id; ?>" data-price="<?php echo $prod['price']; ?>">
                                                    <td class="text-center">
                                                        <input class="form-check-input item-checkbox" type="checkbox" name="selected_items[]" value="<?php echo $id; ?>" checked>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="img-placeholder me-3">
                                                                <i class="fa-solid fa-laptop text-secondary"></i>
                                                            </div>
                                                            <div>
                                                                <h6 class="fw-bold mb-0 text-dark"><?php echo htmlspecialchars($prod['title']); ?></h6>
                                                                <small class="text-muted">In Stock: <?php echo $prod['stock']; ?></small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>Rs. <?php echo number_format($prod['price'], 2); ?></td>
                                                    <td class="text-center">
                                                        <div class="d-inline-flex align-items-center border rounded">
                                                            <button type="button" class="btn btn-sm btn-light px-2 py-1 qty-minus" onclick="changeQuantity(<?php echo $id; ?>, -1)"><i class="fa-solid fa-minus"></i></button>
                                                            <input type="text" id="qty-<?php echo $id; ?>" name="quantities[<?php echo $id; ?>]" class="qty-input border-0 bg-transparent" value="<?php echo $qty; ?>" readonly>
                                                            <button type="button" class="btn btn-sm btn-light px-2 py-1 qty-plus" onclick="changeQuantity(<?php echo $id; ?>, 1)"><i class="fa-solid fa-plus"></i></button>
                                                        </div>
                                                    </td>
                                                    <td class="text-end fw-bold text-dark item-total-display">Rs. <?php echo number_format($item_total, 2); ?></td>
                                                </tr>
                                                <?php 
                                            }
                                        } 
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card card-custom p-4">
                            <h4 class="fw-bold text-dark mb-4">Order Summary</h4>
                            <div class="d-flex justify-content-between mb-3 text-secondary">
                                <span>Selected Items Total</span>
                                <span id="summary-subtotal">Rs. 0.00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3 text-secondary">
                                <span>Shipping Fee</span>
                                <span class="text-success fw-bold">FREE</span>
                            </div>
                            <hr class="my-3">
                            <div class="d-flex justify-content-between mb-4">
                                <span class="fw-bold fs-5 text-dark">Grand Total</span>
                                <span class="fw-bold fs-5 text-primary" id="summary-grandtotal">Rs. 0.00</span>
                            </div>

                            <?php if(isset($_SESSION['user_id'])): ?>
                                <button type="submit" name="place_order" class="btn btn-checkout btn-lg w-100 rounded-pill py-3 shadow-sm text-uppercase tracking-wider">
                                    <i class="fa-solid fa-file-invoice-dollar me-2"></i>Place Order & Get Invoice
                                </button>
                            <?php else: ?>
                                <a href="login.php" class="btn btn-danger btn-lg w-100 rounded-pill py-3 shadow-sm text-uppercase">
                                    <i class="fa-solid fa-lock me-2"></i>Please Login to Checkout
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </form>
        <?php else: ?>
            <div class="card card-custom text-center py-5">
                <div class="card-body">
                    <i class="fa-solid fa-cart-arrow-down fa-4x text-muted mb-3 opacity-50"></i>
                    <h4 class="fw-bold text-secondary">Your Cart is Empty!</h4>
                    <a href="index.php" class="btn btn-warning rounded-pill px-4 py-2 fw-bold text-dark mt-3">Go Shopping</a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
    function changeQuantity(productId, change) {
        const qtyInput = document.getElementById('qty-' + productId);
        let currentQty = parseInt(qtyInput.value);
        let newQty = currentQty + change;

        if (newQty < 1) return;

        fetch('actions/update_cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ product_id: productId, quantity: newQty })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                qtyInput.value = newQty;
                calculateCartTotal(); 
            } else {
                alert('Error updating cart');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function calculateCartTotal() {
        let grandTotal = 0;
        const rows = document.querySelectorAll(".cart-item-row");
        
        rows.forEach(row => {
            const checkbox = row.querySelector(".item-checkbox");
            const price = parseFloat(row.getAttribute("data-price"));
            const qty = parseInt(row.querySelector(".qty-input").value);
            const itemTotal = price * qty;
            
            row.querySelector(".item-total-display").innerText = "Rs. " + itemTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            if (checkbox && checkbox.checked) {
                grandTotal += itemTotal;
            }
        });
        
        const formattedTotal = "Rs. " + grandTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        document.getElementById("summary-subtotal").innerText = formattedTotal;
        document.getElementById("summary-grandtotal").innerText = formattedTotal;
    }

    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".item-checkbox").forEach(checkbox => {
            checkbox.addEventListener("change", calculateCartTotal);
        });
        calculateCartTotal();
    });
    </script>
</body>
</html>