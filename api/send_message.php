<?php
header("Content-Type: application/json; charset=UTF-8");
include("../db.php");
session_start();
ini_set('display_errors', 0);
error_reporting(0);

if (!isset($_SESSION['admin_name'])) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "🔒 Session หมดอายุหรือไม่ได้เข้าสู่ระบบ"]);
    exit();
}

$admin = $_SESSION['admin_name'];
$data = json_decode(file_get_contents("php://input"), true);

$receiver_id = isset($data['receiver_id']) && $data['receiver_id'] !== "" ? $data['receiver_id'] : null;
$message = trim($data['message'] ?? '');

if (!$message) {
    echo json_encode(["success" => false, "message" => "❌ กรุณากรอกข้อความ"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO messages (sender, receiver_id, message, created_at) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("sis", $admin, $receiver_id, $message);
$stmt->execute();
$stmt->close();

echo json_encode(["success" => true, "message" => "✅ ส่งข้อความสำเร็จ"]);
?>
