<?php
session_start();

$loggedIn = isset($_SESSION['user_id']);
$userEmail = $loggedIn ? $_SESSION['user_email'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lavender Loom</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="mediaqueries.css">
</head>

<style>
  /* Modal Container */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000;
}

/* Show the modal when it has 'show' class */
.modal.show {
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Modal Content */
.modal-content {
    background-color: #fff;
    padding: 2rem;
    border-radius: 8px;
    width: 90%;
    max-width: 400px;
    position: relative;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Close Button */
.close-modal {
    position: absolute;
    right: 1rem;
    top: 1rem;
    font-size: 1.5rem;
    cursor: pointer;
    color: #666;
}

.close-modal:hover {
    color: #333;
}

/* Form Styles */
.login-form {
    margin-top: 1rem;
}

.login-form input {
    width: 100%;
    padding: 0.75rem;
    margin-bottom: 1rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
}

.login-form button {
    width: 100%;
    padding: 0.75rem;
    background-color: var(--purple, #5a42b0);
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 500;
}

.login-form button:hover {
    background-color: #4a32a8;
}

.message {
    padding: 0.75rem;
    margin-bottom: 1rem;
    border-radius: 4px;
    text-align: center;
}
</style>

<body>
    <div class="nav-container">
        <nav class="small-nav">
            <ul>
                <?php if ($loggedIn): ?>
                    <li><span>Logged in as: <?php echo htmlspecialchars($userEmail); ?></span></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="#" id="loginBtn">Login</a></li>
                    <li><a href="signuppage.php">Signup</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <nav class="large-nav">
            <ul>
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#contact">Contact</a></li>
                <li class="search-bar">
                    <input type="text" placeholder="Search...">
                </li>
            </ul>
        </nav>
    </div>

    <!-- Login Modal -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2 style="text-align: center; color: var(--purple, #5a42b0); margin-bottom: 20px;">Login to Lavender Loom</h2>
            <div class="message" id="loginMessage"></div>
            <form id="loginForm" class="login-form">
                <input type="email" id="email" placeholder="Email" required>
                <input type="password" id="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>

    <section class="hero">
        <div class="hero-text">
            <h1>Welcome to Lavender Loom</h1>
            <p>Get the best quality products at the best prices</p>
            <button class="hero-button">Shop Now</button>
        </div>
    </section>

    <section class="service-banner">
        <ul class="service-list">
            <li class="service-item">
                <span class="service-icon">🕒</span>
                <span class="service-text">Delivery within 24 Hours</span>
            </li>
            <li class="service-item">
                <span class="service-icon">🚚</span>
                <span class="service-text">Deliver to Doorstep</span>
            </li>
            <li class="service-item">
                <span class="service-icon">✅</span>
                <span class="service-text">Freshness Guaranteed</span>
            </li>
            <li class="service-item">
                <span class="service-icon">🖱️</span>
                <span class="service-text">Click and Collect</span>
            </li>
            <li class="service-item">
                <span class="service-icon">💰</span>
                <span class="service-text">Amazing Deals</span>
            </li>
        </ul>
    </section>

    <footer>
        <div class="footer-container">
          <div class="footer-section company-info">
            <h2 class="company-name">Lavender Loom</h2>
            <p>Lavender Loom Pvt. Ltd.</p>
            <p>No: 123, Artisan Street, Colombo, Sri Lanka.</p>
            <p><strong>+94 11 1234567</strong></p>
            <p>(Open 9.00 a.m. to 6.00 p.m.)</p>
          </div>
      
          <div class="footer-section newsletter">
            <h2>Subscribe to our Newsletter</h2>
            <form action="#newsletter">
              <input type="email" placeholder="Enter your email" />
              <button type="submit">Submit</button>
            </form>
          </div>
      
          <div class="footer-section links">
            <h2>Quick Links</h2>
            <ul>
              <li><a href="#">Home</a></li>
              <li><a href="#">Catalogue</a></li>
              <li><a href="#">Custom Orders</a></li>
              <li><a href="#">Track My Order</a></li>
            </ul>
          </div>
      
          <div class="footer-section social">
            <h2>Follow Us</h2>
            <div class="social-icons">
              <a href="#"><img src="facebook-icon.png" alt="Facebook" /></a>
              <a href="#"><img src="twitter-icon.png" alt="Twitter" /></a>
              <a href="#"><img src="youtube-icon.png" alt="YouTube" /></a>
            </div>
          </div>
        </div>
      
        <div class="footer-bottom">
          <p>&copy; 2024 Lavender Loom Pvt. Ltd. All Rights Reserved</p>
          <div class="payment-options">
            <img src="visa.png" alt="Visa" />
            <img src="mastercard.png" alt="MasterCard" />
            <img src="amex.png" alt="Amex" />
          </div>
        </div>
      </footer>

      <script src="login.js"></script>



</body>
</html>
