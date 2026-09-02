/**
 * DAILY BREW - SPECIALTY COFFEE & DIGITAL MARKETING SHOWCASE
 * Client-side Logic & Interactions
 */

// ==========================================
// 1. DATA: MENU ITEMS DATABASE
// ==========================================
const MENU_ITEMS = [
  // 1. ESPRESSO BAR
  {
    id: 'dirty-coffee',
    name: 'Signature Dirty Coffee',
    nameTh: 'เดอร์ตี้ คอฟฟี่ ซิกเนเจอร์',
    category: 'espresso',
    price: 120,
    badge: 'Best Seller ⭐',
    badgeType: 'bestseller',
    image: 'assets/dirty_coffee.jpg',
    description: 'ดับเบิลช็อตเอสเปรสโซเข้มข้นสกัดร้อน ไหลรินลงสู่นมสดผสมครีมสูตรลับเย็นจัด หอมมันเข้มข้นอย่างลงตัว'
  },
  {
    id: 'coconut-espresso',
    name: 'Coconut Cloud Espresso',
    nameTh: 'เอสเปรสโซมะพร้าวน้ำหอมคลาวด์',
    category: 'espresso',
    price: 125,
    badge: 'Signature 🥥',
    badgeType: 'bestseller',
    image: 'assets/coconut_espresso.jpg',
    description: 'น้ำมะพร้าวน้ำหอมสดแท้ 100% หอมหวานธรรมชาติ ท็อปด้วยเอสเปรสโซสกัดร้อนและฟองครีมมะพร้าวนุ่มละมุน'
  },
  {
    id: 'vanilla-oat-latte',
    name: 'Vanilla Bean Oat Latte',
    nameTh: 'วานิลลาบีน โอ๊ต ลาเต้',
    category: 'espresso',
    price: 130,
    badge: 'Plant-Based 🌾',
    badgeType: '',
    image: 'assets/vanilla_oat_latte.jpg',
    description: 'กาแฟลาเต้นมโอ๊ตเกรดพรีเมียม ผสานฝักวานิลลาแท้จากมาดากัสการ์ หอมนุ่มละมุน หวานน้อย สุขภาพดี'
  },
  {
    id: 'flat-white',
    name: 'Velvet Flat White',
    nameTh: 'แฟลต ไวท์ ลาเต้อาร์ต',
    category: 'espresso',
    price: 105,
    badge: 'Popular ☕',
    badgeType: '',
    image: 'assets/flat_white.jpg',
    description: 'ดับเบิลริสเตรตโต้คั่วกลาง ผสานนมสตีมฟองละเอียดเนียนนุ่มดุจแพรไหม วาดลายหงส์ Swan Art สุดประณีต'
  },
  {
    id: 'caramel-macchiato',
    name: 'Iced Salted Caramel Macchiato',
    nameTh: 'ไอซ์ คาราเมล มัคคิอาโต',
    category: 'espresso',
    price: 120,
    badge: 'Sweet & Salty 🍮',
    badgeType: '',
    image: 'assets/caramel_macchiato.jpg',
    description: 'นมสดวานิลลาเย็น ท็อปด้วยเอสเปรสโซเข้มข้น และซอส Salted Caramel เคี่ยวเองสูตรโฮมเมดหอมหวาน'
  },

  // 2. COLD BREW & SPECIALTY
  {
    id: 'orange-coldbrew',
    name: 'Artisan Orange Cold Brew',
    nameTh: 'โคลด์บรูว์ ส้มวาเลนเซีย',
    category: 'coldbrew',
    price: 135,
    badge: 'Barista Pick 🍊',
    badgeType: '',
    image: 'assets/cold_brew.jpg',
    description: 'กาแฟสกัดเย็นนาน 18 ชั่วโมง ผสานกลิ่นหอมของผิวส้มวาเลนเซียอบแห้งและโทนิค สดชื่น ปลุกพลังยามบ่าย'
  },
  {
    id: 'yuzu-sparkling',
    name: 'Sparkling Yuzu Espresso Tonic',
    nameTh: 'สปาร์กลิง ยูซุ เอสเปรสโซ',
    category: 'coldbrew',
    price: 130,
    badge: 'Refreshing 🍋',
    badgeType: 'bestseller',
    image: 'assets/yuzu_espresso.jpg',
    description: 'น้ำส้มยูซุแท้ 100% จากญี่ปุ่น ท็อปด้วยโซดาซ่าและช็อตเอสเปรสโซดอยช้าง เปรี้ยวหวานซ่า สดชื่นตื่นเต็มตา'
  },
  {
    id: 'dirty-matcha',
    name: 'Military Dirty Matcha Espresso',
    nameTh: 'มิลลิทารี เดอร์ตี้ มัทฉะ',
    category: 'coldbrew',
    price: 140,
    badge: 'Trending 3-Layers 🍵',
    badgeType: 'bestseller',
    image: 'assets/dirty_matcha.jpg',
    description: 'ความลงตัวแบบ 3 เลเยอร์: ชามัทฉะอุจิเข้มข้น + นมสดเย็นจัด + ดับเบิลช็อตเอสเปรสโซ หอมทั้งชาและกาแฟ'
  },

  // 3. NON-COFFEE & ARTISANAL TEA
  {
    id: 'uji-matcha-cloud',
    name: 'Kyoto Uji Matcha Cloud',
    nameTh: 'อุจิมัทฉะ คลาวด์',
    category: 'noncoffee',
    price: 125,
    badge: 'Ceremonial Grade 🍵',
    badgeType: '',
    image: 'assets/matcha.jpg',
    description: 'มัทฉะเกรดพิธีชงนำเข้าจากเมืองอุจิ เกียวโต ตีสดชามต่อชาม ท็อปด้วยโฟมนมมะพร้าวเนื้อนุ่มละมุน'
  },
  {
    id: 'valrhona-chocolate',
    name: 'Valrhona Dark Chocolate Float',
    nameTh: 'ดาร์กช็อกโกแลต วาลโรห์นา 70%',
    category: 'noncoffee',
    price: 135,
    badge: 'French Valrhona 🍫',
    badgeType: 'bestseller',
    image: 'assets/valrhona_chocolate.jpg',
    description: 'ช็อกโกแลตฝรั่งเศสแท้เข้มข้น 70% รสชาติเข้มลึก หวานน้อย ท็อปด้วยไอศกรีมวานิลลาและช็อกโกแลตเคิร์ล'
  },
  {
    id: 'peach-cold-tea',
    name: 'Strawberry Peach Sparkling Tea',
    nameTh: 'ชาพีชสตรอว์เบอร์รีสปาร์กลิง',
    category: 'noncoffee',
    price: 115,
    badge: 'Fruity Soda 🍑',
    badgeType: '',
    image: 'assets/peach_cold_tea.jpg',
    description: 'ชาดำอินทรีย์สกัดเย็นเบลนด์เนื้อพีชและสตรอว์เบอร์รีสด ผสานโซดาซ่าสดชื่น เปรี้ยวหวานหอมผลไม้ ดับร้อน'
  },
  {
    id: 'earl-grey-lavender',
    name: 'Earl Grey Lavender Milk Tea',
    nameTh: 'ชานมเอิร์ลเกรย์ ลาเวนเดอร์',
    category: 'noncoffee',
    price: 110,
    badge: 'Floral & Relax 🪻',
    badgeType: '',
    image: 'assets/earl_grey_lavender.jpg',
    description: 'ชาเอิร์ลเกรย์พรีเมียมหอมกลิ่นน้ำมันเบอร์กามอตและดอกลาเวนเดอร์ ผสมนมสดหอมมัน นุ่มนวล ชวนผ่อนคลาย'
  },

  // 4. ARTISANAL BAKERY & SIGNATURE CAKES
  {
    id: 'almond-croissant',
    name: 'French Almond Croissant',
    nameTh: 'ครัวซองต์อัลมอนด์ฝรั่งเศส',
    category: 'bakery',
    price: 85,
    badge: 'Freshly Baked 🥐',
    badgeType: '',
    image: 'assets/croissant.jpg',
    description: 'ครัวซองต์เนยสดแท้จากฝรั่งเศส แป้งกรอบนอกนุ่มฉ่ำเนย สอดไส้ครีมอัลมอนด์และโรยแอลมอนด์สไลซ์อบกรอบ'
  },
  {
    id: 'pain-au-chocolat',
    name: 'French Pain au Chocolat',
    nameTh: 'แปง โอ ช็อกโกลา ฝรั่งเศส',
    category: 'bakery',
    price: 90,
    badge: 'Pure Butter 🍫',
    badgeType: '',
    image: 'assets/pain_au_chocolat.jpg',
    description: 'แป้งพัฟเนยสดฝรั่งเศสอบกรอบเป็นชั้นเลเยอร์ สอดไส้แท่งดาร์กช็อกโกแลตเบลเยียม 2 แท่ง อบใหม่หอมฟุ้ง'
  },
  {
    id: 'truffle-brioche',
    name: 'Truffle Cheese Brioche Toast',
    nameTh: 'บริออชโทสต์ ทรัฟเฟิลชีสเยิ้ม',
    category: 'bakery',
    price: 120,
    badge: 'Chef Special 🧀',
    badgeType: 'bestseller',
    image: 'assets/truffle_brioche.jpg',
    description: 'ขนมปังบริออชนุ่มฉ่ำเนย ปิ้งร้อนๆ สอดไส้มอสซาเรลล่าชีสยืดและซอสเห็ดทรัฟเฟิลดำ หอมฟุ้งสไตล์คาเฟ่บรันช์'
  },
  {
    id: 'cinnamon-roll',
    name: 'Cinnamon Roll Cream Cheese',
    nameTh: 'ซินนามอนโรล ครีมชีสเกลซ',
    category: 'bakery',
    price: 95,
    badge: 'Fresh Glazed 🌀',
    badgeType: '',
    image: 'assets/cinnamon_roll.jpg',
    description: 'ขนมปังโรลเนื้อนุ่ม หอมกลิ่นอบเชยและน้ำตาลทรายแดง ราดด้วยครีมชีสฟรอสติ้งเนียนนุ่ม ทานคู่อเมริกาโน่ร้อน'
  },
  {
    id: 'basque-cheesecake',
    name: 'San Sebastian Basque Cheesecake',
    nameTh: 'บาสก์ชีสเค้กหน้าไหม้',
    category: 'bakery',
    price: 145,
    badge: 'Best Seller 🍰',
    badgeType: 'bestseller',
    image: 'assets/cheesecake.jpg',
    description: 'ชีสเค้กสเปนหน้าไหม้คาราเมล เนื้อเนียนนุ่มละลายในปาก ตรงกลางลาวาเยิ้ม หอมกลิ่นวานิลลามาดากัสการ์'
  },
  {
    id: 'tiramisu',
    name: 'Classic Tiramisu Espresso Cup',
    nameTh: 'ทีรามิสุ เอสเปรสโซมาสคาร์โปเน',
    category: 'bakery',
    price: 150,
    badge: 'Authentic Italian ☕',
    badgeType: 'bestseller',
    image: 'assets/tiramisu.jpg',
    description: 'เลดี้ฟิงเกอร์จุ่มช็อตเอสเปรสโซดอยช้าง สลับชั้นครีมชีสมาสคาร์โปเนแท้ โรยผงโกโก้เข้มข้น คู่แท้ร้านกาแฟ'
  },
  {
    id: 'strawberry-shortcake',
    name: 'Hokkaido Strawberry Shortcake',
    nameTh: 'สตรอว์เบอร์รี ชอร์ตเค้ก ญี่ปุ่น',
    category: 'bakery',
    price: 140,
    badge: 'Fresh Hokkaido 🍓',
    badgeType: '',
    image: 'assets/strawberry_shortcake.jpg',
    description: 'สปันจ์เค้กวานิลลานุ่มเบา ครีมสดฮอกไกโดแท้ และสตรอว์เบอร์รีสดลูกโต หวานละมุน ตัดเลี่ยนได้ดีเยี่ยม'
  },
  {
    id: 'matcha-crepe-cake',
    name: 'Kyoto Matcha Mille Crepe Cake',
    nameTh: 'เครปเค้กมัทฉะ ถั่วแดงหลวง',
    category: 'bakery',
    price: 135,
    badge: '20 Layers Mille 🍵',
    badgeType: '',
    image: 'assets/matcha_crepe_cake.jpg',
    description: 'แป้งเครปมัทฉะบางนุ่มซ้อนกันกว่า 20 ชั้น ปาดครีมสดชาเขียวเข้มข้น เสิร์ฟพร้อมซอสถั่วแดงหลวงหวานละมุน'
  }
];

