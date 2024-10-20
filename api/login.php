<?php
header('Content-Type: application/json');
session_start();  // Start a session

require 'db.php'; // Include your database connection

// Get POST data from the request body (JSON)
$input = json_decode(file_get_contents("php://input"), true);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $input['email'] ?? '';
    $password = $input['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo json_encode([
            "status" => "error",
            "message" => "Please enter both email and password."
        ]);
        exit();
    }

    // Prepare SQL query to fetch the user
    $sql = "SELECT uid, email, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode([
            "status" => "error",
            "message" => "Query preparation failed."
        ]);
        exit();
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Generate a session token for the logged-in user
            $_SESSION['user_id'] = $user['uid'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['logged_in'] = true;
            $_SESSION['token'] = bin2hex(random_bytes(32)); // Secure session token

            echo json_encode([
                "status" => "success",
                "message" => "Login successful.",
                "token" => $_SESSION['token']
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Invalid email or password."
            ]);
        }
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid email or password."
        ]);
    }

    $stmt->close();
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request method."
    ]);
}

$conn->close();
?>
