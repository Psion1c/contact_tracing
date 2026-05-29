<?php
header('Content-Type: application/json');
require 'db.php';

$input = json_decode(file_get_contents('php://input'), true);
$user_id = trim($input['user_id'] ?? '');

if (empty($user_id)) {
    echo json_encode(["status" => "error", "message" => "ID is required."]);
    exit();
}

$stmt = $conn->prepare("SELECT first_name FROM users WHERE user_id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "not_found"]);
} else {
    $user = $result->fetch_assoc();
    
    // Check open session
    $log_stmt = $conn->prepare("SELECT log_id FROM entry_logs WHERE user_id = ? AND time_out IS NULL ORDER BY time_in DESC LIMIT 1");
    $log_stmt->bind_param("s", $user_id);
    $log_stmt->execute();
    $log_result = $log_stmt->get_result();

    if ($log_result->num_rows > 0) {
        $log_row = $log_result->fetch_assoc();
        $update_stmt = $conn->prepare("UPDATE entry_logs SET time_out = NOW() WHERE log_id = ?");
        $update_stmt->bind_param("i", $log_row['log_id']);
        $update_stmt->execute();
        echo json_encode(["status" => "success", "message" => "Signed Out. Goodbye, " . $user['first_name'] . "."]);
    } else {
        $insert_stmt = $conn->prepare("INSERT INTO entry_logs (user_id, time_in) VALUES (?, NOW())");
        $insert_stmt->bind_param("s", $user_id);
        $insert_stmt->execute();
        echo json_encode(["status" => "success", "message" => "Signed In. Welcome, " . $user['first_name'] . "."]);
    }
}
?>