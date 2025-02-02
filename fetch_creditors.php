<?php
header('Content-Type: application/json');

$servername = "127.0.0.1";
$username = "root";
$password = "";
$database = "test01";

$connection = new mysqli($servername, $username, $password, $database);

if ($connection->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

$search = isset($_GET['search']) ? $connection->real_escape_string($_GET['search']) : '';

$query = "SELECT * FROM creditors";
if (!empty($search)) {
    $query .= " WHERE name LIKE '%$search%' OR phone LIKE '%$search%'";
}

$result = $connection->query($query);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(['status' => 'success', 'data' => $data]);

$connection->close();
?>
