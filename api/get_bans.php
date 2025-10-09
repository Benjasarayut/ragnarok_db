<?php
header("Content-Type: application/json; charset=UTF-8");
include("../db.php");

$result = $conn->query("SELECT ban_id, player_id, start_date, end_date, reason, created_at FROM bans ORDER BY created_at DESC");
$bans = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bans[] = $row;
    }
}

echo json_encode($bans);
?>