// ==========================================
// 2. STATE MANAGEMENT
// ==========================================
let cart = [];
let appliedDiscountPercent = 0;
let appliedDiscountAmount = 0;
let appliedCouponCode = '';

// Customizer Modal State
let currentCustomizingItem = null;
let selectedSweetness = '50%';
let selectedMilk = 'Fresh Milk';
let selectedMilkExtraPrice = 0;
let selectedAddons = [];
let selectedAddonPrice = 0;

// Quiz State
let quizAnswers = {
  step1: '',
  step2: '',
  step3: ''
};

// ==========================================
// 3. INITIALIZATION
// ==========================================
document.addEventListener('DOMContentLoaded', () => {
  renderMenuItems('all');
  setupEventListeners();
  loadSavedVoucher();
  loadSavedVipMember();
  initSmartRecommendation();
});

function setupEventListeners() {
  // Navigation Scroll Effect
  const navbar = document.getElementById('navbar');
  window.addEventListener('scroll', () => {
    if (window.scrollY > 40) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  });

  // Mobile Menu Toggle
  const btnMobile = document.getElementById('btn-mobile-menu');
  const navLinks = document.getElementById('nav-links');
  if (btnMobile) {
    btnMobile.addEventListener('click', () => {
      if (navLinks.style.display === 'flex') {
        navLinks.style.display = 'none';
      } else {
        navLinks.style.display = 'flex';
        navLinks.style.flexDirection = 'column';
        navLinks.style.position = 'absolute';
        navLinks.style.top = '80px';
        navLinks.style.left = '0';
        navLinks.style.right = '0';
        navLinks.style.background = '#fdfaf6';
        navLinks.style.padding = '20px';
        navLinks.style.boxShadow = '0 10px 20px rgba(0,0,0,0.1)';
      }
    });
  }

  // Nav Links Click & Active State
  let isClickScrolling = false;
  let clickScrollTimer = null;
  const navLinkElements = document.querySelectorAll('.nav-links a');

  navLinkElements.forEach(link => {
    link.addEventListener('click', function(e) {
      isClickScrolling = true;
      navLinkElements.forEach(l => l.classList.remove('active'));
      this.classList.add('active');

      if (clickScrollTimer) clearTimeout(clickScrollTimer);
      clickScrollTimer = setTimeout(() => {
        isClickScrolling = false;
      }, 900);

      if (window.innerWidth <= 920 && navLinks) {
        navLinks.style.display = 'none';
      }
    });
  });

  // ScrollSpy: Update active nav link on scroll
  const trackedSections = document.querySelectorAll('section[id], footer[id]');
  window.addEventListener('scroll', () => {
    if (isClickScrolling) return; // Prevent interference during smooth scroll after click

    // Check if scrolled to the bottom of the page -> highlight Contact / Footer
    const isAtBottom = (window.innerHeight + window.scrollY) >= (document.documentElement.scrollHeight - 70);
    if (isAtBottom) {
      navLinkElements.forEach(link => {
        link.classList.toggle('active', link.getAttribute('href') === '#contact');
      });
      return;
    }

    let currentId = '';
    const scrollPos = window.scrollY + 200;

    trackedSections.forEach(section => {
      const top = section.offsetTop;
      const height = section.offsetHeight;
      if (scrollPos >= top && scrollPos < top + height) {
        currentId = section.getAttribute('id');
      }
    });

    if (currentId) {
      navLinkElements.forEach(link => {
        link.classList.toggle('active', link.getAttribute('href') === `#${currentId}`);
      });
    }
  });

  // Menu Category Filter Tabs
  const filterButtons = document.querySelectorAll('.menu-tab-btn');
  filterButtons.forEach(btn => {
    btn.addEventListener('click', (e) => {
      filterButtons.forEach(b => b.classList.remove('active'));
      e.target.classList.add('active');
      const category = e.target.getAttribute('data-category');
      renderMenuItems(category);
    });
  });

  // Cart Modal Triggers
  document.getElementById('btn-open-cart').addEventListener('click', openCartModal);

  // Marketing Deck Modal Triggers
  document.getElementById('btn-open-mktg').addEventListener('click', openMktgModal);

  // Customizer Option Chips Setup
  setupCustomizerChips();
}

