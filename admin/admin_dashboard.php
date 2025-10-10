<?php
session_start();
include("../db.php");

if (!isset($_SESSION['admin_name'])) {
  header("Location: ../public/login.html");
  exit();
}

$admin = $_SESSION['admin_name'];
error_reporting(0);

// ✅ สถิติรวม
$totalPlayers = $totalBanned = $newCharsToday = 0;

$q1 = $conn->query("SELECT COUNT(*) AS total FROM players");
if ($q1 && $row = $q1->fetch_assoc()) $totalPlayers = $row['total'];

$q2 = $conn->query("SELECT COUNT(*) AS total FROM bans WHERE NOW() BETWEEN start_date AND end_date");
if ($q2 && $row = $q2->fetch_assoc()) $totalBanned = $row['total'];

$q3 = $conn->query("SELECT COUNT(*) AS total FROM characters WHERE DATE(created_at)=CURDATE()");
if ($q3 && $row = $q3->fetch_assoc()) $newCharsToday = $row['total'];

// ✅ ดึงข้อมูลผู้ใช้ + ชื่อตัวละคร
$sql = "
SELECT 
  p.player_id, 
  p.username, 
  p.email, 
  p.created_at,
  CASE 
    WHEN EXISTS (
      SELECT 1 FROM bans b 
      WHERE b.player_id = p.player_id 
      AND NOW() BETWEEN b.start_date AND b.end_date
    )
    THEN 'Banned' ELSE 'Active' 
  END AS status,
  GROUP_CONCAT(c.name ORDER BY c.created_at ASC SEPARATOR ', ') AS char_names,
  COUNT(c.char_id) AS char_count
