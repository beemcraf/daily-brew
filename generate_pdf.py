# -*- coding: utf-8 -*-
"""
Script to generate the polished Daily Brew Chatbot Knowledge Base PDF
Updated with Online & Delivery / Cloud Roastery model (NO physical storefront).
"""
import os
import subprocess
import shutil

html_content = """<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>Daily Brew — คลังข้อมูลธุรกิจและฐานความรู้สำหรับ AI Chatbot (14 หัวข้อ)</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600;700&family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    @page {
      size: A4 portrait;
      margin: 10mm 12mm 10mm 12mm;
    }
    
    * {
      box-sizing: border-box;
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
    }

    body {
      font-family: 'Sarabun', 'Prompt', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      font-size: 11.5px;
      line-height: 1.45;
      color: #2c2724;
      background-color: #ffffff;
      margin: 0;
      padding: 0;
    }

    h1, h2, h3, h4 {
      font-family: 'Prompt', sans-serif;
      color: #1f1813;
      margin: 0;
      font-weight: 600;
    }

    .doc-header {
      border-bottom: 2px solid #8c531b;
      padding-bottom: 8px;
      margin-bottom: 12px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .doc-title-main {
      font-size: 18px;
      color: #3e2723;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .doc-subtitle {
      font-size: 11px;
      color: #795548;
      margin-top: 2px;
    }

    .doc-badge {
      background: #fdf8f3;
      border: 1px solid #d4a373;
      color: #8c531b;
      padding: 4px 8px;
      border-radius: 5px;
      font-size: 10px;
      font-weight: 600;
      text-align: right;
      line-height: 1.3;
    }

    .topic-card {
      background: #ffffff;
      border: 1px solid #e8ded4;
      border-left: 4px solid #8c531b;
      border-radius: 5px;
      padding: 9px 12px;
      margin-bottom: 10px;
    }

    .topic-card.highlight {
      border-left-color: #c97a2e;
      background: #faf6f1;
    }

    .topic-header {
      display: flex;
      align-items: center;
      gap: 6px;
      margin-bottom: 6px;
    }

    .topic-number {
      background: #8c531b;
      color: #ffffff;
      font-family: 'Prompt', sans-serif;
      font-size: 10.5px;
      font-weight: 600;
      padding: 1px 7px;
      border-radius: 10px;
      white-space: nowrap;
    }

    .topic-title {
      font-size: 13.5px;
      color: #3e2723;
      font-weight: 600;
    }

    .info-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 5px 14px;
    }

    .info-item {
      font-size: 11px;
    }

    .info-label {
      font-weight: 600;
      color: #5d4037;
      display: inline-block;
      min-width: 90px;
    }

    .tag {
      display: inline-block;
      background: #eef1f6;
      color: #3b5998;
      padding: 1px 5px;
      border-radius: 3px;
      font-size: 9.5px;
      margin: 0 2px 0 0;
      font-weight: 500;
    }

    .tag-gold {
      background: #fff8e1;
      color: #b78103;
      border: 1px solid #ffe082;
    }

    .tag-green {
      background: #e8f5e9;
      color: #2e7d32;
    }

    .tag-delivery {
      background: #e0f2fe;
      color: #0369a1;
      border: 1px solid #bae6fd;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 10.5px;
      margin-top: 4px;
      margin-bottom: 2px;
    }

    th {
      background: #3e2723;
      color: #ffffff;
      padding: 5px 7px;
      text-align: left;
      font-family: 'Prompt', sans-serif;
      font-weight: 500;
      border: 1px solid #3e2723;
    }

    td {
      padding: 4.5px 7px;
      border: 1px solid #e4dacf;
      vertical-align: top;
      line-height: 1.35;
    }

    tr:nth-child(even) td {
      background-color: #faf7f3;
    }

    .faq-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 6px 12px;
    }

    .faq-item {
      padding: 5px 7px;
      background: #fdfcfb;
      border: 1px solid #ece4db;
      border-radius: 4px;
      font-size: 10.5px;
      line-height: 1.35;
      page-break-inside: avoid;
    }

    .faq-q {
      font-weight: 600;
      color: #8c531b;
      margin-bottom: 2px;
    }

    .faq-a {
      color: #2d2621;
    }

    .rule-box {
      background: #fdfaf6;
      border: 1px solid #ebd9c8;
      border-radius: 4px;
      padding: 6px 9px;
      margin-bottom: 5px;
      font-size: 10.5px;
      line-height: 1.35;
      page-break-inside: avoid;
    }

    .rule-if {
      color: #c0392b;
      font-weight: 700;
    }

    .rule-then {
      color: #27ae60;
      font-weight: 700;
    }

    .badge-code {
      font-family: monospace;
      background: #f0e6dc;
      padding: 1px 4px;
      border-radius: 3px;
      color: #8c531b;
      font-weight: bold;
      font-size: 10px;
    }

    .page-break {
      page-break-before: always;
    }

    .flow-steps {
      display: flex;
      gap: 6px;
      margin-top: 4px;
    }

    .flow-step-box {
      flex: 1;
      padding: 6px 4px;
      background: #faf6f1;
      border-radius: 4px;
      border: 1px solid #ebd9c8;
      text-align: center;
      font-size: 10px;
      line-height: 1.3;
    }

    .flow-step-box strong {
      color: #8c531b;
      display: block;
      font-size: 11px;
      margin-bottom: 2px;
    }

    .footer-note {
      font-size: 9.5px;
      color: #8d6e63;
      text-align: center;
      margin-top: 10px;
      border-top: 1px solid #e8ded4;
      padding-top: 4px;
    }
  </style>
</head>
<body>

  <!-- ================= PAGE 1 ================= -->
  <!-- HEADER -->
  <div class="doc-header">
    <div>
      <h1 class="doc-title-main">☕ DAILY BREW SPECIALTY COFFEE & ROASTERY</h1>
      <p class="doc-subtitle">คลังข้อมูลธุรกิจและฐานความรู้สำหรับ AI Chatbot (โมเดล Online Delivery & Cloud Roastery 100%)</p>
    </div>
    <div class="doc-badge">
      AI KNOWLEDGE BASE<br>
      สถานะ: Online & Delivery 100%
    </div>
  </div>

  <!-- TOPIC 1 -->
  <div class="topic-card">
    <div class="topic-header">
      <span class="topic-number">หัวข้อที่ 1</span>
      <h3 class="topic-title">ข้อมูลทั่วไปของร้าน/ธุรกิจ (General Business Profile)</h3>
    </div>
    <div class="info-grid">
      <div class="info-item"><span class="info-label">ชื่อร้าน:</span> <strong>Daily Brew (เดลี่ บริว)</strong> | Specialty Coffee & Roastery</div>
      <div class="info-item"><span class="info-label">ประเภทธุรกิจ:</span> โรงคั่วกาแฟสเปเชียลตี้คลาวด์ (Cloud Roastery) & บริการจัดส่งเดลิเวอรี 100%</div>
      <div class="info-item"><span class="info-label">สโลแกนร้าน:</span> <em>"Coffee First, Everything Later."</em></div>
      <div class="info-item"><span class="info-label">เวลาเปิด–ปิด:</span> จันทร์–ศุกร์ 07:00–18:00 น. | เสาร์–อาทิตย์ และวันหยุด 08:00–19:00 น.</div>
      <div class="info-item" style="grid-column: span 2;">
        <span class="info-label">รูปแบบหน้าร้าน/ที่อยู่:</span> 
        <strong><span class="tag-delivery tag">🛵 ขณะนี้ยังไม่มีหน้าร้านสำหรับนั่งทาน (Delivery Only)</span></strong>
        ให้บริการชงสดส่งตรงจากสตูดิโอคั่วกาแฟในกรุงเทพฯ สั่งออนไลน์ผ่านเว็บและแอปเดลิเวอรีจัดส่งด่วนถึงที่ (เตรียมพบกับ Flagship Store เร็วๆ นี้)
      </div>
      <div class="info-item" style="grid-column: span 2;">
        <span class="info-label">จุดเด่นของร้าน:</span> 
        1) เมล็ด Single Origin ดอยช้าง Peaberry คั่วสดใหม่สัปดาห์ต่อสัปดาห์ 
        2) เมนู Signature ชงสดแก้วต่อแก้ว แยกชั้นน้ำแข็งและซีลสุญญากาศพิเศษ ส่งถึงมือไม่ละลาย 
        3) มีนมโอ๊ต/อัลมอนด์พรีเมียมรองรับสายสุขภาพ 
        4) บัตรสะสมแต้ม LINE Reward Card & VIP Club ซื้อ 10 บาท = 1 แต้ม แลกรับเครื่องดื่มฟรี
      </div>
      <div class="info-item"><span class="info-label">ช่องทางติดต่อ:</span> โทร. 02-999-8888, 081-234-5678 | LINE OA: <strong>@285arhlw</strong></div>
      <div class="info-item"><span class="info-label">ช่องทางสั่งซื้อ:</span> เว็บไซต์ <code>beemcraf.github.io/daily-brew/</code>, Grab, LINE MAN, Robinhood</div>
    </div>
  </div>

  <!-- TOPIC 2 -->
  <div class="topic-card">
    <div class="topic-header">
      <span class="topic-number">หัวข้อที่ 2</span>
      <h3 class="topic-title">ข้อมูลสินค้า/บริการ (Products & Services Specifications)</h3>
    </div>
    <table>
      <thead>
        <tr>
          <th style="width: 22%;">ชื่อสินค้า / บริการ</th>
          <th style="width: 13%;">หมวดหมู่</th>
          <th style="width: 8%;">ราคา</th>
          <th style="width: 32%;">คุณสมบัติเด่น & รายละเอียด</th>
          <th style="width: 25%;">ตัวเลือกปรับแต่ง (Options) & สถานะ</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><strong>Signature Dirty Coffee</strong><br><span class="tag-gold tag">Best Seller ⭐</span></td>
          <td>Espresso Bar</td>
          <td><strong>120 ฿</strong></td>
          <td>ดับเบิลช็อตเอสเปรสโซสกัดร้อน ไหลรินลงสู่นมสดผสมครีมสูตรลับเย็นจัด หอมมันเข้มข้น</td>
          <td>หวาน 0%, 25%, 50%, 100% | นมสด / นมโอ๊ต (+15฿)<br><span class="tag-green tag">ชงสดเดลิเวอรี</span></td>
        </tr>
        <tr>
          <td><strong>Coconut Cloud Espresso</strong><br><span class="tag-gold tag">Signature 🥥</span></td>
          <td>Espresso Bar</td>
          <td><strong>125 ฿</strong></td>
          <td>น้ำมะพร้าวน้ำหอมสดแท้ 100% ท็อปด้วยเอสเปรสโซสกัดร้อนและฟองครีมมะพร้าวนุ่ม</td>
          <td>หวานธรรมชาติ / หวานน้อย | เพิ่มช็อต (+20฿)<br><span class="tag-green tag">ชงสดเดลิเวอรี</span></td>
        </tr>
        <tr>
          <td><strong>Vanilla Bean Oat Latte</strong><br><span class="tag-green tag">Plant-Based 🌾</span></td>
          <td>Espresso Bar</td>
          <td><strong>130 ฿</strong></td>
          <td>กาแฟลาเต้นมโอ๊ตเกรดพรีเมียม ผสานฝักวานิลลาแท้มาดากัสการ์ หอมนุ่ม หวานน้อย</td>
          <td>ร้อน / เย็น | หวาน 0%, 50% | สุขภาพดี<br><span class="tag-green tag">ชงสดเดลิเวอรี</span></td>
        </tr>
        <tr>
          <td><strong>Artisan Orange Cold Brew</strong><br><span class="tag">Barista Pick 🍊</span></td>
          <td>Cold Brew</td>
          <td><strong>135 ฿</strong></td>
          <td>กาแฟสกัดเย็นนาน 18 ชม. ผสานผิวส้มวาเลนเซียอบแห้งและโทนิค สดชื่น ปลุกพลัง</td>
          <td>ความซ่า: Normal / Extra Tonic<br><span class="tag-green tag">บรรจุขวดแก้วพรีเมียม</span></td>
        </tr>
        <tr>
          <td><strong>Sparkling Yuzu Tonic</strong><br><span class="tag-gold tag">Refreshing 🍋</span></td>
          <td>Cold Brew</td>
          <td><strong>130 ฿</strong></td>
          <td>น้ำส้มยูซุแท้ 100% ญี่ปุ่น โซดาซ่า ท็อปเอสเปรสโซดอยช้าง เปรี้ยวหวานสดชื่นตื่นเต็มตา</td>
          <td>หวานปกติ / หวาน 50%<br><span class="tag-green tag">ชงสดเดลิเวอรี</span></td>
        </tr>
        <tr>
          <td><strong>Kyoto Uji Matcha Cloud</strong><br><span class="tag-green tag">Ceremonial 🍵</span></td>
          <td>Non-Coffee</td>
          <td><strong>125 ฿</strong></td>
          <td>มัทฉะเกรดพิธีชงนำเข้าจากเมืองอุจิ เกียวโต ตีสดชามต่อชาม ท็อปโฟมนมมะพร้าว</td>
          <td>หวาน 0%, 25%, 50%, 100% | เปลี่ยนนมโอ๊ต (+15฿)<br><span class="tag-green tag">ชงสดเดลิเวอรี</span></td>
        </tr>
        <tr>
          <td><strong>Valrhona Chocolate Float</strong><br><span class="tag-gold tag">French 🍫</span></td>
          <td>Non-Coffee</td>
          <td><strong>135 ฿</strong></td>
          <td>ดาร์กช็อกโกแลตฝรั่งเศสแท้ 70% เข้มลึก ท็อปไอศกรีมวานิลลาและช็อกโกแลตเคิร์ล</td>
          <td>หวานน้อย / หวานปกติ (แยกไอศกรีมให้)<br><span class="tag-green tag">ชงสดเดลิเวอรี</span></td>
        </tr>
        <tr>
          <td><strong>San Sebastian Basque Cheesecake</strong></td>
          <td>Bakery</td>
          <td><strong>145 ฿</strong></td>
          <td>ชีสเค้กหน้าไหม้คาราเมล ตรงกลางลาวาเยิ้ม หอมกลิ่นวานิลลาแท้ อบใหม่ทุกเช้า</td>
          <td>แพ็กกล่องเบเกอรี่อย่างดีพร้อมเจลเย็น<br><span class="tag-green tag">อบใหม่ทุกเช้า</span></td>
        </tr>
        <tr>
          <td><strong>French Almond Croissant</strong></td>
          <td>Bakery</td>
          <td><strong>85 ฿</strong></td>
          <td>ครัวซองต์เนยสดแท้ฝรั่งเศส แป้งกรอบนอกฉ่ำเนย สอดไส้ครีมอัลมอนด์ โรยอัลมอนด์สไลซ์</td>
          <td>อบร้อนห่อฟอยล์รักษาความกรอบ<br><span class="tag-green tag">อบใหม่ทุกเช้า</span></td>
        </tr>
        <tr>
          <td><strong>เมล็ดกาแฟดอยช้าง Peaberry (250g)</strong></td>
          <td>Retail Beans</td>
          <td><strong>280 ฿</strong></td>
          <td>เมล็ดกาแฟคั่วกลาง คั่วสดใหม่ กลิ่นช็อกโกแลต คาราเมล ถั่ว และฟรุตตี้ปลาย</td>
          <td>แบบเมล็ด / บดฟรี (Drip, Espresso, Mokapot, Cold Brew)<br><span class="tag-green tag">จัดส่งพัสดุด่วน</span></td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="page-break"></div>

  <!-- ================= PAGE 2 ================= -->
  <!-- TOPIC 3 -->
  <div class="topic-card">
    <div class="topic-header">
      <span class="topic-number">หัวข้อที่ 3</span>
      <h3 class="topic-title">หมวดหมู่สินค้า/บริการ (Product Categorization)</h3>
    </div>
    <div class="info-grid">
      <div class="info-item">
        <strong>🔥 สินค้าขายดี (Best Sellers):</strong>
        <p style="margin: 2px 0 0 0; color:#555;">Signature Dirty Coffee, Basque Cheesecake, Sparkling Yuzu Espresso Tonic, Valrhona Dark Chocolate Float</p>
      </div>
      <div class="info-item">
        <strong>✨ สินค้าใหม่ (New Arrivals):</strong>
        <p style="margin: 2px 0 0 0; color:#555;">Coconut Cloud Espresso, Military Dirty Matcha (มัทฉะ 3 เลเยอร์), Truffle Cheese Brioche Toast</p>
      </div>
      <div class="info-item">
        <strong>⭐ สินค้าแนะนำ (Barista Picks):</strong>
        <p style="margin: 2px 0 0 0; color:#555;">Artisan Orange Cold Brew 18 ชม., Vanilla Bean Oat Latte, French Almond Croissant</p>
      </div>
      <div class="info-item">
        <strong>📦 จัดหมวดหมู่ตามประเภท:</strong>
        <p style="margin: 2px 0 0 0; color:#555;">1) Espresso Bar  2) Cold Brew & Specialty  3) Non-Coffee & Tea  4) Artisanal Bakery  5) Retail Beans  6) Monthly Pass</p>
      </div>
    </div>
  </div>

  <!-- TOPIC 4 -->
  <div class="topic-card">
    <div class="topic-header">
      <span class="topic-number">หัวข้อที่ 4</span>
      <h3 class="topic-title">การเลือกสินค้าให้เหมาะกับลูกค้า (Personalization & Customer Matching)</h3>
    </div>
    <div class="info-grid">
      <div class="info-item">
        <strong>🎯 เลือกตามความต้องการ (By Need):</strong>
        <ul style="margin: 2px 0; padding-left: 16px;">
          <li><strong>ต้องการตื่นตัว โฟกัสงานที่บ้าน/ออฟฟิศ:</strong> Dirty Coffee, Double Ristretto, Cold Brew สกัดเข้ม</li>
          <li><strong>ต้องการสดชื่น ผ่อนคลายยามบ่าย:</strong> Sparkling Yuzu Espresso Tonic, Strawberry Peach Tea</li>
          <li><strong>สายสุขภาพ/แพ้นมวัว:</strong> Vanilla Bean Oat Latte, Coconut Cloud Espresso</li>
          <li><strong>ไม่ดื่มกาแฟ (Caffeine-free):</strong> Valrhona Chocolate Float, Earl Grey Lavender</li>
        </ul>
      </div>
      <div class="info-item">
        <strong>💰 เลือกตามงบประมาณ (By Budget):</strong>
        <ul style="margin: 2px 0; padding-left: 16px;">
          <li><strong>ประหยัด (ต่ำกว่า 100฿):</strong> ครัวซองต์ (85฿), Pain au Chocolat (90฿), Cinnamon Roll (95฿)</li>
          <li><strong>มาตรฐานคาเฟ่ (100–140฿):</strong> เครื่องดื่มซิกเนเจอร์ทุกเมนู (105฿ – 140฿)</li>
          <li><strong>คุ้มค่าสูงสุดระยะยาว:</strong> สมัคร Daily Pass 10+2 แก้ว (990฿ ตกเฉลี่ย 82.5฿/แก้ว ประหยัด 40%) หรือ Starter Pass 5 แก้ว (499฿ ตก 99฿/แก้ว)</li>
        </ul>
      </div>
      <div class="info-item" style="grid-column: span 2;">
        <strong>☕ เลือกตามลักษณะการใช้งานและโอกาส (By Occasion):</strong>
        สั่งดื่มทำงานช่วง Work From Home หรือในออฟฟิศ แนะนำสั่งเป็นคู่ <em>Work & Chill Combo</em> (เครื่องดื่ม + เค้ก 179฿) | ซื้อฝากหรือชงเอง แนะนำเมล็ดกาแฟดอยช้าง Peaberry คั่วสดใหม่
      </div>
    </div>
  </div>

  <!-- TOPIC 5 -->
  <div class="topic-card">
    <div class="topic-header">
      <span class="topic-number">หัวข้อที่ 5</span>
      <h3 class="topic-title">ข้อมูลเปรียบเทียบสินค้า (Product Comparison Matrix)</h3>
    </div>
    <table>
      <thead>
        <tr>
          <th>ชื่อเมนู</th>
          <th>ราคา</th>
          <th>ระดับความเข้ม</th>
          <th>ระดับความมัน/นม</th>
          <th>จุดเด่น / สัมผัสรสชาติ</th>
          <th>กลุ่มลูกค้าที่เหมาะสมที่สุด</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><strong>Signature Dirty Coffee</strong></td>
          <td>120 ฿</td>
          <td>●●●●○ (เข้ม)</td>
          <td>●●●●● (ครีมมี่)</td>
          <td>นมเย็นจัดตัดกับกาแฟร้อน สัมผัสนุ่มลึก ไม่ใส่น้ำแข็ง แยกส่งอย่างดี</td>
          <td>คอกาแฟชอบความมันนัว ไม่ชอบรสเปรี้ยว</td>
        </tr>
        <tr>
          <td><strong>Artisan Orange Cold Brew</strong></td>
          <td>135 ฿</td>
          <td>●●●○○ (ปานกลาง)</td>
          <td>○○○○○ (ไม่มีนม)</td>
          <td>กาแฟสกัดเย็น 18 ชม. กลิ่นส้มวาเลนเซีย หอมสดชื่น บรรจุขวดแก้ว</td>
          <td>ชอบกาแฟดำสดชื่น ดื่มง่าย แก้ง่วงยามบ่าย</td>
        </tr>
        <tr>
          <td><strong>Vanilla Bean Oat Latte</strong></td>
          <td>130 ฿</td>
          <td>●●○○○ (นุ่มนวล)</td>
          <td>●●●●○ (นมโอ๊ต)</td>
          <td>นมโอ๊ตสวีเดนผสานฝักวานิลลาแท้ หอมละมุน สุขภาพดี</td>
          <td>สาย Plant-based, คนแพ้นมวัว, สายเฮลตี้</td>
        </tr>
        <tr>
          <td><strong>Sparkling Yuzu Tonic</strong></td>
          <td>130 ฿</td>
          <td>●●●○○ (กลางซ่า)</td>
          <td>○○○○○ (ไม่มีนม)</td>
          <td>น้ำส้มยูซุแท้ญี่ปุ่น + โซดาซ่า + เอสเปรสโซ เปรี้ยวหวานสดชื่น</td>
          <td>ชอบรสชาติเปรี้ยวอมหวาน ดับร้อน ปลุกพลัง</td>
        </tr>
        <tr>
          <td><strong>Starter Pass vs Daily Pass</strong></td>
          <td>499฿ / 990฿</td>
          <td>-</td>
          <td>-</td>
          <td>Starter ได้ 5 แก้ว (99฿/แก้ว) | Daily Pass ได้ 12 แก้ว (82.5฿/แก้ว)</td>
          <td>Starter เหมาะทดลองดื่ม | Daily Pass คนดื่มประจำ</td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- TOPIC 6 -->
  <div class="topic-card">
    <div class="topic-header">
      <span class="topic-number">หัวข้อที่ 6</span>
      <h3 class="topic-title">โปรโมชั่นและสิทธิพิเศษ (Promotions & Privileges)</h3>
    </div>
    <div class="info-grid">
      <div class="info-item">
        <strong>🏷️ โค้ดส่วนลด (Active Coupon Codes):</strong>
        <ul style="margin: 2px 0; padding-left: 16px;">
          <li><span class="badge-code">WELCOME10</span>: ส่วนลด 10% สำหรับลูกค้าใหม่กรอกอีเมลรับข่าวสาร</li>
          <li><span class="badge-code">DAILYVIP20</span>: ส่วนลด 20% ทุกบิลสำหรับสมาชิก Daily Brew VIP Club</li>
          <li><span class="badge-code">MORNING20</span>: ลด 20% เมนู Espresso & Cold Brew ช่วง 07:00–10:00 น.</li>
          <li><span class="badge-code">COMBO179</span>: เซ็ต Signature Drink + เบเกอรี่ เหลือ 179 บาท (ปกติ 240฿)</li>
        </ul>
      </div>
      <div class="info-item">
        <strong>💳 บัตรสะสมแต้ม LINE Reward Card & VIP Club:</strong>
        <ul style="margin: 2px 0; padding-left: 16px;">
          <li>ยอดซื้อทุก <strong>10 บาท = 1 แต้ม</strong> ครบ 100 แต้ม แลกฟรี 1 แก้ว</li>
          <li>สมาชิก VIP รับเครื่องดื่มและเค้กฟรีในเดือนเกิด และรับแต้มพิเศษ</li>
          <li>ลิงก์เข้าดูบัตรสะสมแต้มบน LINE: <code>https://u.lin.ee/um3QCOs</code></li>
          <li><em>เงื่อนไข:</em> คูปอง 1 ใบต่อ 1 บิล ไม่สามารถใช้ซ้อนกันได้</li>
        </ul>
      </div>
    </div>
  </div>

  <div class="page-break"></div>

  <!-- ================= PAGE 3 ================= -->
  <!-- TOPIC 7 -->
  <div class="topic-card">
    <div class="topic-header">
      <span class="topic-number">หัวข้อที่ 7</span>
      <h3 class="topic-title">ขั้นตอนการสั่งซื้อเดลิเวอรี (Delivery Ordering Workflow)</h3>
    </div>
    <div class="flow-steps">
      <div class="flow-step-box">
        <strong>ขั้นที่ 1</strong>
        เลือกเมนู & ปรับแต่งหวาน/นม
      </div>
      <div class="flow-step-box">
        <strong>ขั้นที่ 2</strong>
        ใส่ตะกร้า (Add to Cart)
      </div>
      <div class="flow-step-box">
        <strong>ขั้นที่ 3</strong>
        ใส่โค้ดส่วนลด & ที่อยู่จัดส่ง
      </div>
      <div class="flow-step-box">
        <strong>ขั้นที่ 4</strong>
        ชำระเงิน QR / โอนเงิน
      </div>
      <div class="flow-step-box">
        <strong>ขั้นที่ 5</strong>
        ไรเดอร์จัดส่งด่วน 30-45 นาที
      </div>
    </div>
  </div>

  <!-- TOPIC 8 & 9 & 10 (3 Columns or Compact Grids) -->
  <div class="info-grid">
    <div class="topic-card" style="margin-bottom: 0;">
      <div class="topic-header">
        <span class="topic-number">หัวข้อที่ 8</span>
        <h3 class="topic-title">การชำระเงิน (Payment)</h3>
      </div>
      <ul style="margin: 0; padding-left: 16px; font-size: 11px;">
        <li><strong>QR PromptPay:</strong> สแกนจ่ายผ่าน Mobile Banking ทุกธนาคาร ฟรีค่าธรรมเนียม ตรวจสอบยอดออโต้</li>
        <li><strong>โอนเงิน:</strong> ธ.กสิกรไทย <code>123-4-56789-0</code> บจก. เดลี่ บริว คอฟฟี่</li>
        <li><strong>บัตรเครดิต/เดบิต:</strong> Visa, Mastercard, JCB (3D Secure)</li>
        <li><strong>เก็บเงินปลายทาง (COD):</strong> ยอดไม่เกิน 1,500 บาท มีค่าธรรมเนียม 20฿</li>
      </ul>
    </div>

    <div class="topic-card" style="margin-bottom: 0;">
      <div class="topic-header">
        <span class="topic-number">หัวข้อที่ 9</span>
        <h3 class="topic-title">การจัดส่ง (Shipping & Delivery)</h3>
      </div>
      <ul style="margin: 0; padding-left: 16px; font-size: 11px;">
        <li><strong>เครื่องดื่ม & เบเกอรี่:</strong> GrabExpress / LINE MAN ภายใน 30–45 นาที (บรรจุกล่องเก็บความเย็นแยกน้ำแข็ง)</li>
        <li><strong>เมล็ดกาแฟ:</strong> Kerry Express / Flash ภายใน 1–2 วันทำการ</li>
        <li><strong>ค่าส่ง:</strong> ใน กทม. เริ่มต้น 35฿ | พัสดุทั่วไทย 50฿</li>
        <li><strong>เงื่อนไขส่งฟรี:</strong> <u>สั่งครบ 500 บาทขึ้นไป จัดส่งฟรีทันที!</u></li>
      </ul>
    </div>
  </div>

  <div class="topic-card" style="margin-top: 10px;">
    <div class="topic-header">
      <span class="topic-number">หัวข้อที่ 10</span>
      <h3 class="topic-title">การคืน / เปลี่ยน / เคลมสินค้า (Return, Refund & Claim Policy)</h3>
    </div>
    <div class="info-grid">
      <div class="info-item">
        <strong>🍵 เครื่องดื่มและเบเกอรี่:</strong> หากเครื่องดื่มหก เสียหาย หรือผิดสเปก ถ่ายภาพ/วิดีโอแจ้ง LINE OA ภายใน <strong>2 ชั่วโมง</strong> หลังได้รับ ร้านจัดส่งแก้วใหม่ให้ทันที หรือคืนเงินเต็ม 100%
      </div>
      <div class="info-item">
        <strong>📦 เมล็ดกาแฟและอุปกรณ์:</strong> ถุงฉีกขาด วาล์วเสียหาย หรือบดผิดระดับ แจ้งเปลี่ยนสินค้าใหม่ได้ภายใน <strong>7 วัน</strong> ทางร้านออกค่าจัดส่งขากลับให้ทั้งหมด
      </div>
    </div>
  </div>

  <!-- TOPIC 11 -->
  <div class="topic-card">
    <div class="topic-header">
      <span class="topic-number">หัวข้อที่ 11</span>
      <h3 class="topic-title">FAQ คำถามที่พบบ่อยสำหรับ AI Chatbot (20 Questions & Answers)</h3>
    </div>
    <div class="faq-grid">
      <div class="faq-item">
        <div class="faq-q">Q1: ร้านเปิด–ปิดกี่โมง มีวันหยุดไหม?</div>
        <div class="faq-a">A: เปิดรับออเดอร์เดลิเวอรีทุกวัน ไม่มีวันหยุด จ.-ศ. 07:00–18:00 น. | ส.-อา. และวันหยุด 08:00–19:00 น. ค่ะ</div>
      </div>
      <div class="faq-item">
        <div class="faq-q">Q2: มีหน้าร้านไหม ตั้งอยู่ที่ไหน?</div>
        <div class="faq-a">A: <strong>ขณะนี้ยังไม่มีหน้าร้านสำหรับนั่งทานค่ะ</strong> ให้บริการในรูปแบบ Online & Delivery ชงสดส่งด่วนถึงบ้านและออฟฟิศค่ะ</div>
      </div>
      <div class="faq-item">
        <div class="faq-q">Q3: เมนูซิกเนเจอร์อันดับ 1 ของร้านคืออะไร?</div>
        <div class="faq-a">A: Signature Dirty Coffee (120฿) นมเย็นจัดกับเอสเปรสโซสกัดร้อน และ Sparkling Yuzu Tonic (130฿) ค่ะ</div>
      </div>
      <div class="faq-item">
        <div class="faq-q">Q4: ถ้าแพ้นมวัว มีตัวเลือกอะไรบ้าง?</div>
        <div class="faq-a">A: มีนมโอ๊ตพรีเมียมจากสวีเดน และนมอัลมอนด์ (+15฿) และมี Coconut Cloud Espresso ใช้น้ำมะพร้าวแท้ค่ะ</div>
      </div>
      <div class="faq-item">
        <div class="faq-q">Q5: คนไม่ดื่มกาแฟ สั่งอะไรได้บ้าง?</div>
        <div class="faq-a">A: แนะนำ Kyoto Uji Matcha Cloud (125฿), Valrhona Chocolate Float (135฿) หรือ Strawberry Peach Tea (115฿) ค่ะ</div>
      </div>
      <div class="faq-item">
        <div class="faq-q">Q6: สั่งออนไลน์ใช้เวลากี่นาที?</div>
        <div class="faq-a">A: เครื่องดื่มชงสดส่งด่วน 30-45 นาที แยกน้ำแข็งซีลฝาอย่างดี เมล็ดกาแฟจัดส่งพัสดุ 1-2 วันทำการค่ะ</div>
      </div>
      <div class="faq-item">
        <div class="faq-q">Q7: มีโปรโมชั่นส่งฟรีหรือไม่?</div>
        <div class="faq-a">A: สั่งซื้อครบ 500 บาทขึ้นไป จัดส่งฟรีทันทีทุกออเดอร์ค่ะ</div>
      </div>
      <div class="faq-item">
        <div class="faq-q">Q8: มีโค้ดส่วนลดลูกค้าใหม่ไหม?</div>
        <div class="faq-a">A: กรอกอีเมลรับโค้ด <span class="badge-code">WELCOME10</span> ลด 10% หรือสมัคร VIP รับโค้ด <span class="badge-code">DAILYVIP20</span> ลด 20% ค่ะ</div>
      </div>
      <div class="faq-item">
        <div class="faq-q">Q9: บัตรสะสมแต้มใช้งานอย่างไร?</div>
        <div class="faq-a">A: ซื้อครบทุก 10 บาท = 1 แต้มใน LINE OA ครบ 100 แต้มแลกเครื่องดื่มฟรี 1 แก้ว แต้มมีอายุ 1 ปีค่ะ</div>
      </div>
      <div class="faq-item">
        <div class="faq-q">Q10: ปรับความหวานได้กี่ระดับ?</div>
        <div class="faq-a">A: ปรับได้ 4 ระดับ: ไม่หวาน (0%), หวานน้อย (25%), หวานกำลังดี (50% แนะนำ), และหวานปกติ (100%) ค่ะ</div>
      </div>
      <div class="faq-item">
        <div class="faq-q">Q11: เมล็ดกาแฟเป็นของที่ไหน คั่วสดไหม?</div>
        <div class="faq-a">A: เมล็ดหลักคือ Thai Single Origin ดอยช้าง เชียงราย เกรด Peaberry คั่วสดใหม่สัปดาห์ต่อสัปดาห์ค่ะ</div>
      </div>
      <div class="faq-item">
        <div class="faq-q">Q12: สั่งเมล็ดกาแฟ ให้ทางร้านบดให้ได้ไหม?</div>
        <div class="faq-a">A: บดฟรีไม่มีค่าใช้จ่ายค่ะ ระบุวิธีชงได้เลย เช่น Espresso, Moka Pot, Drip Filter หรือ Cold Brew ค่ะ</div>
      </div>
      <div class="faq-item">
        <div class="faq-q">Q13: แพ็กเกจรายเดือน Daily Pass คุ้มอย่างไร?</div>
        <div class="faq-a">A: Daily Pass 990฿ ได้ 10+2 แก้ว (รวม 12 แก้ว เฉลี่ย 82.5฿/แก้ว) ฟรีอัปเกรดนมพืช ประหยัดถึง 40% ค่ะ</div>
      </div>
      <div class="faq-item">
        <div class="faq-q">Q14: ชำระเงินช่องทางใดได้บ้าง?</div>
        <div class="faq-a">A: QR PromptPay, โอนผ่านธ.กสิกรไทย, บัตรเครดิต/เดบิต Visa/Mastercard และเก็บเงินปลายทาง (COD) ค่ะ</div>
      </div>
      <div class="faq-item">
        <div class="faq-q">Q15: ส่งเดลิเวอรี เครื่องดื่มจะละลายหรือเสียรสชาติไหม?</div>
        <div class="faq-a">A: ไม่เลยค่ะ ทางร้านซีลฝาสุญญากาศอย่างดี และแยกน้ำแข็งบรรจุถุงเก็บความเย็น ส่งถึงมือสดชื่นเหมือนชงสดค่ะ</div>
      </div>
      <div class="faq-item">
        <div class="faq-q">Q16: จัดส่งพื้นที่ไหนบ้าง ค่าส่งเท่าไร?</div>
        <div class="faq-a">A: จัดส่งทั่วกรุงเทพฯ และปริมณฑลผ่านไรเดอร์ ค่าส่งคิดตามระยะทางจริงเริ่มต้น 35 บาท สั่งครบ 500 บาทส่งฟรีค่ะ</div>
      </div>
      <div class="faq-item">
        <div class="faq-q">Q17: ได้รับเครื่องดื่มหกหรือผิด ต้องทำอย่างไร?</div>
        <div class="faq-a">A: ถ่ายรูปส่งให้แอดมินทาง LINE OA: @285arhlw ภายใน 2 ชม. ร้านจัดส่งแก้วใหม่ให้ทันที หรือคืนเงิน 100% ค่ะ</div>
      </div>
      <div class="faq-item">
        <div class="faq-q">Q18: เค้กและเบเกอรี่อบสดทุกวันไหม?</div>
        <div class="faq-a">A: ครัวซองต์และบาสก์ชีสเค้กอบสดใหม่ทุกเช้า ไม่ใส่วัตถุกันเสีย ใช้วัตถุดิบนำเข้าพรีเมียมค่ะ</div>
      </div>
      <div class="faq-item">
        <div class="faq-q">Q19: มีเซ็ต Coffee Break ส่งออฟฟิศ/ประชุมไหม?</div>
        <div class="faq-a">A: มีบริการจัดส่ง Coffee & Bakery Boxset สำหรับประชุม สั่งล่วงหน้า 1 วัน ได้รับส่วนลด 15% ติดต่อทาง LINE ได้เลยค่ะ</div>
      </div>
      <div class="faq-item">
        <div class="faq-q">Q20: สมาชิก VIP มีสิทธิประโยชน์อะไรพิเศษ?</div>
        <div class="faq-a">A: รับส่วนลด 20% ทุกคำสั่งซื้อ, ฟรีเครื่องดื่ม+เค้กวันเกิด, และชิมเมล็ด Specialty ล็อตพิเศษก่อนใครค่ะ</div>
      </div>
    </div>
  </div>

  <div class="page-break"></div>

  <!-- ================= PAGE 4 ================= -->
  <!-- TOPIC 12 -->
  <div class="topic-card">
    <div class="topic-header">
      <span class="topic-number">หัวข้อที่ 12</span>
      <h3 class="topic-title">คำถามสำหรับระบบแนะนำสินค้า (Diagnostic Questions for AI Bot)</h3>
    </div>
    <div class="info-grid">
      <div class="rule-box">
        <strong>คำถามที่ 1: ความชอบด้านรสชาติกาแฟ</strong><br>
        <em>"วันนี้อยากดื่มกาแฟเข้มข้นตื่นเต็มตา, กาแฟผลไม้สดชื่น, หรือเครื่องดื่มไม่ผสมกาแฟดีคะ?"</em><br>
        (ตัวเลือก: เข้มมันครีมมี่ / สดชื่นเปรี้ยวหวาน / ชา-โกโก้ Non-Coffee)
      </div>
      <div class="rule-box">
        <strong>คำถามที่ 2: เนื้อสัมผัสและประเภทนม</strong><br>
        <em>"ชอบดื่มแบบผสมนมสดหอมมัน, นมโอ๊ตสุขภาพ หรือชอบกาแฟดำสกัดเย็นคะ?"</em><br>
        (ตัวเลือก: นมสดแท้ / นมโอ๊ต Plant-Based / กาแฟดำ)
      </div>
      <div class="rule-box">
        <strong>คำถามที่ 3: ระดับความหวาน</strong><br>
        <em>"ชอบความหวานระดับไหนดีคะ ร้านปรับได้ตั้งแต่ 0% ถึงหวานปกติ 100% เลยค่ะ"</em><br>
        (ตัวเลือก: ไม่หวาน 0% / หวานน้อย 25-50% / หวานปกติ 100%)
      </div>
      <div class="rule-box">
        <strong>คำถามที่ 4: ความถี่ในการดื่ม & งบประมาณ</strong><br>
        <em>"สั่งดื่มแก้วเดี่ยว หรือดื่มเป็นประจำ อยากดูแพ็กเกจรายเดือนสุดคุ้มด้วยไหมคะ?"</em><br>
        (ตัวเลือก: แก้วเดี่ยว 100-140฿ / สนใจแพ็กเกจประหยัด 40%)
      </div>
    </div>
  </div>

  <!-- TOPIC 13 -->
  <div class="topic-card">
    <div class="topic-header">
      <span class="topic-number">หัวข้อที่ 13</span>
      <h3 class="topic-title">กฎในการแนะนำสินค้า (Chatbot Recommendation Rules / Decision Logic)</h3>
    </div>
    <div>
      <div class="rule-box">
        <span class="rule-if">RULE 1: IF</span> ลูกค้าชอบ <u>กาแฟเข้มข้น + หอมมันนม + ไม่เปรี้ยว</u> <span class="rule-then">→ แนะนำ</span> <strong>Signature Dirty Coffee (120฿)</strong> ชูจุดเด่นนมสูตรลับเย็นจัดกับเอสเปรสโซสกัดร้อน แยกส่งอย่างดี
      </div>
      <div class="rule-box">
        <span class="rule-if">RULE 2: IF</span> ลูกค้าชอบ <u>สดชื่น + ตื่นตัว + ผลไม้เปรี้ยวซ่า</u> <span class="rule-then">→ แนะนำ</span> <strong>Sparkling Yuzu Tonic (130฿)</strong> หรือ <strong>Artisan Orange Cold Brew (135฿)</strong>
      </div>
      <div class="rule-box">
        <span class="rule-if">RULE 3: IF</span> ลูกค้า <u>แพ้นมวัว / ทานเจ / สายสุขภาพคลีน</u> <span class="rule-then">→ แนะนำ</span> <strong>Vanilla Bean Oat Latte (130฿)</strong> หรือ <strong>Coconut Cloud Espresso (125฿)</strong>
      </div>
      <div class="rule-box">
        <span class="rule-if">RULE 4: IF</span> ลูกค้า <u>ไม่ดื่มกาแฟ + ชอบรสเข้มข้น</u> <span class="rule-then">→ แนะนำ</span> <strong>Valrhona Dark Chocolate Float (135฿)</strong> ช็อกโกแลตฝรั่งเศส 70%
      </div>
      <div class="rule-box">
        <span class="rule-if">RULE 5: IF</span> ลูกค้า <u>ไม่ดื่มกาแฟ + ชอบหวานสดชื่นดับร้อน</u> <span class="rule-then">→ แนะนำ</span> <strong>Strawberry Peach Sparkling Tea (115฿)</strong> หรือ <strong>Kyoto Uji Matcha Cloud (125฿)</strong>
      </div>
      <div class="rule-box">
        <span class="rule-if">RULE 6: IF</span> ลูกค้าสั่งเครื่องดื่มแล้ว และถามหาของหวานคู่กัน (Upselling) <span class="rule-then">→ แนะนำ</span> <strong>Work & Chill Combo (179฿)</strong> คู่กับ <strong>Basque Cheesecake</strong> หรือ <strong>Almond Croissant</strong>
      </div>
      <div class="rule-box">
        <span class="rule-if">RULE 7: IF</span> ลูกค้า <u>ดื่มประจำเกือบทุกวัน / ถามหาโปรโมชั่น</u> <span class="rule-then">→ แนะนำ</span> <strong>Daily Brew Pass (10+2 แก้ว เพียง 990฿)</strong> ประหยัด 40% และฟรีอัปเกรดนมพืช
      </div>
      <div class="rule-box">
        <span class="rule-if">RULE 8: IF</span> ลูกค้า <u>ต้องการเมล็ดกาแฟไปชงเองที่บ้าน</u> <span class="rule-then">→ แนะนำ</span> <strong>เมล็ดกาแฟดอยช้าง Peaberry 250g (280฿)</strong> คั่วสดใหม่พร้อมบริการบดฟรี
      </div>
    </div>
  </div>

  <!-- TOPIC 14 -->
  <div class="topic-card highlight">
    <div class="topic-header">
      <span class="topic-number">หัวข้อที่ 14</span>
      <h3 class="topic-title">ข้อมูลติดต่อและบริการหลังการขาย (Contact & Customer Service SLA)</h3>
    </div>
    <div class="info-grid">
      <div class="info-item"><span class="info-label">LINE Official:</span> <strong>@285arhlw</strong> (แอดมินตอบ & สะสมแต้มอัตโนมัติ)</div>
      <div class="info-item"><span class="info-label">เบอร์โทรศัพท์:</span> <strong>02-999-8888, 081-234-5678</strong></div>
      <div class="info-item"><span class="info-label">เว็บไซต์หลัก:</span> <code>https://beemcraf.github.io/daily-brew/</code></div>
      <div class="info-item"><span class="info-label">Facebook Fanpage:</span> Daily Brew Specialty Coffee</div>
      <div class="info-item"><span class="info-label">เวลาติดต่อเจ้าหน้าที่:</span> ทุกวัน 07:00 – 19:00 น. (นอกเวลาทำการ AI Chatbot ตอบอัตโนมัติ 24 ชม.)</div>
      <div class="info-item"><span class="info-label">SLA การตอบกลับ:</span> เจ้าหน้าที่ตอบกลับภายใน 5–10 นาทีในเวลาทำการ | เคลมสินค้าประสานงานทันที</div>
    </div>
  </div>

  <div class="footer-note">
    เอกสารประกอบการจัดทำโครงงานนักศึกษา — ระบบฐานข้อมูลและความรู้สำหรับ AI Chatbot & Customer Data Platform (CDP) | Daily Brew Specialty Coffee
  </div>

</body>
</html>
"""

html_path = "/Users/naphatbeem/Documents/ร้านขายกาแฟ(Daily Brew)/DailyBrew_Chatbot_KnowledgeBase.html"
pdf_path = "/Users/naphatbeem/Documents/ร้านขายกาแฟ(Daily Brew)/DailyBrew_Chatbot_KnowledgeBase.pdf"
desktop_pdf_path = "/Users/naphatbeem/Desktop/DailyBrew_Chatbot_KnowledgeBase.pdf"

with open(html_path, "w", encoding="utf-8") as f:
    f.write(html_content)

print(f"HTML file updated at {html_path}")

chrome_bin = "/Applications/Google Chrome.app/Contents/MacOS/Google Chrome"
cmd = [
    chrome_bin,
    "--headless",
    "--disable-gpu",
    "--no-pdf-header-footer",
    f"--print-to-pdf={pdf_path}",
    html_path
]

res = subprocess.run(cmd, capture_output=True, text=True)
print(f"PDF generation result code: {res.returncode}")
if os.path.exists(pdf_path):
    print(f"PDF successfully generated! Size: {os.path.getsize(pdf_path)} bytes")
    shutil.copyfile(pdf_path, desktop_pdf_path)
    print(f"PDF copied to Desktop: {desktop_pdf_path}")