// ==========================================
// 4. MENU RENDERING
// ==========================================
function renderMenuItems(category) {
  const menuGrid = document.getElementById('menu-grid');
  menuGrid.innerHTML = '';

  const filteredItems = category === 'all' 
    ? MENU_ITEMS 
    : MENU_ITEMS.filter(item => item.category === category);

  filteredItems.forEach(item => {
    const card = document.createElement('div');
    card.className = 'menu-card';
    card.innerHTML = `
      <div class="menu-card-img-wrap">
        <img src="${item.image}" alt="${item.name}" loading="lazy">
        ${item.badge ? `<span class="menu-card-badge ${item.badgeType}">${item.badge}</span>` : ''}
      </div>
      <div class="menu-card-body">
        <div>
          <h3 class="menu-card-title">${item.name}</h3>
          <p style="font-size:0.8rem; color:var(--accent-caramel); font-weight:600; margin-bottom:4px;">${item.nameTh}</p>
          <p class="menu-card-desc">${item.description}</p>
        </div>
        <div class="menu-card-footer">
          <span class="menu-card-price">${item.price} ฿</span>
          <button class="btn-add-cart" onclick="openCustomizer('${item.id}')" title="ปรับแต่งและสั่งซื้อ">
            <i class="fa-solid fa-plus"></i>
          </button>
        </div>
      </div>
    `;
    menuGrid.appendChild(card);
  });
}

// ==========================================
// 5. ITEM CUSTOMIZER & ADD TO CART
// ==========================================
function setupCustomizerChips() {
  // Sweetness
  const sweetnessChips = document.querySelectorAll('#sweetness-options .option-chip');
  sweetnessChips.forEach(chip => {
    chip.addEventListener('click', () => {
      sweetnessChips.forEach(c => c.classList.remove('active'));
      chip.classList.add('active');
      selectedSweetness = chip.getAttribute('data-val');
    });
  });

  // Milk
  const milkChips = document.querySelectorAll('#milk-options .option-chip');
  milkChips.forEach(chip => {
    chip.addEventListener('click', () => {
      milkChips.forEach(c => c.classList.remove('active'));
      chip.classList.add('active');
      selectedMilk = chip.getAttribute('data-val');
      selectedMilkExtraPrice = parseInt(chip.getAttribute('data-price') || 0);
    });
  });

  // Add-ons
  const extraChips = document.querySelectorAll('#extra-options .option-chip');
  extraChips.forEach(chip => {
    chip.addEventListener('click', () => {
      chip.classList.toggle('active');
      calculateAddons();
    });
  });
}

function calculateAddons() {
  const activeExtras = document.querySelectorAll('#extra-options .option-chip.active');
  selectedAddons = [];
  selectedAddonPrice = 0;
  activeExtras.forEach(chip => {
    selectedAddons.push(chip.getAttribute('data-val'));
    selectedAddonPrice += parseInt(chip.getAttribute('data-price') || 0);
  });
}

function openCustomizer(itemId) {
  const item = MENU_ITEMS.find(i => i.id === itemId);
  if (!item) return;

  currentCustomizingItem = item;
  document.getElementById('customizer-modal-name').textContent = item.name;
  document.getElementById('customizer-modal-price').textContent = `${item.price} ฿`;
  document.getElementById('customizer-item-img').src = item.image;

  // Reset choices
  selectedSweetness = '50%';
  selectedMilk = 'Fresh Milk';
  selectedMilkExtraPrice = 0;
  selectedAddons = [];
  selectedAddonPrice = 0;

  // Reset active classes
  document.querySelectorAll('#sweetness-options .option-chip').forEach((c, idx) => {
    c.classList.toggle('active', idx === 2);
  });
  document.querySelectorAll('#milk-options .option-chip').forEach((c, idx) => {
    c.classList.toggle('active', idx === 0);
  });
  document.querySelectorAll('#extra-options .option-chip').forEach(c => c.classList.remove('active'));

  document.getElementById('customizer-modal').classList.add('active');
}

function closeCustomizerModal() {
  document.getElementById('customizer-modal').classList.remove('active');
}

function confirmAddToCart() {
  if (!currentCustomizingItem) return;

  const finalUnitPrice = currentCustomizingItem.price + selectedMilkExtraPrice + selectedAddonPrice;
  const optionsText = `${selectedSweetness} Sweet • ${selectedMilk}${selectedAddons.length > 0 ? ' • ' + selectedAddons.join(', ') : ''}`;

  // Check if identical item with identical options exists
  const existingIdx = cart.findIndex(ci => ci.id === currentCustomizingItem.id && ci.options === optionsText);
  if (existingIdx > -1) {
    cart[existingIdx].qty += 1;
  } else {
    cart.push({
      id: currentCustomizingItem.id,
      name: currentCustomizingItem.name,
      price: finalUnitPrice,
      image: currentCustomizingItem.image,
      options: optionsText,
      qty: 1
    });
  }

  updateCartUI();
  closeCustomizerModal();
  showToast(`เพิ่ม "${currentCustomizingItem.name}" ลงในตะกร้าแล้ว ☕`);
}

function directAddToCart(itemId) {
  const item = MENU_ITEMS.find(i => i.id === itemId);
  if (!item) return;

  const existingIdx = cart.findIndex(ci => ci.id === item.id && ci.options === 'Standard');
  if (existingIdx > -1) {
    cart[existingIdx].qty += 1;
  } else {
    cart.push({
      id: item.id,
      name: item.name,
      price: item.price,
      image: item.image,
      options: 'Standard',
      qty: 1
    });
  }
  updateCartUI();
  showToast(`เพิ่ม "${item.name}" ลงในตะกร้าแล้ว ✨`);
}

