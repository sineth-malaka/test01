<<<<<<< HEAD
<?php
header('Content-Type: application/json');
include 'db_connection.php';

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
            $response[] = $row;
        }

        echo json_encode($response);
        $stmt->close();
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Prepare statement failed: ' . $conn->error]);
    }
} else {
    echo json_encode(['error' => 'No query parameter provided']);
}

$conn->close();
=======
<?php
header('Content-Type: application/json');
include 'db_connection.php';

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
            $response[] = $row;
        }

        echo json_encode($response);
        $stmt->close();
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Prepare statement failed: ' . $conn->error]);
    }
} else {
    echo json_encode(['error' => 'No query parameter provided']);
}

$conn->close();
>>>>>>> 8a70f8a (Initial commit)
?>