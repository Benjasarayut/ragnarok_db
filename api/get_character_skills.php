<?php
// api/get_character_skills.php
header('Content-Type: application/json; charset=utf-8');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

require_once '../db.php';
$conn->set_charset('utf8mb4');

try {
    $player_id = isset($_GET['player_id']) ? (int)$_GET['player_id'] : 0;
    $char_id   = isset($_GET['char_id']) ? (int)$_GET['char_id'] : 0;

    // ถ้าไม่มีทั้ง player_id และ char_id
    if ($player_id <= 0 && $char_id <= 0) {
        throw new Exception("missing player_id or char_id");
    }

    // สร้าง SQL ตามเงื่อนไข
    $sql = "
    SELECT
      s.skill_id,
      s.skill_name,
      s.description,
      s.mp_cost,
      s.cooldown,
      s.power,
      c.name AS character_name,
      COALESCE(cls.class_name, '-') AS class_name
    FROM character_skills cs
    JOIN characters c   ON cs.char_id = c.char_id
    JOIN skills s       ON cs.skill_id = s.skill_id
    LEFT JOIN classes cls ON c.class_id = cls.class_id
    ";

    // ถ้ามี char_id → กรองเฉพาะตัวละครนั้น
    if ($char_id > 0) {
        $sql .= " WHERE c.char_id = ? ORDER BY s.skill_id ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $char_id);
    }
    // ถ้ามีแค่ player_id → ดึงทุกตัวละครของ player
    else {
        $sql .= " WHERE c.player_id = ? ORDER BY s.skill_id ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $player_id);
    }

    $stmt->execute();
    $res = $stmt->get_result();

    $data = [];
    while ($row = $res->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode([
        "success" => true,
        "count"   => count($data),
        "data"    => $data
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
