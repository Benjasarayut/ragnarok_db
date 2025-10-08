<?php
include("../db.php");
header("Content-Type: application/json; charset=UTF-8");

$player_id = intval($_GET['player_id'] ?? 0);
if ($player_id <= 0) {
  echo json_encode(["success" => false, "error" => "missing_player_id", "data" => []]);
  exit;
}

try {
  $stmt = $conn->prepare("
    SELECT progress_id, quest_name, status, progress_percent
    FROM quests_progress
    WHERE player_id = ?
    ORDER BY progress_id ASC
  ");
  $stmt->bind_param("i", $player_id);
  $stmt->execute();
  $result = $stmt->get_result();

  $quests = [];
  while ($row = $result->fetch_assoc()) $quests[] = $row;

  echo json_encode(["success" => true, "data" => $quests], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
  echo json_encode(["success" => false, "error" => $e->getMessage(), "data" => []]);
}
?>
