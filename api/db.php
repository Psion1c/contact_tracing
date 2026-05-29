<?php
$host = 'localhost';
$user = 'root';
$pass = ''; 
$dbname = 'contact_tracing_db';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    header('Content-Type: application/json');
    echo json_encode(["status" => "error", "message" => "Database connection failed."]);
    exit();
}
?>