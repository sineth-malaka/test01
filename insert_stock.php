
<?php
header('Content-Type: application/json');
include 'db_connection.php';

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$plu = $_POST['plu'] ?? '';
$name = $_POST['name'] ?? '';
$category = $_POST['category'] ?? '';
$price = $_POST['price'] ?? '';
$cost = $_POST['cost'] ?? '';
$imagePath = null;

// Validate inputs
if (empty($plu) || empty($name) || empty($category) || !is_numeric($price) || !is_numeric($cost)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid input data"]);
    exit;
}

// Handle file upload
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    $imagePath = $uploadDir . basename($_FILES['image']['name']);
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
        http_response_code(500);
        echo json_encode(["error" => "Failed to upload image"]);
        exit;
    }
}

$sql = "INSERT INTO stock (PLU, name, category, price, cost, image_path) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("sssdds", $plu, $name, $category, $price, $cost, $imagePath);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Stock item inserted successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to insert stock item: " . $stmt->error]);
    }

    $stmt->close();
} else {
    http_response_code(500);
    echo json_encode(["error" => "Prepare statement failed: " . $conn->error]);
}

$conn->close();

?>