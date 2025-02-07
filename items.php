
<?php
header('Content-Type: application/json');
include 'db_connection.php';

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// Fetch query parameters
$category = $_GET['category'] ?? null;
$plu = $_GET['PLU'] ?? null;

// Build query
$query = "SELECT id, PLU, name, category, price, cost, image_path FROM stock WHERE 1=1";
$params = [];
$types = '';

if ($category) {
    $query .= " AND category = ?";
    $params[] = $category;
    $types .= 's';
}
if ($plu) {
    $query .= " AND PLU = ?";
    $params[] = $plu;
    $types .= 's';
}

$stmt = $conn->prepare($query);

if ($stmt) {
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $items = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        echo json_encode($items);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Query failed: ' . $conn->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Prepare statement failed: ' . $conn->error]);
}

$conn->close();

?>