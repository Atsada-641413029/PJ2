# ระบบจัดการวัสดุก่อสร้าง (Construction Materials Management System)

## ภาพรวมโปรเจกต์

ระบบจัดการวัสดุก่อสร้างที่ช่วยให้ผู้ขายสามารถลงสินค้าของร้านตัวเองได้ และผู้ใช้สามารถเลือกซื้อสินค้าได้ โดยระบบจะใช้ AI (Google Gemini 2.5 Flash) ในการวิเคราะห์และเปรียบเทียบราคาจากหลายร้านค้า พร้อมแนะนำร้านค้าที่เหมาะสมที่สุดโดยพิจารณาจากหลายปัจจัย ไม่ใช่แค่ราคาเพียงอย่างเดียว

## เทคโนโลยีที่ใช้

- **Backend**: PHP
- **Database**: MySQL
- **AI API**: Google Gemini 2.5 Flash
- **Frontend**: HTML, CSS, JavaScript

## ผู้ใช้งานในระบบ (User Roles)

### 1. Admin (ผู้ดูแลระบบ)
- อนุมัติการเปิดร้านค้าใหม่
- จัดการผู้ใช้ทั้งหมด
- ดูรายงานและสถิติ
- จัดการหลักเกณฑ์การประเมินร้านค้า

### 2. Seller (ผู้ขาย/เจ้าของร้าน)
- สมัครและกรอกข้อมูลร้านค้า
- รอการอนุมัติจาก Admin
- จัดการสินค้า (เพิ่ม แก้ไข ลบ)
- ดูคำสั่งซื้อ
- อัพเดทสถานะการจัดส่ง
- จัดการโปรโมชั่น

### 3. Customer (ผู้ซื้อ)
- สมัครสมาชิก/เข้าสู่ระบบ
- ค้นหาและเปรียบเทียบสินค้า
- ดูคำแนะนำจาก AI
- สั่งซื้อสินค้า
- ให้คะแนนและรีวิวร้านค้า

---

## โครงสร้างฐานข้อมูล (Database Schema)

### ตาราง: users
จัดเก็บข้อมูลผู้ใช้ทั้งหมด

```sql
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    role ENUM('admin', 'seller', 'customer') NOT NULL,
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role)
);
```

### ตาราง: shops
จัดเก็บข้อมูลร้านค้า

```sql
CREATE TABLE shops (
    shop_id INT PRIMARY KEY AUTO_INCREMENT,
    seller_id INT NOT NULL,
    shop_name VARCHAR(100) NOT NULL,
    shop_description TEXT,
    business_license VARCHAR(50),
    tax_id VARCHAR(20),
    address TEXT NOT NULL,
    province VARCHAR(50),
    district VARCHAR(50),
    postal_code VARCHAR(10),
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    logo_url VARCHAR(255),
    approval_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    approved_by INT,
    approved_at TIMESTAMP NULL,
    rejection_reason TEXT,
    rating_avg DECIMAL(3,2) DEFAULT 0.00,
    total_reviews INT DEFAULT 0,
    total_orders INT DEFAULT 0,
    response_time_hours DECIMAL(5,2) DEFAULT 0.00,
    on_time_delivery_rate DECIMAL(5,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(user_id) ON DELETE SET NULL,
    INDEX idx_approval_status (approval_status),
    INDEX idx_seller (seller_id)
);
```

### ตาราง: categories
หมวดหมู่สินค้า

```sql
CREATE TABLE categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(100) NOT NULL,
    parent_category_id INT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_category_id) REFERENCES categories(category_id) ON DELETE SET NULL,
    INDEX idx_parent (parent_category_id)
);
```

### ตาราง: products
จัดเก็บข้อมูลสินค้า

```sql
CREATE TABLE products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    shop_id INT NOT NULL,
    category_id INT NOT NULL,
    product_name VARCHAR(200) NOT NULL,
    description TEXT,
    brand VARCHAR(100),
    unit VARCHAR(50) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    stock_quantity INT DEFAULT 0,
    min_order_quantity INT DEFAULT 1,
    image_url VARCHAR(255),
    specifications JSON,
    status ENUM('active', 'inactive', 'out_of_stock') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (shop_id) REFERENCES shops(shop_id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE RESTRICT,
    INDEX idx_shop (shop_id),
    INDEX idx_category (category_id),
    INDEX idx_status (status),
    FULLTEXT idx_search (product_name, description, brand)
);
```

