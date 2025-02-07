
<?php
header('Content-Type: application/json');
include 'db_connection.php';

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $bill_amount = filter_var($_POST['bill_amount'], FILTER_VALIDATE_FLOAT);
    $cash = filter_var($_POST['cash'], FILTER_VALIDATE_FLOAT);
    $balance = filter_var($_POST['balance'], FILTER_VALIDATE_FLOAT);
    $credit_amount = filter_var($_POST['credit_amount'], FILTER_VALIDATE_FLOAT);

    if (!$name || !$phone || $bill_amount === false || $cash === false || $balance === false || $credit_amount === false) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input data']);
        exit;
    }

    $stmt = $conn->prepare("
        INSERT INTO creditors (name, phone, bill_amount, cash, balance, credit_amount) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    if ($stmt === false) {
        echo json_encode(['status' => 'error', 'message' => 'Prepare statement failed: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param("ssdddd", $name, $phone, $bill_amount, $cash, $balance, $credit_amount);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Creditor added successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add creditor: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

$conn->close();

?>
