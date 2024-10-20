<?php
session_start();

// Simulated authentication check
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Simulated database connection
$db_connection = [
    'users' => [
        ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com'],
        ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com']
    ],
    'stats' => [
        'total_users' => 1250,
        'total_sales' => 45678,
        'active_users' => 890,
        'new_orders' => 25
    ]
];

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add your form processing logic here
    $message = "Action processed successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            color: #333;
        }

        /* Dashboard Layout */
        .dashboard {
            display: grid;
            grid-template-columns: 250px 1fr;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            background-color: #2c3e50;
            color: white;
            padding: 1rem;
            position: fixed;
            height: 100vh;
            width: 250px;
        }

        .sidebar-header {
            padding: 1rem 0;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .nav-menu {
            list-style: none;
            margin-top: 2rem;
        }

        .nav-item {
            padding: 0.8rem 1rem;
            margin: 0.5rem 0;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .nav-item:hover {
            background-color: #34495e;
        }

        .nav-item i {
            margin-right: 10px;
        }

        /* Main Content Area */
        .main-content {
            padding: 2rem;
            margin-left: 250px;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .stat-card h3 {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .stat-card .value {
            font-size: 1.8rem;
            font-weight: bold;
            color: #2c3e50;
        }

        /* Data Tables */
        .data-table {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        /* Action Buttons */
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-primary {
            background-color: #3498db;
            color: white;
        }

        .btn-danger {
            background-color: #e74c3c;
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .dashboard {
                grid-template-columns: 1fr;
            }

            .sidebar {
                display: none;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar Navigation -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>Admin Panel</h2>
            </div>
            <ul class="nav-menu">
                <li class="nav-item"><i class="fas fa-home"></i> Dashboard</li>
                <li class="nav-item"><i class="fas fa-users"></i> Users</li>
                <li class="nav-item"><i class="fas fa-shopping-cart"></i> Orders</li>
                <li class="nav-item"><i class="fas fa-chart-bar"></i> Analytics</li>
                <li class="nav-item"><i class="fas fa-cog"></i> Settings</li>
                <li class="nav-item"><i class="fas fa-sign-out-alt"></i> Logout</li>
            </ul>
        </div>

        <!-- Main Content Area -->
        <div class="main-content">
            <h1>Dashboard Overview</h1>
            
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Users</h3>
                    <div class="value"><?php echo $db_connection['stats']['total_users']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Total Sales</h3>
                    <div class="value">$<?php echo number_format($db_connection['stats']['total_sales']); ?></div>
                </div>
                <div class="stat-card">
                    <h3>Active Users</h3>
                    <div class="value"><?php echo $db_connection['stats']['active_users']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>New Orders</h3>
                    <div class="value"><?php echo $db_connection['stats']['new_orders']; ?></div>
                </div>
            </div>

            <!-- Recent Users Table -->
            <div class="data-table">
                <h2>Recent Users</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($db_connection['users'] as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo $user['name']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td>
                                <button class="btn btn-primary">Edit</button>
                                <button class="btn btn-danger">Delete</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Additional Features Placeholder -->
            <div class="data-table">
                <h2>Quick Actions</h2>
                <p>Add your custom actions here</p>
                <!-- Add more features as needed -->
            </div>
        </div>
    </div>

    <!-- Add your JavaScript here -->
    <script>
        // Add your interactive features here
        document.addEventListener('DOMContentLoaded', function() {
            // Example: Add click handlers for buttons
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    alert('Action button clicked! Add your functionality here.');
                });
            });
        });
    </script>
</body>
</html>