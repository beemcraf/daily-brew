<?php
/**
 * ==============================================================================
 * Daily Brew — ระบบสั่งซื้อสินค้า สรุปคำสั่งซื้อ และส่งใบเสร็จไปยังอีเมลลูกค้า
 * (Order Checkout, Database Storage & Email Receipt Dispatch)
 * ==============================================================================
 * 
 * ฟังก์ชันการทำงาน:
 * 1. รับข้อมูลคำสั่งซื้อ (Order ID, ข้อมูลลูกค้า, รายการสินค้า, ยอดรวม, ส่วนลด, วิธีชำระเงิน)
 * 2. ตรวจสอบความถูกต้องของข้อมูล (Validation)
 * 3. บันทึกข้อมูลคำสั่งซื้อลงฐานข้อมูล MySQL ตาราง `orders` ในฐานข้อมูล `daily_brew_db`
 * 4. สร้างเนื้อหาใบเสร็จรับเงินสไตล์คาเฟ่พรีเมียมจากแม่แบบ (email-template-order.html)
 * 5. ส่งอีเมลสรุปคำสั่งซื้อและใบเสร็จไปยังอีเมลของลูกค้าทันทีผ่าน PHPMailer / Gmail SMTP
 * 6. ส่งผลลัพธ์กลับในรูปแบบ JSON ให้หน้าเว็บแสดงผลความสำเร็จ
 */

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// โหลดโมดูลส่งอีเมล PHPMailer
require_once __DIR__ . '/mailer.php';

// --------------------------------------------------------------------------
// 1. รับค่าข้อมูลการสั่งซื้อ (รองรับทั้ง JSON และ Form POST)
// --------------------------------------------------------------------------
$isJson = false;
$orderId        = '';
$customerName   = '';
$customerEmail  = '';
$customerPhone  = '';
$customerAddress= '';
$paymentMethod  = 'PromptPay QR';
$items          = [];
$subtotal       = 0;
$discount       = 0;
$couponCode     = 'None';
$total          = 0;
$pointsEarned   = 0;

$contentType = isset($_SERVER['CONTENT_TYPE']) ? trim($_SERVER['CONTENT_TYPE']) : '';
if (stripos($contentType, 'application/json') !== false) {
    $isJson = true;
    $rawInput = file_get_contents('php://input');
    $data = json_decode($rawInput, true);

    $orderId        = isset($data['order_id']) ? trim($data['order_id']) : '';
    $customerName   = isset($data['customer_name']) ? trim($data['customer_name']) : '';
    $customerEmail  = isset($data['customer_email']) ? trim($data['customer_email']) : '';
    $customerPhone  = isset($data['customer_phone']) ? trim($data['customer_phone']) : '';
    $customerAddress= isset($data['customer_address']) ? trim($data['customer_address']) : '';
    $paymentMethod  = isset($data['payment_method']) ? trim($data['payment_method']) : 'PromptPay QR';
    $items          = isset($data['items']) && is_array($data['items']) ? $data['items'] : [];
    $subtotal       = isset($data['subtotal']) ? floatval($data['subtotal']) : 0;
    $discount       = isset($data['discount']) ? floatval($data['discount']) : 0;
    $couponCode     = isset($data['coupon_code']) ? trim($data['coupon_code']) : 'None';
    $total          = isset($data['total']) ? floatval($data['total']) : 0;
    $pointsEarned   = isset($data['points_earned']) ? intval($data['points_earned']) : 0;
} else {
    $orderId        = isset($_POST['order_id']) ? trim($_POST['order_id']) : '';
    $customerName   = isset($_POST['customer_name']) ? trim($_POST['customer_name']) : '';
    $customerEmail  = isset($_POST['customer_email']) ? trim($_POST['customer_email']) : '';
    $customerPhone  = isset($_POST['customer_phone']) ? trim($_POST['customer_phone']) : '';
    $customerAddress= isset($_POST['customer_address']) ? trim($_POST['customer_address']) : '';
    $paymentMethod  = isset($_POST['payment_method']) ? trim($_POST['payment_method']) : 'PromptPay QR';
    $subtotal       = isset($_POST['subtotal']) ? floatval($_POST['subtotal']) : 0;
    $discount       = isset($_POST['discount']) ? floatval($_POST['discount']) : 0;
    $couponCode     = isset($_POST['coupon_code']) ? trim($_POST['coupon_code']) : 'None';
    $total          = isset($_POST['total']) ? floatval($_POST['total']) : 0;
    $pointsEarned   = isset($_POST['points_earned']) ? intval($_POST['points_earned']) : 0;
    
    if (isset($_POST['items'])) {
        $decodedItems = json_decode($_POST['items'], true);
        if (is_array($decodedItems)) {
            $items = $decodedItems;
        }
    }
}