// ==========================================
// 6. CART MANAGEMENT & UI
// ==========================================
function updateCartUI() {
  const totalCount = cart.reduce((sum, item) => sum + item.qty, 0);
  document.getElementById('cart-count-badge').textContent = totalCount;

  const itemsContainer = document.getElementById('cart-items-container');
  if (!itemsContainer) return;

  if (cart.length === 0) {
    itemsContainer.innerHTML = `
      <div style="text-align:center; padding: 35px 10px; color: var(--text-muted);">
        <i class="fa-solid fa-mug-saucer" style="font-size: 2.5rem; margin-bottom: 12px; opacity: 0.5;"></i>
        <p>ยังไม่มีสินค้าในตะกร้า</p>
        <p style="font-size:0.8rem;">เลือกเมนูโปรดของคุณเพื่อเริ่มต้นความอร่อยได้เลยค่ะ</p>
      </div>
    `;
  } else {
    itemsContainer.innerHTML = '';
    cart.forEach((item, index) => {
      const row = document.createElement('div');
      row.className = 'cart-item-row';
      row.innerHTML = `
        <div style="display:flex; gap:12px; align-items:center;">
          <img src="${item.image}" alt="${item.name}" style="width:48px; height:48px; border-radius:8px; object-fit:cover;">
          <div class="cart-item-info">
            <h4>${item.name}</h4>
            <p>${item.options}</p>
            <span style="font-weight:700; color:var(--accent-caramel); font-size:0.88rem;">${item.price} ฿ / แก้ว</span>
          </div>
        </div>
        <div class="cart-item-controls">
          <button class="qty-btn" onclick="adjustCartQty(${index}, -1)">-</button>
          <span style="font-weight:700; font-size:0.95rem; min-width:18px; text-align:center;">${item.qty}</span>
          <button class="qty-btn" onclick="adjustCartQty(${index}, 1)">+</button>
        </div>
      `;
      itemsContainer.appendChild(row);
    });
  }

  // Calculate totals
  const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
  let discountAmount = 0;
  
  if (appliedDiscountPercent > 0) {
    discountAmount = Math.round(subtotal * (appliedDiscountPercent / 100));
  } else if (appliedDiscountAmount > 0) {
    discountAmount = Math.min(appliedDiscountAmount, subtotal);
  }

  const finalTotal = Math.max(0, subtotal - discountAmount);

  document.getElementById('cart-subtotal').textContent = `${subtotal} ฿`;
  document.getElementById('cart-discount').textContent = `-${discountAmount} ฿`;
  document.getElementById('cart-total').textContent = `${finalTotal} ฿`;
}

function adjustCartQty(index, delta) {
  if (!cart[index]) return;
  cart[index].qty += delta;
  if (cart[index].qty <= 0) {
    cart.splice(index, 1);
  }
  updateCartUI();
}

function openCartModal() {
  updateCartUI();
  document.getElementById('cart-modal').classList.add('active');
}

function closeCartModal() {
  document.getElementById('cart-modal').classList.remove('active');
}

// Promo Code Verification
function applyCartPromo() {
  const input = document.getElementById('cart-promo-input');
  const code = input.value.trim().toUpperCase();
  const msgEl = document.getElementById('promo-applied-message');

  if (!code) {
    msgEl.textContent = 'กรุณาระบุโค้ดส่วนลด';
    msgEl.style.color = '#d62828';
    return;
  }

  if (code === 'DAILYVIP20' || code === 'MORNING20') {
    appliedDiscountPercent = 20;
    appliedDiscountAmount = 0;
    appliedCouponCode = code;
    msgEl.textContent = `✓ ใช้โค้ด ${code} สำเร็จ รับส่วนลด 20%`;
    msgEl.style.color = 'var(--accent-green)';
    showToast(`ใช้โค้ด ${code} รับส่วนลด 20% เรียบร้อยแล้วค่ะ! 🎉`);
  } else if (code === 'BREWPASS') {
    appliedDiscountPercent = 25;
    appliedDiscountAmount = 0;
    appliedCouponCode = code;
    msgEl.textContent = `✓ ใช้โค้ด ${code} บัตรสมาชิก รับส่วนลด 25%`;
    msgEl.style.color = 'var(--accent-green)';
    showToast(`เปิดใช้สิทธิ์ Daily Pass ส่วนลด 25% สำเร็จ! 🌟`);
  } else if (code === 'COMBO179') {
    appliedDiscountPercent = 0;
    appliedDiscountAmount = 40;
    appliedCouponCode = code;
    msgEl.textContent = `✓ ใช้โค้ด ${code} รับส่วนลดเซ็ตสุดคุ้ม 40 บาท`;
    msgEl.style.color = 'var(--accent-green)';
    showToast(`ใช้โค้ดคอมโบเซ็ตเรียบร้อยแล้วค่ะ! 🥐`);
  } else {
    msgEl.textContent = '❌ โค้ดส่วนลดไม่ถูกต้องหรือหมดอายุแล้ว';
    msgEl.style.color = '#d62828';
    return;
  }

  updateCartUI();
}

function proceedToCheckout() {
  if (cart.length === 0) {
    showToast('กรุณาเลือกเครื่องดื่มลงในตะกร้าก่อนสั่งซื้อนะคะ ☕');
    return;
  }

  const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
  let discountAmount = 0;
  if (appliedDiscountPercent > 0) {
    discountAmount = Math.round(subtotal * (appliedDiscountPercent / 100));
  } else if (appliedDiscountAmount > 0) {
    discountAmount = Math.min(appliedDiscountAmount, subtotal);
  }
  const finalTotal = Math.max(0, subtotal - discountAmount);
  const pointsEarned = Math.floor(finalTotal / 10);
  const orderId = `DB-ORD-${Math.floor(1000 + Math.random() * 9000)}`;
  const nowStr = new Date().toLocaleString('th-TH', { dateStyle: 'medium', timeStyle: 'short' });

  // Record into Order History
  const orderRecord = {
    id: orderId,
    date: nowStr,
    items: JSON.parse(JSON.stringify(cart)),
    subtotal: subtotal,
    discount: discountAmount,
    coupon: appliedCouponCode || 'None',
    total: finalTotal,
    pointsEarned: pointsEarned,
    status: 'จัดส่งสำเร็จ'
  };

  try {
    const existingOrders = JSON.parse(localStorage.getItem('dailybrew_order_history') || '[]');
    existingOrders.unshift(orderRecord);
    localStorage.setItem('dailybrew_order_history', JSON.stringify(existingOrders));

    // Update VIP Member points & lifetime spend
    const savedMember = localStorage.getItem('dailybrew_vip_member');
    if (savedMember) {
      const member = JSON.parse(savedMember);
      member.points = (member.points || 100) + pointsEarned;
      member.totalSpent = (member.totalSpent || 0) + finalTotal;
      localStorage.setItem('dailybrew_vip_member', JSON.stringify(member));
      loadSavedVipMember();
    }
  } catch (err) {
    console.error('Error recording order history', err);
  }

  closeCartModal();

  // Update Checkout Modal Content with Real Order Data
  const checkoutBody = document.querySelector('#checkout-modal .modal-body');
  if (checkoutBody) {
    let itemsHtml = orderRecord.items.map(it => `
      <div style="display:flex; justify-content:space-between; margin-bottom:4px; font-size:0.85rem;">
        <span>${it.name} x${it.qty}</span>
        <span>${it.price * it.qty} ฿</span>
      </div>
    `).join('');

    checkoutBody.innerHTML = `
      <div style="font-size: 3.5rem; color: var(--accent-green); margin-bottom: 10px;">
        <i class="fa-solid fa-mug-hot"></i>
      </div>
      <h4 style="font-size: 1.3rem; color: var(--primary-espresso); margin-bottom: 4px;">สั่งซื้อสำเร็จแล้วค่ะ! (Order Placed)</h4>
      <p style="color: var(--text-muted); font-size: 0.88rem; margin-bottom: 16px;">
        ออเดอร์หมายเลข <strong style="color:var(--accent-caramel);">${orderId}</strong> บันทึกลงระบบ CDP แล้ว
      </p>

      <div style="background: var(--bg-cream-soft); padding: 16px 18px; border-radius: 14px; text-align: left; margin-bottom: 18px; font-size: 0.88rem; border: 1px solid rgba(80,49,35,0.08);">
        <div style="padding-bottom:10px; margin-bottom:10px; border-bottom:1px dashed rgba(80,49,35,0.15);">
          ${itemsHtml}
        </div>
        <div style="display:flex; justify-content:space-between; margin-bottom: 4px;">
          <span>ยอดรวมสุทธิ:</span>
          <strong style="color:var(--accent-caramel); font-size:1.05rem;">${finalTotal} ฿</strong>
        </div>
        <div style="display:flex; justify-content:space-between; margin-bottom: 4px;">
          <span>แต้มที่ได้รับ (10฿ = 1pt):</span>
          <span style="color:var(--accent-green); font-weight:700;">+${pointsEarned} Points ⭐</span>
        </div>
        <div style="display:flex; justify-content:space-between;">
          <span>สถานะจัดส่ง:</span>
          <span style="color: #00b894; font-weight:700;">🛵 กำลังเตรียมจัดส่งด่วน</span>
        </div>
      </div>

      <div style="display:flex; gap:10px;">
        <button class="btn btn-secondary" style="flex:1;" onclick="closeCheckoutModal(); openMemberPortalModal();">
          <i class="fa-solid fa-clock-rotate-left"></i> ดูประวัติสั่งซื้อ
        </button>
        <button class="btn btn-primary" style="flex:1;" onclick="closeCheckoutModal()">
          สั่งเมนูอื่นเพิ่ม
        </button>
      </div>
    `;
  }

  document.getElementById('checkout-modal').classList.add('active');

  // Reset Cart after purchase
  cart = [];
  appliedDiscountPercent = 0;
  appliedDiscountAmount = 0;
  appliedCouponCode = '';
  updateCartUI();
  showToast(`ชำระเงินสำเร็จ! รับแต้มสะสม +${pointsEarned} Points 🌟`);
}