### ตาราง: promotions
โปรโมชั่นของร้านค้า

```sql
CREATE TABLE promotions (
    promotion_id INT PRIMARY KEY AUTO_INCREMENT,
    shop_id INT NOT NULL,
    product_id INT NULL,
    promotion_name VARCHAR(100) NOT NULL,
    description TEXT,
    discount_type ENUM('percentage', 'fixed_amount', 'free_shipping') NOT NULL,
    discount_value DECIMAL(10,2) NOT NULL,
    min_purchase_amount DECIMAL(10,2) DEFAULT 0.00,
    start_date DATETIME NOT NULL,
    end_date DATETIME NOT NULL,
    status ENUM('active', 'inactive', 'expired') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (shop_id) REFERENCES shops(shop_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
    INDEX idx_shop (shop_id),
    INDEX idx_dates (start_date, end_date),
    INDEX idx_status (status)
);
```

### ตาราง: orders
คำสั่งซื้อ

```sql
CREATE TABLE orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    shop_id INT NOT NULL,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    discount_amount DECIMAL(10,2) DEFAULT 0.00,
    shipping_fee DECIMAL(10,2) DEFAULT 0.00,
    final_amount DECIMAL(10,2) NOT NULL,
    shipping_address TEXT NOT NULL,
    order_status ENUM('pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
    payment_method VARCHAR(50),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES users(user_id) ON DELETE RESTRICT,
    FOREIGN KEY (shop_id) REFERENCES shops(shop_id) ON DELETE RESTRICT,
    INDEX idx_customer (customer_id),
    INDEX idx_shop (shop_id),
    INDEX idx_status (order_status),
    INDEX idx_order_number (order_number)
);
```

### ตาราง: order_items
รายการสินค้าในคำสั่งซื้อ

```sql
CREATE TABLE order_items (
    order_item_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(200) NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE RESTRICT,
    INDEX idx_order (order_id),
    INDEX idx_product (product_id)
);
```

### ตาราง: reviews
รีวิวและคะแนนร้านค้า

```sql
CREATE TABLE reviews (
    review_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    customer_id INT NOT NULL,
    shop_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    product_quality_rating INT CHECK (product_quality_rating BETWEEN 1 AND 5),
    service_rating INT CHECK (service_rating BETWEEN 1 AND 5),
    delivery_rating INT CHECK (delivery_rating BETWEEN 1 AND 5),
    comment TEXT,
    response TEXT,
    response_date TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (shop_id) REFERENCES shops(shop_id) ON DELETE CASCADE,
    INDEX idx_shop (shop_id),
    INDEX idx_customer (customer_id),
    INDEX idx_rating (rating)
);
```

### ตาราง: ai_recommendations
บันทึกผลการวิเคราะห์จาก AI

```sql
CREATE TABLE ai_recommendations (
    recommendation_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    search_query VARCHAR(255) NOT NULL,
    product_category_id INT,
    recommended_shops JSON NOT NULL,
    analysis_factors JSON NOT NULL,
    gemini_response TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (product_category_id) REFERENCES categories(category_id) ON DELETE SET NULL,
    INDEX idx_customer (customer_id),
    INDEX idx_created (created_at)
);
```

### ตาราง: shop_metrics
ข้อมูลสำหรับการประเมินร้านค้า

```sql
CREATE TABLE shop_metrics (
    metric_id INT PRIMARY KEY AUTO_INCREMENT,
    shop_id INT NOT NULL,
    metric_date DATE NOT NULL,
    total_orders INT DEFAULT 0,
    completed_orders INT DEFAULT 0,
    cancelled_orders INT DEFAULT 0,
    avg_response_time_hours DECIMAL(5,2) DEFAULT 0.00,
    on_time_deliveries INT DEFAULT 0,
    late_deliveries INT DEFAULT 0,
    total_revenue DECIMAL(12,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (shop_id) REFERENCES shops(shop_id) ON DELETE CASCADE,
    UNIQUE KEY unique_shop_date (shop_id, metric_date),
    INDEX idx_shop (shop_id),
    INDEX idx_date (metric_date)
);
```

---

## ระบบการทำงานหลัก (Core Features)

### 1. ระบบการสมัครและอนุมัติร้านค้า

