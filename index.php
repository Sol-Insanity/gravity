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
    <style>
        :root {
            --beige-light: #F5F0E5;
            --beige: #E8DCC4;
            --purple-dark: #4A2B5F;
            --purple-light: #6B4485;
            --gold: #C6A55C;
            --gold-light: #D4B979;
            --black: #1A1A1A;
            --gray-dark: #374151;
            --gray-medium: #6B7280;
        }

        /* Authentication Modal Styles */
        .auth-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(26, 26, 26, 0.6);
            z-index: 1000;
        }

        .auth-modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-modal-content {
            background-color: var(--beige-light);
            padding: 2.5rem;
            border-radius: 1rem;
            width: 90%;
            max-width: 400px;
            position: relative;
            box-shadow: 0 4px 15px rgba(26, 26, 26, 0.15);
            margin: 0 auto;
            border: 1px solid var(--gold);
        }

        .auth-close {
            position: absolute;
            right: 1.5rem;
            top: 1rem;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--purple-dark);
        }

        .auth-close:hover {
            color: var(--gold);
        }

        .auth-logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-logo img {
            height: 40px;
            width: auto;
        }

        .auth-title {
            color: var(--purple-dark);
            font-size: 1.875rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .auth-subtitle {
            color: var(--gray-medium);
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-form-group {
            margin-bottom: 1.25rem;
            max-width: 100%;
        }

        .auth-label {
            display: block;
            color: var(--black);
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
            text-align: left;
        }

        .auth-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--gold);
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: all 0.15s ease;
            box-sizing: border-box;
            display: block;
            margin: 0;
            background-color: white;
            color: var(--black);
        }

        .auth-input:focus {
            outline: none;
            border-color: var(--purple-dark);
            box-shadow: 0 0 0 3px rgba(74, 43, 95, 0.1);
        }

        .auth-button {
            width: 100%;
            padding: 0.75rem 1.25rem;
            background-color: var(--purple-dark);
            color: white;
            border: 2px solid var(--purple-dark);
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 2.75rem;
        }

        .auth-button:hover {
            background-color: var(--purple-light);
            border-color: var(--purple-light);
        }

        .auth-button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .message {
            margin-top: 1rem;
            padding: 0.75rem;
            border-radius: 0.5rem;
            text-align: center;
            display: none;
        }

        .message.error {
            background-color: #fef2f2;
            color: #ef4444;
            border: 1px solid #fee2e2;
            margin-bottom: 1rem;
        }

        .message.success {
            background-color: var(--beige);
            color: var(--purple-dark);
            border: 1px solid var(--gold);
        }

        .auth-switch-link {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--gray-medium);
            font-size: 0.875rem;
        }

        .auth-switch-link a {
            color: var(--purple-dark);
            text-decoration: none;
            font-weight: 500;
        }

        .auth-switch-link a:hover {
            color: var(--gold);
            text-decoration: none;
        }

        .spinner {
            display: none;
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid white;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            position: absolute;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="nav-container">
        <nav class="small-nav">
            <ul>
                <?php if ($loggedIn): ?>
                    <li><a><span>Logged in as: <?php echo htmlspecialchars($userEmail); ?></a></span></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="#" id="loginBtn">Login</a></li>
                    <li><a href="#" onclick="openModal('signup')">Signup</a></li>
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
    <div id="loginModal" class="auth-modal">
        <div class="auth-modal-content">
            <span class="auth-close" onclick="closeModal('login')">&times;</span>
            <div class="auth-logo">
                <img src="/api/placeholder/120/40" alt="Lavender Loom Logo">
            </div>
            <h2 class="auth-title">Login to Lavender Loom</h2>
            <p class="auth-subtitle">Welcome back!</p>
            
            <div id="loginMessage" class="message"></div>
            
            <form id="loginForm">
                <div class="auth-form-group">
                    <label class="auth-label" for="loginEmail">Email address</label>
                    <input type="email" id="loginEmail" name="email" class="auth-input" required 
                           placeholder="Enter your email">
                </div>
                
                <div class="auth-form-group">
                    <label class="auth-label" for="loginPassword">Password</label>
                    <input type="password" id="loginPassword" name="password" class="auth-input" required 
                           placeholder="Enter your password">
                </div>

                <button type="submit" class="auth-button">
                    <span class="button-text">Login</span>
                    <div class="spinner"></div>
                </button>
            </form>

            <div class="auth-switch-link">
                Don't have an account? <a href="#" onclick="switchToSignup()">Sign up</a>
            </div>
        </div>
    </div>

    <!-- Signup Modal -->
    <div id="signupModal" class="auth-modal">
        <div class="auth-modal-content">
            <span class="auth-close" onclick="closeModal('signup')">&times;</span>
            <div class="auth-logo">
                <img src="/api/placeholder/120/40" alt="Lavender Loom Logo">
            </div>
            <h2 class="auth-title">Create Account</h2>
            <p class="auth-subtitle">Get started with your free account</p>
            
            <div id="message" class="message"></div>
            
            <form id="signupForm">
                <div class="auth-form-group">
                    <label class="auth-label" for="signupEmail">Email address</label>
                    <input type="email" id="signupEmail" name="email" class="auth-input" required 
                           placeholder="Enter your email">
                </div>
                
                <div class="auth-form-group">
                    <label class="auth-label" for="signupPassword">Password</label>
                    <input type="password" id="signupPassword" name="password" class="auth-input" required 
                           placeholder="Create a password" minlength="8">
                </div>

                <button type="submit" class="auth-button">
                    <span class="button-text">Create Account</span>
                    <div class="spinner"></div>
                </button>
            </form>

            <div class="auth-switch-link">
                Already have an account? <a href="#" onclick="switchToLogin()">Sign in</a>
            </div>
        </div>
    </div>

    <section class="hero">
        <div class="hero-text">
            <h1>Welcome to Lavender Loom</h1>
            <p>Get the best quality products at the best prices</p>
            <a href="shop.php" class="hero-button">Shop Now</a>
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

    <script src="auth.js"></script>
</body>
</html>