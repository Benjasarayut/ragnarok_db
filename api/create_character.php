<?php
include("../db.php");
header("Content-Type: text/html; charset=UTF-8");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  header("Content-Type: application/json; charset=UTF-8");

  $player_id = $_POST['player_id'] ?? null;
  $char_name = trim($_POST['name'] ?? '');
  $gender = $_POST['gender'] ?? 'M';

  if (!$player_id || !$char_name) {
    echo json_encode(["error" => "⚠ กรุณากรอก Player ID และชื่อตัวละคร"]);
    exit;
  }

  try {
    $stmt = $conn->prepare("
      INSERT INTO characters (player_id, name, gender, level, exp, zenny, hp, mp, created_at)
      VALUES (?, ?, ?, 1, 0, 0, 100, 50, NOW())
    ");
    $stmt->bind_param("iss", $player_id, $char_name, $gender);
    $stmt->execute();
    echo json_encode(["success" => true, "message" => "✅ สร้างตัวละครใหม่สำเร็จ!"]);
  } catch (Exception $e) {
    echo json_encode(["error" => "❌ SQL Error: " . $e->getMessage()]);
  }
  exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>⚔️ สร้างตัวละคร | Ragnarok Admin</title>
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
  color: #60a5fa;
  font-weight: 600;
  margin-bottom: 25px;
}
label {
  font-size: 14px;
  color: #94a3b8;
  display: block;
  margin-bottom: 6px;
}
input, select, button {
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
input:focus, select:focus {
  border: 1px solid #60a5fa;
  background: rgba(255,255,255,0.15);
}
select {
  cursor: pointer;
}
button {
  background: linear-gradient(90deg, #2563eb, #60a5fa);
  color: white;
  font-weight: bold;
  cursor: pointer;
  border: none;
  transition: all 0.25s ease;
  letter-spacing: 0.5px;
}
button:hover {
  background: linear-gradient(90deg, #60a5fa, #2563eb);
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
  <h2>⚔️ สร้างตัวละคร</h2>
  <form id="createForm">
    <label>🧙‍♂️ Player ID</label>
    <input type="number" id="player_id" placeholder="กรอก Player ID ของผู้เล่น" required>

    <label>🏷️ ชื่อตัวละคร</label>
    <input type="text" id="char_name" placeholder="เช่น Zerox, Luna, Astra" required>

    <label>⚧ เพศตัวละคร</label>
    <select id="gender">
      <option value="M">ชาย 👦</option>
      <option value="F">หญิง 👧</option>
    </select>

    <button type="submit">✨ ยืนยันการสร้าง</button>
    <p id="msg"></p>
  </form>
  <div class="footer">© 2025 Ragnarok Origin Classic Admin</div>
</div>

<script>
const form = document.getElementById("createForm");
form.addEventListener("submit", async e => {
  e.preventDefault();
  const fd = new URLSearchParams();
  fd.append("player_id", document.getElementById("player_id").value);
  fd.append("name", document.getElementById("char_name").value);
  fd.append("gender", document.getElementById("gender").value);
  const res = await fetch("", { method: "POST", body: fd });
  const d = await res.json();
  const msg = document.getElementById("msg");
  msg.textContent = d.message || d.error;
  msg.className = d.success ? "success" : "error";
});
</script>
</body>
</html>
