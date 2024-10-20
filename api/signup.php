<?php
header('Content-Type: application/json');
require 'db.php'; // Include your database connection

// Get POST data from the request body (JSON)
$input = json_decode(file_get_contents("php://input"), true);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $input['email'] ?? '';
    $password = $input['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo json_encode([
            "status" => "error",
            "message" => "Please provide both email and password."
        ]);
        exit();
    }

    try {
        // Check if email already exists
        $checkSql = "SELECT email FROM users WHERE email = ?";
        $checkStmt = $conn->prepare($checkSql);
        
        if (!$checkStmt) {
            throw new Exception("Query preparation failed: " . $conn->error);
        }

        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            echo json_encode([
                "status" => "error",
                "message" => "Email already exists"
            ]);
            exit();
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user
        $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Query preparation failed: " . $conn->error);
        }

        $stmt->bind_param("ss", $email, $hashedPassword);
        
        if ($stmt->execute()) {
            $userId = $conn->insert_id;
            
            echo json_encode([
                "status" => "success",
                "message" => "Signup successful",
                "data" => [
                    "user_id" => $userId,
                    "email" => $email
                ]
            ]);
        } else {
            throw new Exception("Error executing query: " . $stmt->error);
        }

    } catch (Exception $e) {
        error_log("Signup Error: " . $e->getMessage());
        echo json_encode([
            "status" => "error",
            "message" => "An error occurred during signup"
        ]);
    } finally {
        if (isset($checkStmt)) $checkStmt->close();
        if (isset($stmt)) $stmt->close();
        if (isset($conn)) $conn->close();
    }

} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request method"
    ]);
}
?>