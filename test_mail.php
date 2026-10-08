<?php
/**
 * ==============================================================================
 * Daily Brew — เครื่องมือทดสอบส่งอีเมลด้วย PHP (Gmail SMTP / PHPMailer)
 * (PHP Mailer Testing Dashboard)
 * ==============================================================================
 * เปิดใช้งานผ่านเบราว์เซอร์ที่: http://localhost:8000/test_mail.php
 */

require_once __DIR__ . '/mailer.php';

$sentSuccess = null;
$resultMessage = '';
$recipientEmail = $_POST['email'] ?? 'pomsanbeem9@gmail.com';
$emailType = $_POST['email_type'] ?? 'order';
$customerName = $_POST['customer_name'] ?? 'คุณภัทร (ลูกค้าคนพิเศษ)';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipientEmail = trim($recipientEmail);
    
    if (!filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
        $sentSuccess = false;
        $resultMessage = 'กรุณาระบุอีเมลผู้รับที่ถูกต้องค่ะ';
    } else {
        // เตรียมเนื้อหาตามประเภทที่เลือก
        $orderId = 'DB-ORD-' . date('Y') . '-' . rand(1000, 9999);
        $orderDate = date('d/m/Y, H:i น.');
        
        if ($emailType === 'order') {
            // 1. ใบเสร็จคำสั่งซื้อ
            $templatePath = __DIR__ . '/email-template-order.html';
            $html = file_exists($templatePath) ? file_get_contents($templatePath) : '<h2>ใบเสร็จ Daily Brew</h2>';
            
            $itemsHtml = '
              <tr>
                <td style="padding: 10px; border-bottom: 1px solid #f0e6dc; font-size: 13px;">
                  <strong style="color: #2c1a11;">Signature Dirty Coffee</strong><br>
                  <span style="font-size:11px; color:#8c7668;">หวาน 50%, นมสดสูตรลับเย็นจัด</span>
                </td>
                <td align="center" style="padding: 10px; border-bottom: 1px solid #f0e6dc; font-size: 13px;">x1</td>
                <td align="right" style="padding: 10px; border-bottom: 1px solid #f0e6dc; font-size: 13px; font-weight:700;">120.00 ฿</td>
              </tr>
              <tr>
                <td style="padding: 10px; border-bottom: 1px solid #f0e6dc; font-size: 13px;">
                  <strong style="color: #2c1a11;">French Almond Croissant</strong><br>
                  <span style="font-size:11px; color:#8c7668;">อบร้อนพร้อมทาน</span>
                </td>
                <td align="center" style="padding: 10px; border-bottom: 1px solid #f0e6dc; font-size: 13px;">x1</td>
                <td align="right" style="padding: 10px; border-bottom: 1px solid #f0e6dc; font-size: 13px; font-weight:700;">85.00 ฿</td>
              </tr>
            ';

            $replacements = [
                '{{order_id}}'        => $orderId,
                '{{order_date}}'      => $orderDate,
                '{{customer_name}}'   => htmlspecialchars($customerName),
                '{{customer_email}}'  => htmlspecialchars($recipientEmail),
                '{{customer_phone}}'  => '081-234-5678',
                '{{customer_address}}'=> 'จัดส่งด่วน (Delivery Express) กรุงเทพฯ',
                '{{payment_method}}'  => 'PromptPay QR',
                '{{items_html}}'      => $itemsHtml,
                '{{subtotal}}'        => '205.00 ฿',
                '{{discount}}'        => '20.50 ฿',
                '{{coupon_code}}'     => 'WELCOME10 (-10%)',
                '{{total}}'           => '184.50 ฿',
                '{{points_earned}}'   => '18',
                '{{delivery_status}}' => 'กำลังเตรียมจัดส่งด่วน 🛵'
            ];
            
            $subject = "☕ [ทดสอบ PHP] ใบเสร็จรับเงินและยืนยันคำสั่งซื้อ #{$orderId} — Daily Brew";
            $body = str_replace(array_keys($replacements), array_values($replacements), $html);
            
        } elseif ($emailType === 'vip') {
            // 2. ต้อนรับสมาชิก VIP
            $templatePath = __DIR__ . '/email-template-vip.html';
            $html = file_exists($templatePath) ? file_get_contents($templatePath) : '<h2>ยินดีต้อนรับสมาชิก VIP Daily Brew</h2>';
            
            $memberId = 'VIP-GOLD-' . rand(1000, 9999);
            $replacements = [
                '{{customer_name}}' => htmlspecialchars($customerName),
                '{{customer_email}}'=> htmlspecialchars($recipientEmail),
                '{{member_id}}'     => $memberId,
                '{{reg_date}}'      => $orderDate,
                '{{points}}'        => '100',
                '{{coupon_code}}'   => 'DAILYVIP20'
            ];
            
            $subject = "👑 [ทดสอบ PHP] ยินดีต้อนรับสู่ Daily Brew VIP Club — บัตรสมาชิกระดับโกลด์ของคุณ";
            $body = str_replace(array_keys($replacements), array_values($replacements), $html);
            
        } else {
            // 3. Newsletter Welcome
            $templatePath = __DIR__ . '/email-template-newsletter.html';
            $html = file_exists($templatePath) ? file_get_contents($templatePath) : '<h2>ขอบคุณที่ติดตาม Daily Brew</h2>';
            
            $replacements = [
                '{{customer_email}}'=> htmlspecialchars($recipientEmail),
                '{{coupon_code}}'   => 'WELCOME10',
                '{{subscribe_date}}'=> $orderDate
            ];
            
            $subject = "☕ [ทดสอบ PHP] ขอบคุณที่ติดตาม Daily Brew Journal — รับส่วนลด 10% สำหรับคุณ";
            $body = str_replace(array_keys($replacements), array_values($replacements), $html);
        }

        // ยิงส่งผ่าน PHP Mailer (Gmail SMTP)
        $res = sendDailyBrewMail($recipientEmail, $subject, $body, 'Daily Brew Coffee');
        $sentSuccess = $res['success'];
        $resultMessage = $sentSuccess 
            ? "ส่งอีเมลสำเร็จเรียบร้อยแล้วค่ะ! ✉️ (เครื่องยนต์: {$res['driver']})" 
            : "ส่งไม่สำเร็จ: " . ($res['error'] ?? 'เกิดข้อผิดพลาดในการเชื่อมต่อ SMTP');
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ทดสอบส่งอีเมลผ่าน PHP — Daily Brew Coffee</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    * { box-sizing: border-box; }
    body {
      font-family: 'Prompt', sans-serif;
      background: #f7f3ee;
      color: #2c1a11;
      margin: 0;
      padding: 30px 15px;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }
    .card {
      background: #ffffff;
      max-width: 620px;
      width: 100%;
      border-radius: 18px;
      padding: 35px 30px;
      box-shadow: 0 15px 35px rgba(44, 26, 17, 0.08);
      border: 1px solid #ebd9c8;
    }
    .header {
      text-align: center;
      margin-bottom: 25px;
      border-bottom: 2px dashed #ebd9c8;
      padding-bottom: 20px;
    }
    .header h1 {
      font-size: 22px;
      color: #3e2723;
      margin: 0 0 6px 0;
      font-weight: 700;
    }
    .header p {
      font-size: 13px;
      color: #8c531b;
      margin: 0;
    }
    .badge-php {
      display: inline-block;
      background: #4f5d95;
      color: white;
      font-size: 11px;
      padding: 3px 10px;
      border-radius: 12px;
      font-weight: 600;
      margin-bottom: 10px;
    }
    .form-group {
      margin-bottom: 18px;
    }
    label {
      display: block;
      font-size: 13px;
      font-weight: 600;
      color: #4a2810;
      margin-bottom: 6px;
    }
    input[type="text"], input[type="email"], select {
      width: 100%;
      padding: 12px 14px;
      border: 1.5px solid #d4a373;
      border-radius: 10px;
      font-size: 14px;
      font-family: inherit;
      outline: none;
      background: #fffdfa;
      transition: all 0.2s;
    }
    input:focus, select:focus {
      border-color: #8c531b;
      box-shadow: 0 0 0 3px rgba(140, 83, 27, 0.15);
    }
    .radio-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 10px;
    }
    .radio-card {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px 14px;
      border: 1.5px solid #ebd9c8;
      border-radius: 10px;
      cursor: pointer;
      background: #faf6f1;
      transition: all 0.2s;
    }
    .radio-card:hover {
      border-color: #8c531b;
      background: #fdfaf6;
    }
    .btn-submit {
      width: 100%;
      background: linear-gradient(135deg, #8c531b, #4a2810);
      color: white;
      border: none;
      padding: 14px;
      border-radius: 10px;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
      font-family: inherit;
      box-shadow: 0 6px 15px rgba(140, 83, 27, 0.25);
      transition: all 0.2s;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 8px;
    }
    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(140, 83, 27, 0.35);
    }
    .alert-success {
      background: #eafaf1;
      border: 1.5px solid #2ecc71;
      color: #1e824c;
      padding: 14px 16px;
      border-radius: 10px;
      font-size: 13.5px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .alert-danger {
      background: #fdeeed;
      border: 1.5px solid #e74c3c;
      color: #c0392b;
      padding: 14px 16px;
      border-radius: 10px;
      font-size: 13.5px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .footer-link {
      text-align: center;
      margin-top: 20px;
      font-size: 12.5px;
    }
    .footer-link a {
      color: #8c531b;
      text-decoration: none;
      font-weight: 600;
    }
  </style>
</head>
<body>

  <div class="card">
    <div class="header">
      <span class="badge-php"><i class="fa-brands fa-php"></i> PURE PHP + PHPMailer</span>
      <h1>☕ ทดสอบส่งอีเมลด้วย PHP (Gmail SMTP)</h1>
      <p>ระบบส่งอีเมลฉบับจริง ไม่ผ่าน EmailJS ทดสอบการทำงานของ Backend</p>
    </div>

    <?php if ($sentSuccess === true): ?>
      <div class="alert-success">
        <i class="fa-solid fa-circle-check" style="font-size: 20px;"></i>
        <div>
          <strong><?= htmlspecialchars($resultMessage) ?></strong><br>
          <span style="font-size: 12px; color: #145a32;">
            กรุณาเปิดตรวจสอบกล่องจดหมายที่ <strong><?= htmlspecialchars($recipientEmail) ?></strong> ได้ทันทีค่ะ! (หากไม่พบ ลองดูในโฟลเดอร์ Spam/ถังขยะ)
          </span>
        </div>
      </div>
    <?php elseif ($sentSuccess === false): ?>
      <div class="alert-danger">
        <i class="fa-solid fa-circle-exclamation" style="font-size: 20px;"></i>
        <div>
          <strong><?= htmlspecialchars($resultMessage) ?></strong>
        </div>
      </div>
    <?php endif; ?>

    <form method="POST" action="test_mail.php">
      
      <div class="form-group">
        <label><i class="fa-solid fa-envelope"></i> อีเมลปลายทางผู้รับ (ใส่เมลของคุณเพื่อทดสอบ):</label>
        <input type="email" name="email" value="<?= htmlspecialchars($recipientEmail) ?>" required placeholder="your-email@gmail.com">
      </div>

      <div class="form-group">
        <label><i class="fa-solid fa-user"></i> ชื่อลูกค้า:</label>
        <input type="text" name="customer_name" value="<?= htmlspecialchars($customerName) ?>" required>
      </div>

      <div class="form-group">
        <label><i class="fa-solid fa-file-lines"></i> เลือกแม่แบบอีเมลที่ต้องการทดสอบ:</label>
        <div class="radio-grid">
          <label class="radio-card">
            <input type="radio" name="email_type" value="order" <?= $emailType === 'order' ? 'checked' : '' ?>>
            <div>
              <strong>🧾 ใบเสร็จรับเงินคำสั่งซื้อ (Order Receipt)</strong><br>
              <span style="font-size: 11.5px; color: #666;">ส่งสรุปออเดอร์กาแฟ Dirty Coffee + ครัวซองต์ พร้อมยอดเงินและแต้มสะสม</span>
            </div>
          </label>

          <label class="radio-card">
            <input type="radio" name="email_type" value="vip" <?= $emailType === 'vip' ? 'checked' : '' ?>>
            <div>
              <strong>👑 ต้อนรับสมาชิก VIP Club (VIP Welcome)</strong><br>
              <span style="font-size: 11.5px; color: #666;">ส่งบัตรสมาชิก VIP Gold พร้อมโค้ดลด 20% DAILYVIP20 และแต้มฟรี 100 แต้ม</span>
            </div>
          </label>

          <label class="radio-card">
            <input type="radio" name="email_type" value="newsletter" <?= $emailType === 'newsletter' ? 'checked' : '' ?>>
            <div>
              <strong>💌 ต้อนรับจดหมายข่าว (Newsletter Welcome)</strong><br>
              <span style="font-size: 11.5px; color: #666;">ส่งโค้ดลด 10% WELCOME10 สำหรับลูกค้าใหม่</span>
            </div>
          </label>
        </div>
      </div>

      <button type="submit" class="btn-submit">
        <i class="fa-solid fa-paper-plane"></i> ยืนยันกดส่งอีเมลผ่าน PHP ทันที
      </button>

    </form>

    <div class="footer-link">
      <a href="index.html"><i class="fa-solid fa-arrow-left"></i> กลับไปที่หน้าหลัก Daily Brew</a> | 
      <a href="http://localhost:8000" target="_blank">เปิดผ่าน Localhost Server</a>
    </div>
  </div>

</body>
</html>
