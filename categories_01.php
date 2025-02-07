
<?php
header('Content-Type: application/json');
include 'db_connection.php';

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$sql = "SELECT DISTINCT category FROM stock";
$result = $conn->query($sql);

$categories = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row['category'];
    }
    echo json_encode($categories);
} else {
    echo json_encode(["error" => "Query failed: " . $conn->error]);
}

$conn->close();

?>
