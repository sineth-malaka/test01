<<<<<<< HEAD
<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$database = "test01";

// Start MySQL server (Windows example)
$output = shell_exec('net start MySQL');
if (strpos($output, 'service was started successfully') === false && strpos($output, 'service is already running') === false) {
    die(json_encode(['status' => 'error', 'message' => 'Failed to start MySQL server']));
}

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]));
}
=======
<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$database = "test01";

// Start MySQL server (Windows example)
$output = shell_exec('net start MySQL');
if (strpos($output, 'service was started successfully') === false && strpos($output, 'service is already running') === false) {
    die(json_encode(['status' => 'error', 'message' => 'Failed to start MySQL server']));
}

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]));
}
>>>>>>> 8a70f8a (Initial commit)
?>