<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json; charset=UTF-8");

include("../db.php");

$player_id = intval($_GET['player_id'] ?? 0);
$char_id   = intval($_GET['char_id'] ?? 0);

if ($player_id <= 0 || $char_id <= 0) {
  echo json_encode(["success" => false, "error" => "missing_player_id_or_char_id"]);
  exit;
}

try {
  // ✅ กรองตาม player_id และ char_id
  $stmt = $conn->prepare("
    SELECT 
      e.equip_id,
      c.name AS character_name,
      i.name AS item_name,
      i.item_type,
      e.slot
    FROM equipment e
    JOIN characters c ON e.char_id = c.char_id
    JOIN items i ON e.item_id = i.item_id
    WHERE c.player_id = ? AND c.char_id = ?
    ORDER BY e.equip_id ASC
  ");
  $stmt->bind_param("ii", $player_id, $char_id);
  $stmt->execute();
  $result = $stmt->get_result();

  $data = [];
  while ($row = $result->fetch_assoc()) {
    $data[] = $row;
  }

  echo json_encode(["success" => true, "data" => $data], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
  echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
?>
