<?php
header("Content-Type: application/json; charset=UTF-8");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../db.php");
session_start();

if (!$conn) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "❌ Database connection failed"]);
    exit();
}

if (!isset($_SESSION['admin_name'])) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "❌ Unauthorized"]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

$player_id = $data['player_id'] ?? null;
$name = trim($data['name'] ?? '');
$class_id = intval($data['class_id'] ?? 0);   // ✅ ใช้ class_id แทน class
$gender = $data['gender'] ?? 'M';            // ✅ ค่า default 'M'
$level = intval($data['level'] ?? 1);

if (!$player_id || !$name || !$class_id) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "❌ ข้อมูลไม่ครบ"]);
    exit();
}

// ✅ SQL ตรงกับตารางของคุณแล้ว
$stmt = $conn->prepare("
  INSERT INTO characters (player_id, name, gender, level, class_id, created_at)
  VALUES (?, ?, ?, ?, ?, NOW())
");

if (!$stmt) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "❌ Prepare failed: " . $conn->error]);
    exit();
}

$stmt->bind_param("issii", $player_id, $name, $gender, $level, $class_id);
if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "❌ Execute failed: " . $stmt->error]);
    exit();
}

$stmt->close();
echo json_encode(["success" => true, "message" => "✅ เพิ่มตัวละครสำเร็จ"]);
?>
