<?php 
session_start();
require_once 'config/database.php';

// Fake Login (Testing සඳහා)
$_SESSION['user_id'] = 1;
$_SESSION['user_name'] = "Kasun Kalhara";

// Database එකෙන් බඩු ටික ගන්නවා
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced PHP Store</title>
    <!-- Bootstrap 5 CSS Link (සයිට් එක ලස්සන කරන්න) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons (Icons දාන්න) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar { background: linear-gradient(135deg, #2b3445 0%, #171c24 100%); }
        .product-card { 
            border: none; 
            border-radius: 15px; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.05); 
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: #ffffff;
        }
        .product-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 10px 20px rgba(0,0,0,0.12); 
        }
        .price-tag { font-size: 1.25rem; font-weight: 700; color: #2b3445; }
        .badge-instock { background-color: #d1e7dd; color: #0f5132; }
        .badge-outofstock { background-color: #f8d7da; color: #842029; }
        .btn-cart { background-color: #febd69; color: #131921; font-weight: 600; border: none; }
        .btn-cart:hover { background-color: #f3a847; color: #131921; }
    </style>
</head>
<body>



    <!-- 1. Modern Navigation Bar (Daraz/Amazon style) -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top py-3 mb-5">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3 text-warning" href="index.php">
                <i class="fa-solid fa-bag-shopping me-2"></i>MegaStore
            </a>
            <div class="d-flex align-items-center ms-auto">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <span class="text-white me-4">
                        <i class="fa-regular fa-user me-2 text-warning"></i>Hi, <strong><?php echo $_SESSION['user_name']; ?></strong>
                    </span>
                    <a href="cart.php" class="btn btn-outline-warning position-relative px-4 rounded-pill">
                        <i class="fa-solid fa-cart-shopping me-2"></i>Cart
                        <?php if(!empty($_SESSION['cart'])): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?php echo array_sum($_SESSION['cart']); ?>
                            </span>
                        <?php endif; ?>
                    </a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-warning rounded-pill px-4">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>



    <div class="bg-dark text-white py-5 mb-5" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=2426') center/cover;">
    <div class="container py-5 text-center text-md-start">
        <div class="row align-items-center">
            <div class="col-md-6">
                <span class="badge bg-warning text-dark mb-3 px-3 py-2 fw-bold text-uppercase">Limited Time Offer</span>
                <h1 class="display-4 fw-bold mb-3 text-white">Upgrade Your Tech Setup</h1>
                <p class="lead mb-4 text-white-50">Get the best deals on premium laptops, accessories, and smart devices with official warranty.</p>
                <a href="#explore-products" class="btn btn-warning btn-lg rounded-pill px-5 fw-bold shadow">Shop Now</a>
            </div>
        </div>
    </div>
</div>



<div class="container mb-5">
    <div class="row g-4 text-center bg-white p-4 rounded-4 shadow-sm border mx-0">
        <div class="col-md-4 border-end border-md-0">
            <div class="text-warning mb-2"><i class="fa-solid fa-truck-fast fa-2x"></i></div>
            <h5 class="fw-bold mb-1">Free Delivery</h5>
            <p class="text-muted small m-0">For all orders over Rs. 10,000</p>
        </div>
        <div class="col-md-4 border-end border-md-0">
            <div class="text-warning mb-2"><i class="fa-solid fa-lock fa-2x"></i></div>
            <h5 class="fw-bold mb-1">Secure Payment</h5>
            <p class="text-muted small m-0">100% protected checkout</p>
        </div>
        <div class="col-md-4">
            <div class="text-warning mb-2"><i class="fa-solid fa-arrow-rotate-left fa-2x"></i></div>
            <h5 class="fw-bold mb-1">Easy Returns</h5>
            <p class="text-muted small m-0">7 days return policy</p>
        </div>
    </div>
</div>



<div class="d-flex gap-2 mb-4 overflow-x-auto pb-2" style="white-space: nowrap;">
    <button class="btn btn-warning rounded-pill px-4 btn-sm fw-bold">All Products</button>
    <button class="btn btn-outline-secondary rounded-pill px-4 btn-sm">Laptops</button>
    <button class="btn btn-outline-secondary rounded-pill px-4 btn-sm">Smartphones</button>
    <button class="btn btn-outline-secondary rounded-pill px-4 btn-sm">Audio</button>
    <button class="btn btn-outline-secondary rounded-pill px-4 btn-sm">Accessories</button>
</div>





    <!-- 2. Main Content Container -->
    <div class="container">
        <div class="row mb-4">
            <div class="col">
                <h2 class="fw-bold text-secondary text-uppercase tracking-wider">Explore Products</h2>
                <p class="text-muted">Best deals on premium tech items</p>
            </div>
        </div>

        <!-- 3. Dynamic Product Grid -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach($products as $product): ?>
                <div class="col">
                    <div class="card h-100 product-card p-3">
                        <!-- Placeholder Image (Product එකට අදාල icon එකක්) -->
                        <div class="text-center py-4 my-2 bg-light rounded-3">
                            <i class="fa-solid fa-laptop-code fa-4x text-muted opacity-50"></i>
                        </div>
                        
                        <div class="card-body d-flex flex-column text-start">
                            <h5 class="card-title fw-bold text-dark mb-2"><?php echo htmlspecialchars($product['title']); ?></h5>
                            
                            <!-- Stock Status Badge -->
                            <div class="mb-3">
                                <?php if($product['stock'] > 0): ?>
                                    <span class="badge badge-instock px-3 py-2 rounded-pill">
                                        <i class="fa-solid fa-check me-1"></i> In Stock (<?php echo $product['stock']; ?>)
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-outofstock px-3 py-2 rounded-pill">
                                        <i class="fa-solid fa-xmark me-1"></i> Out of Stock
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="mt-auto d-flex align-items-center justify-content-between">
                                <span class="price-tag">Rs. <?php echo number_format($product['price'], 2); ?></span>
                                
                                <!-- Form Handling -->
                                <?php if($product['stock'] > 0): ?>
                                    <form action="cart.php" method="POST" class="m-0">
                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                        <button type="submit" name="add_to_cart" class="btn btn-cart px-4 py-2 rounded-pill shadow-sm">
                                            <i class="fa-solid fa-cart-plus me-2"></i>Add to Cart
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <button disabled class="btn btn-secondary px-4 py-2 rounded-pill text-white-50">
                                        <i class="fa-solid fa-ban me-2"></i>Disabled
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>


    <div class="bg-warning text-dark py-5 mt-5">
    <div class="container text-center">
        <h3 class="fw-bold mb-2"><i class="fa-regular fa-paper-plane me-2"></i>Subscribe to our Newsletter</h3>
        <p class="mb-4">Get e-mail updates about our latest shops and special offers.</p>
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="input-group mb-3 shadow-sm">
                    <input type="email" class="form-control rounded-start-pill px-4" placeholder="Your Email Address" aria-label="Recipient's username">
                    <button class="btn btn-dark rounded-end-pill px-4 fw-bold" type="button">Subscribe</button>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- 4. Footer -->
    <footer class="bg-white border-top pt-5 mt-0">
    <div class="container">
        <div class="row g-4 text-start">
            <div class="col-lg-4 col-md-6">
                <h5 class="fw-bold text-warning mb-3"><i class="fa-solid fa-bag-shopping me-2"></i>MegaStore</h5>
                <p class="text-muted small">Your one-stop destination for premium tech items and accessories. High quality guaranteed.</p>
                <div class="d-flex gap-3 text-muted">
                    <a href="#" class="text-muted"><i class="fa-brands fa-facebook fa-lg"></i></a>
                    <a href="#" class="text-muted"><i class="fa-brands fa-instagram fa-lg"></i></a>
                    <a href="#" class="text-muted"><i class="fa-brands fa-twitter fa-lg"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-6">
                <h6 class="fw-bold text-dark mb-3">Quick Links</h6>
                <ul class="list-unstyled text-muted small">
                    <li class="mb-2"><a href="#" class="text-decoration-none text-muted">About Us</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Contact Us</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Latest Blogs</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-3 col-6">
                <h6 class="fw-bold text-dark mb-3">Customer Care</h6>
                <ul class="list-unstyled text-muted small">
                    <li class="mb-2"><a href="#" class="text-decoration-none text-muted">FAQ</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Shipping Policy</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Privacy Policy</a></li>
                </ul>
            </div>
            <div class="col-lg-4 col-md-12 text-md-start">
                <h6 class="fw-bold text-dark mb-3">We Accept</h6>
                <div class="d-flex gap-2">
                    <i class="fa-brands fa-cc-visa fa-2x text-secondary"></i>
                    <i class="fa-brands fa-cc-mastercard fa-2x text-secondary"></i>
                    <i class="fa-brands fa-cc-paypal fa-2x text-secondary"></i>
                    <i class="fa-solid fa-money-bill-wave fa-2x text-secondary"></i>
                </div>
            </div>
        </div>
        <hr class="my-4 text-muted">
        <div class="row pb-4 text-muted small">
            <div class="col-md-6 text-center text-md-start">
                <p class="m-0">© 2026 MegaStore. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="m-0">Built with Advanced PHP & Bootstrap 5.</p>
            </div>
        </div>
    </div>
</footer>

</body>
</html>