function closeCheckoutModal() {
  document.getElementById('checkout-modal').classList.remove('active');
}

// ==========================================
// 7. MARKETING CAMPAIGN HELPERS
// ==========================================
function copyCode(code) {
  navigator.clipboard.writeText(code).then(() => {
    showToast(`คัดลอกโค้ด "${code}" สำเร็จแล้ว!`);
  }).catch(() => {
    showToast(`โค้ดของคุณคือ: ${code}`);
  });
}

function applyPromoAndScroll(code) {
  copyCode(code);
  const input = document.getElementById('cart-promo-input');
  if (input) input.value = code;
  
  if (code === 'MORNING20' || code === 'DAILYVIP20') {
    appliedDiscountPercent = 20;
  } else if (code === 'BREWPASS') {
    appliedDiscountPercent = 25;
  } else if (code === 'COMBO179') {
    appliedDiscountAmount = 40;
  }
  
  // Smooth scroll to menu
  const menuEl = document.getElementById('menu');
  if (menuEl) {
    menuEl.scrollIntoView({ behavior: 'smooth' });
  }
  showToast(`นำโค้ด ${code} ไปใช้ตอนสั่งซื้อได้เลยค่ะ!`);
}

function handleLeadCapture(e) {
  e.preventDefault();
  const emailInput = document.getElementById('lead-email');
  const val = emailInput.value.trim();
  if (!val) return;

  localStorage.setItem('dailybrew_vip_user', val);
  localStorage.setItem('dailybrew_active_voucher', 'DAILYVIP20');

  emailInput.value = '';
  showToast(`🎉 ยินดีต้อนรับสู่ VIP Club! รับโค้ด DAILYVIP20 ลด 20% ทันที`);
  
  // Auto fill in cart
  const cartInput = document.getElementById('cart-promo-input');
  if (cartInput) cartInput.value = 'DAILYVIP20';
  appliedDiscountPercent = 20;
  updateCartUI();
}

function loadSavedVoucher() {
  const savedVoucher = localStorage.getItem('dailybrew_active_voucher');
  if (savedVoucher) {
    const input = document.getElementById('cart-promo-input');
    if (input) input.value = savedVoucher;
  }
}

// ==========================================
// 8. INTERACTIVE COFFEE FINDER QUIZ
// ==========================================
function selectQuizOption(step, choiceKey) {
  if (step === 1) {
    quizAnswers.step1 = choiceKey;
    document.getElementById('quiz-step-1').classList.remove('active');
    document.getElementById('quiz-step-2').classList.add('active');
    document.getElementById('quiz-step-title').innerHTML = '<i class="fa-solid fa-compass"></i> Step 2 of 3';
    document.getElementById('dot-2').classList.add('active');
  } else if (step === 2) {
    quizAnswers.step2 = choiceKey;
    document.getElementById('quiz-step-2').classList.remove('active');
    document.getElementById('quiz-step-3').classList.add('active');
    document.getElementById('quiz-step-title').innerHTML = '<i class="fa-solid fa-compass"></i> Step 3 of 3';
    document.getElementById('dot-3').classList.add('active');
  } else if (step === 3) {
    quizAnswers.step3 = choiceKey;
    calculateQuizResult();
  }
}

function prevQuizStep(targetStep) {
  document.querySelectorAll('.quiz-step').forEach(s => s.classList.remove('active'));
  if (targetStep === 1) {
    document.getElementById('quiz-step-1').classList.add('active');
    document.getElementById('quiz-step-title').innerHTML = '<i class="fa-solid fa-compass"></i> Step 1 of 3';
    document.getElementById('dot-2').classList.remove('active');
    document.getElementById('dot-3').classList.remove('active');
  } else if (targetStep === 2) {
    document.getElementById('quiz-step-2').classList.add('active');
    document.getElementById('quiz-step-title').innerHTML = '<i class="fa-solid fa-compass"></i> Step 2 of 3';
    document.getElementById('dot-3').classList.remove('active');
  }
}

