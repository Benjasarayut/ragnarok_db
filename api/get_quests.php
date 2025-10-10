<?php
include("../db.php");
header("Content-Type: application/json; charset=UTF-8");

// 📌 รับค่า player_id และ char_id จาก URL
$player_id = intval($_GET['player_id'] ?? 0);
$char_id   = intval($_GET['char_id'] ?? 0);

if ($player_id <= 0 || $char_id <= 0) {
    echo json_encode([
        "success" => false,
        "error" => "missing_player_or_char_id",
        "data" => []
    ]);
    exit;
}

try {
    $sql = "
        SELECT 
            progress_id,
            quest_name,
            status,
            progress_percent
        FROM quests_progress
        WHERE player_id = ? AND char_id = ?
        ORDER BY progress_id ASC
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("ii", $player_id, $char_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $quests = [];
    while ($row = $result->fetch_assoc()) {
        $quests[] = $row;
    }

    echo json_encode([
        "success" => true,
        "data" => $quests
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage(),
        "data" => []
    ], JSON_UNESCAPED_UNICODE);
}
?>
