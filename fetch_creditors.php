<?php
header('Content-Type: application/json');
include 'db_connection.php';

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$query = "SELECT * FROM creditors";
if (!empty($search)) {
    $query .= " WHERE name LIKE '%$search%' OR phone LIKE '%$search%'";
}

$result = $conn->query($query);

$data = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Query failed: ' . $conn->error]);
}

$conn->close();
?>