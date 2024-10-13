<?php
// Database connection details
$host = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "gravity";

$message = '';

try {
    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $message = "Please fill in all fields.";
        } else {
            // Check if email already exists
            $checkSql = "SELECT * FROM users WHERE email = ?";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bind_param("s", $email);
            $checkStmt->execute();
            $result = $checkStmt->get_result();

            if ($result->num_rows > 0) {
                $message = "Email already exists. Please use a different email.";
            } else {
                // Hash the password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                
                if (!$stmt) {
                    throw new Exception("Prepare failed: " . $conn->error);
                }

                $stmt->bind_param("ss", $email, $hashedPassword);
                
                if ($stmt->execute()) {
                    $message = "Signup successful!";
                } else {
                    $message = "Error: " . $stmt->error;
                }

                $stmt->close();
            }
            $checkStmt->close();
        }
    }
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    $message = "An error occurred. Please try again later.";
} finally {
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input {
            margin: 0.5rem 0;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 0.75rem;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 1rem;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            text-align: center;
            margin-top: 1rem;
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Sign Up</h1>
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign Up</button>
        </form>
    </div>
</body>
</html>