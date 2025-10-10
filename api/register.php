<?php
header("Content-Type: application/json; charset=UTF-8");
session_start(); // ✅ เปิด session
include("../db.php");
ini_set('display_errors', 1);
error_reporting(E_ALL);

// รับค่าจาก body
$data = json_decode(file_get_contents("php://input"), true);
$username = trim($data["username"] ?? "");
$email = trim($data["email"] ?? "");
$class_id = intval($data["class_id"] ?? 0);

if (!$username || !$email || !$class_id) {
  echo json_encode(["success" => false, "message" => "❌ ข้อมูลไม่ครบ"]);
  exit();
}

// ตรวจสอบชื่อซ้ำ
$stmt = $conn->prepare("SELECT player_id FROM players WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
  echo json_encode(["success" => false, "message" => "⚠️ ชื่อผู้ใช้นี้มีอยู่แล้ว"]);
  exit();
}

// เพิ่มผู้เล่นใหม่
$stmt = $conn->prepare("INSERT INTO players (username, email) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $email);

if ($stmt->execute()) {
  $player_id = $stmt->insert_id;

  // ✅ สร้างตัวละครใหม่
  $char_name = $username;
  $char_stmt = $conn->prepare("INSERT INTO characters (player_id, name, class_id, level) VALUES (?, ?, ?, 1)");
  $char_stmt->bind_param("isi", $player_id, $char_name, $class_id);
  $char_stmt->execute();

  // ✅ login อัตโนมัติ
  $_SESSION["username"] = $username;

  echo json_encode([
    "success" => true,
    "message" => "✅ สมัครและสร้างตัวละครแล้ว",
    "redirect" => "../api/get_characters.php"
  ]);
} else {
  echo json_encode(["success" => false, "message" => "❌ เกิดข้อผิดพลาด"]);
}
