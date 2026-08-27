# 🛠️ เอกสารสรุปเทคโนโลยีที่ใช้พัฒนา (Technology Stack)
### โครงการ: เว็บไซต์ร้านกาแฟ Daily Brew (Digital Marketing Mini Project)
**สโลแกน:** *"Coffee First, Everything Later."*  
**ผู้พัฒนา:** ณภัทร (Beem) — `beemcraf`  
**ลิงก์เว็บไซต์:** [https://beemcraf.github.io/daily-brew/](https://beemcraf.github.io/daily-brew/)

---

## 💻 1. เทคโนโลยีหลักในส่วนของ Frontend (Client-Side)

| หมวดหมู่ | เทคโนโลยี / เครื่องมือ | รายละเอียดการนำไปใช้งาน |
| :--- | :--- | :--- |
| **โครงสร้างเว็บ (Structure)** | **HTML5 (Semantic HTML)** | • ใช้แท็กเชิงความหมาย เช่น `<header>`, `<nav>`, `<main>`, `<section>`, `<footer>`<br>• กำหนด Meta Tags ครบถ้วนสำหรับ **SEO** (Title, Description, Keywords, OpenGraph)<br>• รองรับ Accessibility (ARIA Labels สำหรับปุ่มและเมนู) |
| **การตกแต่งและดีไซน์ (Styling)** | **Vanilla CSS3** | • **Design System & CSS Variables:** กำหนด Palette สีระดับพรีเมียม (Dark Espresso, Warm Crema, Golden Caramel)<br>• **Layout Engine:** ใช้ **CSS Grid** และ **Flexbox** สำหรับจัดวางเลย์เอาต์ที่ยืดหยุ่น<br>• **Glassmorphism & Shadows:** ใช้ `backdrop-filter: blur()` และเงาหลายระดับ<br>• **Micro-Animations:** เอฟเฟกต์แอนิเมชันลื่นไหล เช่น `@keyframes float`, `slideInRight`, `fadeIn`<br>• **Responsive Web Design:** รองรับหน้าจอทุกขนาด (Desktop, Tablet, Mobile) ผ่าน Media Queries |
| **ฟอนต์และการแสดงผล (Typography)** | **Google Fonts** | • `'Playfair Display'`: ฟอนต์หัวเรื่องหรูหราสไตล์ Classic Coffee House<br>• `'Outfit'`: ฟอนต์ภาษาอังกฤษโมเดิร์น อ่านง่ายสำหรับตัวเลขและราคา<br>• `'Prompt'`: ฟอนต์ภาษาไทยสไตล์โมเดิร์น ลายเส้นสะอาด สบายตา |
| **ไอคอน (Iconography)** | **Font Awesome 6.5.1** | • ใช้งานชุดไอคอนเวกเตอร์ SVG คุณภาพสูงสำหรับเมนู, ปุ่มสั่งซื้อ, ตะกร้าสินค้า และโซเชียลมีเดีย |
| **ลอจิกและการทำงาน (Logic & Interactivity)** | **Vanilla JavaScript (ES6+)** | • **State Management:** จัดการสถานะตะกร้าสินค้า (Cart), ตัวเลือกเครื่องดื่ม (Customizer), และผลแบบทดสอบ (Quiz)<br>• **Dynamic DOM Rendering:** เรนเดอร์เมนู กรองหมวดหมู่ และคำนวณราคาส่วนลดแบบ Real-time<br>• **Interactive AI Coffee Matcher:** อัลกอริทึมคำนวณรสชาติกาแฟ 3 ขั้นตอน<br>• **ScrollSpy & Click Lock:** ตรวจจับตำแหน่ง Scroll เพื่อสลับ Active Capsule Pill ของเมนูอัตโนมัติ<br>• **Toast Engine:** ระบบแสดงการแจ้งเตือนสไตล์โมเดิร์นเมื่อกดสั่งหรือคัดลอกโค้ด |
| **การจัดเก็บข้อมูล (Web Storage)** | **HTML5 Web Storage API** | • ใช้ `localStorage` ในการบันทึกข้อมูลสมาชิก VIP Club และโค้ดคูปองส่วนลดที่เก็บไว้ใช้งาน |
| **การคัดลอกข้อมูล (Clipboard API)** | **Navigator Clipboard API** | • ระบบกดปุ่มคลิกเดียวเพื่อคัดลอกโค้ดส่วนลด (`navigator.clipboard.writeText`) |

---

## 🎯 2. เทคโนโลยีและกรอบแนวคิดทางการตลาดดิจิทัล (Digital Marketing Stack & Framework)

โปรเจกต์นี้ได้รับการออกแบบโดยผสานหลักการ **Digital Marketing Funnel (AIDA Framework)** เข้ากับฟังก์ชันบนหน้าเว็บไซต์:

```
[1. AWARENESS]      ───► Hero Section + Slogan + Instagram UGC (#DailyBrewMoments)
[2. INTEREST]       ───► Interactive "Find Your Daily Brew" Quiz + Direct Trade Story
[3. DESIRE]         ───► Lead Capture (รับ Voucher 20%) + Morning Rush / Combo Deals
[4. ACTION/RETENTION]───► Customizer + Cart Simulator + Daily Pass Subscription + Receipt
```

### ฟีเจอร์ทางการตลาดที่สร้างขึ้น:
1. **Lead Generation & Data Capture:** ฟอร์มรับสิทธิ์สมาชิก VIP เพื่อเก็บฐานข้อมูล (Email / เบอร์โทร) แลกกับคูปองส่วนลด `DAILYVIP20`
2. **Interactive Content (Personalization):** มินิเกมจับคู่กาแฟตามอารมณ์เพื่อสร้าง Customer Engagement และลด Bounce Rate
3. **Conversion Optimization:** ระบบคำนวณโปรโมชั่นอัตโนมัติในตะกร้าสินค้า (ลด 20%, บัตรสมาชิก Daily Pass, คอมโบเซ็ต)
4. **Marketing Strategy Deck Modal:** หน้าต่างสรุปกลยุทธ์การตลาด 4 ด้าน (Persona, AIDA Funnel, Omnichannel, KPIs) สำหรับใช้พรีเซนต์อาจารย์

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
- **No Third-Party Tracker Latency:** โค้ดทั้งหมดรันแบบ Native บนเบราว์เซอร์ ลื่นไหล 60 FPS
- **Mobile First & Cross-Browser Compatibility:** ใช้งานได้สมบูรณ์ทั้งบน Chrome, Safari, Edge, Firefox ทั้งบนคอมพิวเตอร์และสมาร์ตโฟน
