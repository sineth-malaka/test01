<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$database = "test01";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]));
}

// Fetch data from the stock table
$sql = "SELECT PLU, name, stock, price, cost FROM stock";
$result = $conn->query($sql);

// Prepare data for JSON response
$data = [];
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

// Send JSON response
echo json_encode($data);
$conn->close();
?>
