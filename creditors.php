<?php
// Database connection
$servername = "127.0.0.1";
$username = "root";
$password = "";
$database = "test01";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

$query = $_GET['query'] ?? '';
if (!empty($query)) {
    $sql = "SELECT id, name, phone, credit_amount FROM creditors WHERE name LIKE ? OR phone LIKE ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $searchParam = '%' . $query . '%';
        $stmt->bind_param("ss", $searchParam, $searchParam);
        $stmt->execute();

        $result = $stmt->get_result();
        $response = [];

        while ($row = $result->fetch_assoc()) {
            $response[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'phone' => $row['phone'],
                'credit_amount' => $row['credit_amount']
            ];
        }

        $stmt->close();
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to prepare SQL statement: ' . $conn->error]);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode([]);
}

$conn->close();
?>
