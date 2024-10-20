<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | YourApp</title>
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --error-color: #ef4444;
            --success-color: #22c55e;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #f3f4f6 0%, #fff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .container {
            background: white;
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            width: 100%;
            max-width: 400px;
        }

        .logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo img {
            height: 40px;
            width: auto;
        }

        h1 {
            color: #111827;
            font-size: 1.875rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .subtitle {
            color: #6b7280;
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        label {
            display: block;
            color: #374151;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: border-color 0.15s ease;
        }

        input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        button {
            width: 100%;
            padding: 0.75rem 1.25rem;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.15s ease;
        }

        button:hover {
            background-color: var(--primary-hover);
        }

        button:disabled {
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
            color: var(--error-color);
            border: 1px solid #fee2e2;
        }

        .message.success {
            background-color: #f0fdf4;
            color: var(--success-color);
            border: 1px solid #dcfce7;
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            color: #6b7280;
            font-size: 0.875rem;
        }

        .login-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        /* Loading spinner */
        .spinner {
            display: none;
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid #ffffff;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <!-- Replace with your logo -->
            <img src="/api/placeholder/120/40" alt="Logo">
        </div>
        <h1>Create Account</h1>
        <p class="subtitle">Get started with your free account</p>
        
        <form id="signupForm">
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" id="email" name="email" required 
                       placeholder="Enter your email">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required 
                       placeholder="Create a password" minlength="8">
            </div>

            <button type="submit">
                <span class="button-text">Create Account</span>
                <div class="spinner"></div>
            </button>
        </form>

        <div id="message" class="message"></div>

        <div class="login-link">
            Already have an account? <a href="login.html">Sign in</a>
        </div>
    </div>

    <script>
        document.getElementById('signupForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const form = e.target;
            const button = form.querySelector('button');
            const buttonText = form.querySelector('.button-text');
            const spinner = form.querySelector('.spinner');
            const messageDiv = document.getElementById('message');
            
            // Get form data
            const email = form.email.value.trim();
            const password = form.password.value;

            // Disable button and show spinner
            button.disabled = true;
            buttonText.style.display = 'none';
            spinner.style.display = 'block';
            messageDiv.style.display = 'none';

            try {
                const response = await fetch('api/signup.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();

                messageDiv.style.display = 'block';
                if (data.status === 'success') {
                    messageDiv.className = 'message success';
                    messageDiv.textContent = 'Account created successfully! Redirecting...';
                    // Redirect to login page after 2 seconds
                    setTimeout(() => {
                        window.location.href = 'login.html';
                    }, 2000);
                } else {
                    messageDiv.className = 'message error';
                    messageDiv.textContent = data.message || 'An error occurred. Please try again.';
                }
            } catch (error) {
                messageDiv.style.display = 'block';
                messageDiv.className = 'message error';
                messageDiv.textContent = 'Network error. Please check your connection and try again.';
            } finally {
                // Re-enable button and hide spinner
                button.disabled = false;
                buttonText.style.display = 'block';
                spinner.style.display = 'none';
            }
        });
    </script>
</body>
</html>