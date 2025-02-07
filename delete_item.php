
<?php
header('Content-Type: application/json');
include 'db_connection.php';

if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$id = $_GET['id'] ?? '';

if (empty($id) || !filter_var($id, FILTER_VALIDATE_INT)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid or missing ID parameter"]);
    exit;
}

$sql = "DELETE FROM stock WHERE id = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Item deleted successfully!"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to delete item: " . $stmt->error]);
    }

    $stmt->close();
} else {
    http_response_code(500);
    echo json_encode(["error" => "Prepare statement failed: " . $conn->error]);
}

$conn->close();

?>
