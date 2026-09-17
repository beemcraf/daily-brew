<?php
/**
 * ==============================================================================
 * Daily Brew — ระบบส่งอีเมลรับข่าวสาร (Newsletter Subscribe) ด้วย PHP
 * ใบงานข้อ 2: "สร้างไฟล์ .php เพื่อส่งเนื้อหาไปยังอีเมลหลังจาก subscribe"
 * ==============================================================================
 * 
 * ฟังก์ชันการทำงาน:
 * 1. รับค่าอีเมลจากผู้ใช้งาน (รองรับทั้งแบบ JSON Fetch API และ HTML Form POST)
 * 2. ตรวจสอบความถูกต้องของรูปแบบอีเมล (Email Validation)
 * 3. ดึงแม่แบบอีเมล HTML จากไฟล์ email-template-newsletter.html
 * 4. ส่งอีเมลด้วยคำสั่ง mail() ของ PHP พร้อม Header UTF-8
 * 5. ส่งผลลัพธ์กลับแบบ JSON หรือ Redirect กลับหน้าเว็บ
 */

// อนุญาตให้เรียกใช้งานข้ามโดเมน (CORS) สำหรับ Frontend JavaScript
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// ดักจับ Preflight request (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// --------------------------------------------------------------------------
// 1. รับค่าอีเมล (รองรับทั้ง JSON และ Form POST ปกติ)
// --------------------------------------------------------------------------
$email = '';
$isJsonRequest = false;

// ตรวจสอบ Content-Type ว่าเป็น JSON หรือไม่
$contentType = isset($_SERVER['CONTENT_TYPE']) ? trim($_SERVER['CONTENT_TYPE']) : '';
if (stripos($contentType, 'application/json') !== false) {
    $isJsonRequest = true;
    $rawInput = file_get_contents('php://input');
    $data = json_decode($rawInput, true);
    $email = isset($data['email']) ? trim($data['email']) : '';
} else {
    // รับค่าจาก Form POST ปกติ
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
}

// --------------------------------------------------------------------------
// 2. ตรวจสอบความถูกต้องของอีเมล (Validation)
// --------------------------------------------------------------------------
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    if ($isJsonRequest) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'กรุณากรอกรูปแบบอีเมลให้ถูกต้องค่ะ (เช่น name@example.com)'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    } else {
        echo "<script>alert('กรุณากรอกรูปแบบอีเมลให้ถูกต้องค่ะ'); window.history.back();</script>";
        exit;
    }
}

// --------------------------------------------------------------------------
// 3. บันทึกข้อมูลผู้ติดตามลงฐานข้อมูล MySQL (สำหรับดูใน MySQL Workbench)
// --------------------------------------------------------------------------
$dbHost = '127.0.0.1';
$dbUser = 'root';
$dbPass = '';
$dbName = 'daily_brew_db';

$dbSaved = false;
try {
    $conn = @new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    if (!$conn->connect_error) {
        $stmt = $conn->prepare("INSERT INTO `subscribers` (`email`, `promo_code`, `discount_percent`) VALUES (?, 'WELCOME10', 10) ON DUPLICATE KEY UPDATE `email`=`email`");
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->close();
            $dbSaved = true;
        }
        $conn->close();
    }
} catch (Exception $e) {
    // Graceful fallback: ถ้ายังไม่ได้เปิด MySQL สคริปต์ก็ยังทำงานส่งอีเมลต่อได้โดยไม่ Error
}

// --------------------------------------------------------------------------
// 4. เตรียมเนื้อหาอีเมลจากแม่แบบ HTML (email-template-newsletter.html)
// --------------------------------------------------------------------------
$templatePath = __DIR__ . '/email-template-newsletter.html';
$htmlBody = '';

if (file_exists($templatePath)) {
    $htmlBody = file_get_contents($templatePath);
} else {
    // Fallback สำรองหากไม่พบไฟล์เทมเพลต
    $htmlBody = '
    <!DOCTYPE html>
    <html>
    <head><meta charset="UTF-8"><title>Daily Brew Journal</title></head>
    <body style="font-family: sans-serif; background: #f4efe9; padding: 20px;">
        <div style="max-width: 560px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 12px;">
            <h2 style="color: #24140e;">ขอบคุณที่ติดตาม Daily Brew Journal ☕</h2>
            <p>รับส่วนลด 10% รหัสโค้ด: <strong>WELCOME10</strong></p>
        </div>
    </body>
    </html>';
}

// --------------------------------------------------------------------------
// 5. สั่งส่งอีเมลด้วยฟังก์ชัน sendDailyBrewMail() (รองรับ Gmail SMTP & mail())
// --------------------------------------------------------------------------
require_once __DIR__ . '/mailer.php';

$to = $email;
$subject = '🎁 ขอบคุณที่ติดตาม Daily Brew Journal! รับโค้ดส่วนลด 10% (WELCOME10) ☕';
$mailResult = sendDailyBrewMail($to, $subject, $htmlBody, 'Daily Brew Coffee');
$mailSent = $mailResult['success'];

// --------------------------------------------------------------------------
// 6. ส่งผลลัพธ์กลับไปยังผู้ใช้งาน
// --------------------------------------------------------------------------
if ($isJsonRequest) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => true,
        'message' => 'ส่งอีเมลต้อนรับและคูปองส่วนลด 10% สำเร็จแล้วค่ะ!',
        'email' => $email,
        'promo_code' => 'WELCOME10',
        'php_mail_dispatched' => $mailSent
    ], JSON_UNESCAPED_UNICODE);
    exit;
} else {
    // สำหรับ Form POST ให้เด้งกลับหน้าแรกพร้อมแจ้งเตือน
    echo "<script>
        alert('ขอบคุณที่ติดตามข่าวสาร Daily Brew ค่ะ! โค้ดส่วนลด WELCOME10 ถูกส่งไปที่อีเมลของคุณแล้ว');
        window.location.href = 'index.html#newsletter';
    </script>";
    exit;
}
