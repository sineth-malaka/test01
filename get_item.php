<<<<<<< HEAD
<?php
header('Content-Type: application/json');
include 'db_connection.php';

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$id = $_GET['id'] ?? '';

if (empty($id) || !filter_var($id, FILTER_VALIDATE_INT)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid or missing ID parameter"]);
    exit;
}

$sql = "SELECT * FROM stock WHERE id = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(["error" => "Item not found"]);
    }

    $stmt->close();
} else {
    http_response_code(500);
    echo json_encode(["error" => "Prepare statement failed: " . $conn->error]);
}

$conn->close();
=======
<?php
header('Content-Type: application/json');
include 'db_connection.php';

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$id = $_GET['id'] ?? '';

if (empty($id) || !filter_var($id, FILTER_VALIDATE_INT)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid or missing ID parameter"]);
    exit;
}

$sql = "SELECT * FROM stock WHERE id = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(["error" => "Item not found"]);
    }

    $stmt->close();
} else {
    http_response_code(500);
    echo json_encode(["error" => "Prepare statement failed: " . $conn->error]);
}

$conn->close();
>>>>>>> 8a70f8a (Initial commit)
?>