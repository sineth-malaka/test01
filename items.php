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

// Fetch query parameters
$category = $_GET['category'] ?? null;
$plu = $_GET['PLU'] ?? null;

// Build query
$query = "SELECT id, PLU, name, category, price, cost, image_path FROM stock WHERE 1=1";

if ($category) {
    $query .= " AND category = ?";
}
if ($plu) {
    $query .= " AND PLU = ?";
}

$stmt = $connection->prepare($query);

if ($category && $plu) {
    $stmt->bind_param("ss", $category, $plu);
} elseif ($category) {
    $stmt->bind_param("s", $category);
} elseif ($plu) {
    $stmt->bind_param("s", $plu);
}

$stmt->execute();
$result = $stmt->get_result();

$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}

// Send items as JSON
echo json_encode($items);

$connection->close();
?>
