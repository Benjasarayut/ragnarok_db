<?php
include("../db.php");
header("Content-Type: text/html; charset=UTF-8");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  header("Content-Type: application/json; charset=UTF-8");

  $char_id = $_POST['char_id'] ?? null;
  $field = $_POST['field'] ?? null;
  $value = $_POST['value'] ?? null;

  $allowed = ['name', 'level', 'exp', 'zenny', 'hp', 'mp', 'gender'];

  if (!$char_id || !$field || !in_array($field, $allowed)) {
    echo json_encode(["error" => "⚠ กรุณาระบุ Character ID และฟิลด์ที่ถูกต้อง"]);
    exit;
  }

  try {
    $stmt = $conn->prepare("UPDATE characters SET {$field}=? WHERE char_id=?");
    $stmt->bind_param("si", $value, $char_id);
    $stmt->execute();
    echo json_encode(["success" => true, "message" => "✅ อัปเดตข้อมูลตัวละครสำเร็จ!"]);
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
<title>🧩 แก้ไขตัวละคร | Ragnarok Admin</title>
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
  color: #93c5fd;
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

/* 🎨 ปรับ Dropdown (Select) ให้สวยเหมือน Ragnarok */
select {
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
  position: relative;
  background: linear-gradient(135deg, #1e3a8a, #2563eb);
  color: #f8fafc;
  font-weight: 500;
  border: none;
  padding-right: 35px; /* เผื่อพื้นที่ลูกศร */
}
select::after {
  content: "▼";
  position: absolute;
  right: 15px;
  top: 50%;
  transform: translateY(-50%);
  color: #f8fafc;
}
select option {
  background-color: #1e293b;
  color: #f8fafc;
  padding: 10px;
}
select option:hover,
select option:focus,
select option:checked {
  background-color: #2563eb;
  color: #fff;
}

button {
  background: linear-gradient(90deg, #3b82f6, #60a5fa);
  color: white;
  font-weight: bold;
  cursor: pointer;
  border: none;
  transition: all 0.25s ease;
  letter-spacing: 0.5px;
}
button:hover {
  background: linear-gradient(90deg, #60a5fa, #3b82f6);
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
  <h2>🧩 แก้ไขข้อมูลตัวละคร</h2>
  <form id="editForm">
    <label>🆔 Character ID</label>
    <input type="number" id="char_id" placeholder="กรอกหมายเลขตัวละคร" required>

    <label>📂 ฟิลด์ที่ต้องการแก้ไข</label>
    <select id="field">
      <option value="name">ชื่อ</option>
      <option value="level">เลเวล</option>
      <option value="exp">EXP</option>
      <option value="zenny">Zenny</option>
      <option value="hp">HP</option>
      <option value="mp">MP</option>
      <option value="gender">เพศ</option>
    </select>

    <label>✏️ ค่าใหม่</label>
    <input type="text" id="value" placeholder="กรอกค่าที่ต้องการเปลี่ยน" required>

    <button type="submit">💾 บันทึกการเปลี่ยนแปลง</button>
    <p id="msg"></p>
  </form>
  <div class="footer">© 2025 Ragnarok Origin Classic Admin</div>
</div>

<script>
const form = document.getElementById("editForm");
form.addEventListener("submit", async e => {
  e.preventDefault();
  const fd = new URLSearchParams();
  fd.append("char_id", document.getElementById("char_id").value);
  fd.append("field", document.getElementById("field").value);
  fd.append("value", document.getElementById("value").value);

  const res = await fetch("", { method: "POST", body: fd });
  const d = await res.json();
  const msg = document.getElementById("msg");
  msg.textContent = d.message || d.error;
  msg.className = d.success ? "success" : "error";
});
</script>
</body>
</html>
