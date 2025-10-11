<?php
header("Content-Type: application/json; charset=UTF-8");
include("../db.php");
session_start();

// ✅ เปิด error log เฉพาะตอน debug (อย่าเปิดใน production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 🧭 ตรวจสิทธิ์
if (!isset($_SESSION['admin_name'])) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "❌ Unauthorized"]);
    exit();
}

// 📥 รับข้อมูลจาก JSON
$data = json_decode(file_get_contents("php://input"), true);
$char_id  = isset($data['char_id']) ? intval($data['char_id']) : 0;
$name     = isset($data['name']) ? trim($data['name']) : '';
$class_id = isset($data['class_id']) ? intval($data['class_id']) : 0;
$level    = isset($data['level']) ? intval($data['level']) : 1;

// ⚠️ ตรวจสอบความถูกต้องของข้อมูล
if ($char_id <= 0 || empty($name) || $class_id <= 0 || $level <= 0) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "⚠️ กรุณากรอกข้อมูลให้ครบถ้วนและถูกต้อง"
    ]);
    exit();
}

// 📝 เตรียมคำสั่ง SQL
$stmt = $conn->prepare("UPDATE characters SET name = ?, class_id = ?, level = ? WHERE char_id = ?");
if (!$stmt) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "❌ Prepare failed: " . $conn->error
    ]);
    exit();
}

$stmt->bind_param("siii", $name, $class_id, $level, $char_id);

// 🧾 รันคำสั่ง SQL
if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "✅ อัปเดตข้อมูลตัวละครเรียบร้อยแล้ว"
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "❌ Update failed: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