function calculateQuizResult() {
  document.querySelectorAll('.quiz-step').forEach(s => s.classList.remove('active'));
  document.getElementById('quiz-step-result').classList.add('active');
  document.getElementById('quiz-step-title').innerHTML = '<i class="fa-solid fa-sparkles"></i> Your Perfect Daily Brew Match';

  let recommendedItemId = 'dirty-coffee';
  let pairingItemId = 'almond-croissant';

  // Smart Matching Decision Tree
  if (quizAnswers.step2 === 'matcha') {
    if (quizAnswers.step1 === 'strong' || quizAnswers.step3 === 'creamy') {
      recommendedItemId = 'dirty-matcha';
      pairingItemId = 'matcha-crepe-cake';
    } else {
      recommendedItemId = 'uji-matcha-cloud';
      pairingItemId = 'matcha-crepe-cake';
    }
  } else if (quizAnswers.step1 === 'refresh' || quizAnswers.step2 === 'fruity') {
    if (quizAnswers.step3 === 'black') {
      recommendedItemId = 'yuzu-sparkling';
      pairingItemId = 'pain-au-chocolat';
    } else if (quizAnswers.step3 === 'fresh_milk' || quizAnswers.step3 === 'oat_milk') {
      recommendedItemId = 'coconut-espresso';
      pairingItemId = 'strawberry-shortcake';
    } else {
      recommendedItemId = 'peach-cold-tea';
      pairingItemId = 'basque-cheesecake';
    }
  } else if (quizAnswers.step1 === 'sweet' || quizAnswers.step2 === 'caramel') {
    if (quizAnswers.step2 === 'espresso') {
      recommendedItemId = 'valrhona-chocolate';
      pairingItemId = 'truffle-brioche';
    } else {
      recommendedItemId = 'caramel-macchiato';
      pairingItemId = 'cinnamon-roll';
    }
  } else if (quizAnswers.step3 === 'oat_milk') {
    recommendedItemId = 'vanilla-oat-latte';
    pairingItemId = 'almond-croissant';
  } else if (quizAnswers.step1 === 'smooth') {
    if (quizAnswers.step3 === 'fresh_milk' && quizAnswers.step2 === 'fruity') {
      recommendedItemId = 'earl-grey-lavender';
      pairingItemId = 'strawberry-shortcake';
    } else {
      recommendedItemId = 'flat-white';
      pairingItemId = 'tiramisu';
    }
  } else if (quizAnswers.step1 === 'strong') {
    if (quizAnswers.step3 === 'black') {
      recommendedItemId = 'orange-coldbrew';
      pairingItemId = 'pain-au-chocolat';
    } else {
      recommendedItemId = 'dirty-coffee';
      pairingItemId = 'almond-croissant';
    }
  } else {
    recommendedItemId = 'coconut-espresso';
    pairingItemId = 'basque-cheesecake';
  }

  const recItem = MENU_ITEMS.find(i => i.id === recommendedItemId) || MENU_ITEMS[0];
  const pairItem = MENU_ITEMS.find(i => i.id === pairingItemId) || MENU_ITEMS[5];
  const container = document.getElementById('quiz-result-card-content');
  
  container.innerHTML = `
    <img src="${recItem.image}" alt="${recItem.name}" class="quiz-result-img">
    <div class="quiz-result-details">
      <span class="badge-tag" style="margin-bottom:6px; font-size:0.75rem;"><i class="fa-solid fa-star"></i> แนะนำตาม Mood ของคุณ</span>
      <h3 style="margin: 0 0 6px 0; font-size: 1.4rem;">${recItem.name}</h3>
      <div class="quiz-result-price">${recItem.price} ฿</div>
      <p class="quiz-result-desc">${recItem.description}</p>
      
      <!-- Food Pairing Suggestion inside Quiz Result -->
      <div style="background: rgba(200, 137, 55, 0.08); border: 1px dashed rgba(200, 137, 55, 0.35); border-radius: 12px; padding: 10px 14px; margin-bottom: 16px; display: flex; align-items: center; justify-content: space-between; gap: 10px;">
        <div style="display:flex; align-items:center; gap: 10px;">
          <img src="${pairItem.image}" alt="${pairItem.name}" style="width: 42px; height: 42px; border-radius: 8px; object-fit: cover;">
          <div style="text-align: left;">
            <div style="font-size: 0.76rem; color: var(--accent-caramel); font-weight: 700;">🥐 แนะนำสั่งคู่กัน (Pairing Match):</div>
            <div style="font-size: 0.88rem; font-weight: 600; color: var(--primary-espresso);">${pairItem.name} (${pairItem.price} ฿)</div>
          </div>
        </div>
        <button class="btn btn-secondary btn-sm" style="font-size: 0.78rem; padding: 5px 10px; white-space: nowrap;" onclick="directAddToCart('${pairItem.id}')">
          + สั่งขนมคู่กัน
        </button>
      </div>

      <div style="display:flex; gap:10px; flex-wrap: wrap;">
        <button class="btn btn-primary" onclick="directAddToCart('${recItem.id}')">
          <i class="fa-solid fa-cart-shopping"></i> สั่งแก้วนี้ทันที
        </button>
        <button class="btn btn-secondary" onclick="openCustomizer('${recItem.id}')">
          <i class="fa-solid fa-sliders"></i> ปรับแต่งเมนู
        </button>
      </div>
    </div>
  `;
}

function restartQuiz() {
  quizAnswers = { step1: '', step2: '', step3: '' };
  document.querySelectorAll('.quiz-step').forEach(s => s.classList.remove('active'));
  document.getElementById('quiz-step-1').classList.add('active');
  document.getElementById('quiz-step-title').innerHTML = '<i class="fa-solid fa-compass"></i> Step 1 of 3';
  document.getElementById('dot-1').classList.add('active');
  document.getElementById('dot-2').classList.remove('active');
  document.getElementById('dot-3').classList.remove('active');
}

// ==========================================
// 9. MARKETING STRATEGY MODAL FOR PRESENTATION
// ==========================================
function openMktgModal() {
  document.getElementById('mktg-modal').classList.add('active');
}

function closeMktgModal() {
  document.getElementById('mktg-modal').classList.remove('active');
}

function switchMktgTab(e, tabId) {
  document.querySelectorAll('.mktg-tab-btn').forEach(btn => btn.classList.remove('active'));
  document.querySelectorAll('.mktg-tab-pane').forEach(pane => pane.classList.remove('active'));

  e.target.classList.add('active');
  const targetPane = document.getElementById(tabId);
  if (targetPane) targetPane.classList.add('active');
}

// ==========================================
// 10. TOAST NOTIFICATIONS
// ==========================================
function showToast(message) {
  const container = document.getElementById('toast-container');
  if (!container) return;

  const toast = document.createElement('div');
  toast.className = 'toast';
  toast.innerHTML = `
    <i class="fa-solid fa-circle-check" style="color: var(--accent-gold);"></i>
    <span>${message}</span>
  `;
  container.appendChild(toast);

  setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transform = 'translateX(100%)';
    toast.style.transition = 'all 0.3s ease';
    setTimeout(() => toast.remove(), 300);
  }, 3200);
}

// ==========================================
// 11. VIP MEMBERSHIP REGISTRATION (CRM & LOYALTY)
// ==========================================
function updateLiveCard() {
  const nameInput = document.getElementById('member-fullname');
  const nameDisplay = document.getElementById('card-display-name');
  if (nameInput && nameDisplay) {
    nameDisplay.textContent = nameInput.value.trim() ? nameInput.value.trim() : 'คุณ สมาชิกคนพิเศษ';
  }
}

function handleVipRegistration(event) {
  event.preventDefault();
  
  const fullname = document.getElementById('member-fullname').value.trim();
  const phone = document.getElementById('member-phone').value.trim();
  const email = document.getElementById('member-email').value.trim();
  const birthday = document.getElementById('member-birthday').value;
  const favoriteCoffee = document.getElementById('member-favorite-coffee').value;
  const milk = document.getElementById('member-milk').value;
  const address = document.getElementById('member-address').value.trim();

  if (!fullname || !phone || !email) {
    showToast('กรุณากรอกข้อมูลที่จำเป็นให้ครบถ้วนค่ะ');
    return;
  }

  // Generate unique VIP Member ID
  const randomIdNum = Math.floor(1000 + Math.random() * 9000);
  const memberId = `DB-2026-${randomIdNum}`;

  const memberData = {
    fullname,
    phone,
    email,
    birthday,
    favoriteCoffee,
    milk,
    address,
    memberId,
    points: 100,
    tier: 'VIP Gold Member',
    joinedDate: new Date().toLocaleDateString('th-TH')
  };

  // Save to localStorage
  localStorage.setItem('dailybrew_vip_member', JSON.stringify(memberData));

  // Update card elements
  const cardName = document.getElementById('card-display-name');
  const cardId = document.getElementById('card-display-id');
  const cardPoints = document.getElementById('card-display-points');
  const cardStatus = document.getElementById('card-display-status');

  if (cardName) cardName.textContent = fullname;
  if (cardId) cardId.textContent = memberId;
  if (cardPoints) cardPoints.textContent = '100 PTS + 20% OFF';
  if (cardStatus) cardStatus.textContent = 'ACTIVE (GOLD)';

  // Auto apply welcome voucher code
  applyPromoAndScroll('DAILYVIP20');

  // Replace form with congratulations confirmation
  const formCard = document.getElementById('membership-form-card');
  if (formCard) {
    formCard.innerHTML = `
      <div class="vip-registered-success">
        <div style="font-size: 3rem; margin-bottom: 10px;">🎉</div>
        <h4>ยินดีต้อนรับสู่ Daily Brew VIP Club!</h4>
        <p>คุณ <strong>${fullname}</strong> ได้รับสถานะ <strong>VIP Gold Member</strong> เรียบร้อยแล้วค่ะ</p>
        <div style="background: rgba(255,255,255,0.85); padding: 14px; border-radius: 12px; margin: 15px 0; text-align:left; font-size:0.88rem;">
          <p style="margin:4px 0;"><strong>💳 รหัสสมาชิก:</strong> <code style="color:var(--accent-caramel); font-size:1rem;">${memberId}</code></p>
          <p style="margin:4px 0;"><strong>⭐ แต้มต้อนรับ:</strong> 100 Points</p>
          <p style="margin:4px 0;"><strong>🎁 ส่วนลดต้อนรับ:</strong> ส่วนลด 20% (โค้ด <code>DAILYVIP20</code> ใส่ในตะกร้าให้อัตโนมัติแล้ว)</p>
          <p style="margin:4px 0;"><strong>🎂 สิทธิพิเศษวันเกิด:</strong> ฟรีเครื่องดื่ม + เบเกอรีในวันเกิด</p>
        </div>
        <div style="display:flex; gap:10px; justify-content:center; flex-wrap:wrap; margin-top:15px;">
          <button class="btn btn-primary" onclick="openCartModal()">
            <i class="fa-solid fa-bag-shopping"></i> ไปที่ตะกร้าเพื่อใช้ส่วนลด 20%
          </button>
          <button class="btn btn-secondary" onclick="resetVipForm()">
            <i class="fa-solid fa-pen-to-square"></i> แก้ไขข้อมูลสมาชิก
          </button>
        </div>
      </div>
    `;
  }

  showToast(`ยินดีต้อนรับคุณ ${fullname}! รับส่วนลด 20% เรียบร้อยแล้ว 👑`);
}