#### ขั้นตอนการสมัครร้านค้า:
1. ผู้ขายสร้างบัญชีผู้ใช้ (role = 'seller')
2. กรอกข้อมูลร้านค้า:
   - ชื่อร้าน
   - รายละเอียดร้าน
   - เลขทะเบียนการค้า
   - เลขประจำตัวผู้เสียภาษี
   - ที่อยู่ร้าน
   - เบอร์โทรศัพท์
   - อีเมล
   - โลโก้ร้าน
3. ส่งคำขออนุมัติ (approval_status = 'pending')
4. Admin ตรวจสอบและอนุมัติ/ปฏิเสธ
5. หากอนุมัติ: approval_status = 'approved', ผู้ขายสามารถเพิ่มสินค้าได้
6. หากปฏิเสธ: approval_status = 'rejected', บันทึกเหตุผลใน rejection_reason

### 2. ระบบจัดการสินค้า

#### ฟีเจอร์สำหรับผู้ขาย:
- เพิ่มสินค้าใหม่ (ชื่อ, รายละเอียด, ราคา, จำนวน, รูปภาพ)
- แก้ไขข้อมูลสินค้า
- ลบสินค้า
- จัดการสต็อก
- สร้างและจัดการโปรโมชั่น

### 3. ระบบค้นหาและเปรียบเทียบสินค้า

#### ขั้นตอนการทำงาน:
1. ผู้ใช้ค้นหาสินค้า (ชื่อสินค้า, หมวดหมู่)
2. ระบบค้นหาสินค้าที่ตรงกันจากหลายร้าน
3. เรียงลำดับตามราคาจากถูกไปแพง
4. ดึงข้อมูลเพิ่มเติมของแต่ละร้าน:
   - คะแนนรีวิว
   - จำนวนรีวิว
   - โปรโมชั่นที่มี
   - เวลาตอบกลับเฉลี่ย
   - อัตราการส่งของตรงเวลา
   - จำนวนคำสั่งซื้อทั้งหมด

### 4. ระบบวิเคราะห์และแนะนำด้วย AI (Google Gemini 2.5 Flash)

#### ปัจจัยที่ใช้ในการวิเคราะห์:
1. **ราคา** (Price) - น้ำหนัก 30%
2. **คะแนนรีวิว** (Rating) - น้ำหนัก 25%
3. **โปรโมชั่น** (Promotions) - น้ำหนัก 15%
4. **การบริการ** (Service Quality) - น้ำหนัก 20%
   - เวลาตอบกลับ
   - อัตราการส่งของตรงเวลา
5. **ความน่าเชื่อถือ** (Reliability) - น้ำหนัก 10%
   - จำนวนคำสั่งซื้อ
   - อายุร้าน

#### ขั้นตอนการทำงาน:
1. รวบรวมข้อมูลสินค้าจากทุกร้านที่มีสินค้าตรงกัน
2. คำนวณคะแนนเบื้องต้นตามปัจจัยต่างๆ
3. ส่งข้อมูลไปยัง Gemini API พร้อม prompt:
   ```
   วิเคราะห์และแนะนำร้านค้าที่เหมาะสมที่สุด 5 อันดับแรก สำหรับการซื้อ [ชื่อสินค้า]
   
   ข้อมูลร้านค้า:
   [JSON ข้อมูลร้านทั้งหมด]
   
   กรุณาวิเคราะห์โดยพิจารณา:
   - ราคาที่แข่งขันได้
   - คุณภาพการบริการ
   - โปรโมชั่นที่มี
   - ความน่าเชื่อถือของร้าน
   - ประสบการณ์ของลูกค้าก่อนหน้า
   
   แนะนำ 5 ร้านพร้อมเหตุผลที่ชัดเจน
   ```
4. Gemini วิเคราะห์และส่งผลลัพธ์กลับมา
5. แสดงผลแนะนำ 5 ร้านพร้อมเหตุผล
6. บันทึกผลการวิเคราะห์ลงตาราง ai_recommendations

### 5. ระบบสั่งซื้อและชำระเงิน

#### ขั้นตอน:
1. ผู้ใช้เลือกสินค้าและจำนวน
2. เพิ่มลงตะกร้า
3. ตรวจสอบคำสั่งซื้อ
4. กรอกที่อยู่จัดส่ง
5. เลือกวิธีชำระเงิน
6. ยืนยันคำสั่งซื้อ
7. ผู้ขายได้รับแจ้งเตือน
8. ผู้ขายยืนยันและจัดส่ง
9. อัพเดทสถานะการจัดส่ง
10. ลูกค้าได้รับสินค้า
11. ลูกค้าให้คะแนนและรีวิว

