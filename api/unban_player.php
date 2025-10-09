<?php
header("Content-Type: application/json; charset=UTF-8");
include("../db.php");

$input = json_decode(file_get_contents("php://input"), true);
$ban_id = $input["ban_id"] ?? null;

if (!$ban_id) {
  echo json_encode(["success" => false, "message" => "❌ Missing ban_id"]);
  exit;
}

$stmt = $conn->prepare("DELETE FROM bans WHERE ban_id = ?");
$stmt->bind_param("i", $ban_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
  echo json_encode(["success" => true, "message" => "🗑️ ยกเลิกแบนผู้เล่นเรียบร้อย"]);
} else {
  echo json_encode(["success" => false, "message" => "⚠️ ไม่พบข้อมูลแบนที่ต้องการลบ"]);
}
?>
