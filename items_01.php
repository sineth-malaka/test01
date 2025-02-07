
<?php
header('Content-Type: application/json');
include 'db_connection.php';

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Fetch items from the stock table
$sql = "SELECT id, PLU, name, category, price, cost, image_path FROM stock";
$result = $conn->query($sql);

$items = [];
if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
    }
    echo json_encode($items);
} else {
    echo json_encode(["error" => "Query failed: " . $conn->error]);
}

$conn->close();

?>