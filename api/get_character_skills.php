<?php
// api/get_character_skills.php
header('Content-Type: application/json; charset=utf-8');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

require_once '../db.php';
$conn->set_charset('utf8mb4');

$player_id = (int)($_GET['player_id'] ?? 0);
if ($player_id <= 0) {
  echo json_encode(["success" => false, "error" => "missing player_id"]);
  exit;
}

// ดึงสกิลของตัวละครทั้งหมดที่เป็นของผู้เล่นคนนี้
$sql = "
SELECT
  s.skill_id        AS skill_id,
  s.skill_name      AS skill_name,
  s.description     AS description,
  s.mp_cost         AS mp_cost,
  s.cooldown        AS cooldown,
  s.power           AS power,
  c.name            AS character_name,   -- <- เปลี่ยนจาก Character เป็น character_name
  COALESCE(cls.class_name, '-') AS class_name
FROM character_skills cs
JOIN characters c   ON cs.char_id = c.char_id
JOIN skills s       ON cs.skill_id = s.skill_id
LEFT JOIN classes cls ON c.class_id = cls.class_id
WHERE c.player_id = ?
ORDER BY s.skill_id ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $player_id);
$stmt->execute();
$res  = $stmt->get_result();

$data = [];
while ($row = $res->fetch_assoc()) {
  $data[] = $row;
}

echo json_encode([
  "success" => true,
  "count"   => count($data),
  "data"    => $data
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
