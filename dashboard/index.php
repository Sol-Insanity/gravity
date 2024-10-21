<?php
require_once __DIR__ . '/../api/db.php';


// Calculate statistics for the dashboard
// Get total users and new users registered today
$stats_query = "SELECT 
    COUNT(*) as total_users,
    COUNT(CASE WHEN DATE(ud.created_at) = CURDATE() THEN 1 END) as new_users_today
    FROM users u
    LEFT JOIN user_details ud ON u.uid = ud.user_id";
$stats_result = $conn->query($stats_query);
$stats_data = $stats_result->fetch_assoc();

// Prepare stats array for dashboard cards
$stats = [
    'total_users' => $stats_data['total_users'],
    'new_users_today' => $stats_data['new_users_today'],
    'active_users' => $stats_data['total_users'], // You might want to modify this based on your definition of active
    'revenue_today' => NULL // Placeholder - replace with actual revenue calculation
];

// Handle user update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    // Get form data
    $user_id = $_POST['user_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $contact_number = $_POST['contact_number'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $zip_code = $_POST['zip_code'];
    
    // Prepare and execute update query using prepared statement for security
    $update_query = "UPDATE user_details SET 
        first_name = ?, 
        last_name = ?, 
        contact_number = ?,
        address = ?,
        city = ?,
        zip_code = ?
        WHERE user_id = ?";
    
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssssi", $first_name, $last_name, $contact_number, $address, $city, $zip_code, $user_id);
    
    // Check if update was successful and set appropriate message
    if ($stmt->execute()) {
        $success_message = "User updated successfully!";
    } else {
        $error_message = "Error updating user: " . $conn->error;
    }
    $stmt->close();
}

// Fetch all users with their details
// Join users table with user_details to get complete information
$users_query = "SELECT u.uid, u.email, ud.* 
                FROM users u 
                LEFT JOIN user_details ud ON u.uid = ud.user_id 
                ORDER BY ud.created_at DESC";
$users_result = $conn->query($users_query);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    // Get form data
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $contact_number = $_POST['contact_number'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $zip_code = $_POST['zip_code'];
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Insert into users table first
        $user_query = "INSERT INTO users (email, password) VALUES (?, ?)";
        $stmt = $conn->prepare($user_query);
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        
        // Get the inserted user's ID
        $user_id = $conn->insert_id;
        
        // Insert into user_details table
        $details_query = "INSERT INTO user_details (user_id, first_name, last_name, contact_number, address, city, zip_code) 
                         VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($details_query);
        $stmt->bind_param("issssss", $user_id, $first_name, $last_name, $contact_number, $address, $city, $zip_code);
        $stmt->execute();
        
        // Commit transaction
        $conn->commit();
        $success_message = "User added successfully!";
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $error_message = "Error adding user: " . $e->getMessage();
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <!-- Sidebar Navigation -->
        <div class="sidebar">
            <div class="sidebar-header">
                Admin Panel
            </div>
            <div class="nav-item">
                <i class="fas fa-home"></i> Dashboard
            </div>
            <div class="nav-item">
                <i class="fas fa-users"></i> Users
            </div>
            <div class="nav-item">
                <i class="fas fa-chart-bar"></i> Analytics
            </div>
            <div class="nav-item">
                <i class="fas fa-cog"></i> Settings
            </div>
            <div class="nav-item">
                <i class="fas fa-sign-out-alt"></i> Logout
            </div>
        </div>
        
        <!-- Main Content Area -->
        <div class="main-content">
            <div class="header">
                <h1>Dashboard Overview</h1>
            </div>

            <!-- Display success/error messages if any -->
            <?php if (isset($success_message)): ?>
                <div class="success-message"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <!-- Quick Action Buttons -->
            <div class="quick-actions">
                <button class="action-button">
                    <i class="fas fa-plus"></i> New User
                </button>
                <button class="action-button">
                    <i class="fas fa-file"></i> Generate Report
                </button>
                <button class="action-button">
                    <i class="fas fa-sync"></i> Refresh Data
                </button>
            </div>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>TOTAL USERS</h3>
                    <div class="value"><?php echo number_format($stats['total_users']); ?></div>
                </div>
                <div class="stat-card">
                    <h3>ACTIVE USERS</h3>
                    <div class="value"><?php echo number_format($stats['active_users']); ?></div>
                </div>
                <div class="stat-card">
                    <h3>NEW USERS TODAY</h3>
                    <div class="value"><?php echo number_format($stats['new_users_today']); ?></div>
                </div>
                <div class="stat-card">
                    <h3>TODAY'S REVENUE</h3>
                    <div class="value">$<?php echo number_format($stats['revenue_today'], 2); ?></div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="users-table-container">
                <h2>Current Users</h2>
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>Email</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>City</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // Loop through all users and display their information
                        while ($user = $users_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['contact_number']); ?></td>
                            <td><?php echo htmlspecialchars($user['city']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                            <td>
                                <button class="edit-button" onclick="openEditModal(<?php 
                                    // Prepare user data for the edit modal
                                    echo htmlspecialchars(json_encode([
                                        'user_id' => $user['uid'],
                                        'first_name' => $user['first_name'],
                                        'last_name' => $user['last_name'],
                                        'contact_number' => $user['contact_number'],
                                        'address' => $user['address'],
                                        'city' => $user['city'],
                                        'zip_code' => $user['zip_code']
                                    ]));
                                ?>)">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->

    <!-- Add User Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add New User</h2>
            <span class="close" onclick="closeAddModal()">&times;</span>
        </div>
        <form id="addForm" method="POST">
            <input type="hidden" name="add_user" value="1">
            <div class="form-group">
                <label for="email">Email*</label>
                <input type="email" id="add_email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password*</label>
                <input type="password" id="add_password" name="password" required>
            </div>
            <div class="form-group">
                <label for="first_name">First Name*</label>
                <input type="text" id="add_first_name" name="first_name" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name*</label>
                <input type="text" id="add_last_name" name="last_name" required>
            </div>
            <div class="form-group">
                <label for="contact_number">Contact Number</label>
                <input type="text" id="add_contact_number" name="contact_number">
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="add_address" name="address">
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" id="add_city" name="city">
            </div>
            <div class="form-group">
                <label for="zip_code">ZIP Code</label>
                <input type="text" id="add_zip_code" name="zip_code">
            </div>
            <button type="submit" class="action-button">Add User</button>
        </form>
    </div>
</div>

    <!-- Edit User Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit User Details</h2>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <form id="editForm" method="POST">
                <input type="hidden" name="update_user" value="1">
                <input type="hidden" name="user_id" id="edit_user_id">
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="edit_first_name" name="first_name" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="edit_last_name" name="last_name" required>
                </div>
                <div class="form-group">
                    <label for="contact_number">Contact Number</label>
                    <input type="text" id="edit_contact_number" name="contact_number">
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="edit_address" name="address">
                </div>
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="edit_city" name="city">
                </div>
                <div class="form-group">
                    <label for="zip_code">ZIP Code</label>
                    <input type="text" id="edit_zip_code" name="zip_code">
                </div>
                <button type="submit" class="action-button">Update User</button>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>