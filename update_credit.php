<<<<<<< HEAD
<?php
header('Content-Type: application/json');
include 'db_connection.php';

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
            $creditor = $result->fetch_assoc();
            $newCreditAmount = $creditor['credit_amount'] + $creditAmount;

            // Update the credit amount
            $updateSql = "UPDATE creditors SET credit_amount = ? WHERE id = ?";
            $updateStmt = $conn->prepare($updateSql);

            if ($updateStmt) {
                $updateStmt->bind_param("di", $newCreditAmount, $creditor['id']);
                if ($updateStmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Credit amount updated successfully']);
                } else {
                    http_response_code(500);
                    echo json_encode(['error' => 'Failed to update credit amount: ' . $updateStmt->error]);
                }
                $updateStmt->close();
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Prepare statement failed: ' . $conn->error]);
            }
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Creditor not found']);
        }
        $stmt->close();
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Prepare statement failed: ' . $conn->error]);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input data']);
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
            $creditor = $result->fetch_assoc();
            $newCreditAmount = $creditor['credit_amount'] + $creditAmount;

            // Update the credit amount
            $updateSql = "UPDATE creditors SET credit_amount = ? WHERE id = ?";
            $updateStmt = $conn->prepare($updateSql);

            if ($updateStmt) {
                $updateStmt->bind_param("di", $newCreditAmount, $creditor['id']);
                if ($updateStmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Credit amount updated successfully']);
                } else {
                    http_response_code(500);
                    echo json_encode(['error' => 'Failed to update credit amount: ' . $updateStmt->error]);
                }
                $updateStmt->close();
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Prepare statement failed: ' . $conn->error]);
            }
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Creditor not found']);
        }
        $stmt->close();
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Prepare statement failed: ' . $conn->error]);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input data']);
}

$conn->close();
>>>>>>> 8a70f8a (Initial commit)
?>