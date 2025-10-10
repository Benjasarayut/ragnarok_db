<?php
header("Content-Type: application/json; charset=UTF-8");
include("../db.php");

$data = json_decode(file_get_contents("php://input"), true);
$ban_id = $data['ban_id'] ?? null;

if (!$ban_id) {
  http_response_code(400);
  echo json_encode(["success" => false, "message" => "ไม่พบ Ban ID"]);
  exit();
}

$stmt = $conn->prepare("DELETE FROM bans WHERE ban_id = ?");
$stmt->bind_param("i", $ban_id);

if ($stmt->execute()) {
  echo json_encode(["success" => true, "message" => "✅ ยกเลิกแบนสำเร็จ"]);
} else {
  http_response_code(500);
  echo json_encode(["success" => false, "message" => "❌ ไม่สามารถยกเลิกแบนได้"]);
}
