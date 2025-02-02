<?php
header('Content-Type: application/json');

$servername = "127.0.0.1";
$username = "root";
$password = "";
$database = "test01";

// Create database connection
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$plu = $_POST['plu'];
$name = $_POST['name'];
$category = $_POST['category'];
$price = $_POST['price'];
$cost = $_POST['cost'];
$imagePath = null;

// Handle file upload
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    $imagePath = $uploadDir . basename($_FILES['image']['name']);
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
        echo json_encode(["error" => "Failed to upload image."]);
        exit;
    }
}

// Insert or update record
$sql = "INSERT INTO stock (PLU, name, category, price, cost, image_path)
        VALUES (?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
        name = VALUES(name), category = VALUES(category), price = VALUES(price), 
        cost = VALUES(cost), image_path = VALUES(image_path)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssdds", $plu, $name, $category, $price, $cost, $imagePath);

if ($stmt->execute()) {
    echo json_encode(["message" => "Item saved successfully!"]);
} else {
    echo json_encode(["error" => "Failed to save item: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
