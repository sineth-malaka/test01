
<?php
header('Content-Type: application/json');
include 'db_connection.php';

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$plu = $_POST['plu'] ?? '';
$name = $_POST['name'] ?? '';
$stock = $_POST['stock'] ?? '';
$price = $_POST['price'] ?? '';
$cost = $_POST['cost'] ?? '';
$category = $_POST['category'] ?? '';
$imagePath = null;

// Validate inputs
if (empty($plu) || empty($name) || !is_numeric($stock) || !is_numeric($price) || !is_numeric($cost) || empty($category)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid input data"]);
    exit;
}

// Handle file upload
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
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

$sql = "UPDATE stock SET name = ?, stock = ?, price = ?, cost = ?, category = ?, image_path = ? WHERE PLU = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("siddsss", $name, $stock, $price, $cost, $category, $imagePath, $plu);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Item updated successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to update item: " . $stmt->error]);
    }

    $stmt->close();
} else {
    http_response_code(500);
    echo json_encode(["error" => "Prepare statement failed: " . $conn->error]);
}

$conn->close();

?>
