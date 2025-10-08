<?php
include("../db.php");

$player_id = $_GET['player_id'] ?? 0;

if (!$player_id) {
  echo json_encode(["success" => false, "error" => "Missing player_id"]);
  exit();
}

// ✅ ดึง char_id ของผู้เล่น
$sqlChar = "SELECT char_id FROM characters WHERE player_id = ? LIMIT 1";
$stmtChar = $conn->prepare($sqlChar);
$stmtChar->bind_param("i", $player_id);
$stmtChar->execute();
$resultChar = $stmtChar->get_result();
$char = $resultChar->fetch_assoc();

if (!$char) {
  echo json_encode(["success" => false, "error" => "No character found"]);
  exit();
}

$char_id = $char['char_id'];

// ✅ ดึงข้อมูลจาก awakening_logs
$sql = "SELECT 
          log_id,
          char_id,
          old_awake_level,
          new_awake_level,
          awakened_status,
          log_message,
          log_time
        FROM awakening_logs
        WHERE char_id = ?
        ORDER BY log_time DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $char_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}

echo json_encode([
  "success" => true,
  "data" => $data
], JSON_UNESCAPED_UNICODE);
?>
