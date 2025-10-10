<?php
session_start();
include("../db.php");
header("Content-Type: application/json; charset=UTF-8");

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    echo json_encode(["error" => "⚠ กรุณากรอกข้อมูลให้ครบ"]);
    exit;
}

$master_password = "123456";

try {
    // 🔸 ตรวจสอบแอดมิน
    $stmt = $conn->prepare("SELECT admin_id, email, password, role FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $admin_result = $stmt->get_result();

    if ($admin_result->num_rows > 0) {
        $admin = $admin_result->fetch_assoc();

        if ($password === $master_password) {
            $_SESSION['user_id'] = $admin['admin_id'];
            $_SESSION['email'] = $admin['email'];
            $_SESSION['role'] = "admin";
            $_SESSION['admin_name'] = $admin['email'];

            echo json_encode([
                "success" => true,
                "message" => "เข้าสู่ระบบในฐานะแอดมิน",
                "role" => "admin",
                "redirect" => "../admin/admin_dashboard.php"
            ]);
            exit;
        }
    }

    // 🔹 ตรวจสอบ player
    $stmt = $conn->prepare("SELECT player_id, username, email FROM players WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $player_result = $stmt->get_result();

    if ($player_result->num_rows > 0) {
        $player = $player_result->fetch_assoc();

        if ($password === $master_password) {
            // 🚨 ตรวจสอบว่าโดนแบนอยู่ไหม
            $banStmt = $conn->prepare("
                SELECT reason, end_date 
                FROM bans 
                WHERE player_id = ? 
                AND NOW() BETWEEN start_date AND end_date
            ");
            $banStmt->bind_param("i", $player['player_id']);
            $banStmt->execute();
            $banResult = $banStmt->get_result();

            if ($banResult->num_rows > 0) {
                $banInfo = $banResult->fetch_assoc();
                http_response_code(403);
                echo json_encode([
                    "success" => false,
                    "error" => "🚫 บัญชีนี้ถูกแบน",
                    "reason" => $banInfo['reason'],
                    "end_date" => $banInfo['end_date']
                ]);
                exit;
            }

            // ✅ ถ้าไม่โดนแบน → ล็อกอินได้
            $_SESSION['user_id'] = $player['player_id'];
            $_SESSION['username'] = $player['username'];
            $_SESSION['role'] = "player";

            echo json_encode([
                "success" => true,
                "message" => "เข้าสู่ระบบสำเร็จ",
                "role" => "player",
                "redirect" => "../user/user_dashboard.php"
            ]);
            exit;
        }
    }

    // ❌ ไม่พบผู้ใช้หรือรหัสไม่ถูก
    echo json_encode(["error" => "❌ อีเมลหรือรหัสผ่านไม่ถูกต้อง (ใช้รหัส 123456 เท่านั้น)"]);

} catch (Exception $e) {
    echo json_encode(["error" => "⚠ SQL Error: " . $e->getMessage()]);
}
?>
