<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'player') {
    header("Location: ../public/login.html");
    exit;
}
$username = $_SESSION['username'] ?? 'ผู้เล่น';
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>Player Dashboard</title>
<link rel="stylesheet" href="../styles.css">
</head>
<body>
<header>
  <h1>🏠 Home</h1>
  <a href="../auth/logout.php" class="logout">Logout</a>
</header>

<section class="layout">
  <aside class="sidebar">
    <h3>ชื่อผู้ใช้</h3>
    <p><?= htmlspecialchars($username) ?></p>
  </aside>

  <main class="content">
    <h2>คำสั่งหลัก</h2>
    <div class="menu">
      <button>Item</button>
      <button>ของสวมใส่</button>
      <button>เควส</button>
      <button>ของดรอป</button>
    </div>
  </main>
</section>

<footer>© 2025 Ragnarok Origin Classic</footer>
</body>
</html>
