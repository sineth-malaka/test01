<?php
header('Content-Type: application/json');
include 'db_connection.php';

// Check connection
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]));
}

// Fetch data from the stock table
$sql = "SELECT PLU, name, stock, price, cost FROM stock";
$result = $conn->query($sql);

// Prepare data for JSON response
$data = [];
if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                "PLU" => $row["PLU"],
                "name" => $row["name"],
                "stock" => intval($row["stock"]),
                "price" => floatval($row["price"]),
                "cost" => floatval($row["cost"]),
            ];
        }
    }
    echo json_encode(["success" => true, "data" => $data]);
} else {
    echo json_encode(["success" => false, "message" => "Query failed: " . $conn->error]);
}

$conn->close();
?>