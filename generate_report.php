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
        IFNULL(SUM(bi.quantity * s.cost), 0) AS total_cost,
        COUNT(DISTINCT b.id) AS bill_count
    FROM bills b
    JOIN bill_items bi ON b.id = bi.bill_id
    JOIN stock s ON bi.PLU = s.PLU
    WHERE b.created_at BETWEEN ? AND ?
";

$stmt1 = $connection->prepare($summaryQuery);
$stmt1->bind_param("ss", $startDatetime, $endDatetime);
$stmt1->execute();
$summaryResult = $stmt1->get_result()->fetch_assoc();
$stmt1->close();

// Item-wise Sales Query
$itemQuery = "
    SELECT s.PLU, s.name, SUM(bi.quantity) AS sell_quantity, 
           SUM(bi.quantity * s.price) AS total_revenue, 
           SUM((s.price - s.cost) * bi.quantity) AS profit, 
           SUM(bi.quantity * s.cost) AS cost
    FROM bill_items bi
    JOIN stock s ON bi.PLU = s.PLU
    JOIN bills b ON bi.bill_id = b.id
    WHERE b.created_at BETWEEN ? AND ?
    GROUP BY s.PLU, s.name
";

$stmt2 = $connection->prepare($itemQuery);
$stmt2->bind_param("ss", $startDatetime, $endDatetime);
$stmt2->execute();
$itemData = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt2->close();

// Bill-wise Sales Query
$billQuery = "
    SELECT 
        b.id AS bill_number, 
        b.created_at AS time, 
        b.total_amount, 
        b.cash,
        s.PLU, 
        s.name, 
        bi.quantity, 
        s.price, 
        (bi.quantity * s.price) AS total_price
    FROM bills b
    JOIN bill_items bi ON b.id = bi.bill_id
    JOIN stock s ON bi.PLU = s.PLU
    WHERE b.created_at BETWEEN ? AND ?
    ORDER BY b.created_at, b.id
";

$stmt3 = $connection->prepare($billQuery);
$stmt3->bind_param("ss", $startDatetime, $endDatetime);
$stmt3->execute();
$billResult = $stmt3->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt3->close();

echo json_encode([
    'status' => 'success',
    'summary' => $summaryResult,
    'items' => $itemData,
    'bills' => $billResult
]);

$connection->close();
?>