### 6. ระบบรีวิวและคะแนน

#### ฟีเจอร์:
- ให้คะแนนร้านค้า (1-5 ดาว)
- ให้คะแนนแยกตาม:
  - คุณภาพสินค้า
  - การบริการ
  - การจัดส่ง
- เขียนความคิดเห็น
- ผู้ขายสามารถตอบกลับรีวิวได้
- คำนวณคะแนนเฉลี่ยอัตโนมัติ

---

## แผนการพัฒนา (Development Plan)

> [!IMPORTANT]
> **การพัฒนาจะเริ่มจาก UI/Frontend ก่อน** แล้วค่อยเชื่อมต่อกับ Backend ในภายหลัง

### Phase 1: UI/Frontend Development - Authentication & Layout (สัปดาห์ที่ 1)
**เน้น: สร้าง UI เท่านั้น ยังไม่ต้องเชื่อมต่อฐานข้อมูล**
- [ ] สร้างโครงสร้างไฟล์โปรเจกต์
- [ ] สร้าง CSS Framework/Design System (สี, ฟอนต์, components)
- [ ] หน้า Login (UI อย่างเดียว)
- [ ] หน้า Register (UI อย่างเดียว)
- [ ] สร้าง Navigation/Menu สำหรับแต่ละ Role
- [ ] สร้าง Layout Template (Header, Sidebar, Footer)

### Phase 2: UI/Frontend - Admin Panel (สัปดาห์ที่ 1-2)
**เน้น: สร้าง UI เท่านั้น ใช้ Mock Data**
- [ ] Dashboard สำหรับ Admin (แสดง Mock Data)
- [ ] หน้าอนุมัติร้านค้า (UI + Mock Data)
- [ ] หน้าจัดการผู้ใช้ (UI + Mock Data)
- [ ] หน้าดูรายงานและสถิติ (UI + Mock Data)

### Phase 3: UI/Frontend - Seller Panel (สัปดาห์ที่ 2)
**เน้น: สร้าง UI เท่านั้น ใช้ Mock Data**
- [ ] หน้าสมัครร้านค้า (Shop Registration Form UI)
- [ ] Dashboard สำหรับผู้ขาย (UI + Mock Data)
- [ ] หน้าจัดการสินค้า - Product CRUD (UI + Mock Data)
- [ ] หน้าจัดการโปรโมชั่น (UI + Mock Data)
- [ ] หน้าดูคำสั่งซื้อ (UI + Mock Data)
- [ ] หน้าอัพเดทสถานะการจัดส่ง (UI + Mock Data)

### Phase 4: UI/Frontend - Customer Panel (สัปดาห์ที่ 3)
**เน้น: สร้าง UI เท่านั้น ใช้ Mock Data**
- [ ] หน้าหลัก/Landing Page
- [ ] หน้าค้นหาสินค้า (UI + Mock Data)
- [ ] หน้าแสดงรายละเอียดสินค้า (UI + Mock Data)
- [ ] หน้าเปรียบเทียบและแนะนำ (UI + Mock AI Response)
- [ ] ระบบตะกร้าสินค้า (UI + Local Storage)
- [ ] หน้าสั่งซื้อและชำระเงิน (UI + Mock Data)
- [ ] หน้าประวัติการสั่งซื้อ (UI + Mock Data)
- [ ] หน้าให้คะแนนและรีวิว (UI + Mock Data)

### Phase 5: Database Setup (สัปดาห์ที่ 4)
**เริ่มพัฒนา Backend**
- [ ] สร้างฐานข้อมูล MySQL
- [ ] สร้างตารางทั้งหมดตาม schema
- [ ] สร้าง user admin เริ่มต้น
- [ ] สร้างข้อมูลตัวอย่าง (categories, ร้านค้าทดสอบ, สินค้าตัวอย่าง)

