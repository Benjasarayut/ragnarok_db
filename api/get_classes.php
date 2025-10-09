<?php
header("Content-Type: application/json; charset=UTF-8");
include("../db.php");
session_start();
ini_set('display_errors', 0);
error_reporting(0);

if (!isset($_SESSION['admin_name'])) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "❌ Unauthorized"]);
    exit();
}

$sql = "SELECT class_id, class_name FROM classes ORDER BY class_id ASC";
$result = $conn->query($sql);
$classes = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $classes[] = $row;
    }
}

echo json_encode($classes);
?>