// --------------------------------------------------------------------------
// 2. ตรวจสอบความถูกต้องของข้อมูล (Validation)
// --------------------------------------------------------------------------
if (empty($customerEmail) || !filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'กรุณากรอกอีเมลสำหรับรับใบเสร็จให้ถูกต้อง']);
    exit;
}

if (empty($customerName)) {
    $customerName = 'ลูกค้าคนสำคัญ';
}

if (empty($orderId)) {
    $orderId = 'DB-ORD-' . date('Y') . '-' . rand(1000, 9999);
}

if (empty($items)) {
    // ข้อมูลจำลองหากไม่มีรายการส่งมา
    $items = [
        ['name' => 'Signature Dirty Coffee', 'qty' => 1, 'price' => 135],
        ['name' => 'Basque Burnt Cheesecake', 'qty' => 1, 'price' => 145]
    ];
    $subtotal = 280;
    $total = 280;
}

if ($total <= 0 && $subtotal > 0) {
    $total = max(0, $subtotal - $discount);
}

if ($pointsEarned <= 0) {
    $pointsEarned = floor($total / 10);
}

// --------------------------------------------------------------------------
// 3. สร้างสรุปรายการสินค้า HTML สำหรับแทรกลงในเทมเพลตอีเมล
// --------------------------------------------------------------------------
$itemsHtml = '';
$itemsSummaryArr = [];

foreach ($items as $item) {
    $name = htmlspecialchars($item['name'] ?? 'เมนูกาแฟ');
    $qty = intval($item['qty'] ?? 1);
    $price = floatval($item['price'] ?? 0);
    $itemTotal = $price * $qty;
    $options = htmlspecialchars($item['options'] ?? $item['custom'] ?? '');

    $optHtml = !empty($options) ? "<br><span style=\"font-size:11px; color:#8c7668;\">{$options}</span>" : '';

    $itemsHtml .= "
      <tr>
        <td style=\"padding: 10px 10px; border-bottom: 1px solid #f0e6dc; font-size: 13px;\">
          <strong style=\"color: #2c1a11;\">{$name}</strong>{$optHtml}
        </td>
        <td align=\"center\" style=\"padding: 10px 6px; border-bottom: 1px solid #f0e6dc; font-size: 13px; color: #63534b;\">
          x{$qty}
        </td>
        <td align=\"right\" style=\"padding: 10px 10px; border-bottom: 1px solid #f0e6dc; font-size: 13px; font-weight: 700; color: #2c1a11;\">
          " . number_format($itemTotal, 2) . " ฿
        </td>
      </tr>
    ";

    $itemsSummaryArr[] = "{$name} x{$qty} (" . number_format($itemTotal, 0) . "฿)";
}

$itemsSummaryText = implode(', ', $itemsSummaryArr);
$itemsJsonStr = json_encode($items, JSON_UNESCAPED_UNICODE);

// --------------------------------------------------------------------------
// 4. บันทึกข้อมูลคำสั่งซื้อลง MySQL Database (ตาราง orders)
// --------------------------------------------------------------------------
$dbHost = '127.0.0.1';
$dbUser = 'root';
$dbPass = '';
$dbName = 'daily_brew_db';
$dbPort = 3306;

if (file_exists(__DIR__ . '/config.local.php')) {
    $localCfg = require __DIR__ . '/config.local.php';
    if (isset($localCfg['db'])) {
        $dbHost = $localCfg['db']['host'] ?? $dbHost;
        $dbUser = $localCfg['db']['user'] ?? $dbUser;
        $dbPass = $localCfg['db']['pass'] ?? $dbPass;
        $dbName = $localCfg['db']['name'] ?? $dbName;
        $dbPort = $localCfg['db']['port'] ?? $dbPort;
    }
}

$dbSaved = false;
$dbError = null;

