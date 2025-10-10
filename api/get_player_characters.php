<?php
include("../db.php");
header("Content-Type: application/json");

$player_id = $_GET['player_id'] ?? null;
if (!$player_id) { echo json_encode([]); exit; }

$res = $conn->query("SELECT char_id, name, class_id, level FROM characters WHERE player_id = $player_id");
$chars = $res->fetch_all(MYSQLI_ASSOC);
echo json_encode($chars);
?>
