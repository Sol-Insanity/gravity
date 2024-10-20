<?php
require_once 'api/db.php';
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$categoryQuery = "SELECT * FROM categories ORDER BY name";
$categories = $conn->query($categoryQuery);

$selectedCategory = isset($_GET['category']) ? (int)$_GET['category'] : null;

$itemsQuery = "
    SELECT i.*, c.name as category_name, 
           (SELECT file_path FROM item_pictures WHERE item_id = i.item_id LIMIT 1) as main_picture
    FROM items i
    JOIN categories c ON i.category_id = c.category_id
";

if ($selectedCategory) {
    $itemsQuery .= " WHERE i.category_id = $selectedCategory";
}

$itemsQuery .= " ORDER BY i.created_at DESC";
$items = $conn->query($itemsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gravity Shop</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Previous styles remain the same */
        
        /* Previous styles remain the same */
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --background-color: #f8f9fa;
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
            font-size: 2.5em;
            margin-bottom: 20px;
        }

        .filters {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .filter-btn {
            padding: 10px 20px;
            border: none;
            background-color: white;
            color: var(--primary-color);
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: var(--card-shadow);
        }

        .filter-btn:hover, .filter-btn.active {
            background-color: var(--secondary-color);
            color: white;
        }

        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 30px;
            padding: 20px;
        }

        .item-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s ease;
        }

        .item-card:hover {
            transform: translateY(-5px);
        }

        .item-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .item-info {
            padding: 15px;
        }

        .item-name {
            font-size: 1.2em;
            margin-bottom: 10px;
            color: var(--primary-color);
        }

        .item-category {
            color: var(--secondary-color);
            font-size: 0.9em;
            margin-bottom: 10px;
        }

        .item-price {
            font-weight: bold;
            color: var(--primary-color);
            font-size: 1.1em;
            margin-bottom: 10px;
        }

        .stock-status {
            font-size: 0.9em;
            color: #666;
        }

        .stock-low {
            color: #e74c3c;
        }

        .stock-available {
            color: #27ae60;
        }

        @media (max-width: 768px) {
            .items-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }

            .filters {
                flex-wrap: wrap;
            }
        }

        .no-image {
            background: #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 200px;
        }

        .no-image i {
            font-size: 3em;
            color: #ccc;
        }


        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            margin-bottom: 20px;
        }

        .cart-icon {
            position: relative;
            font-size: 1.5em;
            cursor: pointer;
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: var(--secondary-color);
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.7em;
            min-width: 18px;
            text-align: center;
        }

        .cart-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            min-width: 300px;
            max-height: 400px;
            overflow-y: auto;
            display: none;
            z-index: 1000;
        }

        .cart-dropdown.show {
            display: block;
        }

        .cart-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-total {
            padding: 10px;
            font-weight: bold;
            text-align: right;
            border-top: 2px solid #eee;
        }

        .add-to-cart-btn {
            background-color: var(--secondary-color);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }

        .add-to-cart-btn:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        .remove-item {
            color: #e74c3c;
            cursor: pointer;
            padding: 5px;
        }

        .cart-footer {
            padding: 15px;
            border-top: 2px solid #eee;
        }

        .checkout-btn {
            background-color: #27ae60;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 1.1em;
            transition: background-color 0.3s ease;
        }

        .checkout-btn:hover {
            background-color: #219a52;
        }

        .empty-cart {
            padding: 20px;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-container">
                <h1 class="shop-title">Gravity Shop</h1>
                <div class="cart-wrapper">
                    <div class="cart-icon" onclick="toggleCart()">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count">0</span>
                        <div class="cart-dropdown">
                            <div id="cart-items"></div>
                            <div class="cart-total">
                                Total: $<span id="cart-total">0.00</span>
                            </div>
                            <div class="cart-footer">
                                <button class="checkout-btn" onclick="proceedToCheckout()">
                                    Proceed to Checkout
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="filters">
                <a href="?" class="filter-btn <?php echo !$selectedCategory ? 'active' : ''; ?>">
                    All Items
                </a>
                <?php while($category = $categories->fetch_assoc()): ?>
                    <a href="?category=<?php echo $category['category_id']; ?>" 
                       class="filter-btn <?php echo $selectedCategory == $category['category_id'] ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars($category['name']); ?>
                    </a>
                <?php endwhile; ?>
            </div>
        </div>
    </header>

    <main class="container">
        <div class="items-grid">
            <?php while($item = $items->fetch_assoc()): ?>
                <div class="item-card">
                    <?php if($item['main_picture']): ?>
                        <img src="<?php echo htmlspecialchars($item['main_picture']); ?>" 
                             alt="<?php echo htmlspecialchars($item['name']); ?>" 
                             class="item-image">
                    <?php else: ?>
                        <div class="no-image">
                            <i class="fas fa-image"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="item-info">
                        <h3 class="item-name"><?php echo htmlspecialchars($item['name']); ?></h3>
                        <p class="item-category"><?php echo htmlspecialchars($item['category_name']); ?></p>
                        <p class="item-price">$<?php echo number_format($item['price'], 2); ?></p>
                        <p class="stock-status <?php echo $item['stock'] < 5 ? 'stock-low' : 'stock-available'; ?>">
                            <?php 
                            if($item['stock'] == 0) {
                                echo 'Out of Stock';
                            } elseif($item['stock'] < 5) {
                                echo 'Only ' . $item['stock'] . ' left!';
                            } else {
                                echo 'In Stock';
                            }
                            ?>
                        </p>
                        <button 
                            class="add-to-cart-btn" 
                            onclick='addToCart(<?php echo json_encode($item); ?>)'
                            <?php echo $item['stock'] == 0 ? 'disabled' : ''; ?>>
                            Add to Cart
                        </button>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </main>

    <script>
        let cart = [];
        const cartDropdown = document.querySelector('.cart-dropdown');
        
        function toggleCart() {
            cartDropdown.classList.toggle('show');
        }

        document.addEventListener('click', (e) => {
            if (!e.target.closest('.cart-wrapper') && cartDropdown.classList.contains('show')) {
                cartDropdown.classList.remove('show');
            }
        });

        function addToCart(item) {
            const existingItem = cart.find(i => i.item_id === item.item_id);
            
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({
                    ...item,
                    quantity: 1
                });
            }
            
            updateCartUI();
        }

        function removeFromCart(itemId) {
            cart = cart.filter(item => parseInt(item.item_id) !== parseInt(itemId));
            updateCartUI();
        }

        function updateCartUI() {
            const cartCount = document.querySelector('.cart-count');
            const cartItems = document.getElementById('cart-items');
            const cartTotal = document.getElementById('cart-total');
            
            // Update cart count
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            cartCount.textContent = totalItems;
            
            // Update cart items
            if (cart.length === 0) {
                cartItems.innerHTML = '<div class="empty-cart">Your cart is empty</div>';
            } else {
                cartItems.innerHTML = cart.map(item => `
                    <div class="cart-item">
                        <div>
                            ${item.name} x ${item.quantity}
                            <div>$${(parseFloat(item.price) * item.quantity).toFixed(2)}</div>
                        </div>
                        <i class="fas fa-trash remove-item" onclick="removeFromCart('${item.item_id}')"></i>
                    </div>
                `).join('');
            }
            
            // Update total
            const total = cart.reduce((sum, item) => sum + (parseFloat(item.price) * item.quantity), 0);
            cartTotal.textContent = total.toFixed(2);
            
            // Save cart to session storage
            sessionStorage.setItem('cart', JSON.stringify(cart));
        }

        function proceedToCheckout() {
            // Save cart data to session storage
            sessionStorage.setItem('cart', JSON.stringify(cart));
            
            // Create a form to post the cart data
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'checkout.php';
            
            // Add cart data as hidden input
            const cartInput = document.createElement('input');
            cartInput.type = 'hidden';
            cartInput.name = 'cart_data';
            cartInput.value = JSON.stringify(cart);
            form.appendChild(cartInput);
            
            // Submit the form
            document.body.appendChild(form);
            form.submit();
        }

        // Load cart from session storage on page load
        window.addEventListener('load', () => {
            const savedCart = sessionStorage.getItem('cart');
            if (savedCart) {
                cart = JSON.parse(savedCart);
                updateCartUI();
            }
        });
    </script>
</body>
</html>