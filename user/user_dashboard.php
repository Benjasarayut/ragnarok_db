<?php
session_start();
include("../db.php");

if (!isset($_SESSION['username'])) {
  header("Location: ../public/login.html");
  exit();
}

$user = $_SESSION['username'];

// ✅ ดึง player_id
$result = $conn->query("SELECT player_id FROM players WHERE username='$user' LIMIT 1");
$player = $result->fetch_assoc();
$player_id = $player['player_id'] ?? 0;

// ✅ ดึงตัวละครทั้งหมดของผู้เล่น
$chars = [];
if ($player_id) {
  $charQuery = $conn->query("
    SELECT c.char_id, c.name, c.level, cls.class_name
    FROM characters c
    LEFT JOIN classes cls ON c.class_id = cls.class_id
    WHERE c.player_id = $player_id
    ORDER BY c.level DESC
  ");
  $chars = $charQuery->fetch_all(MYSQLI_ASSOC);
}

// 🆕 ✅ รับ char_id จาก URL
$selectedCharId = isset($_GET['char_id']) ? intval($_GET['char_id']) : 0;

// 🧭 ถ้าไม่มีใน URL → ใช้ตัวแรกเป็นค่า default
if (!$selectedCharId && count($chars) > 0) {
  $selectedCharId = $chars[0]['char_id'];
}

// ✅ ตรวจสอบว่า char_id ที่เลือกเป็นของ player จริง
$valid = false;
foreach ($chars as $c) {
  if ($c['char_id'] == $selectedCharId) {
    $valid = true;
    break;
  }
}

if (!$valid) {
  $selectedCharId = $chars[0]['char_id'] ?? 0;
}

// ✅ ดึงข้อมูลตัวละครที่เลือก
$info = null;
$stmt = $conn->prepare("
    SELECT 
      c.char_id,
      c.name AS char_name,
      c.level,
      c.exp,
      c.zenny,
      c.hp,
      c.mp,
      cls.class_name,
      aw.awake_level,
      aw.awakened,

      g.guild_name,
      gr.role_name AS guild_role,
      (SELECT COUNT(*) FROM guild_members gm2 WHERE gm2.guild_id = g.guild_id) AS guild_members,

      p.name      AS pet_name,
      p.species   AS pet_species,
      p.level     AS pet_level,
      p.happiness AS pet_happiness

    FROM characters c
    LEFT JOIN classes cls       ON c.class_id = cls.class_id
    LEFT JOIN awakenings aw     ON c.char_id = aw.char_id
    LEFT JOIN guild_members gm  ON c.char_id = gm.char_id
    LEFT JOIN guilds g          ON gm.guild_id = g.guild_id
    LEFT JOIN guild_roles gr    ON gm.role_id = gr.role_id   -- ✅ เพิ่มตรงนี้
    LEFT JOIN pets p            ON c.player_id = p.owner_id
    WHERE c.char_id = ?
    LIMIT 1
");

if (!$stmt) {
  die('❌ SQL Error: ' . $conn->error);
}

$stmt->bind_param("i", $selectedCharId);
$stmt->execute();
$info = $stmt->get_result()->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <title>🏠 Ragnarok Dashboard</title>
  <style>
    /* ====== (สไตล์เหมือนเดิมของคุณ) ====== */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

    body {
      margin: 0;
      font-family: "Poppins", sans-serif;
      background: radial-gradient(circle at top, #1e293b 0%, #0f172a 70%);
      color: #e2e8f0;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      background-attachment: fixed;
    }

    body::before {
      content: "";
      position: fixed;
      inset: 0;
      background: radial-gradient(circle at 30% 10%, rgba(99, 102, 241, 0.25), transparent 60%), radial-gradient(circle at 70% 80%, rgba(147, 51, 234, 0.25), transparent 60%);
      z-index: -1;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 14px 40px;
      background: rgba(17, 24, 39, 0.95);
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(8px);

      width: 100%;
      /* ✅ ยืดเต็มหน้าจอ */
      box-sizing: border-box;
      /* ✅ กัน overflow ตอนมี padding */
      position: fixed;
      /* 🧭 ให้ header ลอยด้านบนตลอด */
      top: 0;
      left: 0;
      right: 0;
      z-index: 1000;
      /* ✅ ไม่ให้เมนูอื่นบัง */
    }

    header h1 {
      font-size: 1.5rem;
      color: #a5b4fc;
      text-shadow: 0 0 10px rgba(165, 180, 252, 0.9);
    }

    .nav-menu {
      display: flex;
      align-items: center;
      gap: 10px;
      overflow-x: auto;
      /* ✅ เปิดการเลื่อนแนวนอน */
      white-space: nowrap;
      /* ✅ ป้องกันการตัดบรรทัด */
      scrollbar-width: thin;
      /* ✅ ให้ scrollbar เล็ก */
      scrollbar-color: #6366f1 transparent;
      /* ✅ สี scrollbar */
      padding-bottom: 4px;
      /* ✅ กัน scrollbar ชิดขอบ */
    }

    .nav-menu::-webkit-scrollbar {
      height: 6px;
      /* ✅ ความสูง scrollbar */
    }

    .nav-menu::-webkit-scrollbar-thumb {
      background: linear-gradient(135deg, #6366f1, #9333ea);
      border-radius: 4px;
    }

    .nav-menu::-webkit-scrollbar-track {
      background: transparent;
    }

    .nav-menu button {
      background: linear-gradient(135deg, #3b82f6, #9333ea);
      border: none;
      padding: 9px 16px;
      border-radius: 10px;
      color: white;
      font-weight: 600;
      box-shadow: 0 0 10px rgba(147, 51, 234, 0.3);
      cursor: pointer;
      transition: all 0.25s ease;
    }

    .nav-menu button,
    .nav-menu a {
      flex-shrink: 0;
      /* ✅ ป้องกันปุ่มถูกบีบ */
    }


    .nav-menu button:hover {
      transform: translateY(-2px);
      background: linear-gradient(135deg, #6366f1, #8b5cf6);
      box-shadow: 0 0 20px rgba(147, 51, 234, 0.6);
    }

    .nav-menu button.active {
      background: linear-gradient(135deg, #8b5cf6, #3b82f6);
      box-shadow: 0 0 25px rgba(59, 130, 246, 0.7);
      text-shadow: 0 0 8px rgba(255, 255, 255, 0.7);
    }

    .logout-btn {
      background: linear-gradient(135deg, #ef4444, #f87171);
      padding: 9px 18px;
      border-radius: 10px;
      color: #fff;
      font-weight: 600;
      box-shadow: 0 0 12px rgba(239, 68, 68, 0.5);
      text-decoration: none;
      transition: all 0.25s ease;
    }

    .logout-btn:hover {
      background: linear-gradient(135deg, #f87171, #fb7185);
      box-shadow: 0 0 20px rgba(248, 113, 113, 0.7);
      transform: translateY(-2px);
    }

    /* 🧭 ปรับ main ให้ชัดเจนว่า grid 2 คอลัมน์ */
    main {
      display: grid;
      grid-template-columns: 300px 1fr;
      /* ✅ ด้านขวาขยายเต็ม */
      margin-top: 80px;
      /* ความสูงพอให้ header ไม่ทับ */
      gap: 24px;
      flex: 1;
      padding: 25px 40px;
      width: 100%;
      box-sizing: border-box;
    }

    .sidebar {
      background: linear-gradient(180deg, rgba(30, 41, 59, 0.95), rgba(15, 23, 42, 0.98));
      border-radius: 16px;
      padding: 22px;
      box-shadow: 0 0 25px rgba(59, 130, 246, 0.2);
    }

    .sidebar h2 {
      color: #7dd3fc;
      margin-bottom: 10px;
      text-shadow: 0 0 6px rgba(125, 211, 252, 0.6);
    }

    .username {
      background: rgba(255, 255, 255, 0.05);
      padding: 10px 18px;
      border-radius: 10px;
      font-weight: 600;
      color: #f1f5f9;
      text-align: center;
      margin-bottom: 15px;
    }

    .stat-box {
      background: rgba(255, 255, 255, 0.05);
      border-radius: 12px;
      padding: 15px;
      box-shadow: inset 0 0 8px rgba(0, 0, 0, 0.4);
    }

    .stat-box p {
      margin: 6px 0;
      display: flex;
      justify-content: space-between;
      font-size: 14px;
    }

    .bar {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 8px;
      overflow: hidden;
      height: 8px;
      margin-top: 4px;
    }

    .bar span {
      display: block;
      height: 100%;
      border-radius: 8px;
    }

    .exp span {
      background: #fde047;
      width: 70%;
    }

    .hp span {
      background: #ef4444;
      width: 50%;
    }

    .mp span {
      background: #3b82f6;
      width: 40%;
    }

    /* 🎯 บังคับ Dashboard ขวาให้เต็มพื้นที่ */
    .dashboard {
      background: rgba(17, 24, 39, 0.85);
      border-radius: 18px;
      padding: 25px;
      box-shadow: 0 0 25px rgba(96, 165, 250, 0.3), inset 0 0 12px rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(59, 130, 246, 0.2);
      width: 100%;
      /* ✅ เต็มความกว้าง */
      flex: 1;
      display: flex;
      flex-direction: column;
      overflow-x: hidden;
      /* ✅ ตัดการ scroll ด้านล่างออก */
    }

    /* 🧾 ตารางภายใน */
    .table-container table {
      width: 100%;
      /* ✅ ให้ขยายเต็ม แต่ไม่เกิน container */
      border-collapse: collapse;
    }

    .table-container th,
    .table-container td {
      padding: 10px 12px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      text-align: left;
      white-space: nowrap;
      /* ✅ ไม่ให้ข้อความพับบรรทัดจนเบี้ยว layout */
    }

    .table-container th {
      background: rgba(59, 130, 246, 0.25);
      color: #bfdbfe;
    }

    .table-container tr:hover {
      background: rgba(255, 255, 255, 0.05);
      transition: background 0.25s;
    }

    /* 🧭 ปรับ content ให้รับขนาดเต็ม */
    .content {
      flex: 1;
      width: 100%;
      display: flex;
      flex-direction: column;
      background: rgba(255, 255, 255, 0.04);
      border-radius: 12px;
      padding: 15px;
      box-shadow: inset 0 0 15px rgba(0, 0, 0, 0.3);
      backdrop-filter: blur(6px);
      opacity: 0;
      transform: translateY(5px);
      transition: opacity 0.5s ease, transform 0.5s ease;
      overflow: hidden;
      /* ✅ ตัดส่วนเกินที่ล้น */
    }

    .content.show {
      opacity: 1;
      transform: translateY(0);
    }

    /* 🧭 ตารางให้ขยายเต็มและไม่โดนตัด */
    .content table {
      width: 100%;
      /* ✅ ให้กินเต็มพื้นที่ */
      min-width: 600px;
      /* ✅ กันบีบ */
      border-collapse: collapse;
    }

    .content th,
    .content td {
      padding: 10px 12px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      text-align: left;
      white-space: nowrap;
      /* ✅ ป้องกันตัดบรรทัด */
    }

    .content th {
      background: rgba(59, 130, 246, 0.25);
      color: #bfdbfe;
      text-transform: capitalize;
    }

    .content tr:hover {
      background: rgba(255, 255, 255, 0.05);
    }

    footer {
      text-align: center;
      padding: 12px;
      background: rgba(17, 24, 39, 0.8);
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      font-size: 14px;
      color: #9ca3af;
    }

    /* 🎨 Guild Select */
    .guild-select-container {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 10px;
    }

    .guild-select-label {
      display: flex;
      align-items: center;
      gap: 6px;
      color: #dbeafe;
      font-weight: 600;
      font-size: 15px;
    }

    .guild-select {
      background: rgba(30, 41, 59, 0.9);
      color: #f1f5f9;
      padding: 6px 12px;
      border-radius: 10px;
      border: 1px solid rgba(59, 130, 246, 0.5);
      outline: none;
      font-size: 14px;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .guild-select:hover {
      background: rgba(51, 65, 85, 0.95);
      border-color: #60a5fa;
    }

    .guild-select:focus {
      border-color: #818cf8;
      box-shadow: 0 0 0 2px rgba(129, 140, 248, 0.3);
    }

    .char-title {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 1.1rem;
      font-weight: 600;
      color: #93c5fd;
      background: rgba(59, 130, 246, 0.15);
      border: 1px solid rgba(59, 130, 246, 0.4);
      padding: 10px 14px;
      border-radius: 12px;
      margin-bottom: 10px;
      box-shadow: 0 0 12px rgba(59, 130, 246, 0.3);
      backdrop-filter: blur(6px);
      text-shadow: 0 0 8px rgba(147, 197, 253, 0.6);
    }

    #charSelect {
      width: 100%;
      background: rgba(30, 41, 59, 0.8);
      color: #e2e8f0;
      padding: 10px 12px;
      font-size: 0.95rem;
      border-radius: 10px;
      border: 1px solid rgba(59, 130, 246, 0.4);
      margin-bottom: 15px;
      outline: none;
      transition: all 0.25s ease;
    }

    #charSelect:hover {
      background: rgba(51, 65, 85, 0.9);
      border-color: #60a5fa;
    }

    #charSelect:focus {
      border-color: #818cf8;
      box-shadow: 0 0 0 2px rgba(129, 140, 248, 0.3);
    }
  </style>
</head>

<body>
  <header>
    <h1>🏠 Ragnarok Dashboard</h1>
    <nav class="nav-menu">
      <button data-api="get_items" class="active">🎒 Item</button>
      <button data-api="get_equipment">🧥 Equipment</button>
      <button data-api="get_quests">🗺️ Quests</button>
      <button data-api="get_inventory">📦 Inventory</button>
      <button data-api="get_character_skills">🧠 Skills</button>
      <button data-api="get_pets">🐾 Pets</button>
      <button data-api="get_guilds">🏰 Guild</button>
      <button data-api="get_awakening_logs">📜 Awakening Logs</button>
      <a href="../auth/logout.php" class="logout-btn">🚪 Logout</a>
    </nav>
  </header>

  <main>
    <aside class="sidebar">
      <h2>👤 Name</h2>
      <p class="username"><?= htmlspecialchars($user) ?></p>

      <!-- 🎭 เลือกตัวละคร -->
      <?php if (count($chars) > 0): ?>
        <div class="char-title">
          🧝 ตัวละครของคุณ:
        </div>
        <select id="charSelect" onchange="changeCharacter()">
          <?php foreach ($chars as $c): ?>
            <option value="<?= $c['char_id'] ?>" <?= $c['char_id'] == $selectedCharId ? 'selected' : '' ?>>
              <?= htmlspecialchars($c['name']) ?> (Lv.<?= $c['level'] ?> <?= htmlspecialchars($c['class_name']) ?>)
            </option>
          <?php endforeach; ?>
        </select>
      <?php else: ?>
        <p style="color:#f87171;">⚠️ ยังไม่มีตัวละคร</p>
      <?php endif; ?>

      <?php if ($info): ?>
        <div class="stat-box" id="charInfoBox">
          <p><strong>ชื่อตัวละคร:</strong> <?= htmlspecialchars($info['char_name']) ?></p>
          <p><strong>อาชีพ:</strong> <?= htmlspecialchars($info['class_name'] ?? '-') ?></p>
          <p><strong>เลเวล:</strong> <?= $info['level'] ?></p>
          <p><strong>EXP:</strong> <?= number_format($info['exp']) ?></p>
          <div class="bar exp"><span></span></div>
          <p><strong>HP:</strong> <?= number_format($info['hp']) ?></p>
          <div class="bar hp"><span></span></div>
          <p><strong>MP:</strong> <?= number_format($info['mp']) ?></p>
          <div class="bar mp"><span></span></div>
          <p><strong>Awake Level:</strong> <?= $info['awake_level'] ?? 0 ?></p>
          <p><strong>Awakened:</strong> <?= ($info['awakened'] ?? 0) ? '✅ Yes' : '❌ No' ?></p>
          <p><strong>Zenny:</strong> 💰 <?= number_format($info['zenny']) ?></p>
        </div>
      <?php endif; ?>
    </aside>

    <section class="dashboard">
      <article class="content show" id="contentBox">
        <p>เลือกเมนูด้านบนเพื่อดูข้อมูลจริงจากฐานข้อมูล...</p>
      </article>
    </section>
  </main>

  <footer>© 2025 Ragnarok Origin Classic</footer>

  <script>
    const box = document.getElementById("contentBox");
    const menuButtons = document.querySelectorAll(".nav-menu button");
    const playerId = <?= $player_id ?>;
    const charSelect = document.getElementById('charSelect');

    // 🧭 เปลี่ยนตัวละคร → Reload หน้า พร้อมส่ง char_id ใหม่
    function changeCharacter() {
      const selectedChar = charSelect.value;
      window.location.href = `user_dashboard.php?char_id=${selectedChar}`;
    }

    // 📥 โหลดข้อมูลเมื่อกดเมนู
    menuButtons.forEach(btn => {
      btn.addEventListener("click", async () => {
        // สลับ active menu
        menuButtons.forEach(b => b.classList.remove("active"));
        btn.classList.add("active");

        const api = btn.dataset.api;
        const charId = charSelect ? charSelect.value : <?= $selectedCharId ?>;
        box.classList.remove("show");
        box.innerHTML = "<p style='color:gray;'>⏳ กำลังโหลดข้อมูล...</p>";

        try {
          const res = await fetch(`../api/${api}.php?player_id=${playerId}&char_id=${charId}`);
          const result = await res.json();

          // 🏰 Guild Menu
          if (api === "get_guilds") {
            const guilds = result.data.guilds;
            if (!guilds || guilds.length === 0) {
              box.innerHTML = `<p style='color:gray;'>ไม่มีข้อมูลกิลด์</p>`;
              setTimeout(() => box.classList.add("show"), 50);
              return;
            }

            // Dropdown เลือกกิลด์
            let guildSelectHTML = `
          <div class="guild-select-container">
            <span class="guild-select-label">🏰 เลือกกิลด์:</span>
            <select id="guildSelect" class="guild-select" 
              onchange="renderGuild(${JSON.stringify(guilds).replace(/"/g, '&quot;')})">
        `;

            guilds.forEach(g => {
              guildSelectHTML += `<option value="${g.guild_id}">${g.guild_name}</option>`;
            });

            guildSelectHTML += `</select></div><hr><div id="guildContent"></div>`;
            box.innerHTML = guildSelectHTML;

            // render กิลด์แรก
            renderGuild(guilds);
            setTimeout(() => box.classList.add("show"), 50);
            return;
          }

          // 📊 เมนูอื่น (Item / Skill / Inventory / etc.)
          if (!result || result.success === false) {
            box.innerHTML = `<p style='color:#f87171;'>❌ ${result.message || result.error || "โหลดข้อมูลล้มเหลว"}</p>`;
          } else if (!Array.isArray(result.data) || result.data.length === 0) {
            box.innerHTML = "<p style='color:gray;'>ไม่มีข้อมูลในส่วนนี้</p>";
          } else {
            let html = "<table><thead><tr>";

            // ✅ Mapping ชื่อ column → ภาษาไทย
            const thMap = {
              item_id: "รหัสไอเทม",
              item_name: "ชื่อไอเทม",
              quantity: "จำนวน",
              equip_id: "รหัสอุปกรณ์",
              equip_name: "ชื่ออุปกรณ์",
              slot: "ช่อง",
              skill_id: "รหัสสกิล",
              skill_name: "ชื่อสกิล",
              level: "เลเวล",
              pet_name: "ชื่อสัตว์เลี้ยง",
              pet_species: "สายพันธุ์",
              pet_level: "เลเวลสัตว์เลี้ยง",
              pet_happiness: "ความสุข"
            };

            Object.keys(result.data[0]).forEach(k => {
              html += `<th>${thMap[k] ?? k}</th>`;
            });

            html += "</tr></thead><tbody>";
            result.data.forEach(row => {
              html += "<tr>";
              Object.values(row).forEach(v => {
                html += `<td>${v ?? '-'}</td>`;
              });
              html += "</tr>";
            });
            html += "</tbody></table>";
            box.innerHTML = html;
          }

        } catch (err) {
          box.innerHTML = `<p style='color:#f87171;'>⚠ Error: ${err.message}</p>`;
        }

        setTimeout(() => box.classList.add("show"), 50);
      });
    });

    // 🏰 ✅ ฟังก์ชัน global สำหรับเปลี่ยนข้อมูลกิลด์
    function renderGuild(guilds) {
      const selectedId = document.getElementById('guildSelect').value;
      const g = guilds.find(x => x.guild_id == selectedId);
      if (!g) return;

      const myRole = g.my_role;
      const roleIcon = myRole === "Leader" ? "✨" : "👤";
      const roleText = myRole === "Leader" ? "หัวหน้ากิลด์ (Leader)" : "สมาชิก (Member)";

      let html = `
    <h2>🏰 ${g.guild_name}</h2>
    <p>👑 หัวหน้า: ${g.leader_name}</p>
    <p>📜 คำอธิบาย: ${g.description ?? '-'}</p>
    <p>🕒 วันที่สร้าง: ${g.creation_date}</p>
    <hr>
    <p><strong>${roleIcon} บทบาทของคุณ:</strong> ${roleText}</p>
    <h3>👥 สมาชิกในกิลด์ (${g.members.length} คน)</h3>
    <table>
      <thead>
        <tr>
          <th>ชื่อตัวละคร</th>
          <th>ชื่อผู้เล่น</th>
          <th>บทบาท</th>
        </tr>
      </thead>
      <tbody>
  `;

      g.members.forEach(m => {
        const roleStyle = m.role === "Leader" ? "style='color:#facc15;font-weight:bold;'" : "";
        const mIcon = m.role === "Leader" ? "✨" : "👤";
        html += `
      <tr ${roleStyle}>
        <td>${m.character_name}</td>
        <td>${m.player_name}</td>
        <td>${mIcon} ${m.role}</td>
      </tr>
    `;
      });

      html += `</tbody></table>`;
      document.getElementById("guildContent").innerHTML = html;
      setTimeout(() => box.classList.add("show"), 50);
    }
  </script>

</body>

</html>