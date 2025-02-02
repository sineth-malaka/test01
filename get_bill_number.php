<?php
header('Content-Type: application/json');

// Database connection
$servername = "127.0.0.1";
$username = "root";
$password = "";
$database = "test01";

$connection = new mysqli($servername, $username, $password, $database);

if ($connection->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

// Fetch the latest bill number
$query = "SELECT MAX(id) AS max_id FROM bills";
$result = $connection->query($query);
$row = $result->fetch_assoc();

$latestBillNumber = $row['max_id'] ?? 0;
$newBillNumber = $latestBillNumber + 1;

// Send the new bill number as JSON
echo json_encode(['bill_number' => $newBillNumber]);

$connection->close();
?>
