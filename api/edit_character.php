<?php
header("Content-Type: application/json; charset=UTF-8");
include("../db.php");
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['admin_name'])) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "❌ Unauthorized"]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);
$char_id = $data['char_id'] ?? null;
$name = trim($data['name'] ?? '');
$class_id = intval($data['class_id'] ?? 0);
$level = intval($data['level'] ?? 1);

if (!$char_id || !$name || !$class_id) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "⚠️ ข้อมูลไม่ครบ"]);
    exit();
}

$stmt = $conn->prepare("UPDATE characters SET name=?, class_id=?, level=? WHERE char_id=?");
if (!$stmt) {
    echo json_encode(["success" => false, "message" => "❌ DB Prepare Failed: ".$conn->error]);
    exit();
}

$stmt->bind_param("siii", $name, $class_id, $level, $char_id);
if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "✅ อัปเดตข้อมูลตัวละครเรียบร้อย"]);
} else {
    echo json_encode(["success" => false, "message" => "❌ Update Failed: ".$stmt->error]);
}
$stmt->close();
?>
