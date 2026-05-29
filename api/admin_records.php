<?php
header('Content-Type: application/json');
require 'db.php';

// Extract credentials from custom headers (Bypasses XAMPP Authorization stripping)
$user = $_SERVER['HTTP_X_ADMIN_USER'] ?? '';
$pass = $_SERVER['HTTP_X_ADMIN_PASS'] ?? '';

if (empty($user) || empty($pass)) {
    echo json_encode(["status" => "unauthorized", "message" => "Missing credentials"]);
    exit();
}

// Validate Admin Credentials
$stmt = $conn->prepare("SELECT admin_id FROM admins WHERE username = ? AND password = ?");
$stmt->bind_param("ss", $user, $pass);
$stmt->execute();
if ($stmt->get_result()->num_rows !== 1) {
    echo json_encode(["status" => "unauthorized", "message" => "Invalid credentials"]);
    exit();
}

// Handle GET Parameters
$filter = $_GET['filter'] ?? '';
$keyword = $_GET['keyword'] ?? '';

$sql = "SELECT u.user_id, u.first_name, u.last_name, u.barangay, u.city, u.province, e.time_in, e.time_out 
        FROM users u JOIN entry_logs e ON u.user_id = e.user_id";

if (!empty($keyword)) {
    $safe_keyword = $conn->real_escape_string($keyword);
    $allowed_filters = ['city', 'user_id', 'last_name', 'time_in'];
    
    if (in_array($filter, $allowed_filters)) {
        if ($filter === 'time_in') {
            $sql .= " WHERE DATE(e.time_in) = '$safe_keyword'"; 
        } else {
            $sql .= " WHERE u.$filter LIKE '%$safe_keyword%'";
        }
    }
}
$sql .= " ORDER BY e.time_in DESC";

$result = $conn->query($sql);
$records = [];
while ($row = $result->fetch_assoc()) {
    $records[] = $row;
}

echo json_encode(["status" => "success", "records" => $records]);
?>