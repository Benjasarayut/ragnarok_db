<?php
include("../db.php");
header("Content-Type: text/html; charset=UTF-8");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  header("Content-Type: application/json; charset=UTF-8");
  $char_id = $_POST['char_id'] ?? null;
  $field = $_POST['field'] ?? null;
  $value = $_POST['value'] ?? null;
  $allowed = ['name','level','exp','zenny','hp','mp','gender'];

  if (!$char_id || !$field || !in_array($field,$allowed)) {
    echo json_encode(["error" => "⚠ กรุณาระบุ char_id และ field ที่ถูกต้อง"]);
    exit;
  }

  try {
    $stmt = $conn->prepare("UPDATE characters SET {$field}=? WHERE char_id=?");
    $stmt->bind_param("si",$value,$char_id);
    $stmt->execute();
    echo json_encode(["success"=>true,"message"=>"✅ อัปเดตตัวละครสำเร็จ"]);
  } catch(Exception $e){
    echo json_encode(["error"=>$e->getMessage()]);
  }
  exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head><meta charset="UTF-8"><title>🧩 แก้ไขตัวละคร</title>
<style>
body{font-family:Poppins,sans-serif;background:#0f172a;color:#e2e8f0;display:flex;align-items:center;justify-content:center;height:100vh;}
form{background:rgba(255,255,255,0.05);padding:25px;border-radius:12px;width:380px;box-shadow:0 0 15px rgba(0,0,0,0.4);}
h2{text-align:center;color:#93c5fd;}
input,select,button{width:100%;margin:8px 0;padding:10px;border:none;border-radius:8px;}
button{background:#3b82f6;color:white;cursor:pointer;}button:hover{background:#2563eb;}
#msg{text-align:center;margin-top:10px;}
</style></head>
<body>
<form id="editForm">
  <h2>🧩 แก้ไขตัวละคร</h2>
  <input type="number" id="char_id" placeholder="Character ID" required>
  <select id="field">
    <option value="name">ชื่อ</option>
    <option value="level">เลเวล</option>
    <option value="exp">EXP</option>
    <option value="zenny">Zenny</option>
    <option value="hp">HP</option>
    <option value="mp">MP</option>
    <option value="gender">เพศ</option>
  </select>
  <input type="text" id="value" placeholder="ค่าใหม่" required>
  <button type="submit">บันทึก</button>
  <p id="msg"></p>
</form>
<script>
editForm.addEventListener("submit",async e=>{
  e.preventDefault();
  const f=new URLSearchParams();
  f.append("char_id",char_id.value);
  f.append("field",field.value);
  f.append("value",value.value);
  const res=await fetch("",{method:"POST",body:f});
  const d=await res.json();
  msg.textContent=d.message||d.error;
  msg.style.color=d.success?"lightgreen":"#ff6b6b";
});
</script>
</body>
</html>