### Phase 6: Backend Integration - Authentication & Core Features (สัปดาห์ที่ 4-5)
**เชื่อมต่อ UI กับ Backend**
- [ ] สร้างระบบ Login/Logout (เชื่อมกับฐานข้อมูล)
- [ ] สร้างระบบ Registration (เชื่อมกับฐานข้อมูล)
- [ ] สร้างระบบจัดการ Session
- [ ] สร้างระบบ Role-based Access Control
- [ ] เชื่อมต่อ Admin Panel กับฐานข้อมูล
- [ ] เชื่อมต่อ Seller Panel กับฐานข้อมูล
- [ ] เชื่อมต่อ Customer Panel กับฐานข้อมูล

### Phase 7: AI Integration (สัปดาห์ที่ 5-6)
**เชื่อมต่อ Gemini API**
- [ ] ติดตั้งและตั้งค่า Google Gemini API
- [ ] สร้างฟังก์ชันเชื่อมต่อ Gemini API
- [ ] สร้างระบบรวบรวมข้อมูลสำหรับการวิเคราะห์
- [ ] สร้าง Prompt Template
- [ ] เชื่อมต่อหน้าแสดงผลการวิเคราะห์กับ Gemini API
- [ ] บันทึกผลการวิเคราะห์ลงฐานข้อมูล

### Phase 8: Testing & Optimization (สัปดาห์ที่ 6-7)
- [ ] ทดสอบ User Flow ทั้งหมด
- [ ] ทดสอบระบบ AI Recommendation
- [ ] ปรับปรุง UI/UX
- [ ] เพิ่ม Error Handling
- [ ] ทดสอบ Security
- [ ] Optimize Performance

### Phase 9: Deployment (สัปดาห์ที่ 7)
- [ ] เตรียม Production Environment
- [ ] Deploy ระบบ
- [ ] ทดสอบบน Production
- [ ] สร้างเอกสารคู่มือการใช้งาน

---

## โครงสร้างไฟล์โปรเจกต์ (Project Structure)

```
PJ2/
├── config/
│   ├── database.php          # การเชื่อมต่อฐานข้อมูล
│   ├── config.php            # การตั้งค่าทั่วไป
│   └── gemini_api.php        # การตั้งค่า Gemini API
├── includes/
│   ├── auth.php              # ฟังก์ชันการยืนยันตัวตน
│   ├── functions.php         # ฟังก์ชันทั่วไป
│   └── session.php           # จัดการ Session
├── admin/
│   ├── index.php             # Dashboard
│   ├── approve_shops.php     # อนุมัติร้านค้า
│   ├── manage_users.php      # จัดการผู้ใช้
│   └── reports.php           # รายงาน
├── seller/
│   ├── index.php             # Dashboard
│   ├── register_shop.php     # สมัครร้านค้า
│   ├── products.php          # จัดการสินค้า
│   ├── promotions.php        # จัดการโปรโมชั่น
│   └── orders.php            # คำสั่งซื้อ
├── customer/
│   ├── index.php             # หน้าหลัก
│   ├── search.php            # ค้นหาสินค้า
│   ├── product_detail.php    # รายละเอียดสินค้า
│   ├── compare.php           # เปรียบเทียบและแนะนำ
│   ├── cart.php              # ตะกร้า
│   ├── checkout.php          # สั่งซื้อ
│   ├── orders.php            # ประวัติการสั่งซื้อ
│   └── review.php            # ให้คะแนน
├── api/
│   ├── gemini_analyze.php    # API สำหรับวิเคราะห์ด้วย Gemini
│   ├── products_api.php      # API สินค้า
│   └── orders_api.php        # API คำสั่งซื้อ
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── uploads/
│   ├── products/             # รูปสินค้า
│   └── shops/                # โลโก้ร้าน
├── login.php                 # หน้า Login
├── register.php              # หน้า Register
├── logout.php                # Logout
└── index.php                 # หน้าแรก
```

---

## API Integration: Google Gemini 2.5 Flash

### การตั้งค่า API

```php
// config/gemini_api.php
<?php
define('GEMINI_API_KEY', 'YOUR_API_KEY_HERE');
define('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent');
?>
```

### ตัวอย่างการเรียกใช้ API

