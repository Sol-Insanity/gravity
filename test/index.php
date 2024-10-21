<?php
// Simulated data - in a real application, this would come from a database
$stats = [
    'total_users' => 1250,
    'active_users' => 847,
    'new_users_today' => 23,
    'revenue_today' => 2845.50
];

$recent_activity = [
    ['user' => 'John Doe', 'action' => 'Created new post', 'time' => '2 minutes ago'],
    ['user' => 'Jane Smith', 'action' => 'Updated profile', 'time' => '15 minutes ago'],
    ['user' => 'Mike Johnson', 'action' => 'Deleted comment', 'time' => '1 hour ago'],
    ['user' => 'Sarah Wilson', 'action' => 'Uploaded new image', 'time' => '2 hours ago'],
    ['user' => 'Tom Brown', 'action' => 'Changed password', 'time' => '3 hours ago'],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f0f2f5;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            padding: 20px;
        }

        .sidebar-header {
            font-size: 24px;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #34495e;
        }

        .nav-item {
            padding: 12px 15px;
            margin: 5px 0;
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

        .main-content {
            flex: 1;
            padding: 20px;
        }

        .header {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-card {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .stat-card h3 {
            color: #7f8c8d;
            font-size: 0.9em;
            margin-bottom: 10px;
        }

        .stat-card .value {
            font-size: 1.8em;
            font-weight: bold;
            color: #2c3e50;
        }

        .recent-activity {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .activity-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .activity-table th,
        .activity-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ecf0f1;
        }

        .activity-table th {
            color: #7f8c8d;
            font-weight: 600;
        }

        .quick-actions {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .action-button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #3498db;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .action-button:hover {
            background-color: #2980b9;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
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
        
        <div class="main-content">
            <div class="header">
                <h1>Dashboard Overview</h1>
            </div>

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

            <div class="recent-activity">
                <h2>Recent Activity</h2>
                <table class="activity-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Action</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_activity as $activity): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($activity['user']); ?></td>
                            <td><?php echo htmlspecialchars($activity['action']); ?></td>
                            <td><?php echo htmlspecialchars($activity['time']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>