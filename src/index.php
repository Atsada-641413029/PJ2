<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Construction Mart - ระบบจัดการวัสดุก่อสร้าง</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <style>
        /* Header Styles */
        .header {
            background: var(--white);
            box-shadow: var(--shadow-md);
            position: sticky;
            top: 0;
            z-index: var(--z-sticky);
        }
        
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: var(--space-4) var(--space-6);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: var(--space-3);
            text-decoration: none;
        }
        
        .logo-icon {
            font-size: var(--text-3xl);
        }
        
        .logo-text h1 {
            font-size: var(--text-xl);
            margin: 0;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .header-actions {
            display: flex;
            gap: var(--space-3);
            align-items: center;
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: var(--white);
            padding: var(--space-12) var(--space-6);
            text-align: center;
        }
        
        .hero-section h2 {
            color: var(--white);
            font-size: var(--text-4xl);
            margin-bottom: var(--space-4);
        }
        
        .hero-section p {
            font-size: var(--text-lg);
            opacity: 0.95;
            max-width: 800px;
            margin: 0 auto var(--space-6);
        }
        
        .search-box {
            max-width: 600px;
            margin: 0 auto;
            position: relative;
        }
        
        .search-box input {
            width: 100%;
            padding: var(--space-4) var(--space-6);
            font-size: var(--text-lg);
            border: none;
            border-radius: var(--radius-full);
            box-shadow: var(--shadow-lg);
        }
        
        /* Products Section */
        .products-section {
            max-width: 1200px;
            margin: 0 auto;
            padding: var(--space-8) var(--space-6);
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--space-6);
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: var(--space-6);
        }
        
        .product-card {
            background: var(--white);
            border-radius: var(--radius-xl);
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: all var(--transition-base);
            cursor: pointer;
        }
        
        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
        }
        
        .product-image {
            width: 100%;
            height: 200px;
            background: var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
        }
        
        .product-info {
            padding: var(--space-4);
        }
        
        .product-name {
            font-weight: var(--font-semibold);
            margin-bottom: var(--space-2);
            color: var(--gray-900);
        }
        
        .product-price {
            font-size: var(--text-xl);
            font-weight: var(--font-bold);
            color: var(--primary-color);
            margin-bottom: var(--space-3);
        }
        
        .product-shop {
            font-size: var(--text-sm);
            color: var(--gray-600);
            margin-bottom: var(--space-3);
        }
        
        .product-rating {
            display: flex;
            align-items: center;
            gap: var(--space-2);
            font-size: var(--text-sm);
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <a href="index.php" class="logo">
                <span class="logo-icon">🏗️</span>
                <div class="logo-text">
                    <h1>Construction Mart</h1>
                </div>
            </a>
            
            <div class="header-actions">
                <a href="login.php" class="btn btn-outline">เข้าสู่ระบบ</a>
                <a href="register.php" class="btn btn-primary">สมัครเป็นผู้ขาย</a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-section">
        <h2>ค้นหาวัสดุก่อสร้างที่ใช่สำหรับคุณ</h2>
        <p>เปรียบเทียบราคาจากหลายร้านค้า พร้อมคำแนะนำจาก AI</p>
        
        <div class="search-box">
            <input type="text" placeholder="ค้นหาสินค้า เช่น ปูนซีเมนต์, อิฐ, เหล็กเส้น..." id="searchInput">
        </div>
    </section>

    <!-- Products Section -->
    <section class="products-section">
        <div class="section-header">
            <h3>สินค้าแนะนำ</h3>
            <a href="#" class="text-primary">ดูทั้งหมด →</a>
        </div>
        
        <div class="products-grid" id="productsGrid">
            <!-- Mock Products - Will be replaced with real data -->
            <div class="product-card">
                <div class="product-image">🧱</div>
                <div class="product-info">
                    <div class="product-name">ปูนซีเมนต์ตราช้าง</div>
                    <div class="product-price">฿180/ถุง</div>
                    <div class="product-shop">ร้าน: วัสดุก่อสร้างสมชาย</div>
                    <div class="product-rating">
                        <span>⭐ 4.5</span>
                        <span class="text-muted">(120 รีวิว)</span>
                    </div>
                </div>
            </div>
            
            <div class="product-card">
                <div class="product-image">🪨</div>
                <div class="product-info">
                    <div class="product-name">อิฐมอญ 3 รู</div>
                    <div class="product-price">฿2.50/ก้อน</div>
                    <div class="product-shop">ร้าน: อิฐบล็อกสุรชัย</div>
                    <div class="product-rating">
                        <span>⭐ 4.8</span>
                        <span class="text-muted">(85 รีวิว)</span>
                    </div>
                </div>
            </div>
            
            <div class="product-card">
                <div class="product-image">⚙️</div>
                <div class="product-info">
                    <div class="product-name">เหล็กเส้น RB6</div>
                    <div class="product-price">฿15/กก.</div>
                    <div class="product-shop">ร้าน: เหล็กไทยรุ่งเรือง</div>
                    <div class="product-rating">
                        <span>⭐ 4.6</span>
                        <span class="text-muted">(95 รีวิว)</span>
                    </div>
                </div>
            </div>
            
            <div class="product-card">
                <div class="product-image">🏖️</div>
                <div class="product-info">
                    <div class="product-name">ทรายหยาบ</div>
                    <div class="product-price">฿450/คิว</div>
                    <div class="product-shop">ร้าน: ทรายและหินบางบัว</div>
                    <div class="product-rating">
                        <span>⭐ 4.3</span>
                        <span class="text-muted">(67 รีวิว)</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Simple search functionality (mock)
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const query = this.value;
                alert('กำลังค้นหา: ' + query + '\n(ฟีเจอร์นี้จะพัฒนาใน Phase 4-5)');
            }
        });
        
        // Product card click
        document.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('click', function() {
                alert('กำลังดูรายละเอียดสินค้า\n(ฟีเจอร์นี้จะพัฒนาใน Phase 4-5)');
            });
        });
    </script>
</body>
</html>
