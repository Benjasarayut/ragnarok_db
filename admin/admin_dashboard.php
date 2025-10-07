<?php
session_start();
include("../db.php");

// ✅ ตรวจสอบสิทธิ์การเข้าถึง
if (!isset($_SESSION['admin_name'])) {
  header("Location: ../public/login.html");
  exit();
}

$admin = $_SESSION['admin_name'];

// ✅ ตัวแปรเริ่มต้น
$totalPlayers = 0;
$totalBanned = 0;
$newCharsToday = 0;

// ✅ ดึงจำนวนผู้เล่นทั้งหมด
$q1 = $conn->query("SELECT COUNT(*) AS total FROM players");
if ($q1 && $row = $q1->fetch_assoc()) {
  $totalPlayers = $row['total'];
} else {
  echo "<!-- ⚠ SQL Error (players): {$conn->error} -->";
}

// ✅ ดึงจำนวนผู้เล่นที่ถูกแบน
$q2 = $conn->query("SELECT COUNT(*) AS total FROM bans");
if ($q2 && $row = $q2->fetch_assoc()) {
  $totalBanned = $row['total'];
} else {
  echo "<!-- ⚠ SQL Error (bans): {$conn->error} -->";
}

// ✅ ดึงจำนวนตัวละครที่สร้างวันนี้
$q3 = $conn->query("SELECT COUNT(*) AS total FROM characters WHERE DATE(created_at)=CURDATE()");
if ($q3 && $row = $q3->fetch_assoc()) {
  $newCharsToday = $row['total'];
} else {
  echo "<!-- ⚠ SQL Error (characters): {$conn->error} -->";
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard | Ragnarok Origin Classic</title>
  <style>
    * {margin: 0; padding: 0; box-sizing: border-box; font-family: "Poppins", sans-serif;}
    body {
      min-height: 100vh;
      display: flex; flex-direction: column;
      background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
      color: #e2e8f0;
    }
    header {
      display: flex; justify-content: space-between; align-items: center;
      background: rgba(15, 23, 42, 0.9);
      padding: 15px 40px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.5);
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    header h1 {font-size: 1.3rem; color: #facc15; display: flex; align-items: center; gap: 10px;}
    nav a {margin-left: 25px; color: #94a3b8; text-decoration: none; transition: 0.3s;}
    nav a:hover {color: #f87171;}
    nav a.logout {color: #ef4444;}

    main {display: flex; gap: 25px; flex: 1; padding: 25px;}

    /* Sidebar */
    .sidebar {
      width: 240px;
      background: rgba(255,255,255,0.05);
      border-radius: 15px;
      padding: 25px;
      box-shadow: inset 0 0 10px rgba(255,255,255,0.05);
    }
    .sidebar h2 {
      text-align: center; color: #93c5fd; margin-bottom: 20px;
    }
    .sidebar button {
      display: flex; align-items: center; gap: 10px;
      width: 100%; padding: 12px 15px; margin-bottom: 10px;
      border: none; border-radius: 8px;
      background: rgba(255,255,255,0.07); color: #e2e8f0;
      cursor: pointer; transition: all 0.25s ease; font-weight: 500;
    }
    .sidebar button:hover {
      background: #2563eb; color: #fff; transform: translateX(3px);
    }

    /* Content */
    .content {
      flex: 1;
      display: flex; flex-direction: column; gap: 20px;
      background: rgba(255,255,255,0.05);
      padding: 25px; border-radius: 15px;
      box-shadow: 0 8px 30px rgba(0,0,0,0.4);
    }
    .content h3 {
      color: #bfdbfe; text-align: center; font-size: 22px; font-weight: 600; margin-bottom: 15px;
    }
    .dashboard-grid {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 20px;
    }
    .welcome-box, .stats-box {
      background: rgba(255,255,255,0.08);
      border-radius: 10px;
      padding: 20px;
      line-height: 1.7;
      box-shadow: 0 0 10px rgba(0,0,0,0.3);
    }
    .stats-box h4 {color: #60a5fa; margin-bottom: 10px;}
    .stats-box span {display: block; color: #facc15; font-weight: 600;}

    footer {
      background: rgba(0,0,0,0.5);
      text-align: center;
      padding: 15px;
      color: #94a3b8;
      font-size: 14px;
    }

    /* Responsive */
    @media (max-width: 900px) {
      main {flex-direction: column;}
      .dashboard-grid {grid-template-columns: 1fr;}
    }
  </style>
</head>
<body>

  <header>
    <h1>👑 Ragnarok Admin Dashboard</h1>
    <nav>
      <a href="#">Home</a>
      <a href="../auth/logout.php" class="logout">Logout</a>
    </nav>
  </header>

  <main>
    <aside class="sidebar">
  <h2>Admin</h2>
  <button onclick="location.href='../api/ban_players.php'">🔒 แบนผู้เล่น</button>
  <button onclick="location.href='../api/create_character.php'">⚔️ สร้างตัวละคร</button>
  <button onclick="location.href='../api/edit_character.php'">🧩 แก้ไขตัวละคร</button>
  <button onclick="location.href='../api/messages.php'">💬 ข้อความ</button>
</aside>


    <section class="content">
      <h3>สวัสดีคุณแอดมิน, <?= htmlspecialchars($admin) ?> 👋</h3>

      <div class="dashboard-grid">
        <div class="welcome-box">
          <p>ยินดีต้อนรับสู่แดชบอร์ดผู้ดูแลระบบ <strong>Ragnarok Origin Classic</strong>.<br>
          คุณสามารถจัดการข้อมูลผู้เล่น แบน/ปลดแบน ตรวจสอบกิจกรรม และส่งข้อความถึงผู้เล่นได้ที่นี่</p>
        </div>

        <div class="stats-box">
          <h4>📊 สถิติระบบ</h4>
          <span>ผู้เล่นทั้งหมด: <?= $totalPlayers ?></span>
          <span>ถูกแบน: <?= $totalBanned ?></span>
          <span>ตัวละครใหม่วันนี้: <?= $newCharsToday ?></span>
        </div>
      </div>
    </section>
  </main>

  <footer>
    © 2025 Ragnarok Origin Classic
  </footer>
</body>
</html>
