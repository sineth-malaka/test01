<?php
header('Content-Type: application/json');
include 'db_connection.php';

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

// Fetch distinct categories from the `stock` table
$query = "SELECT DISTINCT category FROM stock";
$result = $conn->query($query);

$categories = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row['category'];
    }
    echo json_encode($categories);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Query failed: ' . $conn->error]);
}

$conn->close();
?>