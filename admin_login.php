<?php
/**
 * ==============================================================================
 * Daily Brew — หน้าเข้าสู่ระบบผู้ดูแลร้าน (Admin Login)
 * ==============================================================================
 * สำหรับผู้จัดการร้านและแอดมิน เพื่อเข้าสู่ระบบจัดการหลังบ้าน (Admin Hub)
 */

session_start();

// ถ้าล็อกอินอยู่แล้ว ให้ไปที่หน้า admin.php ทันที
if (!empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: admin.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // รหัสผ่านตามที่กำหนด: User: admin, Pass: 123456
    if ($username === 'admin' && $password === '123456') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user'] = 'admin';
        $_SESSION['admin_name'] = 'ผู้จัดการร้าน Daily Brew';
        $_SESSION['admin_login_time'] = date('d/m/Y H:i:s');
        
        header('Location: admin.php');
        exit;
    } else {
        $error = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง กรุณาตรวจสอบอีกครั้งค่ะ';
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>เข้าสู่ระบบผู้ดูแลร้าน — Daily Brew Admin</title>
  
  <!-- Fonts: Prompt -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    body {
      font-family: 'Prompt', sans-serif;
      background: radial-gradient(circle at center, #2e1a12 0%, #150b07 100%);
      color: #f7f3ee;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }
    .login-card {
      background: rgba(36, 20, 14, 0.92);
      backdrop-filter: blur(12px);
      border: 1px solid rgba(212, 163, 115, 0.3);
      border-radius: 20px;
      padding: 40px 35px;
      width: 100%;
      max-width: 420px;
      box-shadow: 0 25px 60px rgba(0, 0, 0, 0.6), 0 0 40px rgba(212, 163, 115, 0.1);
      text-align: center;
      position: relative;
    }
    .brand-logo {
      width: 75px;
      height: 75px;
      margin: 0 auto 15px auto;
      background: linear-gradient(135deg, #8c531b, #4a2810);
      border: 2px solid #d4a373;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 32px;
      color: #f7f3ee;
      box-shadow: 0 8px 20px rgba(0,0,0,0.4);
    }
    h1 {
      font-size: 24px;
      color: #e6b360;
      font-weight: 700;
      letter-spacing: 0.5px;
      margin-bottom: 4px;
    }
    .subtitle {
      font-size: 13px;
      color: #c9b3a3;
      margin-bottom: 25px;
    }
    .alert-error {
      background: rgba(192, 57, 43, 0.2);
      border: 1px solid #c0392b;
      color: #ff7675;
      padding: 12px 14px;
      border-radius: 10px;
      font-size: 13px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
      text-align: left;
    }
    .form-group {
      text-align: left;
      margin-bottom: 18px;
    }
    label {
      display: block;
      font-size: 13px;
      color: #d4a373;
      margin-bottom: 6px;
      font-weight: 500;
    }
    .input-wrapper {
      position: relative;
    }
    .input-wrapper i.prefix-icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: #8c7668;
      font-size: 15px;
    }
    input[type="text"], input[type="password"] {
      width: 100%;
      padding: 13px 14px 13px 42px;
      background: rgba(20, 10, 6, 0.7);
      border: 1.5px solid rgba(212, 163, 115, 0.3);
      border-radius: 10px;
      color: #ffffff;
      font-size: 14px;
      font-family: inherit;
      outline: none;
      transition: all 0.25s;
    }
    input[type="text"]:focus, input[type="password"]:focus {
      border-color: #e6b360;
      box-shadow: 0 0 0 3px rgba(230, 179, 96, 0.2);
      background: rgba(20, 10, 6, 0.95);
    }
    .btn-login {
      width: 100%;
      background: linear-gradient(135deg, #d4a373, #8c531b);
      color: #ffffff;
      border: none;
      padding: 14px;
      border-radius: 10px;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
      font-family: inherit;
      box-shadow: 0 8px 25px rgba(140, 83, 27, 0.4);
      transition: all 0.25s;
      margin-top: 10px;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 8px;
    }
    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 30px rgba(140, 83, 27, 0.6);
      background: linear-gradient(135deg, #e6b360, #a06020);
    }
    .helper-box {
      margin-top: 25px;
      padding: 12px;
      background: rgba(255, 255, 255, 0.04);
      border-radius: 8px;
      font-size: 12px;
      color: #a89487;
    }
    .helper-box strong {
      color: #e6b360;
    }
    .back-home {
      display: inline-block;
      margin-top: 20px;
      font-size: 12.5px;
      color: #8c7668;
      text-decoration: none;
      transition: color 0.2s;
    }
    .back-home:hover {
      color: #e6b360;
    }
  </style>
</head>
<body>

  <div class="login-card">
    <div class="brand-logo">☕</div>
    <h1>Daily Brew Admin</h1>
    <p class="subtitle">ระบบจัดการธุรกิจกาแฟและคำสั่งซื้อหลังบ้าน</p>

    <?php if (!empty($error)): ?>
      <div class="alert-error">
        <i class="fa-solid fa-circle-exclamation"></i>
        <span><?= htmlspecialchars($error) ?></span>
      </div>
    <?php endif; ?>

    <form method="POST" action="admin_login.php">
      <div class="form-group">
        <label for="username"><i class="fa-solid fa-user-shield"></i> ชื่อผู้ใช้ (Username):</label>
        <div class="input-wrapper">
          <i class="fa-solid fa-user prefix-icon"></i>
          <input type="text" id="username" name="username" placeholder="ระบุชื่อผู้ใช้งาน" required autofocus value="<?= htmlspecialchars($_POST['username'] ?? 'admin') ?>">
        </div>
      </div>

      <div class="form-group">
        <label for="password"><i class="fa-solid fa-key"></i> รหัสผ่าน (Password):</label>
        <div class="input-wrapper">
          <i class="fa-solid fa-lock prefix-icon"></i>
          <input type="password" id="password" name="password" placeholder="ระบุรหัสผ่าน" required>
        </div>
      </div>

      <button type="submit" class="btn-login">
        <i class="fa-solid fa-right-to-bracket"></i> เข้าสู่ระบบจัดการร้าน
      </button>
    </form>

    <div class="helper-box">
      🔒 บัญชีแอดมิน: <strong>admin</strong> | รหัสผ่าน: <strong>123456</strong>
    </div>

    <a href="index.html" class="back-home">
      <i class="fa-solid fa-arrow-left"></i> กลับไปหน้าร้าน Daily Brew
    </a>
  </div>

</body>
</html>
