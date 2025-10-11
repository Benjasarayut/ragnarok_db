<?php
session_start();
include("../db.php");
header("Content-Type: application/json; charset=UTF-8");

if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit();
}

$char_id = intval($_POST['char_id'] ?? 0);
$stat = $_POST['stat'] ?? '';

$allowed = ['str', 'agi', 'vit', 'int_stat', 'dex', 'luk'];
if ($char_id <= 0 || !in_array($stat, $allowed)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Invalid input"]);
    exit();
}

// ✅ ตรวจสอบ stat point
$check = $conn->query("SELECT stat_points FROM characters WHERE char_id = $char_id");
$row = $check->fetch_assoc();
$points = $row['stat_points'] ?? 0;

if ($points <= 0) {
    echo json_encode(["success" => false, "message" => "❌ ไม่มีแต้มเหลือ"]);
    exit();
}

// ✅ update
$conn->query("UPDATE character_stats SET $stat = $stat + 1 WHERE char_id = $char_id");
$conn->query("UPDATE characters SET stat_points = stat_points - 1 WHERE char_id = $char_id");

echo json_encode(["success" => true, "message" => "✅ เพิ่มค่า $stat สำเร็จ"]);
