<?php
session_start();
include("../db.php");
header("Content-Type: application/json; charset=UTF-8");

if (!isset($_SESSION['admin_name'])) {
  http_response_code(401);
  echo json_encode([
    "success" => false,
    "message" => "❌ Unauthorized"
  ]);
  exit;
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

$result = $conn->query($sql);
$characters = [];

if ($result) {
  while ($row = $result->fetch_assoc()) {
    $characters[] = $row;
  }
}

echo json_encode($characters, JSON_UNESCAPED_UNICODE);
