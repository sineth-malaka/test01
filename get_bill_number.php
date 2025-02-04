<?php
header('Content-Type: application/json');
include 'db_connection.php';

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// Fetch the latest bill number
$query = "SELECT MAX(id) AS max_id FROM bills";
$result = $conn->query($query);

if ($result) {
    $row = $result->fetch_assoc();
    $latestBillNumber = $row['max_id'] ?? 0;
    $newBillNumber = $latestBillNumber + 1;

    // Send the new bill number as JSON
    echo json_encode(['bill_number' => $newBillNumber]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Query failed: ' . $conn->error]);
}

$conn->close();
?>