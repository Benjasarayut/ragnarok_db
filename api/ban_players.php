<?php
include("../db.php");
header("Content-Type: text/html; charset=UTF-8");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  header("Content-Type: application/json; charset=UTF-8");

  $player_id = $_POST['player_id'] ?? null;
  $reason = trim($_POST['reason'] ?? 'ไม่มีเหตุผลระบุ');
  $days = intval($_POST['days'] ?? 1);

  if (!$player_id) {
    echo json_encode(["error" => "⚠ กรุณาระบุ Player ID"]);
    exit;
  }

  try {
    $check = $conn->prepare("SELECT * FROM players WHERE player_id=?");
    $check->bind_param("i", $player_id);
    $check->execute();
    $res = $check->get_result();
    if ($res->num_rows === 0) {
      echo json_encode(["error" => "❌ ไม่พบผู้เล่นในระบบ"]);
      exit;
    }

    $stmt = $conn->prepare("
      INSERT INTO bans (player_id, reason, start_date, end_date)
      VALUES (?, ?, NOW(), DATE_ADD(NOW(), INTERVAL ? DAY))
    ");
    $stmt->bind_param("isi", $player_id, $reason, $days);
    $stmt->execute();

    echo json_encode(["success" => true, "message" => "✅ แบนผู้เล่น ID {$player_id} เป็นเวลา {$days} วันสำเร็จ!"]);
  } catch (Exception $e) {
    echo json_encode(["error" => "❌ SQL Error: ".$e->getMessage()]);
  }
  exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>🔒 แบนผู้เล่น | Ragnarok Admin</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
body {
  font-family: "Poppins", sans-serif;
  background: radial-gradient(circle at top, #0f172a 0%, #1e293b 100%);
  color: #e2e8f0;
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  margin: 0;
}
.container {
  background: rgba(255,255,255,0.05);
  backdrop-filter: blur(10px);
  border-radius: 16px;
  padding: 35px;
  width: 420px;
  box-shadow: 0 0 25px rgba(0,0,0,0.5);
  animation: fadeIn 0.6s ease-in-out;
}
@keyframes fadeIn {
  from {opacity: 0; transform: translateY(10px);}
  to {opacity: 1; transform: translateY(0);}
}
h2 {
  text-align: center;
  color: #f87171;
  font-weight: 600;
  margin-bottom: 25px;
}
label {
  font-size: 14px;
  color: #94a3b8;
  display: block;
  margin-bottom: 6px;
}
input, textarea, button {
  width: 100%;
  margin: 10px 0;
  padding: 12px 15px;
  border: none;
  border-radius: 10px;
  background: rgba(255,255,255,0.1);
  color: #fff;
  font-size: 15px;
  box-sizing: border-box;
  outline: none;
  transition: 0.2s;
}
input:focus, textarea:focus {
  border: 1px solid #f87171;
  background: rgba(255,255,255,0.15);
}
textarea {
  resize: none;
  height: 90px;
}
button {
  background: linear-gradient(90deg, #ef4444, #f87171);
  color: white;
  font-weight: bold;
  cursor: pointer;
  border: none;
  transition: all 0.25s ease;
  letter-spacing: 0.5px;
}
button:hover {
  background: linear-gradient(90deg, #f87171, #ef4444);
  transform: translateY(-1px);
}
#msg {
  text-align: center;
  margin-top: 10px;
  font-weight: 500;
}
.success {
  color: #4ade80;
}
.error {
  color: #f87171;
}
.footer {
  text-align: center;
  margin-top: 15px;
  font-size: 13px;
  color: #64748b;
}
</style>
</head>
<body>

<div class="container">
  <h2>🔒 แบนผู้เล่น</h2>
  <form id="banForm">
    <label>🧙‍♂️ Player ID</label>
    <input type="number" id="player_id" placeholder="กรอก Player ID" required>

    <label>📅 จำนวนวัน</label>
    <input type="number" id="days" placeholder="จำนวนวัน" value="1" min="1">

    <label>📝 เหตุผลในการแบน</label>
    <textarea id="reason" placeholder="พิมพ์เหตุผล (ถ้าไม่มี ให้เว้นว่าง)"></textarea>

    <button type="submit">🚫 ยืนยันการแบน</button>
    <p id="msg"></p>
  </form>
  <div class="footer">© 2025 Ragnarok Origin Classic Admin</div>
</div>

<script>
const form = document.getElementById("banForm");
form.addEventListener("submit", async e => {
  e.preventDefault();
  const fd = new URLSearchParams();
  fd.append("player_id", player_id.value);
  fd.append("days", days.value);
  fd.append("reason", reason.value);
  const res = await fetch("", { method: "POST", body: fd });
  const d = await res.json();
  msg.textContent = d.message || d.error;
  msg.className = d.success ? "success" : "error";
});
</script>
</body>
</html>
