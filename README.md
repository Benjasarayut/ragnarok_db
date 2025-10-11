# ragnarok_db

คุณสมบัติหลัก

🔐 Login/Logout (session)

🧙‍♀️ แดชบอร์ดผู้เล่น: ตัวละครหลัก, เลเวล/EXP/HP/MP, เงิน (Zenny)

🧩 เมนูย่อย: Items, Equipment, Inventory, Skills, Pets, Guild, Awakening Logs, Quests

⚙️ API ตอบ JSON เท่านั้น (กัน error “Unexpected token ‘<’ … not valid JSON”)

🧷 ใช้ Prepared Statements ทุกจุด

```csharp

โครงสร้างโปรเจกต์
ragnarok_db/
├─ db.php                  # เชื่อมต่อฐานข้อมูล (อย่า echo/print ที่นี่)
├─ README.md
├─ ragnarok_origin_classic.sql   # (ถ้ามี) สคริปต์สร้างสคีมา/ข้อมูลตัวอย่าง
├─ public/
│  ├─ login.html
│  └─ styles/
├─ auth/
│  ├─ login.php            # รับ POST -> JSON
│  └─ logout.php
├─ user/
│  └─ user_dashboard.php   # UI หลัก (เรียก API เป็น JSON)
└─ api/
   ├─ get_items.php
   ├─ get_equipment.php
   ├─ get_inventory.php
   ├─ get_character_skills.php
   ├─ get_pets.php
   ├─ get_guilds.php
   └─ get_awakening_logs.php

```
ความต้องการระบบ

PHP 8.x (แนะนำ XAMPP บน Windows)

MySQL/MariaDB

Apache (mod_php)

การติดตั้ง & ตั้งค่า

โคลน/คัดลอกโปรเจกต์ ไปที่ C:\xampp\htdocs\ragnarok_db

สร้างฐานข้อมูล

```

CREATE DATABASE ragnarok_origin_classic
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

```

อัปเดตการเชื่อมต่อใน db.php
```
<?php
$servername = "localhost";
$username   = "root";
$password   = "";               // ถ้ามีรหัสให้ใส่
$dbname     = "ragnarok_origin_classic";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset('utf8mb4');
if ($conn->connect_error) {
    // ห้าม echo / print อะไรในไฟล์นี้
    http_response_code(500);
    exit;
}
?>

```
⚠️ สำคัญ: db.php ห้ามมี echo, var_dump, หรือ HTML ใด ๆ เพราะทุก API จะรวมไฟล์นี้และต้องคืนค่าเป็น JSON เท่านั้น

นำเข้าตารางและตัวอย่างข้อมูล

หากมีไฟล์ ragnarok_origin_classic.sql ให้ Import ผ่าน phpMyAdmin

หรือใช้ DDL/INSERT ที่ทีมให้ไว้ในเอกสาร SRS

เริ่มต้น Apache/MySQL แล้วเปิด

http://localhost/ragnarok_db/public/login.html

วิธีใช้งาน (Dev/ทดสอบเร็ว)

ล็อกอินด้วยอีเมลผู้เล่นที่อยู่ในตาราง players

โค้ด login เวอร์ชัน dev อนุญาตรหัสผ่าน 123456 (ถ้าเปิดโหมด master password)

เข้าแดชบอร์ด → คลิกปุ่มเมนูด้านบนเพื่อยิง API และเรนเดอร์ตารางจาก JSON

สคีมา (ย่อ)

ตารางหลักที่ระบบเรียกใช้บ่อย:

players(player_id PK, username UNIQUE, email UNIQUE, password_hash, created_at)

characters(char_id PK, player_id FK, name, level, exp, hp, mp, zenny, created_at)

items(item_id PK, name UNIQUE, item_type ENUM, description)

inventories(player_id FK, item_id FK, quantity) – คลังระดับ ผู้เล่น

equipment(char_id FK, slot ENUM, item_id FK) – 1 ช่อง/ตัวละคร 1 ชิ้น (PK (char_id, slot))

