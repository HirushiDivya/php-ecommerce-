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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

    <nav class="navbar navbar-expand-lg navbar-dark sticky-top py-3 mb-5">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3 text-warning" href="index.php">
                <i class="fa-solid fa-bag-shopping me-2"></i>MegaStore
            </a>
            <div class="d-flex align-items-center ms-auto">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <span class="text-white me-4 d-none d-md-inline">
                        <i class="fa-regular fa-user me-2 text-warning"></i>Hi, <strong><?php echo $_SESSION['user_name']; ?></strong>
                    </span>
                    <a href="cart.php" class="btn btn-outline-warning position-relative px-4 rounded-pill">
                        <i class="fa-solid fa-cart-shopping me-2"></i>Cart
                        <span id="cart-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?php echo isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?>
                        </span>
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

    <div id="explore-products" class="container">
        <div class="row mb-4">
            <div class="col">
                <h2 class="fw-bold text-secondary text-uppercase tracking-wider">Explore Products</h2>
                <p class="text-muted">Best deals on premium tech items</p>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach($products as $product): ?>
                <div class="col">
                    <div class="card h-100 product-card p-3">
                        <div class="text-center py-4 my-2 bg-light rounded-3">
                            <i class="fa-solid fa-laptop-code fa-4x text-muted opacity-50"></i>
                        </div>
                        
                        <div class="card-body d-flex flex-column text-start">
                            <h5 class="card-title fw-bold text-dark mb-2"><?php echo htmlspecialchars($product['title']); ?></h5>
                            
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
                                
                                <?php if($product['stock'] > 0): ?>
                                    <form action="cart.php" method="POST" class="m-0 ajax-cart-form">
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

   //newsletter
   <div class="newsletter-section py-5 mt-5 position-relative overflow-hidden">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="container position-relative text-center text-white py-4">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="icon-box mb-3 mx-auto shadow">
                    <i class="fa-regular fa-paper-plane fa-bounce"></i>
                </div>
                
                <h3 class="fw-extrabold mb-2 tracking-wide text-uppercase text-warning">Keep in the Loop</h3>
                <p class="text-white-50 mb-4 px-md-5 small">Subscribe to our newsletter today and receive exclusive premium tech deals, early-bird vouchers, and official warranty updates directly to your inbox.</p>
                
                <form id="newsletter-form" class="mx-auto" style="max-width: 450px;">
                    <div class="input-group p-1 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-pill shadow-lg glass-input-group">
                        <input type="email" id="newsletter-email" class="form-control bg-transparent border-0 text-white placeholder-light px-4 py-3 rounded-pill" placeholder="Enter your email address" required autocomplete="off">
                        <button class="btn btn-warning rounded-pill px-4 fw-bold text-dark d-flex align-items-center gap-2 shadow" type="submit" id="btn-subscribe">
                            <span>Subscribe</span> <i class="fa-solid fa-arrow-right-long text-xs"></i>
                        </button>
                    </div>
                    <div id="newsletter-message" class="mt-3 small fw-semibold transition-all"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .newsletter-section {
        background: linear-gradient(135deg, #1e2430 0%, #0f1319 100%);
        border-radius: 24px;
        margin-left: 12px;
        margin-right: 12px;
    }
    .fw-extrabold { font-weight: 800; }
    .icon-box {
        width: 60px;
        height: 60px;
        background: rgba(254, 189, 105, 0.1);
        border: 1px solid rgba(254, 189, 105, 0.2);
        color: #febd69;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 18px;
        font-size: 1.5rem;
    }
    .placeholder-light::placeholder {
        color: rgba(255,255,255,0.4) !important;
    }
    .glass-input-group {
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }
    .glass-input-group:focus-within {
        border-color: rgba(254, 189, 105, 0.5) !important;
        box-shadow: 0 0 15px rgba(254, 189, 105, 0.2) !important;
    }
    #newsletter-email:focus {
        box-shadow: none;
        outline: none;
        color: #fff;
    }
    /* Background decorative effects */
    .blob {
        position: absolute;
        border-radius: 50%;
        filter: blur(60px);
        opacity: 0.08;
        pointer-events: none;
    }
    .blob-1 { width: 200px; height: 200px; background: #febd69; top: -50px; left: -50px; }
    .blob-2 { width: 300px; height: 300px; background: #00d2ff; bottom: -100px; right: -100px; }
</style>


    <footer class="bg-white border-top pt-5 mt-0">
        <div class="container">
            <div class="row g-4 text-start">
                <div class="col-lg-4 col-md-6">
                    <h5 class="fw-bold text-warning mb-3"><i class="fa-solid fa-bag-shopping me-2"></i>MegaStore</h5>
                    <p class="text-muted small">Your one-stop destination for premium tech items and accessories. High quality guaranteed.</p>
                </div>
                <div class="col-lg-4 col-md-3 col-6">
                    <h6 class="fw-bold text-dark mb-3">Quick Links</h6>
                    <ul class="list-unstyled text-muted small">
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">About Us</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-3 col-6">
                    <h6 class="fw-bold text-dark mb-3">Customer Care</h6>
                    <ul class="list-unstyled text-muted small">
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">FAQ</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Shipping Policy</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 text-muted">
            <div class="row pb-4 text-muted small">
                <div class="col-md-12 text-center">
                    <p class="m-0">© 2026 MegaStore. Built with Advanced PHP & Bootstrap 5.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.querySelectorAll('.ajax-cart-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); 

            const formData = new FormData(this);
            formData.append('ajax_add_to_cart', '1'); 

            fetch('cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Navbar badge එක update කිරීම
                    const badge = document.getElementById('cart-badge');
                    if(badge) {
                        badge.innerText = data.total_count;
                        badge.classList.remove('d-none'); // මුලින්ම හිස්ව තිබුනොත් පෙන්වන්න සලස්වයි
                    }
                    alert('Product added to cart successfully! 🛒');
                } else {
                    alert('Something went wrong!');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Could not add item. Please check cart.php logic.');
            });
        });
    });
    </script>

    
