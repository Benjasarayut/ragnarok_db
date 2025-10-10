<?php
header('Content-Type: application/json; charset=utf-8');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

require_once '../db.php';
$conn->set_charset('utf8mb4');

try {
    $player_id = isset($_GET['player_id']) ? (int)$_GET['player_id'] : 0;
    $char_id   = isset($_GET['char_id']) ? (int)$_GET['char_id'] : 0;

    if ($player_id <= 0 && $char_id <= 0) {
        throw new Exception("Missing player_id or char_id");
    }

    $sql = "
        SELECT 
            pet_id,
            name AS pet_name,
            species,
            level,
            happiness,
            owner_id,
            char_id
        FROM pets
    ";

    if ($char_id > 0) {
        $sql .= " WHERE char_id = ? ORDER BY level DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $char_id);
    } else {
        $sql .= " WHERE owner_id = ? ORDER BY level DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $player_id);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode([
        "success" => true,
        "count" => count($data),
        "data" => $data
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
