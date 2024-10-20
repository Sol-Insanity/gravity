<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Lavender Loom</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: #f8f8f8;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .login-container h2 {
            text-align: center;
            color: var(--purple);
            margin-bottom: 20px;
        }
        .login-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .login-form button {
            width: 100%;
            padding: 10px;
            background-color: var(--purple);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .login-form button:hover {
            background-color: #4a32a8;
        }
        .message {
            text-align: center;
            color: #e74c3c;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login to Lavender Loom</h2>
        <p class="message" id="loginMessage"></p> <!-- Display error or success messages -->
        <form id="loginForm">
            <input type="email" id="email" placeholder="Email" required>
            <input type="password" id="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent form submission

            // Capture form inputs
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const messageElement = document.getElementById('loginMessage');

            // Basic validation
            if (email === '' || password === '') {
                messageElement.style.color = 'red';
                messageElement.textContent = "Please enter both email and password.";
                return; // Stop execution if inputs are invalid
            }

            // Create the POST request to the API
            fetch('api/login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    email: email,
                    password: password
                })
            })
            .then(response => response.json())
            .then(data => {
                // Handle response
                if (data.status === 'success') {
                    messageElement.style.color = 'green';
                    messageElement.textContent = data.message;
                    // Redirect or set session (optional)
                    setTimeout(() => {
                        localStorage.setItem('sessionToken', data.token);
                        window.location.href = 'index.php'; // Redirect to index page on success
                    }, 1000);
                } else {
                    messageElement.style.color = 'red';
                    messageElement.textContent = data.message; // Show error message
                }
            })
            .catch(error => {
                console.error('Error:', error);
                messageElement.textContent = "An error occurred. Please try again.";
            });
        });

    </script>
</body>
</html>
