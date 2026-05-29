<?php
header('Content-Type: application/json');
require 'db.php';

$input = json_decode(file_get_contents('php://input'), true);

$stmt = $conn->prepare("INSERT INTO users (user_id, first_name, middle_name, last_name, barangay, city, province, contact_number, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssss", $input['user_id'], $input['fname'], $input['mname'], $input['lname'], $input['brgy'], $input['city'], $input['prov'], $input['contact'], $input['email']);

if ($stmt->execute()) {
    $log_stmt = $conn->prepare("INSERT INTO entry_logs (user_id, time_in) VALUES (?, NOW())");
    $log_stmt->bind_param("s", $input['user_id']);
    $log_stmt->execute();
    
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Database Error: " . $conn->error]);
}
?>