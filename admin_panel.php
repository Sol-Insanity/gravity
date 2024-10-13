<?php
session_start();

// Database connection details
$host = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "gravity";

$message = '';

// Function to get database connection
function getDbConnection() {
    global $host, $dbUsername, $dbPassword, $dbName;
    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Add item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_item'])) {
    $conn = getDbConnection();
    
    $cat_id = $_POST['cat_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    
    // Handle image upload
    $image = null; // Default to null in case no image is uploaded
    $original_image_name = null; // Store the original image name
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Set upload directory
        $target_dir = "uploads/";
        
        // Get the original filename
        $original_image_name = basename($_FILES["image"]["name"]);
        
        // Generate a unique filename
        $image_name = uniqid() . "-" . $original_image_name;
        $target_file = $target_dir . $image_name;
        
        // Get file extension
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate image file type
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowed_types)) {
            $message = "Only JPG, JPEG, PNG, and GIF files are allowed.";
        } else {
            // Move the file to the target directory
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image = $image_name; // Store the unique file name
            } else {
                $message = "Error uploading image.";
            }
        }
    }

    // Insert item into database (including the unique and original image names)
    $sql = "INSERT INTO items (cat_id, name, price, stock, image, original_image_name) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isdsss", $cat_id, $name, $price, $stock, $image, $original_image_name);

    if ($stmt->execute()) {
        $message = "Item added successfully.";
    } else {
        $message = "Error adding item: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}



// Fetch items
function getItems() {
    $conn = getDbConnection();
    $sql = "SELECT * FROM items";
    $result = $conn->query($sql);
    $items = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
    }
    $conn->close();
    return $items;
}

$items = getItems();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Lavender Loom</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="mediaqueries.css">
    <style>
        .admin-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f8f8f8;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .admin-container h2 {
            color: var(--purple);
            margin-bottom: 20px;
        }
        .admin-form input, .admin-form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .admin-form button {
            padding: 10px 20px;
            background-color: var(--purple);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .admin-form button:hover {
            background-color: #4a32a8;
        }
        .message {
            color: #e74c3c;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: var(--purple);
            color: white;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <h2>Admin Panel - Add Item</h2>
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form class="admin-form" method="post" enctype="multipart/form-data">
            <input type="number" name="cat_id" placeholder="Category ID" required>
            <input type="text" name="name" placeholder="Item Name" required>
            <input type="number" name="price" step="0.01" placeholder="Price" required>
            <input type="number" name="stock" placeholder="Stock" required>
            <input type="file" name="image"> <!-- Optional image upload -->
            <button type="submit" name="add_item">Add Item</button>
        </form>


        <h2>Items List</h2>
        <table>
            <tr>
                <th>Item ID</th>
                <th>Category ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Image</th> <!-- Add image column -->
            </tr>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?php echo $item['item_id']; ?></td>
                <td><?php echo $item['cat_id']; ?></td>
                <td><?php echo $item['name']; ?></td>
                <td><?php echo $item['price']; ?></td>
                <td><?php echo $item['stock']; ?></td>
                <td>
                    <?php if ($item['image']): ?>
                        <img src="uploads/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" style="width: 50px; height: 50px;">
                    <?php else: ?>
                        No image
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

    </div>
</body>
</html>