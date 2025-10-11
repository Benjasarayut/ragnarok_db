<?php
session_start();
include("../db.php");
header("Content-Type: application/json; charset=UTF-8");

if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit();
}

$char_id = intval($_GET['char_id'] ?? 0);
if ($char_id <= 0) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Invalid char_id"]);
    exit();
}

$sql = "
SELECT cs.str, cs.agi, cs.vit, cs.int_stat, cs.dex, cs.luk, c.stat_points
FROM character_stats cs
JOIN characters c ON cs.char_id = c.char_id
WHERE cs.char_id = ?
LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $char_id);
$stmt->execute();
$res = $stmt->get_result();

if ($row = $res->fetch_assoc()) {
    echo json_encode([
        "success" => true,
        "stats" => [
            "str" => $row['str'],
            "agi" => $row['agi'],
            "vit" => $row['vit'],
            "int" => $row['int_stat'],
            "dex" => $row['dex'],
            "luk" => $row['luk']
        ],
        "points" => $row['stat_points']
    ]);
} else {
    echo json_encode(["success" => false, "message" => "No stats found"]);
}
