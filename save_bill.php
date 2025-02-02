<?php
header('Content-Type: application/json');

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

// Read POST data
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['bill_items'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid data provided.']);
    exit;
}

$billNumber = $data['bill_number'];
$totalAmount = $data['total_amount'];
$cash = $data['cash'];
$creditorId = $data['creditor_id'] ?? null;
$creditorName = isset($data['creditor_name']) ? trim($data['creditor_name']) : null;
$creditAmount = $data['credit_amount'] ?? 0;
$balance = $data['balance'] ?? 0;
$billItems = $data['bill_items'];

// Validate bill items
if (empty($billItems)) {
    http_response_code(400);
    echo json_encode(['error' => 'No bill items provided.']);
    exit;
}

// Start transaction
$conn->begin_transaction();

try {
    // Insert bill details
    $stmt = $conn->prepare("INSERT INTO bills (total_amount, cash, creditor_name) VALUES (?, ?, ?)");
    if (!$stmt) {
        throw new Exception("Failed to prepare statement for bills: " . $conn->error);
    }
    $stmt->bind_param("dds", $totalAmount, $cash, $creditorName);
    if (!$stmt->execute()) {
        throw new Exception("Failed to execute statement for bills: " . $stmt->error);
    }
    $billId = $stmt->insert_id;

    // Insert bill items
    $itemStmt = $conn->prepare("INSERT INTO bill_items (bill_id, plu, quantity) VALUES (?, ?, ?)");
    if (!$itemStmt) {
        throw new Exception("Failed to prepare statement for bill items: " . $conn->error);
    }

    foreach ($billItems as $item) {
        if (!isset($item['PLU'], $item['quantity'])) {
            throw new Exception("Invalid bill item data.");
        }
        $itemStmt->bind_param("isi", $billId, $item['PLU'], $item['quantity']);
        if (!$itemStmt->execute()) {
            throw new Exception("Failed to execute statement for bill items: " . $itemStmt->error);
        }
    }

    // Update only Bill Amount, Cash, Balance, Credit Amount for the creditor
    if ($creditorId) {
        $updateStmt = $conn->prepare("
            UPDATE creditors 
            SET bill_amount = bill_amount + ?, 
                cash = cash + ?, 
                balance = balance + ?, 
                credit_amount = ?
            WHERE id = ?
        ");

        if (!$updateStmt) {
            throw new Exception("Failed to prepare update statement: " . $conn->error);
        }

        $updateStmt->bind_param("ddddi", $totalAmount, $cash, $balance, $creditAmount, $creditorId);

        if (!$updateStmt->execute()) {
            throw new Exception("Failed to update creditor: " . $updateStmt->error);
        }
    }

    // Commit transaction
    $conn->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save bill: ' . $e->getMessage()]);
}

$conn->close();
?>
