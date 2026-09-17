# 🐘 Daily Brew — คู่มือระบบส่งอีเมลด้วย PHP (PHP Backend Guide)

เอกสารนี้จัดทำขึ้นเพื่ออธิบายและแนะนำวิธีใช้งานระบบส่งอีเมลด้วยภาษา **PHP** ตามใบงานที่ได้รับมอบหมายครบทั้ง 5 ข้อ

---

## 📋 สรุปความสอดคล้องกับใบงาน (5/5 ข้อ ครบถ้วน 100%)

| ข้อ | รายการตามใบงาน | สิ่งที่ทำในโปรเจกต์ | ไฟล์ที่เกี่ยวข้อง |
| :---: | :--- | :--- | :--- |
| **1** | **สร้างระบบ subscribe เพื่อรับข่าวสารในหน้าแรก** | มีกล่องกรอกอีเมลรับข่าวสารและคูปองลด 10% (WELCOME10) อยู่ที่ด้านล่างของหน้าแรก | [`index.html`](index.html#newsletter) |
| **2** | **สร้างไฟล์ .php เพื่อส่งเนื้อหาไปยังอีเมลหลังจาก subscribe** | สร้างสคริปต์ PHP รับอีเมล ดึงเทมเพลต HTML และส่งอีเมลต้อนรับ 10% ด้วยคำสั่ง `mail()` | [`subscribe.php`](subscribe.php) |
| **3** | **สร้าง personalized marketing เพื่อแนะนำสินค้าที่ลูกค้าสนใจไปทางอีเมล** | ระบบวิเคราะห์รสชาติกาแฟที่ลูกค้าเลือก (Dirty, Cold Brew, Latte, Matcha) แล้วสุ่มแนะนำเมนูกาแฟ+เบเกอรีที่เข้าคู่กันลงในเนื้อหาอีเมลเฉพาะบุคคล | [`register_vip.php`](register_vip.php)<br>[`email-template-vip.html`](email-template-vip.html) |
| **4** | **สร้างไฟล์ .php เพื่อส่งเนื้อหาไปยังอีเมลหลังจากสมัครสมาชิก** | สร้างสคริปต์ PHP รับข้อมูลสมาชิก (ชื่อ, เบอร์, อีเมล, วันเกิด, กาแฟ, นม, ที่อยู่) สร้างรหัสบัตร VIP และส่งบัตรพร้อมโค้ดลด 20% (DAILYVIP20) | [`register_vip.php`](register_vip.php) |
| **5** | **สร้างระบบแนะนำสินค้า (ต่างจาก 3) จากพฤติกรรมหรือข้อมูลการใช้งาน** | มีระบบแนะนำสินค้าบนหน้าเว็บ 2 จุด:<br>1) **Smart AI Banner:** แนะนำเซ็ตกาแฟคู่เบเกอรีตามช่วงเวลาเช้า/บ่าย/เย็น<br>2) **Coffee Quiz:** แนะนำกาแฟตามคำตอบไลฟ์สไตล์ของลูกค้า | [`index.html`](index.html#menu)<br>[`app.js`](app.js) |

---

## 🛠️ โครงสร้างไฟล์ PHP ที่สร้างขึ้น

### 1. [`subscribe.php`](subscribe.php) (สำหรับข้อ 2)
* **การทำงาน:** รับค่าอีเมลจากฟอร์มหน้าแรก
* **กระบวนการ:**
  1. ตรวจสอบความถูกต้องของอีเมลด้วย `filter_var($email, FILTER_VALIDATE_EMAIL)`
  2. ดึงโครงสร้างอีเมลสวยหรูจาก [`email-template-newsletter.html`](email-template-newsletter.html)
  3. ส่งอีเมลต้อนรับมอบโค้ดลด 10% (`WELCOME10`) ด้วยคำสั่ง `mail()` ของ PHP
  4. ตอบกลับผลลัพธ์แบบ JSON ให้หน้าเว็บ หรือ Redirect กลับหน้าแรก

### 2. [`register_vip.php`](register_vip.php) (สำหรับข้อ 4 และข้อ 3)
* **การทำงาน:** รับข้อมูลการสมัครสมาชิก VIP จากหน้า [`vip.html`](vip.html)
* **กระบวนการ:**
  1. ตรวจสอบข้อมูลจำเป็น (ชื่อ, เบอร์, อีเมล)
  2. **Personalized Marketing Engine (ข้อ 3):** ตรวจสอบค่า `$favoriteCoffee` ที่ลูกค้าเลือก:
     * **ชอบ Dirty Coffee:** แนะนำคู่ *Military Dirty Matcha & Basque Burnt Cheesecake*
     * **ชอบ Cold Brew:** แนะนำคู่ *Artisan Orange Cold Brew & Pain au Chocolat*
     * **ชอบ Latte:** แนะนำคู่ *Caramel Macchiato & Almond Croissant*
     * **ชอบ Matcha/ชา:** แนะนำคู่ *Kyoto Dirty Matcha & Hokkaido Strawberry Shortcake*
     * **อื่นๆ:** แนะนำคู่ *Signature Dirty Coffee & Butter Croissant*
  3. สุ่มสร้างรหัสสมาชิก VIP อัตโนมัติ เช่น `DB-2026-VIP-8842`
  4. นำข้อมูลลูกค้าและเมนูแนะนำเฉพาะบุคคลไปใส่ในแม่แบบ [`email-template-vip.html`](email-template-vip.html)
  5. ส่งอีเมลสิทธิพิเศษ VIP (ส่วนลด 20% โค้ด `DAILYVIP20`) ผ่านฟังก์ชัน `mail()` ของ PHP

---

## 💻 วิธีนำไปทดสอบด้วย XAMPP (สำหรับใช้สัปดาห์หน้า)

เมื่อถึงชั่วโมงเรียนในสัปดาห์หน้า หากอาจารย์ให้รันโปรเจกต์ด้วย XAMPP ให้ทำตาม 3 ขั้นตอนนี้ได้เลยค่ะ:

1. **คัดลอกโฟลเดอร์โปรเจกต์:**
   * นำโฟลเดอร์ `ร้านขายกาแฟ(Daily Brew)` ไปวางไว้ในโฟลเดอร์ `htdocs` ของ XAMPP
     * Windows: `C:\xampp\htdocs\daily-brew`
     * Mac: `/Applications/XAMPP/htdocs/daily-brew`
2. **เปิด XAMPP Control Panel:**
   * กดปุ่ม **Start** ที่บริการ **Apache**
3. **เปิดเบราว์เซอร์เข้าใช้งาน:**
   * หน้าแรก: `http://localhost/daily-brew/index.html`
   * หน้าสมัคร VIP: `http://localhost/daily-brew/vip.html`
   * เมื่อกดสมัครสมาชิกหรือกรอกอีเมลรับข่าวสาร โค้ดหน้าเว็บจะส่งข้อมูลมาประมวลผลที่ไฟล์ `.php` ทั้งสองตัวโดยอัตโนมัติทันทีค่ะ!