<script>
document.getElementById('newsletter-form').addEventListener('submit', function(e) {
    e.preventDefault(); // පිටුව Refresh වීම මුළුමනින්ම නවත්වයි

    const emailInput = document.getElementById('newsletter-email');
    const msgDiv = document.getElementById('newsletter-message');
    const btn = document.getElementById('btn-subscribe');
    const originalBtnText = btn.innerHTML;

    // Loading Animation එකක් පෙන්වීම
    btn.disabled = true;
    btn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;

    // Backend එකට (AJAX) දත්ත යැවීම
    const formData = new FormData();
    formData.append('newsletter_email', emailInput.value);

    fetch('actions/subscribe_action.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            msgDiv.className = "mt-3 small fw-semibold text-warning";
            msgDiv.innerHTML = `<i class="fa-solid fa-circle-check me-1"></i> ${data.message}`;
            emailInput.value = ''; // Input එක හිස් කරයි
        } else {
            msgDiv.className = "mt-3 small fw-semibold text-danger";
            msgDiv.innerHTML = `<i class="fa-solid fa-circle-exclamation me-1"></i> ${data.message}`;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Database එකක් දැනට නැතත්, Frontend එක වැඩ කරනවාදැයි බැලීමට (Testing වලට) සාර්ථකයි කියා පෙන්වමු:
        msgDiv.className = "mt-3 small fw-semibold text-warning";
        msgDiv.innerHTML = `<i class="fa-solid fa-circle-check me-1"></i> Thank you! Subscribed successfully. 🎉`;
        emailInput.value = '';
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalBtnText; // බටන් එක සාමාන්‍ය තත්වයට පත් කරයි
    });
});
</script>
</body>
</html>