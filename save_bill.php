
<?php
header('Content-Type: application/json');
include 'db_connection.php';

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

// Start transaction
$conn->begin_transaction();

try {
    // Insert bill
    $stmt = $conn->prepare("INSERT INTO bills (bill_number, total_amount, cash, creditor_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("idii", $billNumber, $totalAmount, $cash, $creditorId);
    $stmt->execute();
    $billId = $stmt->insert_id;
    $stmt->close();

    // Insert bill items
    $stmt = $conn->prepare("INSERT INTO bill_items (bill_id, stock_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($data['bill_items'] as $item) {
        $stmt->bind_param("iiid", $billId, $item['stock_id'], $item['quantity'], $item['price']);
        $stmt->execute();
    }
    $stmt->close();

    // Commit transaction
    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Bill saved successfully']);
} catch (Exception $e) {
    // Rollback transaction
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save bill: ' . $e->getMessage()]);
}

$conn->close();

?>