<?php
/**
 * ==============================================================================
 * Daily Brew — ระบบจัดการร้านกาแฟและคำสั่งซื้อหลังบ้าน (Admin Hub & Management)
 * ==============================================================================
 */

session_start();

// ตรวจสอบสิทธิ์การเข้าใช้งาน
if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}

// โหลดโมดูลส่งอีเมล PHPMailer
require_once __DIR__ . '/mailer.php';

// จัดการออกจากระบบ (Logout)
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: admin_login.php');
    exit;
}

// --------------------------------------------------------------------------
// 1. เชื่อมต่อฐานข้อมูล MySQL (daily_brew_db)
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

$dbConnected = false;
$mysqli = @new mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort);
if (!$mysqli->connect_errno) {
    $mysqli->set_charset('utf8mb4');
    $dbConnected = true;
}

$noticeMessage = '';
$noticeType = 'success';
$activeTab = htmlspecialchars($_POST['active_tab'] ?? $_GET['tab'] ?? 'orders');

// --------------------------------------------------------------------------
// 2. จัดการ Action ต่างๆ ที่แอดมินสั่งงาน
// --------------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $dbConnected) {
    $action = $_POST['action'] ?? '';

    // Action A: อัปเดตสถานะคำสั่งซื้อ
    if ($action === 'update_order_status') {
        $orderId = trim($_POST['order_id'] ?? '');
        $newStatus = trim($_POST['status'] ?? '');
        if ($orderId && $newStatus) {
            $stmt = $mysqli->prepare("UPDATE `orders` SET `status` = ? WHERE `order_id` = ?");
            if ($stmt) {
                $stmt->bind_param('ss', $newStatus, $orderId);
                $stmt->execute();
                $stmt->close();
                $noticeMessage = "อัปเดตสถานะออเดอร์ #{$orderId} เป็น \"{$newStatus}\" เรียบร้อยแล้วค่ะ";
            }
        }
    }

    // Action B: สั่งส่งใบเสร็จซ้ำเข้าอีเมลลูกค้าผ่าน PHP (Gmail SMTP)
    elseif ($action === 'resend_receipt') {
        $orderId = trim($_POST['order_id'] ?? '');
        if ($orderId) {
            $stmt = $mysqli->prepare("SELECT * FROM `orders` WHERE `order_id` = ? LIMIT 1");
            if ($stmt) {
                $stmt->bind_param('s', $orderId);
                $stmt->execute();
                $orderData = $stmt->get_result()->fetch_assoc();
                $stmt->close();

                if ($orderData && !empty($orderData['customer_email'])) {
                    $templatePath = file_exists(__DIR__ . '/templates/email-template-order.html')
                        ? __DIR__ . '/templates/email-template-order.html'
                        : __DIR__ . '/email-template-order.html';
                    $html = file_exists($templatePath) ? file_get_contents($templatePath) : '<h2>ใบเสร็จคำสั่งซื้อ</h2>';
                    
                    $itemsArr = json_decode($orderData['items_json'] ?? '[]', true) ?: [];
                    $itemsHtml = '';
                    foreach ($itemsArr as $it) {
                        $name = htmlspecialchars($it['name'] ?? 'กาแฟ Daily Brew');
                        $qty = intval($it['qty'] ?? 1);
                        $price = floatval($it['price'] ?? 0);
                        $tot = $price * $qty;
                        $opt = htmlspecialchars($it['options'] ?? $it['custom'] ?? '');
                        $optHtml = $opt ? "<br><span style='font-size:11px; color:#8c7668;'>{$opt}</span>" : '';
                        
                        $itemsHtml .= "
                          <tr>
                            <td style='padding:10px; border-bottom:1px solid #f0e6dc; font-size:13px;'>
                              <strong>{$name}</strong>{$optHtml}
                            </td>
                            <td align='center' style='padding:10px; border-bottom:1px solid #f0e6dc; font-size:13px;'>x{$qty}</td>
                            <td align='right' style='padding:10px; border-bottom:1px solid #f0e6dc; font-size:13px; font-weight:700;'>" . number_format($tot, 2) . " ฿</td>
                          </tr>
                        ";
                    }

                    $replacements = [
                        '{{order_id}}'        => $orderData['order_id'],
                        '{{order_date}}'      => $orderData['created_at'],
                        '{{customer_name}}'   => htmlspecialchars($orderData['customer_name']),
                        '{{customer_email}}'  => htmlspecialchars($orderData['customer_email']),
                        '{{customer_phone}}'  => htmlspecialchars($orderData['customer_phone']),
                        '{{customer_address}}'=> nl2br(htmlspecialchars($orderData['customer_address'] ?? 'จัดส่งเดลิเวอรี')),
                        '{{payment_method}}'  => htmlspecialchars($orderData['payment_method']),
                        '{{items_html}}'      => $itemsHtml,
                        '{{subtotal}}'        => number_format($orderData['subtotal'], 2) . ' ฿',
                        '{{discount}}'        => number_format($orderData['discount'], 2) . ' ฿',
                        '{{coupon_code}}'     => htmlspecialchars($orderData['coupon_code'] ?: 'None'),
                        '{{total}}'           => number_format($orderData['total'], 2) . ' ฿',
                        '{{points_earned}}'   => number_format($orderData['points_earned']),
                        '{{delivery_status}}' => htmlspecialchars($orderData['status'])
                    ];

                    $subject = "☕ [ส่งซ้ำ] ใบเสร็จรับเงินคำสั่งซื้อ #{$orderData['order_id']} — Daily Brew";
                    $body = str_replace(array_keys($replacements), array_values($replacements), $html);

                    $mailRes = sendDailyBrewMail($orderData['customer_email'], $subject, $body, 'Daily Brew Coffee');
                    if ($mailRes['success']) {
                        $noticeMessage = "ส่งใบเสร็จรับเงินไปยังอีเมล {$orderData['customer_email']} เรียบร้อยแล้วค่ะ! ✉️";
                    } else {
                        $noticeType = 'danger';
                        $noticeMessage = "ส่งอีเมลไม่สำเร็จ: " . ($mailRes['error'] ?? 'ข้อผิดพลาดในการเชื่อมต่อ SMTP');
                    }
                }
            }
        }
    }

    // Action C: ปรับแต้มสะสมสมาชิก VIP
    elseif ($action === 'update_vip_points') {
        $memberId = trim($_POST['member_id'] ?? '');
        $pointsDelta = intval($_POST['points_delta'] ?? 0);
        if ($memberId && $pointsDelta !== 0) {
            $stmt = $mysqli->prepare("UPDATE `vip_members` SET `points` = GREATEST(0, `points` + ?) WHERE `member_id` = ?");
            if ($stmt) {
                $stmt->bind_param('is', $pointsDelta, $memberId);
                $stmt->execute();
                $stmt->close();
                $noticeMessage = "ปรับแต้มสมาชิก {$memberId} จำนวน " . ($pointsDelta > 0 ? "+{$pointsDelta}" : "{$pointsDelta}") . " แต้มเรียบร้อยแล้วค่ะ ⭐";
            }
        }
    }

    // Action D: สลับสถานะสต็อกสินค้า (In Stock / Out of Stock)
    elseif ($action === 'toggle_stock') {
        $itemKey = trim($_POST['item_key'] ?? '');
        $stockFile = __DIR__ . '/menu_stock.json';
        if ($itemKey && file_exists($stockFile)) {
            $stockData = json_decode(file_get_contents($stockFile), true) ?: [];
            if (isset($stockData[$itemKey])) {
                $stockData[$itemKey]['in_stock'] = !$stockData[$itemKey]['in_stock'];
                file_put_contents($stockFile, json_encode($stockData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                $newSt = $stockData[$itemKey]['in_stock'] ? 'พร้อมเสิร์ฟ 🟢' : 'ของหมดชั่วคราว 🔴';
                $noticeMessage = "อัปเดตสต็อก \"{$stockData[$itemKey]['nameTh']}\" เป็น {$newSt} แล้วค่ะ";
            }
        }
    }

    // Action E: ส่งอีเมลข่าวสาร Broadcast โปรโมชั่น
    elseif ($action === 'broadcast_newsletter') {
        $subj = trim($_POST['broadcast_subject'] ?? '');
        $msg = trim($_POST['broadcast_message'] ?? '');
        if ($subj && $msg) {
            $subsRes = $mysqli->query("SELECT `email` FROM `subscribers`");
            $sentCount = 0;
            if ($subsRes) {
                while ($sub = $subsRes->fetch_assoc()) {
                    $to = $sub['email'];
                    $bodyHtml = "
                      <div style='font-family:sans-serif; max-width:550px; margin:0 auto; padding:20px; border:1px solid #ebd9c8; border-radius:12px; background:#fffdfa;'>
                        <h2 style='color:#3e2723;'>☕ ข่าวสารพิเศษจาก Daily Brew</h2>
                        <div style='font-size:14px; color:#4a2810; line-height:1.7;'>" . nl2br(htmlspecialchars($msg)) . "</div>
                        <hr style='border:none; border-top:1px dashed #d4a373; margin:20px 0;'>
                        <p style='font-size:12px; color:#8c7668;'>สั่งเครื่องดื่มออนไลน์ได้ที่: <a href='https://beemcraf.github.io/daily-brew/'>Daily Brew Direct</a></p>
                      </div>
                    ";
                    $res = sendDailyBrewMail($to, "☕ " . $subj, $bodyHtml, 'Daily Brew Coffee');
                    if ($res['success']) $sentCount++;
                }
            }
            $noticeMessage = "ส่งอีเมล Broadcast ไปยังผู้ติดตามจำนวน {$sentCount} ท่านเรียบร้อยแล้วค่ะ! 📣";
        }
    }

    // Action F: ลบรายการคำสั่งซื้อ (Delete Order)
    elseif ($action === 'delete_order') {
        $orderId = trim($_POST['order_id'] ?? '');
        if ($orderId) {
            $stmt = $mysqli->prepare("DELETE FROM `orders` WHERE `order_id` = ? LIMIT 1");
            if ($stmt) {
                $stmt->bind_param('s', $orderId);
                $stmt->execute();
                $stmt->close();
                $noticeMessage = "ลบรายการคำสั่งซื้อ #{$orderId} เรียบร้อยแล้วค่ะ 🗑️";
            }
        }
    }

    // Action G: ลบข้อมูลสมาชิก VIP (Delete VIP Member)
    elseif ($action === 'delete_vip') {
        $memberId = trim($_POST['member_id'] ?? '');
        if ($memberId) {
            $stmt = $mysqli->prepare("DELETE FROM `vip_members` WHERE `member_id` = ? LIMIT 1");
            if ($stmt) {
                $stmt->bind_param('s', $memberId);
                $stmt->execute();
                $stmt->close();
                $noticeMessage = "ลบข้อมูลสมาชิก VIP รหัส {$memberId} เรียบร้อยแล้วค่ะ 🗑️";
            }
        }
    }

    // Action H: ลบผู้ติดตามข่าวสาร (Delete Subscriber)
    elseif ($action === 'delete_subscriber') {
        $subId = intval($_POST['subscriber_id'] ?? 0);
        $subEmail = trim($_POST['subscriber_email'] ?? '');
        if ($subId > 0) {
            $stmt = $mysqli->prepare("DELETE FROM `subscribers` WHERE `id` = ? LIMIT 1");
            if ($stmt) {
                $stmt->bind_param('i', $subId);
                $stmt->execute();
                $stmt->close();
                $noticeMessage = "ลบรายชื่อผู้ติดตาม " . ($subEmail ? "({$subEmail})" : "") . " เรียบร้อยแล้วค่ะ 🗑️";
            }
        }
    }
}

// --------------------------------------------------------------------------
// 3. ดึงข้อมูลสถิติภาพรวม (KPIs)
// --------------------------------------------------------------------------
$totalRevenue = 0;
$totalOrders = 0;
$totalVips = 0;
$totalSubscribers = 0;

$ordersList = [];
$vipsList = [];
$subscribersList = [];

if ($dbConnected) {
    // ยอดขายและจำนวนออเดอร์
    $res = $mysqli->query("SELECT SUM(`total`) as rev, COUNT(*) as cnt FROM `orders`");
    if ($res) {
        $row = $res->fetch_assoc();
        $totalRevenue = floatval($row['rev'] ?? 0);
        $totalOrders = intval($row['cnt'] ?? 0);
    }

    // จำนวนสมาชิก VIP
    $res = $mysqli->query("SELECT COUNT(*) as cnt FROM `vip_members`");
    if ($res) {
        $row = $res->fetch_assoc();
        $totalVips = intval($row['cnt'] ?? 0);
    }

    // จำนวนผู้ติดตาม Newsletter
    $res = $mysqli->query("SELECT COUNT(*) as cnt FROM `subscribers`");
    if ($res) {
        $row = $res->fetch_assoc();
        $totalSubscribers = intval($row['cnt'] ?? 0);
    }

    // ดึงออเดอร์ทั้งหมด
    $res = $mysqli->query("SELECT * FROM `orders` ORDER BY `id` DESC LIMIT 100");
    if ($res) {
        while ($r = $res->fetch_assoc()) {
            $ordersList[] = $r;
        }
    }

    // ดึงสมาชิก VIP ทั้งหมด
    $res = $mysqli->query("SELECT * FROM `vip_members` ORDER BY `id` DESC LIMIT 100");
    if ($res) {
        while ($r = $res->fetch_assoc()) {
            $vipsList[] = $r;
        }
    }

    // ดึงรายชื่อผู้ติดตามทั้งหมด
    $res = $mysqli->query("SELECT * FROM `subscribers` ORDER BY `id` DESC LIMIT 100");
    if ($res) {
        while ($r = $res->fetch_assoc()) {
            $subscribersList[] = $r;
        }
    }
}

// โหลดข้อมูลสต็อกสินค้า
$stockFile = __DIR__ . '/menu_stock.json';
$menuStock = file_exists($stockFile) ? json_decode(file_get_contents($stockFile), true) : [];
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daily Brew — ระบบบริหารจัดการร้านกาแฟ (Admin Hub)</title>

  <!-- Google Fonts: Prompt -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome 6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <style>
    :root {
      --espresso: #1f130d;
      --espresso-light: #2c1a12;
      --caramel: #8c531b;
      --gold: #d4a373;
      --gold-light: #e6b360;
      --cream: #faf6f1;
      --cream-card: #ffffff;
      --border-color: #ebd9c8;
      --text-dark: #2c1a11;
      --text-muted: #795548;
      --green: #27ae60;
      --red: #c0392b;
      --blue: #2980b9;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Prompt', sans-serif;
      background: #f5f0ea;
      color: var(--text-dark);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* Topbar */
    .topbar {
      background: var(--espresso);
      color: #ffffff;
      padding: 12px 24px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 2px solid var(--gold);
      position: sticky;
      top: 0;
      z-index: 100;
      box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }
    .brand-section {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .brand-icon {
      font-size: 24px;
      background: linear-gradient(135deg, var(--gold-light), var(--caramel));
      width: 42px;
      height: 42px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--espresso);
      font-weight: bold;
    }
    .brand-title {
      font-size: 18px;
      font-weight: 700;
      color: var(--gold-light);
      line-height: 1.2;
    }
    .brand-sub {
      font-size: 11px;
      color: #c9b3a3;
      letter-spacing: 0.5px;
    }
    .topbar-right {
      display: flex;
      align-items: center;
      gap: 15px;
      font-size: 13px;
    }
    .status-pill {
      background: rgba(255,255,255,0.08);
      border: 1px solid rgba(212,163,115,0.3);
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 11px;
      display: flex;
      align-items: center;
      gap: 6px;
    }
    .dot-green {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: #2ecc71;
      display: inline-block;
      box-shadow: 0 0 6px #2ecc71;
    }
    .btn-logout {
      background: rgba(192,57,43,0.2);
      border: 1px solid #c0392b;
      color: #ff7675;
      padding: 6px 14px;
      border-radius: 8px;
      text-decoration: none;
      font-size: 12px;
      font-weight: 600;
      transition: all 0.2s;
    }
    .btn-logout:hover {
      background: #c0392b;
      color: white;
    }

    /* Container & Layout */
    .admin-container {
      max-width: 1320px;
      width: 100%;
      margin: 20px auto;
      padding: 0 20px;
      flex: 1;
    }

    /* Alert Banner */
    .alert-banner {
      padding: 12px 18px;
      border-radius: 10px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 14px;
      font-weight: 500;
      animation: fadeIn 0.3s ease;
    }
    .alert-banner.success {
      background: #eafaf1;
      border: 1.5px solid #2ecc71;
      color: #1e824c;
    }
    .alert-banner.danger {
      background: #fdeeed;
      border: 1.5px solid #e74c3c;
      color: #c0392b;
    }

    /* KPI Cards Grid */
    .kpi-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 16px;
      margin-bottom: 25px;
    }
    .kpi-card {
      background: var(--cream-card);
      border: 1px solid var(--border-color);
      border-radius: 14px;
      padding: 18px 20px;
      box-shadow: 0 4px 12px rgba(44, 26, 17, 0.04);
      display: flex;
      justify-content: space-between;
      align-items: center;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .kpi-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(44, 26, 17, 0.08);
    }
    .kpi-info h4 {
      font-size: 12.5px;
      color: var(--text-muted);
      margin-bottom: 4px;
      font-weight: 500;
    }
    .kpi-info .kpi-value {
      font-size: 26px;
      font-weight: 700;
      color: var(--espresso);
    }
    .kpi-icon {
      width: 50px;
      height: 50px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
    }
    .icon-gold { background: #fdf5e8; color: #b78103; }
    .icon-green { background: #eafaf1; color: #27ae60; }
    .icon-purple { background: #f4ecf7; color: #8e44ad; }
    .icon-blue { background: #ebf5fb; color: #2980b9; }

    /* Nav Tabs */
    .tabs-header {
      display: flex;
      gap: 8px;
      border-bottom: 2px solid var(--border-color);
      margin-bottom: 20px;
      overflow-x: auto;
      padding-bottom: 2px;
    }
    .tab-btn {
      padding: 10px 18px;
      border: none;
      background: none;
      font-family: inherit;
      font-size: 14px;
      font-weight: 600;
      color: var(--text-muted);
      cursor: pointer;
      border-radius: 8px 8px 0 0;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: all 0.2s;
      white-space: nowrap;
    }
    .tab-btn:hover {
      color: var(--caramel);
      background: rgba(212,163,115,0.1);
    }
    .tab-btn.active {
      color: var(--espresso);
      background: var(--cream-card);
      border: 1px solid var(--border-color);
      border-bottom: 3px solid var(--caramel);
      box-shadow: 0 -2px 6px rgba(0,0,0,0.03);
    }
    .badge-count {
      background: #f0e6dc;
      color: var(--caramel);
      padding: 1px 7px;
      border-radius: 10px;
      font-size: 11px;
      font-weight: 700;
    }

    /* Content Panes */
    .tab-pane {
      display: none;
      animation: fadeIn 0.25s ease;
    }
    .tab-pane.active {
      display: block;
    }

    /* Main Table Container */
    .table-card {
      background: var(--cream-card);
      border: 1px solid var(--border-color);
      border-radius: 14px;
      padding: 20px;
      box-shadow: 0 4px 15px rgba(44, 26, 17, 0.04);
      margin-bottom: 25px;
    }
    .table-header-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 16px;
      flex-wrap: wrap;
      gap: 10px;
    }
    .table-title {
      font-size: 17px;
      font-weight: 700;
      color: var(--espresso);
      display: flex;
      align-items: center;
      gap: 8px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13px;
    }
    th {
      background: #faf6f1;
      color: var(--espresso);
      padding: 11px 12px;
      text-align: left;
      font-weight: 600;
      border-bottom: 2px solid var(--border-color);
      white-space: nowrap;
    }
    td {
      padding: 12px;
      border-bottom: 1px solid #f2ece4;
      vertical-align: middle;
    }
    tr:hover td {
      background: #fdfaf6;
    }

    /* Status Badges */
    .badge {
      display: inline-block;
      padding: 3px 9px;
      border-radius: 12px;
      font-size: 11.5px;
      font-weight: 600;
      white-space: nowrap;
    }
    .badge-prep { background: #fef5e7; color: #b78103; border: 1px solid #f9e79f; }
    .badge-delivering { background: #e8f8f5; color: #117864; border: 1px solid #a3e4d7; }
    .badge-done { background: #eafaf1; color: #1e824c; border: 1px solid #a9dfbf; }
    .badge-cancel { background: #fdedec; color: #922b21; border: 1px solid #f5b7b1; }
    .badge-vip { background: linear-gradient(135deg, #d4a373, #8c531b); color: white; }

    /* Action Buttons */
    .btn-action {
      background: #faf6f1;
      border: 1px solid var(--border-color);
      color: var(--espresso);
      padding: 5px 9px;
      border-radius: 6px;
      font-size: 12px;
      cursor: pointer;
      font-family: inherit;
      transition: all 0.2s;
      display: inline-flex;
      align-items: center;
      gap: 5px;
    }
    .btn-action:hover {
      background: var(--gold);
      color: white;
      border-color: var(--gold);
    }
    .btn-resend {
      background: #ebf5fb;
      border-color: #aed6f1;
      color: #1f618d;
    }
    .btn-resend:hover {
      background: #2980b9;
      color: white;
    }
    .btn-delete {
      background: #fdf2f2;
      border-color: #f5b7b1;
      color: #c0392b;
    }
    .btn-delete:hover {
      background: #c0392b;
      color: white;
      border-color: #c0392b;
      box-shadow: 0 2px 6px rgba(192, 57, 43, 0.25);
    }

    /* Forms & Inputs */
    select.status-select {
      padding: 4px 8px;
      border-radius: 6px;
      border: 1px solid var(--border-color);
      font-family: inherit;
      font-size: 12px;
      background: white;
      outline: none;
    }

    /* Stock Switch */
    .switch {
      position: relative;
      display: inline-block;
      width: 44px;
      height: 24px;
    }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider {
      position: absolute;
      cursor: pointer;
      top: 0; left: 0; right: 0; bottom: 0;
      background-color: #ccc;
      transition: .3s;
      border-radius: 24px;
    }
    .slider:before {
      position: absolute;
      content: "";
      height: 18px;
      width: 18px;
      left: 3px;
      bottom: 3px;
      background-color: white;
      transition: .3s;
      border-radius: 50%;
    }
    input:checked + .slider { background-color: var(--green); }
    input:checked + .slider:before { transform: translateX(20px); }

    /* Broadcast Form Box */
    .broadcast-box {
      background: #faf6f1;
      border: 1.5px dashed var(--gold);
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 20px;
    }
    .form-input {
      width: 100%;
      padding: 10px 12px;
      border: 1px solid var(--border-color);
      border-radius: 8px;
      font-family: inherit;
      font-size: 13.5px;
      margin-bottom: 12px;
      outline: none;
    }
    .form-input:focus {
      border-color: var(--caramel);
    }

    /* Modal for Order Details */
    .modal-overlay {
      display: none;
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0,0,0,0.6);
      backdrop-filter: blur(4px);
      z-index: 1000;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }
    .modal-overlay.active { display: flex; }
    .modal-content {
      background: white;
      border-radius: 16px;
      max-width: 580px;
      width: 100%;
      padding: 24px;
      box-shadow: 0 20px 50px rgba(0,0,0,0.3);
      max-height: 85vh;
      overflow-y: auto;
    }
    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #f0e6dc;
      padding-bottom: 12px;
      margin-bottom: 16px;
    }
    .close-modal-btn {
      background: none;
      border: none;
      font-size: 20px;
      cursor: pointer;
      color: #999;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(4px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

  <!-- Top Navigation Bar -->
  <header class="topbar">
    <div class="brand-section">
      <div class="brand-icon">☕</div>
      <div>
        <div class="brand-title">Daily Brew Admin Hub</div>
        <div class="brand-sub">ระบบบริหารจัดการร้านกาแฟและคำสั่งซื้อหลังบ้าน</div>
      </div>
    </div>

    <div class="topbar-right">
      <div class="status-pill">
        <span class="dot-green"></span>
        <span>MySQL: <?= $dbConnected ? 'Online 3306' : 'Offline' ?></span>
      </div>
      <div class="status-pill">
        <span class="dot-green"></span>
        <span>Gmail SMTP: Active</span>
      </div>
      <div style="color:#d4a373; font-weight:600;">
        <i class="fa-solid fa-user-circle"></i> <?= htmlspecialchars($_SESSION['admin_user'] ?? 'admin') ?>
      </div>
      <a href="?action=logout" class="btn-logout">
        <i class="fa-solid fa-right-from-bracket"></i> ออกจากระบบ
      </a>
    </div>
  </header>

  <!-- Main Container -->
  <main class="admin-container">

    <!-- Notification Alert -->
    <?php if (!empty($noticeMessage)): ?>
      <div class="alert-banner <?= $noticeType ?>">
        <i class="fa-solid fa-circle-check"></i>
        <span><?= htmlspecialchars($noticeMessage) ?></span>
      </div>
    <?php endif; ?>

    <!-- 4 KPI Summary Cards -->
    <section class="kpi-grid">
      <!-- Revenue -->
      <div class="kpi-card">
        <div class="kpi-info">
          <h4>ยอดขายรวมทั้งหมด</h4>
          <div class="kpi-value"><?= number_format($totalRevenue, 2) ?> ฿</div>
        </div>
        <div class="kpi-icon icon-gold">
          <i class="fa-solid fa-coins"></i>
        </div>
      </div>

      <!-- Orders -->
      <div class="kpi-card">
        <div class="kpi-info">
          <h4>จำนวนคำสั่งซื้อ</h4>
          <div class="kpi-value"><?= number_format($totalOrders) ?> ออเดอร์</div>
        </div>
        <div class="kpi-icon icon-green">
          <i class="fa-solid fa-box-open"></i>
        </div>
      </div>

      <!-- VIP Members -->
      <div class="kpi-card">
        <div class="kpi-info">
          <h4>สมาชิก VIP Gold</h4>
          <div class="kpi-value"><?= number_format($totalVips) ?> ท่าน</div>
        </div>
        <div class="kpi-icon icon-purple">
          <i class="fa-solid fa-crown"></i>
        </div>
      </div>

      <!-- Subscribers -->
      <div class="kpi-card">
        <div class="kpi-info">
          <h4>ผู้ติดตาม Newsletter</h4>
          <div class="kpi-value"><?= number_format($totalSubscribers) ?> อีเมล</div>
        </div>
        <div class="kpi-icon icon-blue">
          <i class="fa-solid fa-envelope-open-text"></i>
        </div>
      </div>
    </section>

    <!-- Navigation Tabs -->
    <div class="tabs-header">
      <button class="tab-btn <?= $activeTab === 'orders' ? 'active' : '' ?>" onclick="switchTab('orders', this)">
        <i class="fa-solid fa-receipt"></i> จัดการคำสั่งซื้อ
        <span class="badge-count"><?= count($ordersList) ?></span>
      </button>

      <button class="tab-btn <?= $activeTab === 'vip' ? 'active' : '' ?>" onclick="switchTab('vip', this)">
        <i class="fa-solid fa-crown"></i> สมาชิก VIP Club
        <span class="badge-count"><?= count($vipsList) ?></span>
      </button>

      <button class="tab-btn <?= $activeTab === 'stock' ? 'active' : '' ?>" onclick="switchTab('stock', this)">
        <i class="fa-solid fa-mug-saucer"></i> สต็อกเมนูเครื่องดื่ม
        <span class="badge-count"><?= count($menuStock) ?></span>
      </button>

      <button class="tab-btn <?= $activeTab === 'subscribers' ? 'active' : '' ?>" onclick="switchTab('subscribers', this)">
        <i class="fa-solid fa-paper-plane"></i> ข่าวสาร & ผู้ติดตาม
        <span class="badge-count"><?= count($subscribersList) ?></span>
      </button>

      <button class="tab-btn <?= $activeTab === 'system' ? 'active' : '' ?>" onclick="switchTab('system', this)">
        <i class="fa-solid fa-server"></i> สุขภาพระบบ & เครื่องมือ
      </button>
    </div>

    <!-- ================= TAB 1: ORDERS ================= -->
    <div id="tab-orders" class="tab-pane <?= $activeTab === 'orders' ? 'active' : '' ?>">
      <div class="table-card">
        <div class="table-header-row">
          <div class="table-title">
            <i class="fa-solid fa-list-check" style="color:var(--caramel);"></i> 
            รายการคำสั่งซื้อเดลิเวอรีล่าสุด
          </div>
          <div style="font-size:12.5px; color:var(--text-muted);">
            แสดงสูงสุด 100 ออเดอร์ล่าสุดจากฐานข้อมูล MySQL
          </div>
        </div>

        <?php if (empty($ordersList)): ?>
          <p style="text-align:center; padding:30px; color:#888;">ยังไม่มีรายการสั่งซื้อในระบบ</p>
        <?php else: ?>
          <div style="overflow-x:auto;">
            <table>
              <thead>
                <tr>
                  <th>รหัสออเดอร์</th>
                  <th>ชื่อลูกค้า</th>
                  <th>เบอร์โทร</th>
                  <th>รายการสินค้า</th>
                  <th>ยอดชำระ</th>
                  <th>วิธีชำระ</th>
                  <th>สถานะออเดอร์</th>
                  <th>วันที่สั่งซื้อ</th>
                  <th style="text-align:center;">จัดการ</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($ordersList as $ord): 
                  $status = $ord['status'] ?? 'กำลังเตรียมชง';
                  $badgeCls = 'badge-prep';
                  if (strpos($status, 'จัดส่งด่วน') !== false) $badgeCls = 'badge-delivering';
                  elseif (strpos($status, 'สำเร็จ') !== false) $badgeCls = 'badge-done';
                  elseif (strpos($status, 'ยกเลิก') !== false) $badgeCls = 'badge-cancel';
                ?>
                  <tr>
                    <td>
                      <strong style="color:var(--caramel); font-family:monospace;"><?= htmlspecialchars($ord['order_id']) ?></strong>
                    </td>
                    <td>
                      <strong><?= htmlspecialchars($ord['customer_name']) ?></strong><br>
                      <span style="font-size:11px; color:#777;"><?= htmlspecialchars($ord['customer_email']) ?></span>
                    </td>
                    <td><?= htmlspecialchars($ord['customer_phone'] ?: '-') ?></td>
                    <td style="max-width:280px;">
                      <div style="font-size:12px; line-height:1.4; color:#333;">
                        <?= htmlspecialchars($ord['items_summary']) ?>
                      </div>
                    </td>
                    <td>
                      <strong style="color:var(--espresso); font-size:14px;"><?= number_format($ord['total'], 2) ?> ฿</strong>
                      <?php if ($ord['discount'] > 0): ?>
                        <br><span style="font-size:10.5px; color:var(--green);">ลด -<?= number_format($ord['discount'], 2) ?>฿ (<?= htmlspecialchars($ord['coupon_code']) ?>)</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <span style="font-size:12px;"><?= htmlspecialchars($ord['payment_method']) ?></span>
                    </td>
                    <td>
                      <form method="POST" action="admin.php" style="display:inline-block;">
                        <input type="hidden" name="action" value="update_order_status">
                        <input type="hidden" name="order_id" value="<?= htmlspecialchars($ord['order_id']) ?>">
                        <select name="status" class="status-select" onchange="this.form.submit()">
                          <option value="กำลังเตรียมชง" <?= $status === 'กำลังเตรียมชง' ? 'selected' : '' ?>>☕ กำลังเตรียมชง</option>
                          <option value="กำลังจัดส่งด่วน 🛵" <?= $status === 'กำลังจัดส่งด่วน 🛵' ? 'selected' : '' ?>>🛵 กำลังจัดส่งด่วน</option>
                          <option value="จัดส่งสำเร็จ ✅" <?= $status === 'จัดส่งสำเร็จ ✅' ? 'selected' : '' ?>>✅ จัดส่งสำเร็จ</option>
                          <option value="ยกเลิกออเดอร์ ❌" <?= $status === 'ยกเลิกออเดอร์ ❌' ? 'selected' : '' ?>>❌ ยกเลิกออเดอร์</option>
                        </select>
                      </form>
                    </td>
                    <td style="font-size:11.5px; color:#777; white-space:nowrap;">
                      <?= date('d/m/y H:i', strtotime($ord['created_at'])) ?>
                    </td>
                    <td style="text-align:center; white-space:nowrap;">
                      <form method="POST" action="admin.php" style="display:inline-block;" onsubmit="return confirm('ยืนยันส่งใบเสร็จซ้ำเข้าอีเมลลูกค้านะคะ?');">
                        <input type="hidden" name="action" value="resend_receipt">
                        <input type="hidden" name="order_id" value="<?= htmlspecialchars($ord['order_id']) ?>">
                        <input type="hidden" name="active_tab" value="orders">
                        <button type="submit" class="btn-action btn-resend" title="ส่งใบเสร็จซ้ำผ่าน PHP">
                          <i class="fa-solid fa-envelope"></i> ส่งใบเสร็จซ้ำ
                        </button>
                      </form>
                      
                      <button type="button" class="btn-action" onclick='viewOrderModal(<?= json_encode($ord, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                        <i class="fa-solid fa-eye"></i> ดู
                      </button>

                      <form method="POST" action="admin.php" style="display:inline-block;" onsubmit="return confirm('คุณต้องการลบคำสั่งซื้อ #<?= htmlspecialchars($ord['order_id']) ?> ใช่หรือไม่? (ลบแล้วกู้คืนไม่ได้นะคะ)');">
                        <input type="hidden" name="action" value="delete_order">
                        <input type="hidden" name="order_id" value="<?= htmlspecialchars($ord['order_id']) ?>">
                        <input type="hidden" name="active_tab" value="orders">
                        <button type="submit" class="btn-action btn-delete" title="ลบออเดอร์นี้">
                          <i class="fa-solid fa-trash-can"></i> ลบ
                        </button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- ================= TAB 2: VIP CRM ================= -->
    <div id="tab-vip" class="tab-pane <?= $activeTab === 'vip' ? 'active' : '' ?>">
      <div class="table-card">
        <div class="table-header-row">
          <div class="table-title">
            <i class="fa-solid fa-crown" style="color:#d4a373;"></i> 
            รายชื่อสมาชิก Daily Brew VIP Club (CDP Database)
          </div>
          <div style="font-size:12.5px; color:var(--text-muted);">
            ระบบสมาชิกระดับโกลด์ ส่วนลด 20% และสะสมแต้ม
          </div>
        </div>

        <?php if (empty($vipsList)): ?>
          <p style="text-align:center; padding:30px; color:#888;">ยังไม่มีข้อมูลสมาชิก VIP ในระบบ</p>
        <?php else: ?>
          <div style="overflow-x:auto;">
            <table>
              <thead>
                <tr>
                  <th>รหัสสมาชิก</th>
                  <th>ชื่อ-นามสกุล</th>
                  <th>เบอร์โทร</th>
                  <th>อีเมล</th>
                  <th>กาแฟแก้วโปรด</th>
                  <th>แต้มสะสม</th>
                  <th>โค้ดประจำตัว</th>
                  <th>วันที่สมัคร</th>
                  <th style="text-align:center;">ปรับแต้ม</th>
                  <th style="text-align:center;">จัดการ</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($vipsList as $vip): ?>
                  <tr>
                    <td>
                      <span class="badge badge-vip"><?= htmlspecialchars($vip['member_id']) ?></span>
                    </td>
                    <td><strong><?= htmlspecialchars($vip['fullname']) ?></strong></td>
                    <td><?= htmlspecialchars($vip['phone']) ?></td>
                    <td><?= htmlspecialchars($vip['email']) ?></td>
                    <td>
                      <span style="font-size:12px; color:var(--caramel);">
                        ☕ <?= htmlspecialchars($vip['favorite_coffee'] ?: '-') ?> (<?= htmlspecialchars($vip['milk_type'] ?: 'นมสด') ?>)
                      </span>
                    </td>
                    <td>
                      <strong style="color:var(--green); font-size:15px;"><?= number_format($vip['points']) ?> PTS</strong>
                    </td>
                    <td>
                      <code style="background:#f4ede4; padding:2px 6px; border-radius:4px; font-weight:bold; color:var(--caramel);">
                        <?= htmlspecialchars($vip['promo_code']) ?>
                      </code>
                    </td>
                    <td style="font-size:11.5px; color:#777; white-space:nowrap;">
                      <?= date('d/m/y H:i', strtotime($vip['created_at'])) ?>
                    </td>
                    <td style="text-align:center; white-space:nowrap;">
                      <form method="POST" action="admin.php" style="display:inline-block;">
                        <input type="hidden" name="action" value="update_vip_points">
                        <input type="hidden" name="member_id" value="<?= htmlspecialchars($vip['member_id']) ?>">
                        <input type="hidden" name="points_delta" value="50">
                        <input type="hidden" name="active_tab" value="vip">
                        <button type="submit" class="btn-action" title="เพิ่ม 50 แต้ม">
                          <i class="fa-solid fa-plus"></i> 50 แต้ม
                        </button>
                      </form>
                      <form method="POST" action="admin.php" style="display:inline-block;">
                        <input type="hidden" name="action" value="update_vip_points">
                        <input type="hidden" name="member_id" value="<?= htmlspecialchars($vip['member_id']) ?>">
                        <input type="hidden" name="points_delta" value="-50">
                        <input type="hidden" name="active_tab" value="vip">
                        <button type="submit" class="btn-action" style="color:#c0392b;" title="ลด 50 แต้ม">
                          <i class="fa-solid fa-minus"></i> 50
                        </button>
                      </form>
                    </td>
                    <td style="text-align:center; white-space:nowrap;">
                      <form method="POST" action="admin.php" style="display:inline-block;" onsubmit="return confirm('คุณต้องการลบสมาชิก VIP คุณ <?= htmlspecialchars($vip['fullname']) ?> (<?= htmlspecialchars($vip['member_id']) ?>) ใช่หรือไม่? (ลบแล้วกู้คืนไม่ได้นะคะ)');">
                        <input type="hidden" name="action" value="delete_vip">
                        <input type="hidden" name="member_id" value="<?= htmlspecialchars($vip['member_id']) ?>">
                        <input type="hidden" name="active_tab" value="vip">
                        <button type="submit" class="btn-action btn-delete" title="ลบสมาชิกท่านนี้">
                          <i class="fa-solid fa-trash-can"></i> ลบ
                        </button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- ================= TAB 3: STOCK & MENU ================= -->
    <div id="tab-stock" class="tab-pane <?= $activeTab === 'stock' ? 'active' : '' ?>">
      <div class="table-card">
        <div class="table-header-row">
          <div class="table-title">
            <i class="fa-solid fa-mug-hot" style="color:var(--caramel);"></i> 
            จัดการสถานะสต็อกเมนูเครื่องดื่มและเบเกอรี
          </div>
          <div style="font-size:12.5px; color:var(--text-muted);">
            สวิตช์เปิด-ปิดสถานะสินค้า หากของหมดสามารถปิดได้ทันที
          </div>
        </div>

        <div style="overflow-x:auto;">
          <table>
            <thead>
              <tr>
                <th>เมนู</th>
                <th>หมวดหมู่</th>
                <th>ราคาปกติ</th>
                <th>สถานะสต็อกปัจจุบัน</th>
                <th style="text-align:center;">สลับสถานะ</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($menuStock as $key => $item): ?>
                <tr>
                  <td>
                    <strong><?= htmlspecialchars($item['nameTh']) ?></strong><br>
                    <span style="font-size:11.5px; color:#777;"><?= htmlspecialchars($item['name']) ?></span>
                  </td>
                  <td>
                    <span class="badge" style="background:#f4ede4; color:var(--caramel);">
                      <?= htmlspecialchars($item['category']) ?>
                    </span>
                  </td>
                  <td><strong><?= number_format($item['price']) ?> ฿</strong></td>
                  <td>
                    <?php if ($item['in_stock']): ?>
                      <span class="badge badge-done">พร้อมเสิร์ฟ 🟢</span>
                    <?php else: ?>
                      <span class="badge badge-cancel">ของหมดชั่วคราว 🔴</span>
                    <?php endif; ?>
                  </td>
                  <td style="text-align:center;">
                    <form method="POST" action="admin.php" style="display:inline-block;">
                      <input type="hidden" name="action" value="toggle_stock">
                      <input type="hidden" name="item_key" value="<?= htmlspecialchars($key) ?>">
                      <input type="hidden" name="active_tab" value="stock">
                      <button type="submit" class="btn-action" style="font-size:12px;">
                        <i class="fa-solid fa-repeat"></i> เปลี่ยนเป็น <?= $item['in_stock'] ? 'ของหมด' : 'พร้อมเสิร์ฟ' ?>
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- ================= TAB 4: SUBSCRIBERS & BROADCAST ================= -->
    <div id="tab-subscribers" class="tab-pane <?= $activeTab === 'subscribers' ? 'active' : '' ?>">
      <div class="table-card">
        <div class="table-header-row">
          <div class="table-title">
            <i class="fa-solid fa-bullhorn" style="color:var(--caramel);"></i> 
            ส่งข่าวสารโปรโมชั่น (Marketing Broadcast)
          </div>
        </div>

        <!-- Broadcast Form -->
        <div class="broadcast-box">
          <form method="POST" action="admin.php" onsubmit="return confirm('ยืนยันส่งอีเมล Broadcast ไปยังผู้ติดตามทุกคนนะคะ?');">
            <input type="hidden" name="action" value="broadcast_newsletter">
            <input type="hidden" name="active_tab" value="subscribers">
            <label style="font-size:13px; font-weight:600; color:var(--espresso); display:block; margin-bottom:6px;">
              หัวข้ออีเมลแคมเปญ (Email Subject):
            </label>
            <input type="text" name="broadcast_subject" class="form-input" placeholder="เช่น โปรสุดสัปดาห์ ลด 20% ทุกเมนู Specialty Coffee!" required>

            <label style="font-size:13px; font-weight:600; color:var(--espresso); display:block; margin-bottom:6px;">
              เนื้อหาข้อความประชาสัมพันธ์ (Message):
            </label>
            <textarea name="broadcast_message" class="form-input" rows="4" placeholder="พิมพ์ข้อความที่ต้องการแจ้งลูกค้า..." required></textarea>

            <button type="submit" class="btn-action" style="background:var(--caramel); color:white; padding:10px 18px; font-weight:600; font-size:14px; border:none;">
              <i class="fa-solid fa-paper-plane"></i> กดส่ง Broadcast ถึงผู้ติดตามทุกคน (<?= count($subscribersList) ?> ท่าน)
            </button>
          </form>
        </div>

        <div class="table-header-row" style="margin-top:20px;">
          <div class="table-title" style="font-size:15px;">
            <i class="fa-solid fa-users"></i> รายชื่อผู้ลงทะเบียนรับข่าวสาร (Subscribers)
          </div>
        </div>

        <div style="overflow-x:auto;">
          <table>
            <thead>
              <tr>
                <th>ลำดับ</th>
                <th>อีเมลผู้ติดตาม</th>
                <th>โค้ดต้อนรับ</th>
                <th>วันที่ลงทะเบียน</th>
                <th style="text-align:center;">จัดการ</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($subscribersList)): ?>
                <tr><td colspan="5" style="text-align:center; color:#888;">ยังไม่มีผู้ติดตามในระบบ</td></tr>
              <?php else: ?>
                <?php foreach ($subscribersList as $idx => $sub): ?>
                  <tr>
                    <td><?= $idx + 1 ?></td>
                    <td><strong><?= htmlspecialchars($sub['email']) ?></strong></td>
                    <td><code><?= htmlspecialchars($sub['promo_code'] ?? 'WELCOME10') ?></code></td>
                    <td style="color:#777; font-size:12px;"><?= date('d/m/y H:i', strtotime($sub['subscribed_at'] ?? 'now')) ?></td>
                    <td style="text-align:center; white-space:nowrap;">
                      <form method="POST" action="admin.php" style="display:inline-block;" onsubmit="return confirm('คุณต้องการลบอีเมล <?= htmlspecialchars($sub['email']) ?> ออกจากรายชื่อผู้ติดตามใช่หรือไม่? (ลบแล้วกู้คืนไม่ได้นะคะ)');">
                        <input type="hidden" name="action" value="delete_subscriber">
                        <input type="hidden" name="subscriber_id" value="<?= intval($sub['id']) ?>">
                        <input type="hidden" name="subscriber_email" value="<?= htmlspecialchars($sub['email']) ?>">
                        <input type="hidden" name="active_tab" value="subscribers">
                        <button type="submit" class="btn-action btn-delete" title="ลบอีเมลนี้">
                          <i class="fa-solid fa-trash-can"></i> ลบ
                        </button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- ================= TAB 5: SYSTEM HEALTH ================= -->
    <div id="tab-system" class="tab-pane <?= $activeTab === 'system' ? 'active' : '' ?>">
      <div class="table-card">
        <div class="table-header-row">
          <div class="table-title">
            <i class="fa-solid fa-microchip" style="color:var(--caramel);"></i> 
            สถานะเซิร์ฟเวอร์และการเชื่อมต่อ (System Diagnostics)
          </div>
          <div>
            <a href="test_mail.php" target="_blank" class="btn-action" style="background:#eafaf1; color:#1e824c; border-color:#2ecc71;">
              <i class="fa-solid fa-vial"></i> เปิดหน้าทดสอบส่งเมล PHP (test_mail.php)
            </a>
          </div>
        </div>

        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap:15px; font-size:13px;">
          <div style="background:#faf6f1; padding:15px; border-radius:10px; border:1px solid var(--border-color);">
            <strong style="color:var(--espresso); display:block; margin-bottom:8px;">🐘 PHP Environment:</strong>
            <div>PHP Version: <strong><?= phpversion() ?></strong></div>
            <div>Server Software: <strong><?= htmlspecialchars($_SERVER['SERVER_SOFTWARE'] ?? 'PHP CLI Server') ?></strong></div>
            <div>Max Execution Time: <strong><?= ini_get('max_execution_time') ?>s</strong></div>
          </div>

          <div style="background:#faf6f1; padding:15px; border-radius:10px; border:1px solid var(--border-color);">
            <strong style="color:var(--espresso); display:block; margin-bottom:8px;">🗄️ MySQL Database:</strong>
            <div>สถานะ: <strong><?= $dbConnected ? 'เชื่อมต่อสำเร็จ (Online)' : 'ไม่สามารถเชื่อมต่อได้' ?></strong></div>
            <div>Database: <strong><?= htmlspecialchars($dbName) ?></strong></div>
            <div>Host & Port: <strong><?= htmlspecialchars($dbHost) ?>:<?= htmlspecialchars($dbPort) ?></strong></div>
          </div>

          <div style="background:#faf6f1; padding:15px; border-radius:10px; border:1px solid var(--border-color);">
            <strong style="color:var(--espresso); display:block; margin-bottom:8px;">✉️ Gmail SMTP (PHPMailer):</strong>
            <div>SMTP Host: <strong><?= htmlspecialchars(SMTP_HOST) ?></strong></div>
            <div>SMTP Port: <strong><?= htmlspecialchars(SMTP_PORT) ?> (<?= htmlspecialchars(SMTP_SECURE) ?>)</strong></div>
            <div>บัญชีผู้ส่ง: <strong><?= htmlspecialchars(SMTP_USER) ?></strong></div>
          </div>
        </div>
      </div>
    </div>

  </main>

  <!-- Modal for Order Full Details -->
  <div class="modal-overlay" id="orderModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3 style="color:var(--espresso); font-size:16px;">
          <i class="fa-solid fa-receipt"></i> รายละเอียดคำสั่งซื้อ <span id="mOrderId" style="color:var(--caramel);"></span>
        </h3>
        <button class="close-modal-btn" onclick="closeOrderModal()">&times;</button>
      </div>

      <div id="mOrderDetails" style="font-size:13px; line-height:1.7;">
        <!-- Injected via JS -->
      </div>
    </div>
  </div>

  <script>
    function switchTab(tabId, btn) {
      document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
      document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));

      if (btn) {
        btn.classList.add('active');
      } else {
        const targetBtn = document.querySelector(`.tab-btn[onclick*="'${tabId}'"]`);
        if (targetBtn) targetBtn.classList.add('active');
      }
      const targetPane = document.getElementById('tab-' + tabId);
      if (targetPane) targetPane.classList.add('active');
      try {
        localStorage.setItem('adminActiveTab', tabId);
      } catch(e) {}
    }

    window.addEventListener('DOMContentLoaded', () => {
      const phpTab = '<?= $activeTab ?>';
      if (phpTab && document.getElementById('tab-' + phpTab)) {
        switchTab(phpTab);
      } else {
        const savedTab = localStorage.getItem('adminActiveTab');
        if (savedTab && document.getElementById('tab-' + savedTab)) {
          switchTab(savedTab);
        }
      }
    });

    function viewOrderModal(ord) {
      document.getElementById('mOrderId').innerText = ord.order_id;
      
      let itemsListHtml = '';
      try {
        const items = JSON.parse(ord.items_json);
        itemsListHtml = items.map(it => `
          <div style="display:flex; justify-content:space-between; padding:6px 0; border-bottom:1px dashed #eee;">
            <div>
              <strong>${it.name}</strong> x${it.qty}
              ${it.options ? `<div style="font-size:11px; color:#888;">${it.options}</div>` : ''}
            </div>
            <div><strong>${(it.price * it.qty).toFixed(2)} ฿</strong></div>
          </div>
        `).join('');
      } catch(e) {
        itemsListHtml = `<div>${ord.items_summary}</div>`;
      }

      document.getElementById('mOrderDetails').innerHTML = `
        <div style="background:#faf6f1; padding:12px; border-radius:8px; margin-bottom:12px;">
          <div><strong>ลูกค้า:</strong> ${ord.customer_name}</div>
          <div><strong>อีเมล:</strong> ${ord.customer_email}</div>
          <div><strong>เบอร์โทร:</strong> ${ord.customer_phone || '-'}</div>
          <div><strong>ที่อยู่จัดส่ง:</strong> ${ord.customer_address || 'เดลิเวอรี'}</div>
          <div><strong>วิธีชำระเงิน:</strong> ${ord.payment_method}</div>
          <div><strong>สถานะปัจจุบัน:</strong> <span style="color:#d4a373; font-weight:bold;">${ord.status}</span></div>
          <div><strong>วันที่สั่งซื้อ:</strong> ${ord.created_at}</div>
        </div>

        <h4 style="margin:10px 0 6px 0; color:#3e2723;">รายการกาแฟและสินค้า:</h4>
        <div style="background:#fff; border:1px solid #ebd9c8; border-radius:8px; padding:10px; margin-bottom:12px;">
          ${itemsListHtml}
        </div>

        <div style="background:#faf6f1; padding:10px 12px; border-radius:8px; display:flex; justify-content:space-between; font-size:14px;">
          <span>ยอดรวมสุทธิ:</span>
          <strong style="color:#8c531b; font-size:16px;">${parseFloat(ord.total).toFixed(2)} ฿</strong>
        </div>
      `;

      document.getElementById('orderModal').classList.add('active');
    }

    function closeOrderModal() {
      document.getElementById('orderModal').classList.remove('active');
    }
  </script>

</body>
</html>
