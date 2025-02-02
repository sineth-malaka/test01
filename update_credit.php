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

// Retrieve POST data
$data = json_decode(file_get_contents('php://input'), true);
$creditorName = $data['creditor_name'] ?? '';
$creditAmount = $data['credit_amount'] ?? 0;

if (!empty($creditorName)) {
    // Check if the creditor exists
    $sql = "SELECT id, credit_amount FROM creditors WHERE name = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $creditorName);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Creditor exists, update the credit amount
            $row = $result->fetch_assoc();
            $newCreditAmount = $row['credit_amount'] + $creditAmount;

            $updateSql = "UPDATE creditors SET credit_amount = ? WHERE id = ?";
            $updateStmt = $conn->prepare($updateSql);

            if ($updateStmt) {
                $updateStmt->bind_param("di", $newCreditAmount, $row['id']);
                $updateStmt->execute();
                http_response_code(200);
                echo json_encode(['message' => 'Credit updated successfully.']);
                $updateStmt->close();
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to prepare update statement: ' . $conn->error]);
            }
        } else {
            // Creditor does not exist, return an error
            http_response_code(400);
            echo json_encode(['error' => 'Creditor not found.']);
        }

        $stmt->close();
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to prepare SQL statement: ' . $conn->error]);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid creditor name or credit amount.']);
}

$conn->close();
?>
