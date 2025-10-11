<?php
include("../db.php");
header("Content-Type: application/json; charset=UTF-8");
error_reporting(0);
ini_set('display_errors', 0);

$player_id = intval($_GET['player_id'] ?? 0);
$char_id = intval($_GET['char_id'] ?? 0);

$response = ["success" => false, "data" => ["guilds" => []]];

if ($char_id <= 0) {
    echo json_encode($response);
    exit;
}

$guilds = [];

/* ✅ Query 1: ตัวละครเป็นสมาชิกใน guild_members */
$sqlMember = "
    SELECT 
        g.guild_id,
        g.guild_name,
        g.description,
        g.creation_date,
        gr.role_name AS my_role,
        c_leader.name AS leader_name
    FROM guilds g
    JOIN guild_members gm ON gm.guild_id = g.guild_id
    LEFT JOIN guild_roles gr ON gm.role_id = gr.role_id
    LEFT JOIN characters c_leader ON g.leader_id = c_leader.char_id
    WHERE gm.char_id = ?
";
$stmt = $conn->prepare($sqlMember);
$stmt->bind_param("i", $char_id);
$stmt->execute();
$memberResult = $stmt->get_result();

while ($row = $memberResult->fetch_assoc()) {
    // ✅ ดึงสมาชิกของกิลด์นั้น
    $memberSql = "
        SELECT 
            c.name AS character_name, 
            p.username AS player_name, 
            gr.role_name AS role
        FROM guild_members gm
        JOIN characters c ON gm.char_id = c.char_id
        JOIN players p ON gm.player_id = p.player_id
        LEFT JOIN guild_roles gr ON gm.role_id = gr.role_id
        WHERE gm.guild_id = ?
    ";
    $memberStmt = $conn->prepare($memberSql);
    $memberStmt->bind_param("i", $row['guild_id']);
    $memberStmt->execute();
    $memberRes = $memberStmt->get_result();
    $row['members'] = $memberRes->fetch_all(MYSQLI_ASSOC);
    $memberStmt->close();

    $guilds[] = $row;
}
$stmt->close();

/* ✅ Query 2: ตัวละครเป็นหัวหน้ากิลด์ (leader) — กรณีไม่มี record ใน guild_members */
$sqlLeader = "
    SELECT 
        g.guild_id,
        g.guild_name,
        g.description,
        g.creation_date,
        'Leader' AS my_role,
        c_leader.name AS leader_name
    FROM guilds g
    LEFT JOIN characters c_leader ON g.leader_id = c_leader.char_id
    WHERE g.leader_id = ?
      AND g.guild_id NOT IN (
          SELECT guild_id FROM guild_members WHERE char_id = ?
      )
";
$stmtLeader = $conn->prepare($sqlLeader);
$stmtLeader->bind_param("ii", $char_id, $char_id);
$stmtLeader->execute();
$leaderResult = $stmtLeader->get_result();

while ($row = $leaderResult->fetch_assoc()) {
    // ✅ ดึงสมาชิกของกิลด์นั้น
    $memberSql = "
        SELECT 
            c.name AS character_name, 
            p.username AS player_name, 
            gr.role_name AS role
        FROM guild_members gm
        JOIN characters c ON gm.char_id = c.char_id
        JOIN players p ON gm.player_id = p.player_id
        LEFT JOIN guild_roles gr ON gm.role_id = gr.role_id
        WHERE gm.guild_id = ?
    ";
    $memberStmt = $conn->prepare($memberSql);
    $memberStmt->bind_param("i", $row['guild_id']);
    $memberStmt->execute();
    $memberRes = $memberStmt->get_result();
    $row['members'] = $memberRes->fetch_all(MYSQLI_ASSOC);
    $memberStmt->close();

    $guilds[] = $row;
}
$stmtLeader->close();

/* 🏁 ส่งกลับ */
$response['success'] = true;
$response['data']['guilds'] = $guilds;

echo json_encode($response, JSON_UNESCAPED_UNICODE);
