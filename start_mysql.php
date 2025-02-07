<<<<<<< HEAD
<?php
header('Content-Type: application/json');

// Start MySQL server (Windows example)
$output = shell_exec('net start MySQL');
if (strpos($output, 'service was started successfully') === false && strpos($output, 'service is already running') === false) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to start MySQL server']);
    exit;
}

echo json_encode(['status' => 'success', 'message' => 'MySQL server started successfully']);
=======
<?php
header('Content-Type: application/json');

// Start MySQL server (Windows example)
$output = shell_exec('net start MySQL');
if (strpos($output, 'service was started successfully') === false && strpos($output, 'service is already running') === false) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to start MySQL server']);
    exit;
}

echo json_encode(['status' => 'success', 'message' => 'MySQL server started successfully']);
>>>>>>> 8a70f8a (Initial commit)
?>