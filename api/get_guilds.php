<?php
include("../db.php");

$player_id = $_GET['player_id'] ?? 0;
if (!$player_id) {
  echo json_encode(["success" => false, "error" => "Missing player_id"]);
  exit;
}

$sql = "
  SELECT 
    g.guild_id,
    g.guild_name,
    g.description,
    g.creation_date,
    p.username AS leader_name
  FROM guilds g
  LEFT JOIN players p ON g.leader_id = p.player_id
  WHERE g.leader_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $player_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}

echo json_encode(["success" => true, "data" => $data], JSON_UNESCAPED_UNICODE);
?>
