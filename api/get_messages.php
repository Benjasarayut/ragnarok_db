<?php
header("Content-Type: application/json; charset=UTF-8");
include("../db.php");

$result = $conn->query("SELECT message_id, sender, receiver_id, message, created_at FROM messages ORDER BY created_at DESC");
$messages = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}

echo json_encode($messages);
?>
