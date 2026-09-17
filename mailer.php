<?php
/**
 * ==============================================================================
 * Daily Brew — โมดูลส่งอีเมลจริงผ่าน Gmail SMTP (PHPMailer)
 * ==============================================================================
 * สำหรับใช้ส่งอีเมลจริงด้วยภาษา PHP ผ่านเซิร์ฟเวอร์ของ Gmail (smtp.gmail.com)
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/phpmailer/Exception.php';
require_once __DIR__ . '/phpmailer/PHPMailer.php';
require_once __DIR__ . '/phpmailer/SMTP.php';

// ==============================================================================
// ⚙️ การตั้งค่า GMAIL SMTP สำหรับส่งอีเมลจริง
// ==============================================================================
$localConfig = file_exists(__DIR__ . '/config.local.php') ? require __DIR__ . '/config.local.php' : [];

define('SMTP_HOST', $localConfig['smtp_host'] ?? 'smtp.gmail.com');
define('SMTP_USER', $localConfig['smtp_user'] ?? 'pomsanbeem9@gmail.com');       // อีเมล Gmail ผู้ส่ง
define('SMTP_PASS', $localConfig['smtp_pass'] ?? 'YOUR_GMAIL_APP_PASSWORD');   // ดึงจาก config.local.php
define('SMTP_PORT', $localConfig['smtp_port'] ?? 465);                          // พอร์ต SSL ของ Gmail (465)
define('SMTP_SECURE', $localConfig['smtp_secure'] ?? 'ssl');                    // 'ssl' หรือ 'tls'

/**
 * ฟังก์ชันกลางสำหรับส่งอีเมล HTML แบบ UTF-8
 * 
 * @param string $toEmail   อีเมลปลายทางผู้รับ
 * @param string $subject   หัวข้ออีเมล
 * @param string $htmlBody  เนื้อหาอีเมลแบบ HTML
 * @param string $fromName  ชื่อผู้ส่งที่แสดงในกล่องจดหมาย
 * @return array            ผลลัพธ์การส่ง ['success' => bool, 'driver' => string, 'error' => string|null]
 */
function sendDailyBrewMail($toEmail, $subject, $htmlBody, $fromName = 'Daily Brew Coffee') {
    // ตรวจสอบว่าได้ใส่รหัสผ่านแอป Gmail ไว้หรือยัง
    $appPassword = trim(str_replace(' ', '', SMTP_PASS));
    $isSmtpConfigured = (!empty($appPassword) && $appPassword !== 'YOUR_GMAIL_APP_PASSWORD');

    if ($isSmtpConfigured) {
        $mail = new PHPMailer(true);
        try {
            // ตั้งค่าการเชื่อมต่อ SMTP
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USER;
            $mail->Password   = $appPassword;
            $mail->SMTPSecure = SMTP_SECURE;
            $mail->Port       = SMTP_PORT;
            $mail->CharSet    = 'UTF-8';

            // ผู้ส่งและผู้รับ
            $mail->setFrom(SMTP_USER, $fromName);
            $mail->addAddress($toEmail);
            $mail->addReplyTo(SMTP_USER, $fromName);

            // ฝังรูปภาพลงในอีเมลโดยตรง (Inline CID Attachment)
            // ทำให้รูปภาพแสดงผล 100% เสมอในทุกอุปกรณ์ โดยไม่ต้องโหลดจากภายนอก
            $vipBannerPath = __DIR__ . '/assets/email_banner_vip.jpg';
            if (file_exists($vipBannerPath) && stripos($htmlBody, 'email_banner_vip') !== false) {
                $mail->addEmbeddedImage($vipBannerPath, 'email_banner_vip', 'email_banner_vip.jpg', 'base64', 'image/jpeg');
                $htmlBody = preg_replace('/src="[^"]*email_banner_vip\.jpg[^"]*"/', 'src="cid:email_banner_vip"', $htmlBody);
            }

            $newsBannerPath = __DIR__ . '/assets/email_banner_newsletter.jpg';
            if (file_exists($newsBannerPath) && stripos($htmlBody, 'email_banner_newsletter') !== false) {
                $mail->addEmbeddedImage($newsBannerPath, 'email_banner_newsletter', 'email_banner_newsletter.jpg', 'base64', 'image/jpeg');
                $htmlBody = preg_replace('/src="[^"]*email_banner_newsletter\.jpg[^"]*"/', 'src="cid:email_banner_newsletter"', $htmlBody);
            }

            $orderBannerPath = __DIR__ . '/assets/email_banner_order.jpg';
            if (file_exists($orderBannerPath) && stripos($htmlBody, 'email_banner_order') !== false) {
                $mail->addEmbeddedImage($orderBannerPath, 'email_banner_order', 'email_banner_order.jpg', 'base64', 'image/jpeg');
                $htmlBody = preg_replace('/src="[^"]*email_banner_order\.jpg[^"]*"/', 'src="cid:email_banner_order"', $htmlBody);
            }

            // เนื้อหาอีเมล
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $htmlBody;

            $mail->send();
            return [
                'success' => true,
                'driver'  => 'gmail_smtp',
                'message' => 'ส่งอีเมลผ่าน Gmail SMTP สำเร็จแล้วค่ะ!'
            ];
        } catch (Exception $e) {
            error_log('PHPMailer Error: ' . $mail->ErrorInfo);
            return [
                'success' => false,
                'driver'  => 'gmail_smtp',
                'error'   => $mail->ErrorInfo,
                'message' => 'ส่งผ่าน Gmail SMTP ไม่สำเร็จ: ' . $mail->ErrorInfo
            ];
        }
    }

    // หากยังไม่ได้ใส่รหัสผ่านแอป ให้ใช้คำสั่ง mail() พื้นฐานของ PHP (Fallback)
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: Daily Brew Coffee <no-reply@dailybrew.cafe>\r\n";
    $headers .= "Reply-To: no-reply@dailybrew.cafe\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

    $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
    $sent = @mail($toEmail, $encodedSubject, $htmlBody, $headers);

    return [
        'success' => $sent,
        'driver'  => 'php_mail',
        'message' => $sent ? 'ส่งผ่านคำสั่ง mail() สำเร็จ' : 'ส่งผ่านคำสั่ง mail() ไม่สำเร็จ (ต้องตั้งค่า sendmail หรือใส่รหัสผ่าน Gmail SMTP)'
    ];
}
