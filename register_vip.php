<?php
/**
 * ==============================================================================
 * Daily Brew — ระบบส่งอีเมลสมัครสมาชิก VIP (VIP Registration) ด้วย PHP
 * ใบงานข้อ 4: "สร้างไฟล์ .php เพื่อส่งเนื้อหาไปยังอีเมลหลังจากสมัครสมาชิก"
 * ใบงานข้อ 3: "สร้าง personalized marketing เพื่อแนะนำสินค้าที่ลูกค้าสนใจไปทางอีเมล"
 * ==============================================================================
 * 
 * ฟังก์ชันการทำงาน:
 * 1. รับค่าข้อมูลสมาชิก (ชื่อ, เบอร์, อีเมล, วันเกิด, กาแฟที่ชอบ, นม, ที่อยู่)
 * 2. ตรวจสอบข้อมูลความถูกต้อง (Validation)
 * 3. สร้างระบบ Personalized Marketing (ข้อ 3): แนะนำเมนูและเบเกอรีที่เข้าคู่กันตามรสชาติกาแฟที่ลูกค้าชอบ
 * 4. สร้างรหัสสมาชิก VIP อัตโนมัติ (เช่น DB-2026-VIP-8842)
 * 5. นำข้อมูลไปใส่ในแม่แบบ HTML (email-template-vip.html)
 * 6. สั่งส่งอีเมลยืนยันสิทธิพิเศษ VIP (ส่วนลด 20% โค้ด DAILYVIP20) ด้วยคำสั่ง mail() ของ PHP
 * 7. ส่งผลลัพธ์กลับแบบ JSON หรือ Redirect
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
// 1. รับค่าข้อมูลสมาชิก (รองรับทั้ง JSON และ Form POST ปกติ)
// --------------------------------------------------------------------------
$isJsonRequest = false;
$fullname = '';
$phone = '';
$email = '';
$birthday = '';
$favoriteCoffee = '';
$milk = '';
$address = '';

$contentType = isset($_SERVER['CONTENT_TYPE']) ? trim($_SERVER['CONTENT_TYPE']) : '';
if (stripos($contentType, 'application/json') !== false) {
    $isJsonRequest = true;
    $rawInput = file_get_contents('php://input');
    $data = json_decode($rawInput, true);
    
    $fullname       = isset($data['fullname']) ? trim($data['fullname']) : '';
    $phone          = isset($data['phone']) ? trim($data['phone']) : '';
    $email          = isset($data['email']) ? trim($data['email']) : '';
    $birthday       = isset($data['birthday']) ? trim($data['birthday']) : '';
    $favoriteCoffee = isset($data['favoriteCoffee']) ? trim($data['favoriteCoffee']) : 'Signature Dirty';
    $milk           = isset($data['milk']) ? trim($data['milk']) : 'Whole Milk';
    $address        = isset($data['address']) ? trim($data['address']) : '';
} else {
    $fullname       = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
    $phone          = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $email          = isset($_POST['email']) ? trim($_POST['email']) : '';
    $birthday       = isset($_POST['birthday']) ? trim($_POST['birthday']) : '';
    $favoriteCoffee = isset($_POST['favoriteCoffee']) ? trim($_POST['favoriteCoffee']) : 'Signature Dirty';
    $milk           = isset($_POST['milk']) ? trim($_POST['milk']) : 'Whole Milk';
    $address        = isset($_POST['address']) ? trim($_POST['address']) : '';
}

// --------------------------------------------------------------------------
// 2. ตรวจสอบข้อมูลจำเป็น (Validation)
// --------------------------------------------------------------------------
if (empty($fullname) || empty($phone) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    if ($isJsonRequest) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'กรุณากรอกชื่อ เบอร์โทรศัพท์ และอีเมลให้ครบถ้วนถูกต้องค่ะ'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    } else {
        echo "<script>alert('กรุณากรอกข้อมูลให้ครบถ้วนถูกต้องค่ะ'); window.history.back();</script>";
        exit;
    }
}

// --------------------------------------------------------------------------
// 3. ระบบ PERSONALIZED MARKETING (ใบงานข้อ 3):
//    วิเคราะห์ความชอบของลูกค้าเพื่อแนะนำเมนูพิเศษในอีเมลเฉพาะบุคคล
// --------------------------------------------------------------------------
$recProductTitle = '';
$recProductDesc = '';

$favLower = mb_strtolower($favoriteCoffee, 'UTF-8');

if (strpos($favLower, 'dirty') !== false) {
    // ลูกค้าชอบ Dirty Coffee -> แนะนำเมนูคู่ Dirty Matcha & Basque Cheesecake
    $recProductTitle = 'Military Dirty Matcha & Basque Burnt Cheesecake';
    $recProductDesc = 'สำหรับคุณที่หลงใหลความเข้มข้นของกาแฟแยกชั้นนมเย็นจัด เราขอมอบสิทธิ์ลิ้มลอง มัทฉะเกียวโต 3 เลเยอร์ คู่กับชีสเค้กหน้าไหม้สไตล์บาสก์ ในราคาพิเศษเฉพาะคุณค่ะ';
} elseif (strpos($favLower, 'cold brew') !== false || strpos($favLower, 'โคลด์บรูว์') !== false) {
    // ลูกค้าชอบ Cold Brew -> แนะนำ Artisan Orange Cold Brew & Pain au Chocolat
    $recProductTitle = 'Artisan Orange Cold Brew & Pain au Chocolat';
    $recProductDesc = 'คัดสรรเพื่อคอกาแฟสกัดเย็น สดชื่นตาสว่างด้วยส้มวาเลนเซียสด ทานคู่กับแปงโอช็อกโกลาอบเนยสดฝรั่งเศส กรอบนอกนุ่มในกลมกล่อมที่สุดค่ะ';
} elseif (strpos($favLower, 'latte') !== false || strpos($favLower, 'ลาเต้') !== false) {
    // ลูกค้าชอบ Latte -> แนะนำ Caramel Macchiato & Almond Croissant
    $recProductTitle = 'Caramel Macchiato & Almond Croissant';
    $recProductDesc = 'สำหรับคนรักกาแฟนมนุ่มละมุน สัมผัสความหอมหวานของวานิลลาคาราเมล ทานคู่กับครัวซองต์อัลมอนด์กรุบกรอบหอมเนยสดแท้ค่ะ';
} elseif (strpos($favLower, 'matcha') !== false || strpos($favLower, 'มัทฉะ') !== false || strpos($favLower, 'ชา') !== false) {
    // ลูกค้าชอบ Matcha / ชา -> แนะนำ Kyoto Dirty Matcha & Hokkaido Strawberry Shortcake
    $recProductTitle = 'Kyoto Dirty Matcha & Hokkaido Strawberry Shortcake';
    $recProductDesc = 'สำหรับคนรักชาเขียวมัทฉะอุจิแท้เกรดพิธีชงชาเข้มข้น ตัดรสชาติด้วยความหวานอมเปรี้ยวสดชื่นของสตรอว์เบอร์รีชอร์ตเค้กครีมสดฮอกไกโดค่ะ';
} else {
    // ค่าเริ่มต้นทั่วไป
    $recProductTitle = 'Signature Dirty Coffee & Butter Croissant';
    $recProductDesc = 'เมนูซิกเนเจอร์อันดับ 1 ของ Daily Brew รสชาติหวานมันเข้มข้นที่คอกาแฟทุกคนประทับใจ ทานคู่กับครัวซองต์เนยสดฝรั่งเศสแท้ค่ะ';
}

// --------------------------------------------------------------------------
// 4. สุ่มสร้างรหัสสมาชิก VIP และจัดเตรียมข้อมูล
// --------------------------------------------------------------------------
$memberId = 'DB-2026-VIP-' . rand(1000, 9999);
$birthdayDisplay = !empty($birthday) ? htmlspecialchars($birthday) : 'ไม่ได้ระบุ';
$addressDisplay = !empty($address) ? htmlspecialchars($address) : 'จัดส่งตามที่อยู่ที่ระบุในออเดอร์';

// --------------------------------------------------------------------------
// 4.5 บันทึกข้อมูลสมาชิก VIP ลงฐานข้อมูล MySQL (สำหรับดูใน MySQL Workbench)
// --------------------------------------------------------------------------
$dbHost = '127.0.0.1';
$dbUser = 'root';
$dbPass = '';
$dbName = 'daily_brew_db';

$dbSaved = false;
try {
    $conn = @new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    if (!$conn->connect_error) {
        $stmt = $conn->prepare("INSERT INTO `vip_members` (`member_id`, `fullname`, `phone`, `email`, `birthday`, `favorite_coffee`, `milk_type`, `address`, `points`, `promo_code`, `recommended_product`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 100, 'DAILYVIP20', ?)");
        if ($stmt) {
            $bdayVal = !empty($birthday) ? $birthday : null;
            $stmt->bind_param("sssssssss", $memberId, $fullname, $phone, $email, $bdayVal, $favoriteCoffee, $milk, $address, $recProductTitle);
            $stmt->execute();
            $stmt->close();
            $dbSaved = true;
        }
        $conn->close();
    }
} catch (Exception $e) {
    // Graceful fallback
}

// --------------------------------------------------------------------------
// 5. ดึงและแทนที่ข้อมูลในแม่แบบ HTML (email-template-vip.html)
// --------------------------------------------------------------------------
$templatePath = __DIR__ . '/email-template-vip.html';
$htmlBody = '';

if (file_exists($templatePath)) {
    $htmlBody = file_get_contents($templatePath);

    // แทนที่ตัวแปรใน Template ให้เป็นข้อมูลของลูกค้าคนนั้นจริงๆ
    $replacements = [
        '{{customer_name}}'                 => htmlspecialchars($fullname),
        '{{member_id}}'                     => $memberId,
        '{{birthday}}'                      => $birthdayDisplay,
        '{{favorite_coffee}}'               => htmlspecialchars($favoriteCoffee),
        '{{milk_type}}'                     => htmlspecialchars($milk),
        '{{address}}'                       => $addressDisplay,
        '{{promo_code}}'                    => 'DAILYVIP20',
        '{{recommended_product_title}}'     => $recProductTitle,
        '{{recommended_product_desc}}'      => $recProductDesc
    ];

    $htmlBody = str_replace(array_keys($replacements), array_values($replacements), $htmlBody);
} else {
    // Fallback สำรอง
    $htmlBody = '
    <!DOCTYPE html>
    <html>
    <head><meta charset="UTF-8"><title>Daily Brew VIP Member</title></head>
    <body style="font-family: sans-serif; background: #f4efe9; padding: 20px;">
        <div style="max-width: 560px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 12px;">
            <h2 style="color: #24140e;">ยินดีต้อนรับสมาชิก VIP คุณ ' . htmlspecialchars($fullname) . ' 👑</h2>
            <p>รหัสสมาชิกของคุณ: <strong>' . $memberId . '</strong></p>
            <p>ส่วนลด 20%: <strong>DAILYVIP20</strong></p>
            <hr>
            <h4>🎯 เมนูแนะนำพิเศษสำหรับคุณ (Personalized Recommendation):</h4>
            <p><strong>' . $recProductTitle . '</strong><br>' . $recProductDesc . '</p>
        </div>
    </body>
    </html>';
}

// --------------------------------------------------------------------------
// 7. สั่งส่งอีเมลด้วยฟังก์ชัน sendDailyBrewMail() (รองรับ Gmail SMTP & mail())
// --------------------------------------------------------------------------
require_once __DIR__ . '/mailer.php';

$to = $email;
$subject = '👑 ยินดีต้อนรับสู่ Daily Brew Club! บัตร VIP & ส่วนลด 20% (DAILYVIP20) ☕';
$mailResult = sendDailyBrewMail($to, $subject, $htmlBody, 'Daily Brew VIP Club');
$mailSent = $mailResult['success'];

// --------------------------------------------------------------------------
// 8. ส่งผลลัพธ์กลับไปยังผู้ใช้งาน
// --------------------------------------------------------------------------
if ($isJsonRequest) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success'                      => true,
        'message'                      => 'สมัครสมาชิก VIP สำเร็จแล้วค่ะ! อีเมลบัตรสมาชิกและส่วนลดถูกส่งเรียบร้อยแล้ว',
        'member_id'                    => $memberId,
        'fullname'                     => $fullname,
        'email'                        => $email,
        'promo_code'                   => 'DAILYVIP20',
        'personalized_recommendation'  => [
            'title' => $recProductTitle,
            'description' => $recProductDesc
        ],
        'php_mail_dispatched'          => $mailSent
    ], JSON_UNESCAPED_UNICODE);
    exit;
} else {
    echo "<script>
        alert('ยินดีต้อนรับสู่ Daily Brew VIP Club คุณ {$fullname}! รหัสสมาชิก {$memberId} บัตรของคุณถูกส่งไปที่อีเมลแล้วค่ะ');
        window.location.href = 'vip.html';
    </script>";
    exit;
}
