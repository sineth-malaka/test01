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
    exit();
}

// Fetch distinct categories from the `stock` table
$query = "SELECT DISTINCT category FROM stock";
$result = $connection->query($query);

$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row['category'];
}

// Send categories as JSON
echo json_encode($categories);

$connection->close();
?>