function loadSavedVipMember() {
  try {
    const saved = localStorage.getItem('dailybrew_vip_member');
    if (!saved) return;
    const member = JSON.parse(saved);
    if (!member || !member.fullname) return;

    const cardName = document.getElementById('card-display-name');
    const cardId = document.getElementById('card-display-id');
    const cardPoints = document.getElementById('card-display-points');

    if (cardName) cardName.textContent = member.fullname;
    if (cardId && member.memberId) cardId.textContent = member.memberId;
    if (cardPoints) cardPoints.textContent = '100 PTS + 20% OFF';

    // Update form if present
    const nameInput = document.getElementById('member-fullname');
    const phoneInput = document.getElementById('member-phone');
    const emailInput = document.getElementById('member-email');
    const addressInput = document.getElementById('member-address');
    if (nameInput) nameInput.value = member.fullname || '';
    if (phoneInput) phoneInput.value = member.phone || '';
    if (emailInput) emailInput.value = member.email || '';
    if (addressInput) addressInput.value = member.address || '';
  } catch (e) {
    console.error('Error loading VIP member', e);
  }
}

function resetVipForm() {
  localStorage.removeItem('dailybrew_vip_member');
  location.reload();
}

// ==========================================
// 12. SUBSCRIPTION PACKAGES & FOOD PAIRING
// ==========================================
function buyPackage(pkgId, pkgName, pkgPrice) {
  cart.push({
    id: pkgId,
    name: `⭐ ${pkgName}`,
    price: pkgPrice,
    image: 'assets/promo_morning.jpg',
    options: 'Monthly Subscription Pass',
    qty: 1
  });
  updateCartUI();
  openCartModal();
  showToast(`เพิ่มแพ็กเกจ "${pkgName}" ลงในตะกร้าแล้วค่ะ 🎉`);
}

function addPairingItem(itemId, itemName, itemPrice, itemImage) {
  const existingIdx = cart.findIndex(ci => ci.id === itemId && ci.options === 'Food Pairing Set');
  if (existingIdx > -1) {
    cart[existingIdx].qty += 1;
  } else {
    cart.push({
      id: itemId,
      name: itemName,
      price: itemPrice,
      image: itemImage,
      options: 'Food Pairing Set',
      qty: 1
    });
  }
  updateCartUI();
  showToast(`เพิ่มเมนูคู่กัน "${itemName}" ในราคาพิเศษ ${itemPrice}฿ แล้ว 🥐`);
}

// ==========================================
// 13. SMART AI RECOMMENDATION ENGINE (Time & Mood)
// ==========================================
function initSmartRecommendation() {
  const hour = new Date().getHours();
  const titleEl = document.getElementById('smart-rec-title');
  const descEl = document.getElementById('smart-rec-desc');
  const tagEl = document.getElementById('smart-rec-tag');
  const btnEl = document.getElementById('btn-smart-rec-order');
  const drinkImg = document.getElementById('smart-rec-img-drink');
  const bakeryImg = document.getElementById('smart-rec-img-bakery');

  if (!titleEl || !descEl) return;

  if (hour >= 6 && hour < 12) {
    tagEl.textContent = '☀️ บาริสต้าแนะนำรับเช้าวันใหม่ (Morning Booster)';
    titleEl.textContent = 'Coconut Cloud Espresso & Pain au Chocolat';
    descEl.textContent = 'เช้าวันนี้สดชื่นด้วยเอสเปรสโซสกัดร้อนผสมผสานน้ำมะพร้าวน้ำหอมสด ทานคู่กับแปงโอช็อกโกลาอบกรอบเนยสดฝรั่งเศส';
    if (drinkImg) drinkImg.src = 'assets/coconut_espresso.jpg';
    if (bakeryImg) bakeryImg.src = 'assets/pain_au_chocolat.jpg';
    if (btnEl) btnEl.setAttribute('onclick', "addPairingSet('coconut-espresso', 'pain-au-chocolat')");
  } else if (hour >= 12 && hour < 17) {
    tagEl.textContent = '🍊 เมนูสดชื่นยามบ่าย (Afternoon Refresh)';
    titleEl.textContent = 'Artisan Orange Cold Brew & Truffle Cheese Brioche';
    descEl.textContent = 'ช่วงบ่ายคลายร้อนด้วยโคลด์บรูว์ส้มวาเลนเซียสดชื่นตาสว่าง ผสานความหอมมันของบริออชโทสต์ทรัฟเฟิลชีสเยิ้ม';
    if (drinkImg) drinkImg.src = 'assets/cold_brew.jpg';
    if (bakeryImg) bakeryImg.src = 'assets/truffle_brioche.jpg';
    if (btnEl) btnEl.setAttribute('onclick', "addPairingSet('orange-coldbrew', 'truffle-brioche')");
  } else {
    tagEl.textContent = '🌙 ผ่อนคลายยามเย็น (Evening Chill)';
    titleEl.textContent = 'Military Dirty Matcha & Hokkaido Strawberry Shortcake';
    descEl.textContent = 'ยามเย็นชิลล์ๆ กับมัทฉะเกียวโต 3 เลเยอร์นุ่มละมุน ดื่มคู่กับสตรอว์เบอร์รีชอร์ตเค้กครีมสดฮอกไกโดหวานกำลังดี';
    if (drinkImg) drinkImg.src = 'assets/dirty_matcha.jpg';
    if (bakeryImg) bakeryImg.src = 'assets/strawberry_shortcake.jpg';
    if (btnEl) btnEl.setAttribute('onclick', "addPairingSet('dirty-matcha', 'strawberry-shortcake')");
  }
}

