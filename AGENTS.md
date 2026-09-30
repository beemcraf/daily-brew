# AGENTS.md — Daily Brew Coffee Shop

## Project Type
Static website (HTML/CSS/JS) + PHP email backend + MySQL database.
A Thai-language specialty coffee shop showcase with CDP features, VIP membership, and email marketing.

## Architecture
- **Frontend**: `index.html` + `style.css` + `app.js` (vanilla, no framework, no build step)
- **PHP backend**: `subscribe.php`, `register_vip.php`, `order_checkout.php`
- **Email module**: `mailer.php` (PHPMailer via Gmail SMTP, fallback to `mail()`)
- **Database**: `database.sql` (MySQL schema: `subscribers`, `vip_members`, `orders`)
- **Config**: `config.local.php` (contains Gmail app password — DO NOT COMMIT)
- **Templates**: `email-template-*.html` (HTML email templates for newsletter, VIP, order)
- **Assets**: `assets/` (images only, no build pipeline)
- **Deployment**: GitHub Pages for frontend; PHP backend needs separate server (not deployed)

## Critical Constraints
- `config.local.php` is gitignored — must contain SMTP credentials for emails to work
- MySQL database is local only — PHP scripts gracefully fallback if DB unavailable
- Frontend uses `localStorage` for client-side CDP data (no server sync for VIP profiles)
- All user-facing text is Thai — maintain UTF-8 encoding throughout
- No package.json, no node_modules, no build commands — pure vanilla stack

## Commands
- **Serve locally**: Use VS Code Live Server or `php -S localhost:8000` from project root
- **Run PHP**: Requires PHP 7.4+ with `mysqli` and `openssl` extensions
- **Database setup**: Import `database.sql` in MySQL Workbench (File → Open SQL Script → Execute)
- **No lint/test/format commands** — this project has none configured

## File Responsibilities
- `app.js`: Menu data, cart logic, quiz engine, CDP/localStorage, Smart AI recommendations, Toast notifications
- `style.css`: Design system with CSS variables, glassmorphism, responsive breakpoints
- `index.html`: Single-page structure with sections for hero, promotions, packages, quiz, menu, VIP, reviews
- `vip.html`: Separate page for VIP registration form
- `subscribe.php`: Newsletter signup → MySQL + welcome email with WELCOME10 code
- `register_vip.php`: VIP signup → MySQL + personalized email with DAILYVIP20 code
- `order_checkout.php`: Order processing → MySQL + receipt email via PHPMailer

## Gotchas
- Email images (banners) are embedded via PHPMailer CID — referenced as `assets/email_banner_*.jpg`
- `config.local.php` contains real credentials in the working copy — never expose in logs or commits
- PHP scripts accept both JSON and form POST — check `Content-Type` header in code
- Points system: 10 THB = 1 point, 100 points = free drink
- Coupon codes: `WELCOME10` (10% off), `DAILYVIP20` (20% off)
- LINE Official Account: `@285arhlw`
- LINE Reward Card URL: `https://u.lin.ee/um3QCOs`