FROM players p
LEFT JOIN characters c ON c.player_id = p.player_id
GROUP BY p.player_id
ORDER BY p.created_at DESC;
";
$result = $conn->query($sql);
$players = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <title>Ragnarok Admin Dashboard</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    /* 🌌 พื้นหลัง */
    body {
      background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
      color: #e2e8f0;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* 🧭 Header */
    header.topnav {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: rgba(15, 23, 42, 0.8);
      backdrop-filter: blur(8px);
      padding: 14px 40px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.6);
      z-index: 999;
    }

    .logo h1 {
      color: #facc15;
      font-weight: 700;
      font-size: 1.2rem;
    }

    /* 🔸 เมนูหลัก */
    .admin-menu {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      margin: 0 40px;
    }

    .admin-menu a {
      text-decoration: none;
      color: #cbd5e1;
      background: rgba(255, 255, 255, 0.08);
      padding: 10px 18px;
      border-radius: 12px;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .admin-menu a:hover {
      background: linear-gradient(135deg, #3b82f6, #9333ea);
      color: #fff;
      box-shadow: 0 0 15px rgba(147, 51, 234, 0.6);
      transform: translateY(-2px);
    }

    .right-menu a {
      color: #94a3b8;
      text-decoration: none;
      padding: 8px 14px;
      border-radius: 8px;
      transition: 0.3s;
    }

    .right-menu a:hover {
      background: rgba(255, 255, 255, 0.1);
      color: #f87171;
    }

    .logout {
      color: #ef4444;
      font-weight: 600;
    }

    /* 📦 กล่อง Section */
    main {
      margin-top: 110px;
      padding: 0 40px 40px;
    }

    .box {
      background: rgba(30, 41, 59, 0.7);
      backdrop-filter: blur(6px);
      border-radius: 16px;
      padding: 20px 25px;
      box-shadow: 0 4px 25px rgba(0, 0, 0, 0.5);
      margin-bottom: 20px;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .box:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 30px rgba(59, 130, 246, 0.25);
    }

    h3 {
      margin-bottom: 12px;
      font-size: 1.1rem;
      color: #facc15;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    /* 🧾 Label + Input */
    label {
      display: block;
      margin-bottom: 6px;
      font-weight: 500;
      color: #94a3b8;
    }

    input,
    select,
    textarea {
      width: 100%;
      padding: 8px 12px;
      border-radius: 10px;
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.1);
      color: #e2e8f0;
      font-size: 0.95rem;
      margin-bottom: 15px;
      transition: all 0.25s ease;
    }

    input:focus,
    select:focus,
    textarea:focus {
      outline: none;
      border-color: #60a5fa;
      box-shadow: 0 0 0 2px rgba(96, 165, 250, 0.4);
      background: rgba(255, 255, 255, 0.08);
    }

    /* 💾 ปุ่ม */
    button,
    input[type="submit"] {
      background: linear-gradient(135deg, #3b82f6, #9333ea);
      color: white;
      border: none;
      padding: 10px 18px;
      border-radius: 10px;
      font-size: 0.95rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.25s ease;
    }

    button:hover,
    input[type="submit"]:hover {
      box-shadow: 0 0 20px rgba(147, 51, 234, 0.5);
      transform: translateY(-1px);
    }

    button:active {
      transform: scale(0.98);
    }

    /* 📊 ตาราง */
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      font-size: 0.95rem;
    }

    th,
    td {
      padding: 10px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    th {
      background: rgba(59, 130, 246, 0.2);
      color: #bfdbfe;
      text-align: left;
    }

    tr:hover {
      background: rgba(255, 255, 255, 0.05);
    }

    /* 🟥 สถานะแบน */
    .status-banned {
      color: #f87171;
      font-weight: bold;
    }

    .status-active {
      color: #4ade80;
      font-weight: bold;
    }

    /* 🪄 Flash message */
    .flash {
      color: #22c55e;
      font-weight: 600;
      animation: flashFade 2s ease forwards;
    }

    @keyframes flashFade {
      0% {
        opacity: 0;
      }

      10% {
        opacity: 1;
      }

      80% {
        opacity: 1;
      }

      100% {
        opacity: 0;
      }
    }

    /* 📜 Page control */
    .page {
      display: none;
    }

    .page.active {
      display: block;
      animation: fade 0.4s ease;
    }

    @keyframes fade {
      from {
        opacity: 0;
        transform: translateY(10px);
      }

      to {
        opacity: 1;
        transform: none;
      }
    }

    footer {
      text-align: center;
      padding: 15px;
      background: rgba(0, 0, 0, 0.5);
      color: #94a3b8;
      font-size: 0.9rem;
    }

    /* 🧭 Dropdown Dark Mode */
    select {
      background: rgba(255, 255, 255, 0.05);
      color: #e2e8f0;
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 10px;
      padding: 8px 12px;
      appearance: none;
      -webkit-appearance: none;
      -moz-appearance: none;
      cursor: pointer;
    }

    /* 🔸 เพิ่มลูกศรสวยๆ แบบ Custom */
    select {
      background-image: url("data:image/svg+xml;utf8,<svg fill='white' height='18' viewBox='0 0 24 24' width='18' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>");
      background-repeat: no-repeat;
      background-position: right 12px center;
      background-size: 18px;
    }

    /* 🪄 เวลา focus */
    select:focus {
      outline: none;
      border-color: #60a5fa;
      box-shadow: 0 0 0 2px rgba(96, 165, 250, 0.4);
      background: rgba(255, 255, 255, 0.08);
    }

    /* 🌚 ปรับสีพื้นหลังของ option */
    option {
      background-color: #1e293b;
      /* สีพื้นหลัง dropdown */
      color: #e2e8f0;
      /* สีข้อความ */
    }

    /* 🟡 สีเมื่อ hover หรือเลือก */
    option:hover,
    option:checked {
      background-color: #334155;
      color: #facc15;
    }

    /* 🧭 Scrollbar ของ dropdown */
    ::-webkit-scrollbar {
      width: 8px;
    }

    ::-webkit-scrollbar-thumb {
      background-color: #334155;
      border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background-color: #475569;
    }
  </style>

</head>

<body>

  <header class="topnav">
    <div class="logo">
      <h1>👑 Ragnarok Admin Dashboard</h1>
    </div>
    <nav class="admin-menu">
      <a href="#" onclick="showPage('users')">👥 ผู้ใช้ทั้งหมด</a>
      <a href="#" onclick="showPage('ban')">🔒 แบนผู้เล่น</a>
      <a href="#" onclick="showPage('create')">⚔️ สร้างตัวละคร</a>
      <a href="#" onclick="showPage('edit')">🧩 แก้ไขตัวละคร</a>
      <a href="#" onclick="showPage('messages')">💬 ข้อความ</a>
      <a href="#" onclick="showPage('ban_history')">📜 ประวัติการแบน</a>
      <a href="#" onclick="showPage('message_history')">🗂️ ประวัติข้อความ</a>
    </nav>
    <div class="right-menu"><a href="../auth/logout.php" class="logout">Logout</a></div>
  </header>

  <main>
    <!-- 🧍 ผู้ใช้ทั้งหมด -->
    <section id="users" class="page active">
      <div class="dashboard-grid">
        <div class="box">
          <h3>📜 ข้อมูลผู้ใช้ทั้งหมด</h3>
          <table>
            <thead>
              <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Character Names</th>
                <th>Count</th>
                <th>Status</th>
                <th>Created At</th>
              </tr>
            </thead>
            <tbody>
              <?php if (count($players) > 0): foreach ($players as $p): ?>
                  <tr data-id="<?= $p['player_id'] ?>">
                    <td><?= htmlspecialchars($p['username']) ?></td>
                    <td><?= htmlspecialchars($p['email']) ?></td>
                    <td title="<?= htmlspecialchars($p['char_names'] ?? '-') ?>">
                      <?= htmlspecialchars($p['char_names'] ?? '-') ?>
                    </td>
                    <td><?= $p['char_count'] ?></td>
                    <td class="<?= $p['status'] === 'Banned' ? 'status-banned' : 'status-active' ?>">
                      <?= $p['status'] ?>
                    </td>
                    <td><?= htmlspecialchars($p['created_at']) ?></td>
                  </tr>
                <?php endforeach;
              else: ?>
                <tr>
                  <td colspan="6" style="text-align:center;">ไม่มีข้อมูลผู้เล่น</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
        <div class="box">
          <h3>📊 สถิติระบบ</h3>
          <p>ผู้เล่นทั้งหมด: <b><?= $totalPlayers ?></b></p>
          <p>ถูกแบน: <b><?= $totalBanned ?></b></p>
          <p>ตัวละครใหม่วันนี้: <b><?= $newCharsToday ?></b></p>
          <hr style="margin:10px 0;border:0.5px solid rgba(255,255,255,0.1)">
          <p>ยินดีต้อนรับ <b><?= htmlspecialchars($admin) ?></b> 👋</p>
        </div>
      </div>
    </section>

    <!-- ⚔️ สร้างตัวละคร -->
    <section id="create" class="page">
      <div class="box">
        <h3>⚔️ สร้างตัวละคร</h3>
        <form id="createCharForm">
          <label>เลือกผู้เล่น:</label><br>
          <select id="createPlayerSelect" required>
            <option value="">-- เลือกผู้เล่น --</option>
            <?php foreach ($players as $p): ?>
              <option value="<?= $p['player_id'] ?>"><?= htmlspecialchars($p['username']) ?></option>
            <?php endforeach; ?>
          </select><br><br>

          <label>ชื่อตัวละคร:</label><br>
          <input type="text" id="createCharName" required><br><br>

          <label>Class:</label><br>
          <select id="createCharClass" required>
            <option value="">-- เลือกคลาส --</option>
          </select><br><br>

          <label>Gender:</label><br>
          <select id="createCharGender" required>
            <option value="">-- เลือกเพศ --</option>
            <option value="M">ชาย 👦</option>
            <option value="F">หญิง 👧</option>
          </select><br><br>

          <label>Level:</label><br>
          <input type="number" id="createCharLevel" value="1" min="1"><br><br>

          <button type="submit">✅ สร้างตัวละคร</button>
        </form>
        <p id="createCharResult"></p>
      </div>
    </section>


    <!-- 🧩 แก้ไขตัวละคร -->
    <section id="edit" class="page">
      <div class="box">
        <h3>🧩 แก้ไขตัวละคร</h3>
        <form id="editCharForm">
          <label>เลือกตัวละคร:</label><br>
          <select id="editCharSelect" required>
            <option value="">-- เลือกตัวละคร --</option>
          </select><br><br>

          <label>ชื่อตัวละคร:</label><br>
          <input type="text" id="editCharName" required><br><br>

          <label>Class:</label><br>
          <select id="editCharClass" required>
            <option value="">-- เลือกคลาส --</option>
          </select><br><br>

          <label>Level:</label><br>
          <input type="number" id="editCharLevel" min="1" required><br><br>

          <button type="submit">💾 บันทึกการแก้ไข</button>
        </form>
        <p id="editCharResult"></p>
      </div>
    </section>


    <!-- 💬 ข้อความ -->
    <section id="messages" class="page">
      <div class="box">
        <h3>💬 ส่งข้อความ</h3>
        <form id="messageForm">
          <label>ผู้รับ (ปล่อยว่าง = ส่งถึงทุกคน):</label><br>
          <select id="messageReceiver">
            <option value="">-- ประกาศทั้งหมด --</option>
            <?php foreach ($players as $p): ?>
              <option value="<?= $p['player_id'] ?>"><?= htmlspecialchars($p['username']) ?></option>
            <?php endforeach; ?>
          </select><br><br>
          <label>ข้อความ:</label><br>
          <textarea id="messageText" rows="3" placeholder="พิมพ์ข้อความที่ต้องการส่ง..." required></textarea><br><br>
          <button type="submit">📨 ส่งข้อความ</button>
        </form>
        <p id="messageResult"></p>
      </div>
    </section>

    <!-- 🗂️ ประวัติข้อความ -->
    <section id="message_history" class="page">
      <div class="box">
        <h3>🗂️ ประวัติข้อความ</h3>
        <table id="messageTable">
          <thead>
            <tr>
              <th>ID</th>
              <th>ผู้ส่ง</th>
              <th>ผู้รับ</th>
              <th>ข้อความ</th>
              <th>วันที่ส่ง</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </section>

    <!-- 🔒 แบนผู้เล่น -->
    <section id="ban" class="page">
      <div class="box">
        <h3>🔒 แบนผู้เล่น</h3>
        <form id="banForm">
          <label>เลือกผู้เล่น:</label><br>
          <select id="banPlayerSelect" required>
            <option value="">-- เลือกผู้เล่น --</option>
            <?php foreach ($players as $p): ?>
              <option value="<?= $p['player_id'] ?>"><?= htmlspecialchars($p['username']) ?></option>
            <?php endforeach; ?>
          </select><br><br>

          <label>วันที่เริ่มแบน:</label><br>
          <input type="date" id="banStart" lang="th" required>
          <p id="banStartDisplay" style="margin-top:4px;color:#facc15;font-size:0.9rem;"></p><br>

          <label>วันที่สิ้นสุดแบน:</label><br>
          <input type="date" id="banEnd" lang="th" required>
          <p id="banEndDisplay" style="margin-top:4px;color:#facc15;font-size:0.9rem;"></p><br>

          <label>เหตุผล:</label><br>
          <textarea id="banReason" rows="2" placeholder="ระบุเหตุผล..." required></textarea><br><br>

          <button type="submit">✅ ดำเนินการแบน</button>
        </form>
        <p id="banResult"></p>
      </div>
    </section>


    <!-- 📜 ประวัติการแบน -->
    <section id="ban_history" class="page">
      <div class="box">
        <h3>📜 ประวัติการแบนผู้เล่น</h3>
        <table id="banTable">
          <thead>
            <tr>
              <th>Ban ID</th>
              <th>Player ID</th>
              <th>Start</th>
              <th>End</th>
              <th>Reason</th>
              <th>Created</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </section>
  </main>

  <footer>© 2025 Ragnarok Origin Classic</footer>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      // 📩 Event หลัก
      document.getElementById("banForm").addEventListener("submit", banPlayer);
      document.getElementById("messageForm").addEventListener("submit", sendMessage);
      document.getElementById("createCharForm").addEventListener("submit", createCharacter);
      document.getElementById("editCharForm").addEventListener("submit", editCharacter);
      document.getElementById("editCharSelect").addEventListener("change", fillEditForm);

      // โหลดข้อมูลเริ่มต้น
      loadClasses("createCharClass");
      loadClasses("editCharClass");
    });

    // 🧭 เปลี่ยนหน้า Dashboard
    function showPage(id) {
      document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
      document.getElementById(id).classList.add('active');

      if (id === 'ban_history') loadBanHistory();
      if (id === 'message_history') loadMessageHistory();
      if (id === 'edit') loadCharactersForEdit();
    }

    // 🧾 โหลด Class ทั้งสร้าง/แก้ไข
    function loadClasses(targetId) {
      fetch("../api/get_classes.php")
        .then(res => res.json())
        .then(data => {
          const select = document.getElementById(targetId);
          select.innerHTML = `<option value="">-- เลือกคลาส --</option>`;
          data.forEach(c => {
            select.innerHTML += `<option value="${c.class_id}">${c.class_name}</option>`;
          });
        })
        .catch(err => console.error(`❌ โหลดคลาส (${targetId}) ไม่สำเร็จ:`, err));
    }

    // ⚔️ สร้างตัวละคร
    function createCharacter(e) {
      e.preventDefault();
      const data = {
        player_id: document.getElementById("createPlayerSelect").value,
        name: document.getElementById("createCharName").value,
        class_id: document.getElementById("createCharClass").value,
        gender: document.getElementById("createCharGender").value,
        level: document.getElementById("createCharLevel").value
      };

      if (!data.player_id || !data.name || !data.class_id || !data.gender) {
        alert("⚠️ กรุณากรอกข้อมูลให้ครบ");
        return;
      }

      fetch("../api/create_character.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(r => {
          document.getElementById("createCharResult").innerText = r.message;
          if (r.success) document.getElementById("createCharForm").reset();
        })
        .catch(err => console.error("🚨 Create Character Error:", err));
    }

    // 🧩 โหลดตัวละครทั้งหมดสำหรับ Dropdown แก้ไข
    function loadCharactersForEdit() {
      fetch("../api/get_characters.php")
        .then(res => res.json())
        .then(data => {
          const select = document.getElementById("editCharSelect");
          select.innerHTML = `<option value="">-- เลือกตัวละคร --</option>`;
          data.forEach(char => {
            select.innerHTML += `<option 
          value="${char.char_id}" 
          data-name="${char.name}" 
          data-class="${char.class}" 
          data-level="${char.level}">
          [${char.char_id}] ${char.name} (${char.class}) - ${char.username}
        </option>`;
          });
        })
        .catch(err => console.error("🚨 Load Characters Error:", err));
    }

    // 📝 Autofill เมื่อเลือกตัวละคร
    function fillEditForm(e) {
      const selected = e.target.options[e.target.selectedIndex];
      if (!selected.value) return;

      document.getElementById("editCharName").value = selected.getAttribute("data-name");
      document.getElementById("editCharLevel").value = selected.getAttribute("data-level");

      const classText = selected.getAttribute("data-class");
      const classSelect = document.getElementById("editCharClass");
      for (let opt of classSelect.options) {
        if (opt.textContent === classText) {
          opt.selected = true;
          break;
        }
      }
    }

    // 💾 บันทึกการแก้ไขตัวละคร
    function editCharacter(e) {
      e.preventDefault();
      const data = {
        char_id: document.getElementById("editCharSelect").value,
        name: document.getElementById("editCharName").value,
        class_id: document.getElementById("editCharClass").value,
        level: document.getElementById("editCharLevel").value
      };

      if (!data.char_id) {
        alert("⚠️ กรุณาเลือกตัวละครก่อนบันทึก");
        return;
      }

      fetch("../api/edit_character.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(r => {
          document.getElementById("editCharResult").innerText = r.message;
          if (r.success) loadCharactersForEdit();
        })
        .catch(err => console.error("🚨 Edit Character Error:", err));
    }

    // 🧭 แปลงวันที่จาก YYYY-MM-DD ➝ DD-MM-YYYY
    function formatDateToDDMMYYYY(dateStr) {
      const [year, month, day] = dateStr.split("-");
      return `${day}-${month}-${year}`;
    }

    // 🗓️ แสดงวันที่ที่เลือก
    const banStart = document.getElementById("banStart");
    const banEnd = document.getElementById("banEnd");
    const banStartDisplay = document.getElementById("banStartDisplay");
    const banEndDisplay = document.getElementById("banEndDisplay");

    // ตั้งค่าให้ไม่สามารถเลือกวันย้อนหลัง
    const today = new Date().toISOString().split("T")[0];
    banStart.min = today;
    banEnd.min = today;

    banStart.addEventListener("change", () => {
      if (banStart.value) {
        banStartDisplay.textContent = "📅 " + formatDateToDDMMYYYY(banStart.value);
        banEnd.min = banStart.value;
      }
    });

    banEnd.addEventListener("change", () => {
      if (banEnd.value) {
        banEndDisplay.textContent = "📅 " + formatDateToDDMMYYYY(banEnd.value);
      }
    });

    // 🧭 Ban Player (ส่ง DD-MM-YYYY ไป Backend)
    function banPlayer(e) {
      e.preventDefault();
      const player_id = document.getElementById("banPlayerSelect").value;
      const start_date = banStart.value ? formatDateToDDMMYYYY(banStart.value) : "";
      const end_date = banEnd.value ? formatDateToDDMMYYYY(banEnd.value) : "";
      const reason = document.getElementById("banReason").value;

      if (!player_id || !start_date || !end_date || !reason) {
        alert("⚠️ กรุณากรอกข้อมูลให้ครบ");
        return;
      }

      fetch("../api/ban_player.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify({
            player_id,
            start_date,
            end_date,
            reason
          })
        })
        .then(res => res.json())
        .then(r => {
          document.getElementById("banResult").innerText = r.message;
          if (r.success) {
            document.getElementById("banForm").reset();
            banStartDisplay.textContent = "";
            banEndDisplay.textContent = "";
            setTimeout(() => {
              showPage("ban_history");
              loadBanHistory();
            }, 500);
          }
        })
        .catch(err => console.error("🚨 Ban Player Error:", err));
    }


    // 🗑️ ยกเลิกแบนผู้เล่น
    function unbanPlayer(ban_id, player_id) {
      if (!confirm(`คุณต้องการยกเลิกแบน Player ID: ${player_id} ใช่หรือไม่?`)) return;

      fetch(`../api/unban_player.php`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify({
            ban_id
          })
        })
        .then(res => res.json())
        .then(r => {
          alert(r.message);
          if (r.success) loadBanHistory(); // โหลดตารางใหม่
        })
        .catch(err => {
          console.error("🚨 Unban Error:", err);
          alert("เกิดข้อผิดพลาดในการยกเลิกแบน");
        });
    }


    // 📜 ประวัติการแบน
    function loadBanHistory() {
      fetch("../api/get_bans.php")
        .then(res => res.json())
        .then(data => {
          const tbody = document.querySelector("#banTable tbody");
          tbody.innerHTML = "";
          if (!data.length) {
            tbody.innerHTML = "<tr><td colspan='7' style='text-align:center;'>ไม่มีข้อมูล</td></tr>";
            return;
          }
          data.forEach(b => {
            tbody.innerHTML += `
          <tr>
            <td>${b.ban_id}</td>
            <td>${b.player_id}</td>
            <td>${b.start_date}</td>
            <td>${b.end_date}</td>
            <td>${b.reason || '-'}</td>
            <td>${b.created_at}</td>
            <td><button class="unban-btn" onclick="unbanPlayer(${b.ban_id}, ${b.player_id})">🗑️ ยกเลิกแบน</button></td>
          </tr>`;
          });
        });
    }

    // ✉️ ส่งข้อความ
    function sendMessage(e) {
      e.preventDefault();
      const receiver_id = document.getElementById("messageReceiver").value || null;
      const message = document.getElementById("messageText").value.trim();
      if (!message) {
        alert("กรุณากรอกข้อความ");
        return;
      }

      fetch("../api/send_message.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify({
            receiver_id,
            message
          })
        })
        .then(res => res.json())
        .then(r => {
          document.getElementById("messageResult").innerText = r.message;
          if (r.success) {
            document.getElementById("messageText").value = "";
            loadMessageHistory();
          }
        });
    }

    // 📜 ประวัติข้อความ
    function loadMessageHistory() {
      fetch("../api/get_messages.php")
        .then(res => res.json())
        .then(data => {
          const tbody = document.querySelector("#messageTable tbody");
          tbody.innerHTML = "";
          if (!data.length) {
            tbody.innerHTML = "<tr><td colspan='5' style='text-align:center;'>ไม่มีข้อมูล</td></tr>";
            return;
          }
          data.forEach(m => {
            tbody.innerHTML += `
          <tr>
            <td>${m.message_id}</td>
            <td>${m.sender}</td>
            <td>${m.receiver_id ?? "ทุกคน"}</td>
            <td>${m.message}</td>
            <td>${m.created_at}</td>
          </tr>`;
          });
        });
    }
  </script>

</body>

</html>