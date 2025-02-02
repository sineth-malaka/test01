<?php
header('Content-Type: application/json');

$servername = "127.0.0.1";
$username = "root";
$password = "";
$database = "test01";

$connection = new mysqli($servername, $username, $password, $database);

if ($connection->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $connection->connect_error]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $bill_amount = filter_var($_POST['bill_amount'], FILTER_VALIDATE_FLOAT);
    $cash = filter_var($_POST['cash'], FILTER_VALIDATE_FLOAT);
    $balance = filter_var($_POST['balance'], FILTER_VALIDATE_FLOAT);
    $credit_amount = filter_var($_POST['credit_amount'], FILTER_VALIDATE_FLOAT);

    if (!$id || !$name || !$phone || $bill_amount === false || $cash === false || $balance === false || $credit_amount === false) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input data']);
        exit;
    }

    $stmt = $connection->prepare("
        UPDATE creditors 
        SET name = ?, phone = ?, bill_amount = ?, cash = ?, balance = ?, credit_amount = ?
        WHERE id = ?
    ");
    $stmt->bind_param("ssddddi", $name, $phone, $bill_amount, $cash, $balance, $credit_amount, $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Creditor updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update creditor: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

$connection->close();
?>
