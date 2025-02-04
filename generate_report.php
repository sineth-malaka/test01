<?php
header('Content-Type: application/json');
include 'db_connection.php';

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

$startDatetime = $_GET['start_datetime'] ?? null;
$endDatetime = $_GET['end_datetime'] ?? null;

if (!$startDatetime || !$endDatetime) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid date and time range']);
    exit;
}

// Summary Report Query
$summaryQuery = "
    SELECT 
        IFNULL(SUM(bi.quantity * s.price), 0) AS total_revenue,
        IFNULL(SUM((s.price - s.cost) * bi.quantity), 0) AS total_profit,
        IFNULL(SUM(bi.quantity * s.cost), 0) AS total_cost
    FROM 
        bill_items bi
    JOIN 
        stock s ON bi.stock_id = s.id
    WHERE 
        bi.datetime BETWEEN ? AND ?
";

$stmt = $conn->prepare($summaryQuery);
if ($stmt === false) {
    echo json_encode(['status' => 'error', 'message' => 'Prepare statement failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param("ss", $startDatetime, $endDatetime);
$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    $summary = $result->fetch_assoc();
    echo json_encode(['status' => 'success', 'data' => $summary]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Query failed: ' . $conn->error]);
}

$stmt->close();
$conn->close();
?>