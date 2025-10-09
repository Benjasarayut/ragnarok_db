<?php
header("Content-Type: application/json; charset=UTF-8");
include("../db.php");
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['admin_name'])) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Unauthorized ⚠️ Session หมดอายุ"]);
    exit();
}

$sql = "
  SELECT 
    c.char_id, 
    c.name, 
    cl.class_name AS class, 
    c.level, 
    p.username 
  FROM characters c
  JOIN players p ON c.player_id = p.player_id
  LEFT JOIN classes cl ON c.class_id = cl.class_id
  ORDER BY c.created_at DESC
";

if (!$conn) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "❌ Database connection failed"]);
    exit();
}

$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "❌ SQL Error: " . $conn->error]);
    exit();
}

$characters = [];
while ($row = $result->fetch_assoc()) {
    $characters[] = $row;
}

echo json_encode($characters);
?>
