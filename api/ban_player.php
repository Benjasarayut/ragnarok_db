<?php
header("Content-Type: application/json; charset=UTF-8");
include("../db.php");

$input = file_get_contents("php://input");
$data = json_decode($input, true);

$player_id = $data['player_id'] ?? null;
$start_date = $data['start_date'] ?? null;  // DD-MM-YYYY
$end_date = $data['end_date'] ?? null;      // DD-MM-YYYY
$reason = $data['reason'] ?? '';

if (!$player_id || !$start_date || !$end_date) {
    echo json_encode(["success" => false, "message" => "❌ ข้อมูลไม่ครบ"]);
    exit;
}

// 🧭 ฟังก์ชันแปลง DD-MM-YYYY ➝ YYYY-MM-DD
function convertToMySQLDate($ddmmyyyy) {
    $parts = explode("-", $ddmmyyyy);
    return $parts[2] . "-" . $parts[1] . "-" . $parts[0];
}

$start_datetime = convertToMySQLDate($start_date) . " 00:00:00";
$end_datetime = convertToMySQLDate($end_date) . " 23:59:59";

$stmt = $conn->prepare("INSERT INTO bans (player_id, start_date, end_date, reason, created_at) VALUES (?, ?, ?, ?, NOW())");
$stmt->bind_param("isss", $player_id, $start_datetime, $end_datetime, $reason);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "✅ แบนผู้เล่นสำเร็จ"]);
} else {
    echo json_encode(["success" => false, "message" => "❌ ไม่สามารถแบนผู้เล่นได้: ".$stmt->error]);
}
$stmt->close();
?>
