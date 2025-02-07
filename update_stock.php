<<<<<<< HEAD
<?php
header('Content-Type: application/json');
include 'db_connection.php';

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$data = json_decode(file_get_contents("php://input"), true);
$response = ["success" => true, "message" => ""];

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid input data"]);
    exit;
}

$stmt = $conn->prepare("UPDATE stock SET stock = ? WHERE PLU = ?");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(["error" => "Prepare statement failed: " . $conn->error]);
    exit;
}

foreach ($data as $item) {
    $plu = $item['PLU'] ?? '';
    $stock = $item['stock'] ?? '';

    if (empty($plu) || !is_numeric($stock)) {
        $response["success"] = false;
        $response["message"] = "Invalid data for PLU: $plu";
        break;
    }

    $stmt->bind_param("is", $stock, $plu);
    if (!$stmt->execute()) {
        $response["success"] = false;
        $response["message"] = "Failed to update stock for PLU: $plu";
        break;
    }
}

$stmt->close();
$conn->close();

echo json_encode($response);
=======
<?php
header('Content-Type: application/json');
include 'db_connection.php';

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$data = json_decode(file_get_contents("php://input"), true);
$response = ["success" => true, "message" => ""];

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid input data"]);
    exit;
}

$stmt = $conn->prepare("UPDATE stock SET stock = ? WHERE PLU = ?");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(["error" => "Prepare statement failed: " . $conn->error]);
    exit;
}

foreach ($data as $item) {
    $plu = $item['PLU'] ?? '';
    $stock = $item['stock'] ?? '';

    if (empty($plu) || !is_numeric($stock)) {
        $response["success"] = false;
        $response["message"] = "Invalid data for PLU: $plu";
        break;
    }

    $stmt->bind_param("is", $stock, $plu);
    if (!$stmt->execute()) {
        $response["success"] = false;
        $response["message"] = "Failed to update stock for PLU: $plu";
        break;
    }
}

$stmt->close();
$conn->close();

echo json_encode($response);
>>>>>>> 8a70f8a (Initial commit)
?>