<?php
include("../db.php");
header("Content-Type: application/json");

$char_id = $_GET['char_id'] ?? null;
if (!$char_id) { echo json_encode([]); exit; }

$res = $conn->query("SELECT * FROM character_stats WHERE char_id = $char_id");
$stats = $res->fetch_assoc();
echo json_encode($stats);
?>
