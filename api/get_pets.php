<?php
include("../db.php");

$player_id = $_GET['player_id'] ?? 0;
if (!$player_id) {
  echo json_encode(["success" => false, "error" => "Missing player_id"]);
  exit;
}

$sql = "
  SELECT 
    pet_id,
    name AS pet_name,
    species,
    level,
    happiness
  FROM pets
  WHERE owner_id = ?
  ORDER BY level DESC
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
