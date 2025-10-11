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

// ✅ ดึงค่า Stats จากตาราง character_stats + stat_points
$stats = null;
if ($selectedCharId) {
  $statStmt = $conn->prepare("
    SELECT s.str, s.agi, s.vit, s.int_stat, s.dex, s.luk, c.stat_points
    FROM character_stats s
    JOIN characters c ON s.char_id = c.char_id
    WHERE s.char_id = ?
    LIMIT 1
  ");
  $statStmt->bind_param("i", $selectedCharId);
  $statStmt->execute();
  $stats = $statStmt->get_result()->fetch_assoc();
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
    LEFT JOIN guild_roles gr    ON gm.role_id = gr.role_id
    LEFT JOIN pets p            ON c.player_id = p.owner_id
    WHERE c.char_id = ?
    LIMIT 1
");

if (!$stmt) die('❌ SQL Error: ' . $conn->error);
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
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

    /* 🏔 พื้นหลัง */
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
      background:
        radial-gradient(circle at 30% 10%, rgba(99, 102, 241, 0.25), transparent 60%),
        radial-gradient(circle at 70% 80%, rgba(147, 51, 234, 0.25), transparent 60%);
      z-index: -1;
    }

    /* 🌟 Header */
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
      box-sizing: border-box;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1000;
    }

    header h1 {
      font-size: 1.5rem;
      color: #a5b4fc;
      text-shadow: 0 0 10px rgba(165, 180, 252, 0.9);
    }

    /* 🧭 เมนูด้านบน */
    .nav-menu {
      display: flex;
      align-items: center;
      gap: 10px;
      overflow-x: auto;
      white-space: nowrap;
      scrollbar-width: thin;
      scrollbar-color: #6366f1 transparent;
      padding-bottom: 4px;
    }

    .nav-menu button,
    .nav-menu a {
      flex-shrink: 0;
    }

    .nav-menu button {
      background: linear-gradient(135deg, #3b82f6, #9333ea);
      border: none;
      padding: 9px 16px;
      border-radius: 10px;
      color: white;
      font-weight: 600;
      cursor: pointer;
      transition: all .25s ease;
    }

    .nav-menu button:hover {
      transform: translateY(-2px);
      background: linear-gradient(135deg, #6366f1, #8b5cf6);
    }

    .nav-menu button.active {
      background: linear-gradient(135deg, #8b5cf6, #3b82f6);
      box-shadow: 0 0 15px rgba(59, 130, 246, 0.6);
    }

    .logout-btn {
      background: linear-gradient(135deg, #ef4444, #f87171);
      padding: 9px 18px;
      border-radius: 10px;
      color: #fff;
      font-weight: 600;
      text-decoration: none;
    }

    .logout-btn:hover {
      box-shadow: 0 0 15px rgba(239, 68, 68, 0.7);
      transform: translateY(-2px);
    }

    /* 🧱 Layout */
    main {
      display: grid;
      grid-template-columns: 300px 1fr;
      margin-top: 80px;
      gap: 24px;
      flex: 1;
      padding: 25px 40px;
      width: 100%;
      box-sizing: border-box;
    }

    /* 🧍 Sidebar */
    .sidebar {
      background: linear-gradient(180deg, rgba(30, 41, 59, 0.95), rgba(15, 23, 42, 0.98));
      border-radius: 16px;
      padding: 22px;
      box-shadow: 0 0 25px rgba(59, 130, 246, 0.25);
    }

    /* 📊 Stat Box */
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

    .char-stats p {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin: 4px 0;
    }

    .char-stats button {
      background: linear-gradient(135deg, #3b82f6, #9333ea);
      border: none;
      padding: 3px 7px;
      color: white;
      font-size: 0.8rem;
      border-radius: 6px;
      cursor: pointer;
      transition: all 0.2s;
    }

    .char-stats button:hover {
      background: linear-gradient(135deg, #6366f1, #8b5cf6);
      box-shadow: 0 0 10px rgba(147, 51, 234, 0.5);
    }

    /* 🖥 Dashboard Content */
    .dashboard .content {
      background: rgba(17, 24, 39, 0.85);
      border-radius: 18px;
      padding: 25px;
      box-shadow: 0 0 25px rgba(96, 165, 250, 0.3),
        inset 0 0 12px rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(59, 130, 246, 0.2);
      width: 100%;
      flex: 1;
      display: flex;
      flex-direction: column;
      overflow-x: auto;
      transition: all 0.3s ease;
    }

    /* 🪄 ตาราง */
    .dashboard table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      font-size: 0.95rem;
      animation: fadeIn 0.4s ease;
    }

    .dashboard th,
    .dashboard td {
      padding: 12px 16px;
      text-align: left;
      border-bottom: 1px solid rgba(255, 255, 255, 0.05);
      white-space: nowrap;
    }

    .dashboard th {
      background: linear-gradient(135deg, rgba(59, 130, 246, 0.25), rgba(147, 51, 234, 0.25));
      color: #bfdbfe;
      font-weight: 600;
      text-transform: capitalize;
      backdrop-filter: blur(5px);
    }

    .dashboard tr:nth-child(even) td {
      background: rgba(255, 255, 255, 0.02);
    }

    .dashboard tr:hover td {
      background: rgba(255, 255, 255, 0.05);
      transition: background 0.25s;
    }

    /* 🖱 Scrollbar */
    .dashboard .content::-webkit-scrollbar {
      height: 6px;
    }

    .dashboard .content::-webkit-scrollbar-thumb {
      background: linear-gradient(135deg, #6366f1, #9333ea);
      border-radius: 4px;
    }

    .dashboard .content::-webkit-scrollbar-track {
      background: transparent;
    }

    /* 🌈 Animations */
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(5px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* 📍 Footer */
    footer {
      text-align: center;
      padding: 12px;
      background: rgba(17, 24, 39, 0.8);
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      font-size: 14px;
      color: #9ca3af;
    }

    /* 🧝 Dropdown ตัวละคร */
    #charSelect {
      width: 100%;
      padding: 8px 12px;
      background: rgba(17, 24, 39, 0.8);
      border: 1px solid rgba(99, 102, 241, 0.5);
      color: #e2e8f0;
      border-radius: 8px;
      font-size: 14px;
      margin-top: 6px;
      margin-bottom: 12px;
      cursor: pointer;
      transition: all 0.25s ease;
    }

    #charSelect:hover {
      border-color: #818cf8;
      box-shadow: 0 0 8px rgba(129, 140, 248, 0.4);
    }

    #charSelect:focus {
      outline: none;
      border-color: #a78bfa;
      box-shadow: 0 0 12px rgba(167, 139, 250, 0.6);
      background: rgba(30, 41, 59, 0.9);
    }

    #charSelect option {
      background: #0f172a;
      color: #e2e8f0;
    }

    /* 🏰 Dropdown เลือกกิลด์ */
    .guild-select-container {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 12px;
    }

    .guild-select-label {
      font-weight: 600;
      color: #a5b4fc;
      white-space: nowrap;
    }

    .guild-select {
      flex: 1;
      padding: 8px 12px;
      background: rgba(17, 24, 39, 0.8);
      border: 1px solid rgba(99, 102, 241, 0.5);
      color: #e2e8f0;
      border-radius: 8px;
      font-size: 14px;
      cursor: pointer;
      transition: all 0.25s ease;
    }

    .guild-select:hover {
      border-color: #818cf8;
      box-shadow: 0 0 8px rgba(129, 140, 248, 0.4);
    }

    .guild-select:focus {
      outline: none;
      border-color: #a78bfa;
      box-shadow: 0 0 12px rgba(167, 139, 250, 0.6);
      background: rgba(30, 41, 59, 0.9);
    }

    .guild-select option {
      background: #0f172a;
      color: #e2e8f0;
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

      <?php if (count($chars) > 0): ?>
        <div class="char-select-container">
          <label for="charSelect" class="guild-select-label">🧝 ตัวละครของคุณ:</label>
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
          <div class="stat-box">
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

            <?php if ($stats): ?>
              <hr>
              <div class="char-stats">
                <p><strong>STR:</strong> <span id="stat-str"><?= $stats['str'] ?></span> <button onclick="upgradeStat('str')">➕</button></p>
                <p><strong>AGI:</strong> <span id="stat-agi"><?= $stats['agi'] ?></span> <button onclick="upgradeStat('agi')">➕</button></p>
                <p><strong>VIT:</strong> <span id="stat-vit"><?= $stats['vit'] ?></span> <button onclick="upgradeStat('vit')">➕</button></p>
                <p><strong>INT:</strong> <span id="stat-int"><?= $stats['int_stat'] ?></span> <button onclick="upgradeStat('int_stat')">➕</button></p>
                <p><strong>DEX:</strong> <span id="stat-dex"><?= $stats['dex'] ?></span> <button onclick="upgradeStat('dex')">➕</button></p>
                <p><strong>LUK:</strong> <span id="stat-luk"><?= $stats['luk'] ?></span> <button onclick="upgradeStat('luk')">➕</button></p>
              </div>
              <p><strong>📊 Stat Points:</strong> <span id="stat-points"><?= $stats['stat_points'] ?></span></p>
            <?php endif; ?>
          </div>
        <?php endif; ?>
    </aside>

    <section class="dashboard">
      <<article class="content show" id="contentBox">
        <p>เลือกเมนูด้านบนเพื่อดูข้อมูลจริงจากฐานข้อมูล...</p>
        <div id="guildContent"></div>
        </article>
    </section>
  </main>

  <footer>© 2025 Ragnarok Origin Classic</footer>

  <script>
    const box = document.getElementById("contentBox");
    const menuButtons = document.querySelectorAll(".nav-menu button");
    const playerId = <?= $player_id ?>;
    const charSelect = document.getElementById('charSelect');
    const charId = <?= $selectedCharId ?>;

    // 🧭 เปลี่ยนตัวละคร
    function changeCharacter() {
      window.location.href = `user_dashboard.php?char_id=${charSelect.value}`;
    }

    // 🧠 อัป Stat
    function upgradeStat(stat) {
      const points = parseInt(document.getElementById('stat-points').textContent);
      if (points <= 0) {
        alert("❌ ไม่มี Stat Point เหลือ");
        return;
      }

      const formData = new FormData();
      formData.append('char_id', charId);
      formData.append('stat', stat);

      fetch('../api/update_stat.php', {
          method: 'POST',
          body: formData
        })
        .then(res => res.json())
        .then(r => {
          if (r.success) {
            reloadStats();
          } else {
            alert(r.message);
          }
        });
    }

    // ♻️ โหลด stat ใหม่
    function reloadStats() {
      fetch(`../api/get_stats.php?char_id=${charId}`)
        .then(res => res.json())
        .then(data => {
          if (!data.success) return;
          document.getElementById('stat-str').textContent = data.stats.str;
          document.getElementById('stat-agi').textContent = data.stats.agi;
          document.getElementById('stat-vit').textContent = data.stats.vit;
          document.getElementById('stat-int').textContent = data.stats.int;
          document.getElementById('stat-dex').textContent = data.stats.dex;
          document.getElementById('stat-luk').textContent = data.stats.luk;
          document.getElementById('stat-points').textContent = data.points;
        });
    }

    // 🏰 Render Guild
    function renderGuild(guilds) {
      const select = document.getElementById('guildSelect');
      const selectedId = select.value;
      const g = guilds.find(x => x.guild_id == selectedId);
      if (!g) return;

      let html = `
      <h2>🏰 ${g.guild_name}</h2>
      <p>👑 หัวหน้า: ${g.leader_name ?? '-'}</p>
      <p>📜 คำอธิบาย: ${g.description ?? '-'}</p>
      <p>🕒 วันที่สร้าง: ${g.creation_date ?? '-'}</p>
      <hr>
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
        const icon = m.role === 'Leader' ? '👑' : '👤';
        const roleStyle = m.role === 'Leader' ? "style='color:#facc15;font-weight:bold;'" : '';
        html += `
        <tr ${roleStyle}>
          <td>${m.character_name}</td>
          <td>${m.player_name}</td>
          <td>${icon} ${m.role}</td>
        </tr>`;
      });

      html += "</tbody></table>";
      document.getElementById('guildContent').innerHTML = html;
    }

    // 🧭 เมนูด้านบน
    menuButtons.forEach(btn => {
      btn.addEventListener("click", async () => {
        // Active Toggle
        menuButtons.forEach(b => b.classList.remove("active"));
        btn.classList.add("active");

        const api = btn.dataset.api;
        const selectedChar = charSelect ? charSelect.value : charId;
        box.classList.remove("show");
        box.innerHTML = "<p style='color:gray;'>⏳ กำลังโหลดข้อมูล...</p>";

        try {
          const res = await fetch(`../api/${api}.php?player_id=${playerId}&char_id=${selectedChar}`);
          const result = await res.json();

          // 🏰 กรณีเมนูกิลด์
          if (api === "get_guilds") {
            const guilds = result.data.guilds;
            if (!guilds || guilds.length === 0) {
              box.innerHTML = `<p style='color:#f87171;'>❌ ไม่มีข้อมูลกิลด์</p>`;
              return;
            }

            let guildSelectHTML = `
            <div class="guild-select-container">
              <span class="guild-select-label">🏰 เลือกกิลด์:</span>
              <select id="guildSelect" class="guild-select"
                onchange='renderGuild(${JSON.stringify(guilds).replace(/"/g, "&quot;")})'>
          `;
            guilds.forEach(g => {
              guildSelectHTML += `<option value="${g.guild_id}">${g.guild_name}</option>`;
            });
            guildSelectHTML += `</select></div><hr><div id="guildContent"></div>`;

            box.innerHTML = guildSelectHTML;
            renderGuild(guilds);
            setTimeout(() => box.classList.add("show"), 50);
            return;
          }

          // 📦 เมนูอื่น (Item / Quests / Skills)
          if (!result || result.success === false) {
            box.innerHTML = `<p style='color:#f87171;'>❌ ${result.message || result.error || "โหลดข้อมูลล้มเหลว"}</p>`;
          } else if (!Array.isArray(result.data) || result.data.length === 0) {
            box.innerHTML = "<p style='color:gray;'>ไม่มีข้อมูลในส่วนนี้</p>";
          } else {
            let html = "<table><thead><tr>";
            Object.keys(result.data[0]).forEach(k => html += `<th>${k}</th>`);
            html += "</tr></thead><tbody>";

            result.data.forEach(row => {
              html += "<tr>";
              Object.values(row).forEach(v => html += `<td>${v ?? '-'}</td>`);
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
  </script>

</body>

</html>