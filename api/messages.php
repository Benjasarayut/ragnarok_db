<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include("../db.php");
header("Content-Type: text/html; charset=UTF-8");

// ====== API POST: ส่งข้อความ ======
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  header("Content-Type: application/json; charset=UTF-8");

  $to_id = isset($_POST['to_id']) && $_POST['to_id'] !== "" && intval($_POST['to_id']) > 0 
            ? intval($_POST['to_id']) 
            : null;
  $message = trim($_POST['message'] ?? '');
  $from = 'admin';

  if ($message === '') {
    echo json_encode(["error" => "⚠ ต้องระบุข้อความ"]);
    exit;
  }

  try {
    if ($to_id === null) {
      // Broadcast message
      $stmt = $conn->prepare("INSERT INTO messages (sender, message, created_at) VALUES (?, ?, NOW())");
      $stmt->bind_param("ss", $from, $message);
    } else {
      // Send to specific player
      $stmt = $conn->prepare("INSERT INTO messages (sender, receiver_id, message, created_at) VALUES (?, ?, ?, NOW())");
      $stmt->bind_param("sis", $from, $to_id, $message);
    }
    $stmt->execute();
    echo json_encode(["success" => true, "message" => "✅ ส่งข้อความสำเร็จ!"]);
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
  <title>💬 ส่งข้อความ | Ragnarok API</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
    body {
      font-family: "Poppins", sans-serif;
      background: radial-gradient(circle at top, #0f172a, #1e293b);
      color: #e2e8f0;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .container {
      background: rgba(255,255,255,0.05);
      border-radius: 16px;
      padding: 30px;
      width: 420px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.4);
      backdrop-filter: blur(10px);
    }

    h2 {
      text-align: center;
      color: #fbbf24;
      margin-bottom: 20px;
      font-weight: 600;
    }

    input, textarea, button {
      width: 100%;
      margin: 10px 0;
      padding: 12px 15px;
      border-radius: 10px;
      border: 1px solid rgba(255,255,255,0.1);
      background: rgba(255,255,255,0.1);
      color: #fff;
      font-size: 15px;
      outline: none;
      transition: 0.2s;
    }

    input[type="number"] {
      width: calc(100% - 4px);   /* ทำให้ความกว้างพอดีกับ textarea */
      box-sizing: border-box;    /* รวม padding ในขนาดจริง */
      text-align: left;          /* ให้ข้อความชิดซ้าย */
    }


    input:focus, textarea:focus {
      border-color: #fbbf24;
      background: rgba(255,255,255,0.15);
    }

    textarea {
      resize: none;
      height: 100px;
      width: calc(100% - 4px);
      box-sizing: border-box;
    }

    button {
      background: linear-gradient(90deg, #f59e0b, #facc15);
      color: #111827;
      font-weight: bold;
      border: none;
      cursor: pointer;
      transition: all 0.25s;
    }

    button:hover {
      transform: translateY(-1px);
      background: linear-gradient(90deg, #fbbf24, #fde047);
    }

    #msg {
      text-align: center;
      margin-top: 8px;
      font-weight: 500;
    }

    .logs {
      margin-top: 25px;
      background: rgba(0,0,0,0.3);
      padding: 15px;
      border-radius: 10px;
      font-size: 14px;
      max-height: 200px;
      overflow-y: auto;
    }

    .logs p {
      margin: 5px 0;
      border-bottom: 1px solid rgba(255,255,255,0.05);
      padding-bottom: 4px;
    }

    .logs span {
      color: #fbbf24;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>💬 ส่งข้อความ</h2>
    <form id="msgForm">
      <input type="number" id="to_id" placeholder="🧙‍♂️ Player ID (ว่าง = ส่งถึงทุกคน)">
      <textarea id="message" placeholder="พิมพ์ข้อความ..." required></textarea>
      <button type="submit">📨 ส่ง</button>
      <p id="msg"></p>
    </form>

    <div class="logs" id="logs">
      <strong>🕓 ข้อความล่าสุด</strong>
      <div id="logList"></div>
    </div>
  </div>

  <script>
    async function loadLogs() {
      const res = await fetch('?logs=1');
      const data = await res.json();
      const list = document.getElementById('logList');
      list.innerHTML = data.map(
        m => `<p><span>${m.sender}</span>: ${m.message} <br><small>${m.created_at}</small></p>`
      ).join('');
    }

    msgForm.addEventListener("submit", async e => {
      e.preventDefault();
      const fd = new URLSearchParams();
      fd.append("to_id", to_id.value || "");
      fd.append("message", message.value);
      const res = await fetch("", { method: "POST", body: fd });
      const d = await res.json();
      msg.textContent = d.message || d.error;
      msg.style.color = d.success ? "lightgreen" : "#ff6b6b";
      if (d.success) {
        message.value = "";
        await loadLogs();
      }
    });

    // โหลดข้อความล่าสุดตอนเริ่ม
    loadLogs();
  </script>
</body>
</html>
