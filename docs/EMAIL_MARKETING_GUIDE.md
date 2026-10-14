# 📬 Daily Brew — คู่มือและแม่แบบการตลาดผ่านอีเมล (Email Marketing & Asset Guide)
### โครงการ: เว็บไซต์ร้านกาแฟ Daily Brew (Digital Marketing & CDP Showcase)
**สโลแกน:** *"Coffee First, Everything Later."*  
**ผู้พัฒนา:** ณภัทร (Beem) — `beemcraf`  
**ลิงก์เว็บไซต์:** [https://beemcraf.github.io/daily-brew/](https://beemcraf.github.io/daily-brew/)

---

## 🎯 1. ภาพรวมกลยุทธ์การตลาดผ่านอีเมล (Email Marketing Strategy)

ในแคมเปญการตลาดของ **Daily Brew** เราแบ่งการสื่อสารผ่านอีเมลออกเป็น **2 เส้นทางหลัก (2 Customer Journeys)** ตามระดับความสนใจและความสัมพันธ์ของลูกค้ากับแบรนด์:

```
[ผู้เข้าชมเว็บไซต์ทั่วไป]
        │
        ├──► 1. สนใจรับโปรโมชั่น/ข่าวสาร (Subscribe Newsletter)
        │      └──► ส่ง "Newsletter Welcome Email" (แจกส่วนลด 10% รหัส WELCOME10)
        │
        └──► 2. สมัครสมาชิกแบบเต็มรูปแบบ (Register VIP Member Club)
               └──► ส่ง "VIP Member Welcome Email" (แจกบัตร VIP Gold, ส่วนลด 20% รหัส DAILYVIP20, สิทธิ์วันเกิด)
```

---

## ☕ 2. แม่แบบที่ 1: Newsletter Welcome Email (ลูกค้า Subscribe รับข่าวสาร)

### 📌 วัตถุประสงค์ (Marketing Objective)
- **Lead Capture & First Conversion:** เปลี่ยนผู้เยี่ยมชมเว็บไซต์ให้กลายเป็นฐานข้อมูลผู้ติดตาม
- **Incentive:** มอบโค้ดส่วนลด **`WELCOME10`** (ลด 10%) เพื่อกระตุ้นให้เกิดคำสั่งซื้อแก้วแรก

### 🖼️ รูปภาพแบนเนอร์หัวอีเมล (Header Banner)
- **ไฟล์ในโปรเจกต์:** `assets/email_banner_newsletter.jpg`
- **URL สำหรับใช้ออนไลน์:** `https://beemcraf.github.io/daily-brew/assets/email_banner_newsletter.jpg`
- **ธีมภาพ:** บรรยากาศเคาน์เตอร์กาแฟ Specialty ยามเช้า เมล็ดกาแฟคั่วใหม่ และลาเต้อาร์ต อบอุ่น สบายตา

### ✍️ ข้อความสำหรับการตั้งค่าอีเมล (Email Copywriting)
* **หัวข้ออีเมล (Subject Line Options):**
  1. `[แนะนำ]` ☕ ยินดีต้อนรับสู่ Daily Brew! รับส่วนลด 10% สำหรับกาแฟแก้วโปรดของคุณ
  2. `[A/B Test]` กาแฟดีๆ รอคุณอยู่... มอบของขวัญต้อนรับลด 10% จาก Daily Brew
* **พรีเฮดเดอร์ (Preheader Text):** *ใช้โค้ด WELCOME10 สำหรับการสั่งซื้อครั้งแรก พร้อมติดตามเมล็ดกาแฟ Single Origin ล็อตใหม่ได้ก่อนใคร*

---

### 💻 โค้ด HTML Email Template สำเร็จรูป (แม่แบบ Newsletter)
*(สามารถคัดลอกโค้ดนี้ไปวางใน EmailJS, Mailchimp, Brevo หรือเครื่องมือส่งอีเมลได้ทันที)*

```html
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome to Daily Brew Community</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4ede4; font-family: 'Prompt', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f4ede4; padding: 30px 10px;">
    <tr>
      <td align="center">
        <!-- Main Card Container -->
        <table role="presentation" width="100%" max-width="600" cellspacing="0" cellpadding="0" border="0" style="max-width: 600px; background-color: #ffffff; border-radius: 18px; overflow: hidden; box-shadow: 0 10px 30px rgba(43,24,16,0.08); border: 1px solid #e8decb;">
          
          <!-- Header Banner Image -->
          <tr>
            <td style="padding: 0; line-height: 0;">
              <img src="https://beemcraf.github.io/daily-brew/assets/email_banner_newsletter.jpg" alt="Welcome to Daily Brew" width="100%" style="display: block; width: 100%; height: auto; max-height: 280px; object-fit: cover;">
            </td>
          </tr>

          <!-- Email Content Body -->
          <tr>
            <td style="padding: 35px 30px;">
              <span style="display: inline-block; background-color: #fbedd7; color: #c88937; font-size: 12px; font-weight: 700; padding: 4px 12px; border-radius: 9999px; letter-spacing: 0.5px; margin-bottom: 15px;">
                🎉 DAILY BREW NEWSLETTER
              </span>
              
              <h1 style="color: #24140e; font-size: 24px; font-weight: 700; margin: 0 0 15px 0; line-height: 1.3;">
                ยินดีต้อนรับสู่คอมมูนิตี้คอกาแฟ Daily Brew! ☕
              </h1>

              <p style="color: #63534b; font-size: 15px; line-height: 1.7; margin: 0 0 20px 0;">
                ขอบคุณที่ร่วมติดตามข่าวสารและเป็นส่วนหนึ่งของครอบครัว <strong>Daily Brew</strong> ค่ะ เราตั้งใจคัดสรรเมล็ดกาแฟ Single Origin คุณภาพสูง และส่งมอบเรื่องราวกาแฟสเปเชียลตี้ที่ดีที่สุดถึงคุณในทุกๆ สัปดาห์
              </p>

              <!-- Coupon Box -->
              <div style="background-color: #fff9f0; border: 2px dashed #c88937; border-radius: 14px; padding: 20px; text-align: center; margin: 25px 0;">
                <div style="color: #8c5b23; font-size: 13px; font-weight: 700; margin-bottom: 6px;">
                  🎁 ของขวัญต้อนรับผู้ติดตามใหม่ • รับส่วนลด 10%
                </div>
                <div style="font-size: 28px; font-weight: 800; color: #c88937; letter-spacing: 3px; margin: 8px 0; font-family: 'Courier New', Courier, monospace;">
                  WELCOME10
                </div>
                <div style="color: #9e8f85; font-size: 12px;">
                  ใช้เป็นส่วนลดสำหรับทุกเมนูเครื่องดื่มและเบเกอรีที่สั่งผ่านหน้าเว็บ
                </div>
              </div>

              <!-- What You Will Receive -->
              <h3 style="color: #24140e; font-size: 16px; margin: 25px 0 12px 0;">
                สิ่งที่คุณจะได้รับจากเราในทุกๆ สัปดาห์:
              </h3>
              <ul style="color: #63534b; font-size: 14px; line-height: 1.8; padding-left: 20px; margin: 0 0 30px 0;">
                <li>✨ แจ้งเตือนเมล็ดกาแฟคั่วใหม่ล็อตพิเศษ (Micro-lot & Seasonal Beans) ก่อนใคร</li>
                <li>🍋 เมนูซิกเนเจอร์ประจำฤดูกาลและไอเดีย Food Pairing ทานคู่กับขนม</li>
                <li>🔥 สิทธิ์รับโค้ดลับ Flash Sale และสิทธิพิเศษเฉพาะผู้ติดตามจดหมายข่าว</li>
              </ul>

              <!-- Call to Action Button -->
              <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr>
                  <td align="center">
                    <a href="https://beemcraf.github.io/daily-brew/#menu" target="_blank" style="display: inline-block; background-color: #c88937; color: #ffffff; font-size: 15px; font-weight: 700; text-decoration: none; padding: 14px 34px; border-radius: 9999px; box-shadow: 0 4px 15px rgba(200,137,55,0.35);">
                      ☕ เริ่มสั่งกาแฟแก้วแรกของคุณ
                    </a>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Footer Info -->
          <tr>
            <td style="background-color: #faf6f0; padding: 25px 30px; text-align: center; border-top: 1px solid #efe7da;">
              <p style="color: #24140e; font-weight: 700; font-size: 13px; margin: 0 0 5px 0;">
                Daily Brew Specialty Coffee & Roastery Lab
              </p>
              <p style="color: #9e8f85; font-size: 12px; margin: 0 0 10px 0;">
                สโลแกน: <em>"Coffee First, Everything Later."</em>
              </p>
              <p style="color: #a89a90; font-size: 11px; margin: 0;">
                อีเมลนี้ส่งถึงคุณเนื่องจากคุณได้กดติดตามข่าวสารที่ beemcraf.github.io/daily-brew
              </p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
```

---

## 👑 3. แม่แบบที่ 2: VIP Member Welcome Email (ลูกค้า สมัครสมาชิก VIP)

### 📌 วัตถุประสงค์ (Marketing Objective)
- **High-Tier Onboarding & Retention:** สร้างความประทับใจระดับพรีเมียม (VIP Status) ทันทีที่ลูกค้ากรอกข้อมูล CDP
- **Loyalty Benefits:** ส่งมอบ **รหัสสมาชิกประจำตัว**, บัตรดิจิทัล **VIP Gold Card**, คูปองส่วนลด **`DAILYVIP20`** (ลด 20%), และสิทธิพิเศษวันเกิด

### 🖼️ รูปภาพแบนเนอร์หัวอีเมล (Header Banner)
- **ไฟล์ในโปรเจกต์:** `assets/email_banner_vip.jpg`
- **URL สำหรับใช้ออนไลน์:** `https://beemcraf.github.io/daily-brew/assets/email_banner_vip.jpg`
- **ธีมภาพ:** บรรยากาศคาเฟ่หรูหรา โทน Dark Espresso & Gold foil พร้อมบัตร VIP Member Card สีดำทองข้างแก้วเอสเปรสโซ

### ✍️ ข้อความสำหรับการตั้งค่าอีเมล (Email Copywriting)
* **หัวข้ออีเมล (Subject Line Options):**
  1. `[แนะนำ]` 👑 ยินดีต้อนรับสู่ Daily Brew VIP Club! บัตรสมาชิก Gold Member และส่วนลด 20% ของคุณ
  2. `[A/B Test]` รหัสสมาชิก VIP ของคุณพร้อมใช้งานแล้ว (รับเครื่องดื่มฟรีในเดือนเกิด + ส่วนลด 20%)
* **พรีเฮดเดอร์ (Preheader Text):** *ยินดีต้อนรับเข้าสู่สถานะ VIP Gold Member รับสิทธิพิเศษส่วนลด 20% รหัส DAILYVIP20 และสิทธิพิเศษวันเกิด*

---

### 💻 โค้ด HTML Email Template สำเร็จรูป (แม่แบบ VIP Member)
*(รองรับ Dynamic Parameters เช่น `{{customer_name}}`, `{{member_id}}`, `{{birthday}}`)*

```html
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Daily Brew VIP Membership</title>
</head>
<body style="margin: 0; padding: 0; background-color: #1a0f0a; font-family: 'Prompt', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #1a0f0a; padding: 30px 10px;">
    <tr>
      <td align="center">
        <!-- Main Card Container -->
        <table role="presentation" width="100%" max-width="600" cellspacing="0" cellpadding="0" border="0" style="max-width: 600px; background-color: #ffffff; border-radius: 18px; overflow: hidden; box-shadow: 0 15px 40px rgba(0,0,0,0.5);">
          
          <!-- Header Banner Image -->
          <tr>
            <td style="padding: 0; line-height: 0;">
              <img src="https://beemcraf.github.io/daily-brew/assets/email_banner_vip.jpg" alt="Daily Brew VIP Gold Member" width="100%" style="display: block; width: 100%; height: auto; max-height: 300px; object-fit: cover;">
            </td>
          </tr>

          <!-- Email Content Body -->
          <tr>
            <td style="padding: 35px 30px;">
              
              <div style="text-align: center; margin-bottom: 20px;">
                <span style="display: inline-block; background-color: #24140e; color: #e6b360; font-size: 12px; font-weight: 700; padding: 5px 14px; border-radius: 9999px; border: 1px solid #e6b360;">
                  👑 VIP GOLD MEMBER STATUS
                </span>
                <h1 style="color: #24140e; font-size: 24px; font-weight: 700; margin: 12px 0 6px 0;">
                  ยินดีต้อนรับสู่ Daily Brew VIP Club!
                </h1>
                <p style="color: #63534b; font-size: 15px; margin: 0;">
                  สวัสดีค่ะคุณ <strong>{{customer_name}}</strong> บัตรสมาชิกดิจิทัลของคุณพร้อมใช้งานแล้วค่ะ
                </p>
              </div>

              <!-- Digital VIP Member Card Render -->
              <div style="background: linear-gradient(135deg, #1f110b 0%, #3d2317 100%); border-radius: 16px; padding: 22px 24px; color: #ffffff; box-shadow: 0 8px 25px rgba(36,20,14,0.3); border: 1px solid #d4a373; margin: 25px 0;">
                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                  <tr>
                    <td style="font-size: 11px; color: #e6b360; font-weight: 700; letter-spacing: 1px;">
                      DAILY BREW VIP PASS
                    </td>
                    <td align="right" style="font-size: 11px; color: #55efc4; font-weight: 700;">
                      ● ACTIVE
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" style="padding: 16px 0 12px 0;">
                      <div style="font-size: 20px; font-weight: 700; color: #ffffff; font-family: 'Playfair Display', Georgia, serif;">
                        {{customer_name}}
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td style="font-size: 12px; color: #c4b5a5;">
                      MEMBER ID: <strong style="color: #e6b360; font-family: monospace; font-size: 14px;">{{member_id}}</strong>
                    </td>
                    <td align="right" style="font-size: 12px; color: #c4b5a5;">
                      STARTING POINTS: <strong style="color: #55efc4;">100 PTS</strong>
                    </td>
                  </tr>
                </table>
              </div>

              <!-- VIP Welcome Voucher -->
              <div style="background-color: #fff9f0; border: 2px dashed #c88937; border-radius: 14px; padding: 20px; text-align: center; margin: 25px 0;">
                <div style="color: #8c5b23; font-size: 13px; font-weight: 700; margin-bottom: 6px;">
                  🎁 ของขวัญต้อนรับสมาชิก VIP • ส่วนลดพิเศษ 20%
                </div>
                <div style="font-size: 30px; font-weight: 800; color: #c88937; letter-spacing: 3px; margin: 8px 0; font-family: 'Courier New', Courier, monospace;">
                  DAILYVIP20
                </div>
                <div style="color: #9e8f85; font-size: 12px;">
                  ใช้ได้ทันทีกับทุกเมนูเครื่องดื่มและเบเกอรี สั่งผ่านเว็บไซต์ Daily Brew
                </div>
              </div>

              <!-- Exclusive VIP Privileges List -->
              <h3 style="color: #24140e; font-size: 16px; margin: 25px 0 14px 0;">
                สิทธิพิเศษเอกสิทธิ์เฉพาะสมาชิก VIP Club:
              </h3>
              <table width="100%" cellspacing="0" cellpadding="8" border="0" style="color: #63534b; font-size: 14px;">
                <tr>
                  <td width="30" valign="top" style="font-size: 18px;">🎂</td>
                  <td><strong>สิทธิพิเศษวันเกิด:</strong> รับฟรีเครื่องดื่ม Signature 1 แก้ว + เบเกอรี 1 ชิ้น ในเดือนเกิดของคุณ ({{birthday}})</td>
                </tr>
                <tr>
                  <td width="30" valign="top" style="font-size: 18px;">⭐</td>
                  <td><strong>สะสมแต้มแลกเครื่องดื่มฟรี:</strong> ทุก 10 บาท = 1 แต้ม สะสมครบ 100 แต้ม แลกฟรีเมนูโปรด</td>
                </tr>
                <tr>
                  <td width="30" valign="top" style="font-size: 18px;">⚡</td>
                  <td><strong>Fast Track Barista Priority:</strong> บริการชงและจัดส่งรวดเร็วพิเศษภายใน 15 นาที</td>
                </tr>
                <tr>
                  <td width="30" valign="top" style="font-size: 18px;">☕</td>
                  <td><strong>รสชาติโปรดที่บันทึกไว้ในระบบ:</strong> เมนูโปรดของคุณคือ <em>{{favorite_coffee}}</em> ({{milk_type}})</td>
                </tr>
              </table>

              <!-- Call to Action Button -->
              <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-top: 30px;">
                <tr>
                  <td align="center">
                    <a href="https://beemcraf.github.io/daily-brew/#menu" target="_blank" style="display: inline-block; background-color: #c88937; color: #ffffff; font-size: 15px; font-weight: 700; text-decoration: none; padding: 14px 36px; border-radius: 9999px; box-shadow: 0 4px 15px rgba(200,137,55,0.35);">
                      🛍️ ใช้ส่วนลด 20% สั่งซื้อเลย
                    </a>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Footer Info -->
          <tr>
            <td style="background-color: #24140e; color: #ffffff; padding: 25px 30px; text-align: center;">
              <p style="color: #e6b360; font-weight: 700; font-size: 13px; margin: 0 0 5px 0;">
                Daily Brew Specialty Coffee • VIP Customer Service
              </p>
              <p style="color: #c4b5a5; font-size: 12px; margin: 0 0 10px 0;">
                สอบถามสิทธิพิเศษหรือแจ้งปัญหา: support@dailybrew.cafe
              </p>
              <p style="color: #7d6e64; font-size: 11px; margin: 0;">
                © 2026 Daily Brew Coffee Co. All rights reserved. Slogan: "Coffee First, Everything Later."
              </p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
```

---

## 🛠️ 4. สิ่งที่ผู้พัฒนา / นักศึกษาต้องทำ (Step-by-Step Action Plan)

### ขั้นตอนที่ 1: ตรวจสอบรูปภาพแบนเนอร์
- รูปภาพทั้ง 2 รูปถูกสร้างและบันทึกไว้ในโฟลเดอร์ `assets/` ของโปรเจกต์เรียบร้อยแล้ว:
  1. `assets/email_banner_newsletter.jpg`
  2. `assets/email_banner_vip.jpg`
- เมื่อที่รัก Push ขึ้น GitHub และ GitHub Pages ทำงาน รูปภาพจะออนไลน์ที่ URL:
  `https://beemcraf.github.io/daily-brew/assets/email_banner_newsletter.jpg`
  `https://beemcraf.github.io/daily-brew/assets/email_banner_vip.jpg`
  ทำให้โปรแกรมเปิดอีเมล (Gmail, Outlook, Apple Mail) ดึงรูปไปแสดงผลได้ทันที

### ขั้นตอนที่ 2: นำโค้ด HTML ไปใช้งานจริง (เลือกเครื่องมือที่ต้องการ)
1. **กรณีใช้ EmailJS (ส่งจากหน้าเว็บตรงเข้า Gmail):**
   - ไปที่ [EmailJS Templates](https://dashboard.emailjs.com/admin/templates)
   - สร้าง Template ใหม่ แล้วเลือกแท็บ **HTML**
   - คัดลอกโค้ด HTML จากข้อ 2 หรือข้อ 3 ไปวาง
   - ตัวแปรในปีกกา เช่น `{{customer_name}}`, `{{member_id}}` จะถูกแทนที่ด้วยข้อมูลจริงที่ลูกค้ากรอก
2. **กรณีใช้ Mailchimp / Brevo (สำหรับส่งแคมเปญหมู่):**
   - สร้างแคมเปญใหม่ เลือก **Code your own / Custom HTML**
   - วางโค้ด HTML ลงไป ระบบจะเรนเดอร์อีเมลสวยงามตรงตามมาตรฐานสากล 100%

### ขั้นตอนที่ 3: เช็คลิสต์ตรวจสอบก่อนนำเสนออาจารย์ (Presentation Checklist)
- [x] โค้ด HTML ใช้ Inline CSS ตามมาตรฐาน Responsive Email (เปิดบนมือถือและคอมพิวเตอร์ได้สวยงาม)
- [x] มีรูปภาพแบนเนอร์ระดับพรีเมียม (16:9 Aspect Ratio) แสดงบรรยากาศ Specialty Coffee
- [x] มีการแจกคูปองที่สอดคล้องกับ Funnel การตลาด (`WELCOME10` สำหรับคนติดตามใหม่, `DAILYVIP20` สำหรับสมาชิก VIP)
- [x] มี Call to Action (CTA Button) นำพาลูกค้ากลับเข้าสู่ตะกร้าสินค้าบนเว็บไซต์
