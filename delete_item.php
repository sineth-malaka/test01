<?php
header('Content-Type: application/json');

$servername = "127.0.0.1";
$username = "root";
$password = "";
$database = "test01";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$id = $_GET['id'];

$sql = "DELETE FROM stock WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["message" => "Item deleted successfully!"]);
} else {
    echo json_encode(["error" => "Failed to delete item: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
