<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$database = "test01";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$plu = $_POST['plu'];
$name = $_POST['name'];
$stock = $_POST['stock'];
$price = $_POST['price'];
$cost = $_POST['cost'];
$category = $_POST['category'];
$imagePath = null;

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $imagePath = 'uploads/' . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);

    $stmt = $conn->prepare("UPDATE items SET name = ?, stock = ?, price = ?, cost = ?, category = ?, image = ? WHERE PLU = ?");
    $stmt->bind_param("siddsss", $name, $stock, $price, $cost, $category, $imagePath, $plu);
} else {
    $stmt = $conn->prepare("UPDATE items SET name = ?, stock = ?, price = ?, cost = ?, category = ? WHERE PLU = ?");
    $stmt->bind_param("siddss", $name, $stock, $price, $cost, $category, $plu);
}

$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(["message" => "Item updated successfully."]);
} else {
    echo json_encode(["error" => "Failed to update item."]);
}

$stmt->close();
$conn->close();
?>
