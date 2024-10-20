CREATE TABLE users (
    uid INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);


CREATE TABLE user_details (
    detail_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    first_name VARCHAR(255) DEFAULT NULL,
    last_name VARCHAR(255) DEFAULT NULL,
    contact_number VARCHAR(15) DEFAULT NULL,
    address VARCHAR(255) DEFAULT NULL,
    city VARCHAR(100) DEFAULT NULL,
    zip_code VARCHAR(20) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(uid) ON DELETE CASCADE
);

CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    description TEXT DEFAULT NULL
);


CREATE TABLE items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category_id INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    description TEXT DEFAULT NULL,  
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE  
);

CREATE TABLE item_pictures (
    picture_id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    picture_url VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (item_id) REFERENCES items(item_id) ON DELETE CASCADE
);


INSERT INTO categories (name, description) VALUES
('Home & Garden', 'Items related to home and gardening.');


INSERT INTO items (name, category_id, price, stock, description) VALUES
('Garden Chair', 1, 49.99, 100, 'A comfortable chair for your garden.'),
('Outdoor Table', 1, 99.99, 50, 'A sturdy table perfect for outdoor dining.'),
('Flower Pot', 1, 15.99, 200, 'A beautiful ceramic flower pot.'),
('Garden Hose', 1, 25.50, 150, 'A durable garden hose for watering plants.');


INSERT INTO item_pictures (item_id, file_path) VALUES
(1, 'images/background.jpeg'),
(2, 'images/background.jpeg'),
(3, 'images/background.jpeg'),
(4, 'images/background.jpeg');