try {
    $mysqli = @new mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort);
    if (!$mysqli->connect_errno) {
        $mysqli->set_charset('utf8mb4');
        
        $stmt = $mysqli->prepare("
            INSERT INTO `orders` 
            (`order_id`, `customer_name`, `customer_email`, `customer_phone`, `customer_address`, `payment_method`, `items_json`, `items_summary`, `subtotal`, `discount`, `coupon_code`, `total`, `points_earned`, `status`)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'กำลังเตรียมจัดส่งด่วน')
            ON DUPLICATE KEY UPDATE 
            `customer_name`=VALUES(`customer_name`), 
            `customer_phone`=VALUES(`customer_phone`), 
            `total`=VALUES(`total`)
        ");

        if ($stmt) {
            $stmt->bind_param(
                'ssssssssdddsi',
                $orderId,
                $customerName,
                $customerEmail,
                $customerPhone,
                $customerAddress,
                $paymentMethod,
                $itemsJsonStr,
                $itemsSummaryText,
                $subtotal,
                $discount,
                $couponCode,
                $total,
                $pointsEarned
            );
            $stmt->execute();
            $dbSaved = true;
            $stmt->close();
        }
        $mysqli->close();
    }
} catch (Throwable $e) {
    $dbError = $e->getMessage();
    error_log("Order DB Save Warning: " . $dbError);
}

// --------------------------------------------------------------------------
// 5. โหลดแม่แบบ HTML และส่งอีเมลใบเสร็จรับเงิน
// --------------------------------------------------------------------------
$orderDate = date('d/m/Y, H:i น.');
$templatePath = __DIR__ . '/email-template-order.html';

if (file_exists($templatePath)) {
    $htmlContent = file_get_contents($templatePath);
} else {
    $htmlContent = "<h2>ใบเสร็จคำสั่งซื้อ {$orderId}</h2><p>ยอดชำระ: " . number_format($total, 2) . " ฿</p>";
}

// แทนที่ Placeholders ทั้งหมด
$replacements = [
    '{{order_id}}'        => $orderId,
    '{{order_date}}'      => $orderDate,
    '{{customer_name}}'   => htmlspecialchars($customerName),
    '{{customer_email}}'  => htmlspecialchars($customerEmail),
    '{{customer_phone}}'  => htmlspecialchars($customerPhone ?: '081-xxx-xxxx'),
    '{{customer_address}}'=> nl2br(htmlspecialchars($customerAddress ?: 'จัดส่งด่วนตามที่อยู่ที่ระบุ')),
    '{{payment_method}}'  => htmlspecialchars($paymentMethod),
    '{{items_html}}'      => $itemsHtml,
    '{{subtotal}}'        => number_format($subtotal, 2) . ' ฿',
    '{{discount}}'        => number_format($discount, 2) . ' ฿',
    '{{coupon_code}}'     => htmlspecialchars($couponCode ?: 'ส่วนลดพิเศษ'),
    '{{total}}'           => number_format($total, 2) . ' ฿',
    '{{points_earned}}'   => number_format($pointsEarned),
    '{{delivery_status}}' => 'กำลังเตรียมชงและจัดส่งด่วน 🛵'
];

$emailBody = str_replace(array_keys($replacements), array_values($replacements), $htmlContent);

// สั่งส่งอีเมลผ่าน Mailer (Gmail SMTP)
$emailSubject = "☕ ใบเสร็จรับเงินและยืนยันคำสั่งซื้อ #{$orderId} — Daily Brew Specialty Coffee";
$mailResult = sendDailyBrewMail($customerEmail, $customerName, $emailSubject, $emailBody);

// --------------------------------------------------------------------------
// 6. ส่งผลลัพธ์การสั่งซื้อกลับไปยังหน้าเว็บ
// --------------------------------------------------------------------------
$response = [
    'success'       => true,
    'order_id'      => $orderId,
    'customer_name' => $customerName,
    'customer_email'=> $customerEmail,
    'total'         => $total,
    'points_earned' => $pointsEarned,
    'db_saved'      => $dbSaved,
    'mail_sent'     => $mailResult['success'],
    'mail_engine'   => $mailResult['driver'] ?? 'gmail_smtp',
    'message'       => 'สั่งซื้อสำเร็จและส่งใบเสร็จรับเงินไปยังอีเมลเรียบร้อยแล้วค่ะ! ☕'
];

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
