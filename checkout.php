<?php
session_start();

// Get cart data from POST or session
$cart_data = isset($_POST['cart_data']) ? json_decode($_POST['cart_data'], true) : [];

// Store in session for persistence
if (!empty($cart_data)) {
    $_SESSION['checkout_cart'] = $cart_data;
} else {
    $cart_data = $_SESSION['checkout_cart'] ?? [];
}

// Calculate totals
$subtotal = 0;
$tax_rate = 0.10; // 10% tax
foreach ($cart_data as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

$tax = $subtotal * $tax_rate;
$total = $subtotal + $tax;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Gravity Shop</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --background-color: #f8f9fa;
            --border-color: #e0e0e0;
            --card-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--background-color);
            color: var(--primary-color);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            background-color: white;
            padding: 20px 0;
            box-shadow: var(--card-shadow);
            margin-bottom: 30px;
        }

        .shop-title {
            text-align: center;
            color: var(--primary-color);
            font-size: 2em;
        }

        .checkout-grid {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
        }

        @media (max-width: 768px) {
            .checkout-grid {
                grid-template-columns: 1fr;
            }
        }

        .card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: var(--card-shadow);
        }

        .section-title {
            font-size: 1.5em;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--border-color);
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .item-details {
            flex-grow: 1;
        }

        .item-name {
            font-weight: 500;
        }

        .item-price {
            color: var(--primary-color);
        }

        .item-quantity {
            color: #666;
            font-size: 0.9em;
        }

        .totals {
            margin-top: 20px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }

        .total-row.final {
            font-size: 1.2em;
            font-weight: bold;
            border-top: 2px solid var(--border-color);
            margin-top: 10px;
            padding-top: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .form-input {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            font-size: 1em;
        }

        .submit-btn {
            background-color: var(--success-color);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 5px;
            font-size: 1.1em;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #219a52;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: var(--secondary-color);
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1 class="shop-title">Gravity Shop</h1>
        </div>
    </header>

    <main class="container">
        <a href="index.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Shopping
        </a>

        <div class="checkout-grid">
            <div class="card">
                <h2 class="section-title">Shipping Information</h2>
                <form id="checkout-form" method="POST" action="process_order.php">
                    <div class="form-group">
                        <label class="form-label" for="name">Full Name</label>
                        <input type="text" id="name" name="name" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="address">Shipping Address</label>
                        <input type="text" id="address" name="address" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="city">City</label>
                        <input type="text" id="city" name="city" class="form-input" required>
                    </div>

                    <button type="submit" class="submit-btn">Place Order</button>
                </form>
            </div>

            <div class="card">
                <h2 class="section-title">Order Summary</h2>
                <div class="order-items">
                    <?php foreach ($cart_data as $item): ?>
                        <div class="order-item">
                            <div class="item-details">
                                <div class="item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                                <div class="item-quantity">Quantity: <?php echo $item['quantity']; ?></div>
                            </div>
                            <div class="item-price">
                                $<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="totals">
                    <div class="total-row">
                        <span>Subtotal</span>
                        <span>$<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    <div class="total-row">
                        <span>Discount (10%)</span>
                        <span>$<?php echo number_format($tax, 2); ?></span>
                    </div>
                    <div class="total-row final">
                        <span>Total</span>
                        <span>$<?php echo number_format($total, 2); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>