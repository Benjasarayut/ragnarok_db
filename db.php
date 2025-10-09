<?php
// db.php — ใช้สำหรับเชื่อมต่อเท่านั้น ไม่ echo อะไรออก
$servername = "localhost";
$username = "root";
$password = "240449"; // หรือใส่รหัสจริงของ MySQL คุณ
$dbname = "ragnarok_origin_classic";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "เชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error]));
}
?>
