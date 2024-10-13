<?php
session_start();

// Database connection details
$host = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "gravity";

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $message = "Please enter both email and password.";
        } else {
            $sql = "SELECT uid, email, password FROM users WHERE email = ?";
            $stmt = $conn->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }

            $stmt->bind_param("s", $email);
            
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }

            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['uid'];
                    $_SESSION['user_email'] = $user['email'];
                    
                    header("Location: index.php");
                    exit();
                } else {
                    $message = "Invalid email or password";
                }
            } else {
                $message = "Invalid email or password";
            }

            $stmt->close();
        }
    } catch (Exception $e) {
        error_log("Error: " . $e->getMessage());
        $message = "An error occurred. Please try again later.";
    } finally {
        if (isset($conn) && $conn instanceof mysqli) {
            $conn->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Lavender Loom</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="mediaqueries.css">
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
</head>s
<body>
    <div class="nav-container">
        <nav class="small-nav">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="signup.php">Signup</a></li>
            </ul>
        </nav>
    </div>

    <div class="login-container">
        <h2>Login to Lavender Loom</h2>
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form class="login-form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>