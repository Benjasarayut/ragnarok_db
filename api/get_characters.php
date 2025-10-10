<?php
header("Content-Type: application/json; charset=UTF-8");
include("../db.php");
session_start();

// ✅ ตรวจสิทธิ์
$is_admin = isset($_SESSION['admin_name']);
$is_user = isset($_SESSION['username']);

// ✅ กำหนด SQL และ bind parameter
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
";
$params = [];
$types = "";

// ✅ ถ้าเป็น user → ดูเฉพาะตัวเอง
if ($is_user) {
  $sql .= " WHERE p.username = ? ";
  $params[] = $_SESSION["username"];
  $types .= "s";
} elseif (!$is_admin) {
  // ❌ ไม่มีสิทธิ์ทั้ง admin และ user
  echo json_encode(["success" => false, "message" => "⛔ Unauthorized"]);
  exit;
}

$sql .= " ORDER BY c.created_at DESC";

$stmt = $conn->prepare($sql);
if ($types) {
  $stmt->bind_param($types, ...$params); // bind parameter หากมี
}
$stmt->execute();
$result = $stmt->get_result();

// ✅ รวบรวมผลลัพธ์
$characters = [];
while ($row = $result->fetch_assoc()) {
  $characters[] = $row;
}

echo json_encode([
  "success" => true,
  "data" => $characters,
  "count" => count($characters),
  "is_admin" => $is_admin
], JSON_UNESCAPED_UNICODE);