```php
// api/gemini_analyze.php
function analyzeShopsWithGemini($productName, $shopsData) {
    $apiKey = GEMINI_API_KEY;
    $url = GEMINI_API_URL . '?key=' . $apiKey;
    
    $prompt = "วิเคราะห์และแนะนำร้านค้าที่เหมาะสมที่สุด 5 อันดับแรก สำหรับการซื้อ {$productName}\n\n";
    $prompt .= "ข้อมูลร้านค้า:\n" . json_encode($shopsData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n\n";
    $prompt .= "กรุณาวิเคราะห์โดยพิจารณา:\n";
    $prompt .= "- ราคาที่แข่งขันได้\n";
    $prompt .= "- คุณภาพการบริการ (คะแนนรีวิว, เวลาตอบกลับ, การส่งของตรงเวลา)\n";
    $prompt .= "- โปรโมชั่นที่มี\n";
    $prompt .= "- ความน่าเชื่อถือของร้าน (จำนวนคำสั่งซื้อ, อายุร้าน)\n";
    $prompt .= "- ประสบการณ์ของลูกค้าก่อนหน้า\n\n";
    $prompt .= "แนะนำ 5 ร้านพร้อมเหตุผลที่ชัดเจนว่าทำไมต้องซื้อจากร้านนี้";
    
    $data = [
        'contents' => [
            [
                'parts' => [
                    ['text' => $prompt]
                ]
            ]
        ],
        'generationConfig' => [
            'temperature' => 0.7,
            'topK' => 40,
            'topP' => 0.95,
            'maxOutputTokens' => 2048,
        ]
    ];
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}
?>
```

---

## ฟีเจอร์เพิ่มเติม (Optional Features)

### 1. ระบบแจ้งเตือน (Notifications)
- แจ้งเตือนผู้ขายเมื่อมีคำสั่งซื้อใหม่
- แจ้งเตือนลูกค้าเมื่อสถานะคำสั่งซื้อเปลี่ยน
- แจ้งเตือน Admin เมื่อมีร้านค้าใหม่รอการอนุมัติ

### 2. ระบบรายงาน (Reports)
- รายงานยอดขายของร้าน
- รายงานสินค้าขายดี
- รายงานร้านค้าที่ได้รับคะแนนสูงสุด

### 3. ระบบแชท (Chat)
- ลูกค้าสามารถแชทสอบถามร้านค้าได้

### 4. ระบบเปรียบเทียบสินค้า
- เปรียบเทียบสินค้าหลายรายการพร้อมกัน
- แสดงตารางเปรียบเทียบ

---

## Security Considerations

### 1. Authentication & Authorization
- ใช้ password hashing (password_hash, password_verify)
- ตรวจสอบ role ก่อนเข้าถึงหน้าต่างๆ
- ใช้ CSRF tokens

### 2. Input Validation
- Validate ข้อมูลทุก input
- ใช้ Prepared Statements เพื่อป้องกัน SQL Injection
- Sanitize ข้อมูลก่อนแสดงผล (XSS Prevention)

### 3. File Upload Security
- ตรวจสอบ file type และ size
- เปลี่ยนชื่อไฟล์ที่อัพโหลด
- จัดเก็บไฟล์นอก document root ถ้าเป็นไปได้

### 4. API Security
- เก็บ API Key ในไฟล์ config ที่ไม่ถูก commit
- จำกัดจำนวนการเรียก API (Rate Limiting)
- ตรวจสอบ response จาก API

---

## Performance Optimization

### 1. Database
- สร้าง Index ที่เหมาะสม
- ใช้ Query Optimization
- ใช้ Connection Pooling

### 2. Caching
- Cache ผลการค้นหาที่ใช้บ่อย
- Cache ผลการวิเคราะห์จาก AI (ระยะเวลาสั้น)

### 3. Frontend
- Minify CSS/JS
- Optimize รูปภาพ
- ใช้ Lazy Loading

---

## ข้อมูล Admin เริ่มต้น

```sql
INSERT INTO users (username, email, password_hash, full_name, role, status) 
VALUES (
    'admin', 
    'admin@constructionmart.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    'System Administrator', 
    'admin', 
    'active'
);
```

---

## หมายเหตุ

- ระบบนี้เป็น MVP (Minimum Viable Product) ที่มุ่งเน้นฟีเจอร์หลัก
- สามารถขยายฟีเจอร์เพิ่มเติมได้ตามความต้องการ
- ควรทดสอบระบบอย่างละเอียดก่อน Deploy จริง
- ควรมีระบบ Backup ฐานข้อมูลเป็นประจำ
- ควรติดตาม API Usage และ Cost ของ Gemini API

---

## ติดต่อและสนับสนุน

หากมีคำถามหรือต้องการความช่วยเหลือเพิ่มเติม กรุณาติดต่อทีมพัฒนา