skills, character_skills(char_id, skill_id, level)

pets(pet_id PK, owner_id FK players, name, species, level, happiness)

guilds(guild_id PK, guild_name, leader_name, creation_date),
guild_members(guild_id FK, char_id FK, role)

awakenings(char_id FK, awake_level, awakened)

quests_progress(char_id FK, quest_name, status, progress_percent)

หมายเหตุ: field name ต้องตรงกับฐานจริง เช่น items.name (ไม่ใช่ item_name)

ตัวอย่าง SQL ที่หน้า phpMyAdmin (ใช้ตัวแปร ไม่ใช้ ?)

ก่อนรัน ตั้งค่าตัวแปร:
```

SET @player_id := 1;
SET @char_id   := 2;
```

1) คลังไอเท็มของผู้เล่น (join รายการไอเท็ม)
```
SELECT it.item_id,
       it.name      AS item_name,
       it.item_type,
       it.description,
       i.quantity
FROM inventories AS i
JOIN items       AS it ON it.item_id = i.item_id
WHERE i.player_id = @player_id
ORDER BY i.quantity DESC, it.name
LIMIT 25;
```
3) อุปกรณ์ที่สวมใส่ของตัวละคร (เรียงตามลำดับสล็อต)
```
SELECT e.slot, e.item_id, it.name AS item_name
FROM equipment e
JOIN items it ON it.item_id = e.item_id
WHERE e.char_id = @char_id
ORDER BY FIELD(
  e.slot,'weapon','shield','head','armor','garment','shoes','accessory1','accessory2'
);
```
5) กิลด์ของตัวละคร + จำนวนสมาชิก
```
SELECT g.guild_id, g.guild_name, gm.role,
       (SELECT COUNT(*) FROM guild_members gm2 WHERE gm2.guild_id = g.guild_id) AS members
FROM guild_members gm
JOIN guilds g ON g.guild_id = gm.guild_id
WHERE gm.char_id = @char_id;
```
7) Awakening
```
SELECT awake_level, awakened
FROM awakenings
WHERE char_id = @char_id;
```
9) ความคืบหน้าเควสต์ (ตามสคีมาปัจจุบัน)
```
SELECT quest_name, status, progress_percent
FROM quests_progress
WHERE char_id = @char_id
ORDER BY quest_name
LIMIT 25;
```
ข้อควรระวัง / แก้ปัญหาทั่วไป

JSON แตก / “Unexpected token ‘<’ … not valid JSON”

ปิดการแสดง error HTML ใน production:
php.ini → display_errors=Off, และ log ไปที่ไฟล์แทน

ห้าม echo อะไรใน db.php และทุกไฟล์ API ต้องขึ้นต้นด้วย
header('Content-Type: application/json; charset=UTF-8');

คอลัมน์ไม่ตรง: ตรวจด้วย

SHOW COLUMNS FROM <table>;


แล้วปรับชื่อคอลัมน์ใน query ให้ตรง (items.name ไม่ใช่ item_name)

Prepared Statements ใน phpMyAdmin: ใช้ @player_id, @char_id แทน ?

FK/ENUM/INDEX: ถ้าสร้างสคีมาตาม DDL ที่ให้ จะลดข้อผิดพลาด join/lookup มาก

API สัญญา (สั้น)

ทุกไฟล์ใน api/*.php ต้องตอบรูปแบบนี้เสมอ:

```

{
  "success": true,
  "data": [ ... ]      // หรือ object ตามกรณี
}


กรณีผิดพลาด:

{
  "success": false,
  "error": "ข้อความอธิบายสั้น ๆ"
}
```
งานที่เหลือ/ปรับแต่งได้

เปลี่ยนระบบรหัสผ่านให้ใช้ password_hash()/password_verify()

เพิ่ม Pagination/ค้นหาในแต่ละเมนู

เพิ่ม Role admin + หน้าจอจัดการข้อมูล

เขียน Unit Test (PHPUnit) สำหรับ API
