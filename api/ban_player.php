<?php
header("Content-Type: application/json; charset=UTF-8");
include("../db.php");

// 🧪 Debug error PHP (ถ้าต้องการ)
error_reporting(E_ALL);
ini_set('display_errors', 1);

$input = file_get_contents("php://input");
$data = json_decode($input, true);

$player_id = $data['player_id'] ?? null;
$start_date = $data['start_date'] ?? null;
$end_date = $data['end_date'] ?? null;
$reason = $data['reason'] ?? '';

if (!$player_id || !$start_date || !$end_date) {
    echo json_encode(["success" => false, "message" => "❌ ข้อมูลไม่ครบ"]);
    exit;
}

// ✅ เพิ่มเวลาเข้าไปในวันที่ (ให้ตรง datatype datetime)
$start_datetime = $start_date . " 00:00:00";
$end_datetime = $end_date . " 23:59:59";

try {
    $stmt = $conn->prepare("INSERT INTO bans (player_id, start_date, end_date, reason, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("isss", $player_id, $start_datetime, $end_datetime, $reason);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "✅ แบนผู้เล่นสำเร็จ"]);
    } else {
        echo json_encode(["success" => false, "message" => "❌ Insert ล้มเหลว: " . $stmt->error]);
    }

    $stmt->close();
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "❌ Error: " . $e->getMessage()]);
}
?>
