<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$database = "test01";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);
$response = ["success" => true, "message" => ""];

foreach ($data as $item) {
    $plu = $conn->real_escape_string($item['PLU']);
    $stock = intval($item['stock']);

    $sql = "UPDATE stock SET stock = $stock WHERE PLU = '$plu'";
    if (!$conn->query($sql)) {
        $response["success"] = false;
        $response["message"] = "Failed to update stock for PLU: $plu";
        break;
    }
}

echo json_encode($response);
$conn->close();
?>