function addPairingSet(drinkId, foodId) {
  const drinkItem = MENU_ITEMS.find(i => i.id === drinkId);
  const foodItem = MENU_ITEMS.find(i => i.id === foodId);
  if (!drinkItem || !foodItem) return;

  // Add drink
  const dIdx = cart.findIndex(c => c.id === drinkItem.id && c.options === 'Standard');
  if (dIdx > -1) {
    cart[dIdx].qty += 1;
  } else {
    cart.push({
      id: drinkItem.id,
      name: drinkItem.name,
      price: drinkItem.price,
      image: drinkItem.image,
      options: 'Standard',
      qty: 1
    });
  }

  // Add bakery
  const fIdx = cart.findIndex(c => c.id === foodItem.id && c.options === 'Standard');
  if (fIdx > -1) {
    cart[fIdx].qty += 1;
  } else {
    cart.push({
      id: foodItem.id,
      name: foodItem.name,
      price: foodItem.price,
      image: foodItem.image,
      options: 'Standard',
      qty: 1
    });
  }

  updateCartUI();
  openCartModal();
  showToast(`เพิ่มเซ็ต "${drinkItem.name} + ${foodItem.name}" ลงในตะกร้าแล้ว ✨`);
}

// ==========================================
// 14. MEMBER PORTAL & CDP DASHBOARD CONTROLS
// ==========================================
function openMemberPortalModal() {
  renderCdpProfile();
  renderOrderHistory();
  document.getElementById('member-portal-modal').classList.add('active');
}

function closeMemberPortalModal() {
  document.getElementById('member-portal-modal').classList.remove('active');
}

function switchPortalTab(e, tabId) {
  document.querySelectorAll('#member-portal-modal .mktg-tab-btn').forEach(btn => btn.classList.remove('active'));
  document.querySelectorAll('#member-portal-modal .mktg-tab-pane').forEach(pane => pane.classList.remove('active'));

  e.target.classList.add('active');
  const targetPane = document.getElementById(tabId);
  if (targetPane) targetPane.classList.add('active');
}

function renderCdpProfile() {
  const saved = localStorage.getItem('dailybrew_vip_member');
  const fieldsContainer = document.getElementById('cdp-fields-container');
  
  if (!saved) {
    // Default Guest / Demo Profile
    document.getElementById('portal-stat-tier').textContent = 'Guest Member';
    document.getElementById('portal-stat-points').textContent = '0 Points';
    document.getElementById('portal-stat-spent').textContent = '0 ฿';
    
    if (fieldsContainer) {
      fieldsContainer.innerHTML = `
        <div style="grid-column: 1 / -1; text-align:center; padding:20px; color:var(--text-muted);">
          <p style="margin-bottom:12px;">ยังไม่ได้ลงทะเบียนสมาชิก VIP</p>
          <a href="#membership" class="btn btn-primary" onclick="closeMemberPortalModal()">
            <i class="fa-solid fa-crown"></i> ไปหน้าสมัครสมาชิกรับส่วนลด 20%
          </a>
        </div>
      `;
    }
    return;
  }

  const member = JSON.parse(saved);
  const orders = JSON.parse(localStorage.getItem('dailybrew_order_history') || '[]');
  const totalSpent = orders.reduce((sum, o) => sum + (o.total || 0), 0);

  document.getElementById('portal-stat-tier').textContent = member.tier || 'VIP Gold Member';
  document.getElementById('portal-stat-points').textContent = `${member.points || 100} Points`;
  document.getElementById('portal-stat-spent').textContent = `${totalSpent} ฿`;

  if (fieldsContainer) {
    fieldsContainer.innerHTML = `
      <div class="cdp-field-item">
        <span class="cdp-field-label"><i class="fa-solid fa-id-badge"></i> รหัสสมาชิก (Member ID)</span>
        <span class="cdp-field-val" style="color:var(--accent-caramel); font-weight:700;">${member.memberId || 'DB-2026-8899'}</span>
      </div>
      <div class="cdp-field-item">
        <span class="cdp-field-label"><i class="fa-solid fa-user"></i> ชื่อ - นามสกุล</span>
        <span class="cdp-field-val">${member.fullname}</span>
      </div>
      <div class="cdp-field-item">
        <span class="cdp-field-label"><i class="fa-solid fa-envelope"></i> อีเมล (Gmail / Email)</span>
        <span class="cdp-field-val">${member.email}</span>
      </div>
      <div class="cdp-field-item">
        <span class="cdp-field-label"><i class="fa-solid fa-phone"></i> เบอร์โทรศัพท์</span>
        <span class="cdp-field-val">${member.phone}</span>
      </div>
      <div class="cdp-field-item">
        <span class="cdp-field-label"><i class="fa-solid fa-cake-candles"></i> วันเกิด (Birthday Freebie)</span>
        <span class="cdp-field-val">${member.birthday ? new Date(member.birthday).toLocaleDateString('th-TH') : 'ไม่ระบุ'}</span>
      </div>
      <div class="cdp-field-item">
        <span class="cdp-field-label"><i class="fa-solid fa-mug-hot"></i> สไตล์กาแฟที่ชอบ (Flavor Preference)</span>
        <span class="cdp-field-val">${member.favoriteCoffee || 'Signature Dirty'}</span>
      </div>
      <div class="cdp-field-item">
        <span class="cdp-field-label"><i class="fa-solid fa-cow"></i> นมที่เลือกดื่ม (Milk Preference)</span>
        <span class="cdp-field-val">${member.milk || 'Fresh Milk'}</span>
      </div>
      <div class="cdp-field-item">
        <span class="cdp-field-label"><i class="fa-solid fa-location-dot"></i> ที่อยู่จัดส่ง / ย่านที่สะดวก</span>
        <span class="cdp-field-val">${member.address || 'ย่านสยาม-จุฬาฯ'}</span>
      </div>
    `;
  }
}

function renderOrderHistory() {
  const container = document.getElementById('order-history-container');
  if (!container) return;

  const orders = JSON.parse(localStorage.getItem('dailybrew_order_history') || '[]');

  if (orders.length === 0) {
    container.innerHTML = `
      <div style="text-align:center; padding: 40px 10px; color: var(--text-muted);">
        <i class="fa-solid fa-receipt" style="font-size: 2.5rem; margin-bottom: 12px; opacity: 0.4;"></i>
        <p>ยังไม่มีประวัติการสั่งซื้อ</p>
        <p style="font-size:0.82rem;">ทดลองสั่งเมนูโปรดของคุณเพื่อเริ่มบันทึกประวัติและสะสมแต้มได้เลยค่ะ</p>
      </div>
    `;
    return;
  }

  container.innerHTML = '';
  orders.forEach((ord, idx) => {
    const card = document.createElement('div');
    card.className = 'order-card';

    const itemsSummary = ord.items.map(it => `${it.name} (x${it.qty})`).join(', ');

    card.innerHTML = `
      <div class="order-card-top">
        <div>
          <span class="order-card-id">#${ord.id}</span>
          <span class="order-card-date">${ord.date}</span>
        </div>
        <span class="order-status-badge">✅ ${ord.status || 'จัดส่งสำเร็จ'}</span>
      </div>
      <div class="order-card-items-text">
        <strong>รายการ:</strong> ${itemsSummary}
      </div>
      <div class="order-card-bottom">
        <div>
          <span class="order-card-total">${ord.total} ฿</span>
          <span class="order-card-points"> (+${ord.pointsEarned || Math.floor(ord.total / 10)} pts)</span>
        </div>
        <button class="btn btn-secondary btn-sm" onclick="reorderPastOrder(${idx})">
          <i class="fa-solid fa-rotate-right"></i> สั่งซ้ำ
        </button>
      </div>
    `;
    container.appendChild(card);
  });
}

function reorderPastOrder(orderIndex) {
  const orders = JSON.parse(localStorage.getItem('dailybrew_order_history') || '[]');
  if (!orders[orderIndex]) return;

  const targetOrder = orders[orderIndex];
  targetOrder.items.forEach(it => {
    cart.push(JSON.parse(JSON.stringify(it)));
  });

  updateCartUI();
  closeMemberPortalModal();
  openCartModal();
  showToast(`เพิ่มรายการจากออเดอร์ #${targetOrder.id} ลงในตะกร้าแล้วค่ะ ☕`);
}

