-- ==============================================================================
-- Daily Brew — โครงสร้างฐานข้อมูล MySQL (Database Schema)
-- สำหรับเปิดและรันในโปรแกรม MySQL Workbench
-- ==============================================================================

-- 1. สร้างฐานข้อมูล (Database)
CREATE DATABASE IF NOT EXISTS `daily_brew_db`
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `daily_brew_db`;

-- ------------------------------------------------------------------------------
-- 2. ตาราง subscribers: เก็บข้อมูลผู้ติดตามข่าวสารและรับคูปอง 10% (ใบงานข้อ 1 & 2)
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `subscribers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `promo_code` VARCHAR(50) DEFAULT 'WELCOME10',
    `discount_percent` INT DEFAULT 10,
    `subscribed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 3. ตาราง vip_members: เก็บข้อมูลสมาชิก Daily Brew Club VIP Gold (ใบงานข้อ 3 & 4)
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `vip_members` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `member_id` VARCHAR(50) NOT NULL UNIQUE,       -- เช่น DB-2026-VIP-8842
    `fullname` VARCHAR(150) NOT NULL,              -- ชื่อ-นามสกุล
    `phone` VARCHAR(30) NOT NULL,                  -- เบอร์โทรศัพท์
    `email` VARCHAR(255) NOT NULL,                 -- อีเมล
    `birthday` DATE NULL,                          -- วันเกิด (สำหรับรับสิทธิ์ฟรีเดือนเกิด)
    `favorite_coffee` VARCHAR(100) DEFAULT 'Signature Dirty', -- กาแฟที่ชอบ (Personalized)
    `milk_type` VARCHAR(50) DEFAULT 'Whole Milk',  -- ประเภทนมที่ชอบ
    `address` TEXT NULL,                           -- ที่อยู่จัดส่ง
    `points` INT DEFAULT 100,                      -- แต้มสะสมเริ่มต้น (100 PTS)
    `promo_code` VARCHAR(50) DEFAULT 'DAILYVIP20', -- โค้ดส่วนลดประจำตัว 20%
    `recommended_product` VARCHAR(255) NULL,       -- เมนูที่ระบบแนะนำเฉพาะบุคคล (ข้อ 3)
    `status` VARCHAR(20) DEFAULT 'ACTIVE',         -- สถานะบัตร
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_member_id` (`member_id`),
    INDEX `idx_vip_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 4. ข้อมูลตัวอย่างเริ่มต้น (Sample Mock Data) เพื่อให้เปิดดูใน Workbench แล้วเห็นข้อมูลทันที
-- ------------------------------------------------------------------------------
INSERT INTO `subscribers` (`email`, `promo_code`, `discount_percent`)
VALUES 
    ('coffee.lover@example.com', 'WELCOME10', 10),
    ('barista.fan@example.com', 'WELCOME10', 10)
ON DUPLICATE KEY UPDATE `email`=`email`;

INSERT INTO `vip_members` (
    `member_id`, `fullname`, `phone`, `email`, `birthday`, 
    `favorite_coffee`, `milk_type`, `address`, `points`, `promo_code`, `recommended_product`
)
VALUES 
    (
        'DB-2026-VIP-1001', 
        'ณภัทร พิเศษสมบูรณ์', 
        '081-234-5678', 
        'beem.dailybrew@example.com', 
        '2000-05-15', 
        'Signature Dirty', 
        'Whole Milk', 
        '123 ถนนสุขุมวิท เขตวัฒนา กรุงเทพฯ 10110', 
        100, 
        'DAILYVIP20',
        'Military Dirty Matcha & Basque Burnt Cheesecake'
    )
ON DUPLICATE KEY UPDATE `member_id`=`member_id`;

-- คำสั่งสำหรับตรวจดูข้อมูลหลังรันเสร็จ
-- SELECT * FROM `subscribers`;
-- SELECT * FROM `vip_members`;
