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

// ✅ ดึงข้อมูลตัวละครหลัก
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
    gm.role AS guild_role,
    (SELECT COUNT(*) FROM guild_members gm2 WHERE gm2.guild_id = g.guild_id) AS guild_members,
    p.name AS pet_name,
    p.species AS pet_species,
    p.level AS pet_level,
    p.happiness AS pet_happiness
  FROM characters c
  LEFT JOIN classes cls ON c.class_id = cls.class_id
  LEFT JOIN awakenings aw ON c.char_id = aw.char_id
  LEFT JOIN guild_members gm ON c.player_id = gm.player_id
  LEFT JOIN guilds g ON gm.guild_id = g.guild_id
  LEFT JOIN pets p ON c.player_id = p.owner_id
  WHERE c.player_id = ?
  ORDER BY c.level DESC
  LIMIT 1
");
if (!$stmt) die('❌ SQL Error: ' . $conn->error);
$stmt->bind_param("i", $player_id);
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
  background: radial-gradient(circle at 30% 10%, rgba(99,102,241,0.25), transparent 60%),
              radial-gradient(circle at 70% 80%, rgba(147,51,234,0.25), transparent 60%);
  z-index: -1;
}

/* ===== HEADER ===== */
header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 14px 40px;
  background: rgba(17, 24, 39, 0.95);
  border-bottom: 1px solid rgba(255,255,255,0.1);
  box-shadow: 0 4px 20px rgba(0,0,0,0.6);
  backdrop-filter: blur(8px);
}

header h1 {
  font-size: 1.5rem;
  color: #a5b4fc;
  text-shadow: 0 0 10px rgba(165,180,252,0.9);
  white-space: nowrap;
}

.nav-menu {
  display: flex;
  align-items: center;
  gap: 10px;
}

/* ปุ่มเมนูหลัก */
.nav-menu button {
  background: linear-gradient(135deg, #3b82f6, #9333ea);
  border: none;
  padding: 9px 16px;
  border-radius: 10px;
  color: white;
  font-weight: 600;
  box-shadow: 0 0 10px rgba(147,51,234,0.3);
  cursor: pointer;
  transition: all 0.25s ease;
}
.nav-menu button:hover {
  transform: translateY(-2px);
  background: linear-gradient(135deg, #6366f1, #8b5cf6);
  box-shadow: 0 0 20px rgba(147,51,234,0.6);
}
.nav-menu button.active {
  background: linear-gradient(135deg, #8b5cf6, #3b82f6);
  box-shadow: 0 0 25px rgba(59,130,246,0.7);
  text-shadow: 0 0 8px rgba(255,255,255,0.7);
}

/* 🌸 ปุ่ม Logout แบบ Gradient Glow */
.logout-btn {
  background: linear-gradient(135deg, #ef4444, #f87171);
  padding: 9px 18px;
  border-radius: 10px;
  color: #fff;
  font-weight: 600;
  box-shadow: 0 0 12px rgba(239,68,68,0.5);
  text-decoration: none;
  transition: all 0.25s ease;
}
.logout-btn:hover {
  background: linear-gradient(135deg, #f87171, #fb7185);
  box-shadow: 0 0 20px rgba(248,113,113,0.7);
  transform: translateY(-2px);
}

/* ========== MAIN LAYOUT ========== */
main {
  display: grid;
  grid-template-columns: 300px 1fr;
  gap: 24px;
  flex: 1;
  padding: 25px 40px;
}

/* Sidebar */
.sidebar {
  background: linear-gradient(180deg, rgba(30,41,59,0.95), rgba(15,23,42,0.98));
  border-radius: 16px;
  padding: 22px;
  box-shadow: 0 0 25px rgba(59,130,246,0.2);
}
.sidebar h2 {
  color: #7dd3fc;
  margin-bottom: 10px;
  text-shadow: 0 0 6px rgba(125,211,252,0.6);
}
.username {
  background: rgba(255,255,255,0.05);
  padding: 10px 18px;
  border-radius: 10px;
  font-weight: 600;
  color: #f1f5f9;
  text-align: center;
  margin-bottom: 15px;
}

/* Character Stats */
.stat-box {
  background: rgba(255,255,255,0.05);
  border-radius: 12px;
  padding: 15px;
  box-shadow: inset 0 0 8px rgba(0,0,0,0.4);
}
.stat-box p {
  margin: 6px 0;
  display: flex;
  justify-content: space-between;
  font-size: 14px;
}
.bar {
  background: rgba(255,255,255,0.1);
  border-radius: 8px;
  overflow: hidden;
  height: 8px;
  margin-top: 4px;
}
.bar span { display: block; height: 100%; border-radius: 8px; }
.exp span { background: #fde047; width: 70%; }
.hp span { background: #ef4444; width: 50%; }
.mp span { background: #3b82f6; width: 40%; }

/* ========== Dashboard ========== */
.dashboard {
  background: rgba(17,24,39,0.85);
  border-radius: 18px;
  padding: 25px;
  box-shadow: 0 0 25px rgba(96,165,250,0.3), inset 0 0 12px rgba(255,255,255,0.05);
  border: 1px solid rgba(59,130,246,0.2);
  overflow: hidden;
}

.content {
  background: rgba(255,255,255,0.04);
  border-radius: 12px;
  padding: 15px;
  box-shadow: inset 0 0 15px rgba(0,0,0,0.3);
  backdrop-filter: blur(6px);
  opacity: 0;
  transform: translateY(5px);
  transition: opacity 0.5s ease, transform 0.5s ease;
}
.content.show {
  opacity: 1;
  transform: translateY(0);
}
.content table {
  width: 100%;
  border-collapse: collapse;
}
.content th, .content td {
  padding: 8px 10px;
  border-bottom: 1px solid rgba(255,255,255,0.1);
}
.content th {
  background: rgba(59,130,246,0.2);
  color: #bfdbfe;
}
.content tr:hover { background: rgba(255,255,255,0.05); }

/* Footer */
footer {
  text-align: center;
  padding: 12px;
  background: rgba(17,24,39,0.8);
  border-top: 1px solid rgba(255,255,255,0.1);
  font-size: 14px;
  color: #9ca3af;
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

menuButtons.forEach(btn => {
  btn.addEventListener("click", async () => {
    // Highlight Active
    menuButtons.forEach(b => b.classList.remove("active"));
    btn.classList.add("active");

    // Load Data
    const api = btn.dataset.api;
    box.classList.remove("show");
    box.innerHTML = "<p style='color:gray;'>⏳ กำลังโหลดข้อมูล...</p>";

    try {
      const res = await fetch(`../api/${api}.php?player_id=<?= $player_id ?>`);
      const result = await res.json();

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

    // Fade-in
    setTimeout(() => box.classList.add("show"), 50);
  });
});
</script>
</body>
</html>
