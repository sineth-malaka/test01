<?php
$data = json_decode(file_get_contents("php://input"), true);
$passwordsFile = "passwords.json";

if (!isset($data["section"]) || !isset($data["new_password"])) {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
    exit;
}

$passwords = json_decode(file_get_contents($passwordsFile), true);
$passwords[$data["section"]] = $data["new_password"];

if (file_put_contents($passwordsFile, json_encode($passwords, JSON_PRETTY_PRINT))) {
    echo json_encode(["status" => "success", "passwords" => $passwords]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to save"]);
}
?>
