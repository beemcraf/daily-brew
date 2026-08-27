# 🛠️ เอกสารสรุปเทคโนโลยีที่ใช้พัฒนา (Technology Stack)
### โครงการ: เว็บไซต์ร้านกาแฟ Daily Brew (Digital Marketing & CDP Mini Project)
**สโลแกน:** *"Coffee First, Everything Later."*  
**ผู้พัฒนา:** ณภัทร (Beem) — `beemcraf`  
**ลิงก์เว็บไซต์:** [https://beemcraf.github.io/daily-brew/](https://beemcraf.github.io/daily-brew/)

---

## 💻 1. เทคโนโลยีหลักในส่วนของ Frontend (Client-Side)

| หมวดหมู่ | เทคโนโลยี / เครื่องมือ | รายละเอียดการนำไปใช้งาน |
| :--- | :--- | :--- |
| **โครงสร้างเว็บ (Structure)** | **HTML5 (Semantic HTML)** | • ใช้แท็กเชิงความหมาย เช่น `<header>`, `<nav>`, `<main>`, `<section>`, `<footer>`<br>• กำหนด Meta Tags ครบถ้วนสำหรับ **SEO** (Title, Description, Keywords, OpenGraph)<br>• รองรับ Accessibility (ARIA Labels สำหรับปุ่มและเมนู) |
| **การตกแต่งและดีไซน์ (Styling)** | **Vanilla CSS3** | • **Design System & CSS Variables:** Palette สีระดับพรีเมียม (Dark Espresso, Warm Crema, Golden Caramel)<br>• **Layout Engine:** ใช้ **CSS Grid** และ **Flexbox** จัดวางเลย์เอาต์<br>• **Glassmorphism & Shadows:** ใช้ `backdrop-filter: blur()` และเงาหลายระดับ<br>• **Micro-Animations:** เอฟเฟกต์แอนิเมชันลื่นไหล เช่น `@keyframes float`, `slideInRight`, `fadeIn`<br>• **Responsive Web Design:** รองรับหน้าจอทุกขนาด (Desktop, Tablet, Mobile) ผ่าน Media Queries |
| **ฟอนต์และการแสดงผล (Typography)** | **Google Fonts** | • `'Playfair Display'`: ฟอนต์หัวเรื่องหรูหราสไตล์ Classic Coffee House<br>• `'Outfit'`: ฟอนต์ภาษาอังกฤษโมเดิร์น อ่านง่ายสำหรับตัวเลขและราคา<br>• `'Prompt'`: ฟอนต์ภาษาไทยสไตล์โมเดิร์น ลายเส้นสะอาด สบายตา |
| **ไอคอน (Iconography)** | **Font Awesome 6.5.1** | • ใช้งานชุดไอคอนเวกเตอร์ SVG คุณภาพสูงสำหรับเมนู, ปุ่มสั่งซื้อ, ตะกร้าสินค้า, และโซเชียลมีเดีย |
| **ลอจิกและการทำงาน (Logic & Interactivity)** | **Vanilla JavaScript (ES6+)** | • **State Management:** จัดการสถานะตะกร้าสินค้า (Cart), ตัวเลือกเครื่องดื่ม (Customizer), ข้อมูลสมาชิก (VIP Member), และผลแบบทดสอบ (Quiz)<br>• **CDP & Order History Engine:** บันทึกประวัติคำสั่งซื้อแบบ Real-time พร้อมคำนวณแต้มสะสม (10฿ = 1pt)<br>• **Smart AI Recommendation Engine:** วิเคราะห์ช่วงเวลาจริง (เช้า/บ่าย/เย็น) และ Food Pairing แนะนำเมนูคู่กันอัตโนมัติ<br>• **Dynamic DOM Rendering:** เรนเดอร์เมนู กรองหมวดหมู่ คำนวณราคาส่วนลด และแสดงบัตรสมาชิกดิจิทัล<br>• **ScrollSpy & Click Lock:** ตรวจจับตำแหน่ง Scroll เพื่อสลับ Active Capsule Pill ของเมนูอัตโนมัติ<br>• **Toast Engine:** ระบบแสดงการแจ้งเตือนสไตล์โมเดิร์นเมื่อกดสั่งหรือคัดลอกโค้ด |
| **การจัดเก็บข้อมูล (Web Storage)** | **HTML5 Web Storage API** | • ใช้ `localStorage` จัดเก็บข้อมูลลูกค้า 360° (Single Customer View: ชื่อ, Gmail, เบอร์, วันเกิด, กาแฟโปรด) และประวัติคำสั่งซื้อย้อนหลัง |
| **การคัดลอกข้อมูล (Clipboard API)** | **Navigator Clipboard API** | • ระบบกดปุ่มคลิกเดียวเพื่อคัดลอกโค้ดส่วนลด (`navigator.clipboard.writeText`) |

---

## 🎯 2. เทคโนโลยีและกรอบแนวคิดทางการตลาดดิจิทัล (Digital Marketing Stack & Framework)

โปรเจกต์นี้ได้รับการออกแบบโดยผสานหลักการ **Digital Marketing Funnel (AIDA Framework)** และ **Customer Data Platform (CDP)** เข้ากับฟังก์ชันบนหน้าเว็บไซต์:

```
[1. AWARENESS]      ───► Hero Section + Slogan + Instagram UGC (#DailyBrewMoments)
[2. INTEREST]       ───► Interactive "Find Your Daily Brew" Quiz + Direct Trade Story
[3. CONSIDERATION]  ───► Smart AI Recommendation (Time-based & Food Pairing)
[4. DESIRE]         ───► VIP Member Club Registration (Gmail/Phone/Birthday) + Vouchers 20%
[5. ACTION]         ───► Customizer + Cart Simulator + Monthly Subscription Passes (5/12/25 Cups)
[6. RETENTION/LTV]  ───► Member Portal (CDP 360° Profile) + Order History + Re-order Button + Birthday Freebies
```

### ฟีเจอร์ทางการตลาดและ CDP ที่สร้างขึ้น:
1. **Customer Data Platform (CDP 360° Profile):** ระบบจัดเก็บและแสดงข้อมูลลูกค้าเชิงลึก (Single Customer View) ทั้งข้อมูลติดต่อ, ความชอบในรสชาติกาแฟ, ประเภทนม, และยอดสั่งซื้อสะสม
2. **Order History & Loyalty Retention:** ระบบบันทึกประวัติการสั่งซื้อย้อนหลัง พร้อมฟังก์ชัน **"สั่งซ้ำ (Re-order)"** ใน 1 คลิก และระบบสะสมแต้มแลกกาแฟฟรี
3. **Coffee Subscription Model (Recurring Revenue):** บัตรสมาชิกแพ็กเกจกาแฟรายเดือน (Starter Pass, Daily Brew Pass, Master Connoisseur) ช่วยเพิ่ม Customer Lifetime Value (LTV)
4. **Smart Cross-Selling & Food Pairing:** ระบบแนะนำเมนูทานคู่กัน (เช่น สั่งกาแฟ แนะนำครัวซองต์อบใหม่ลดราคาพิเศษ 20฿)
5. **Interactive Content (Personalization):** มินิเกมจับคู่กาแฟตามอารมณ์ 3 ขั้นตอน เพื่อสร้าง Engagement
6. **Academic Marketing Strategy Deck:** สรุปแผนการตลาด 4 ด้าน (Persona, Funnel, Omnichannel, KPIs) สำหรับใช้พรีเซนต์อาจารย์

---

## 🚀 3. เครื่องมือในการพัฒนาและการนำขึ้นระบบจริง (DevTools & Deployment)

| เครื่องมือ | หน้าที่การทำงาน |
| :--- | :--- |
| **Git** | ระบบ Distributed Version Control ควบคุมและบันทึกประวัติการเปลี่ยนแปลงโค้ด |
| **GitHub** | คลาวด์เรโพสิทอรีจัดเก็บซอร์สโค้ดของโครงการ (`beemcraf/daily-brew`) |
| **GitHub Pages** | ระบบ Static Web Hosting และ CI/CD Deploy เว็บไซต์ขึ้นสู่อินเทอร์เน็ตแบบอัตโนมัติ |
| **VS Code / IDE** | โปรแกรมแก้ไขโค้ดพร้อมเครื่องมือ Live Server ทดสอบบนเครื่อง Localhost |
| **Generative AI Tools** | ออกแบบ Asset ภาพเมนูกาแฟ เบเกอรี และองค์ประกอบภาพบรรยากาศร้าน |

---

## ⚡ 4. จุดเด่นด้านประสิทธิภาพ (Performance Highlights)

- **Zero Heavy Dependencies:** ไม่ใช้ Framework ขนาดใหญ่ ทำให้เว็บโหลดเร็วเป็นพิเศษ (**Page Load < 1 วินาที**)
- **Client-side Persistence:** ข้อมูลโปรไฟล์ลูกค้าและประวัติการสั่งซื้อบันทึกแบบ Offline-first ผ่าน LocalStorage
- **Mobile First & Cross-Browser Compatibility:** ใช้งานได้สมบูรณ์ทั้งบน Chrome, Safari, Edge, Firefox ทั้งบนคอมพิวเตอร์และสมาร์ตโฟน
