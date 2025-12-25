<?php require_once 'auth-check.php'; ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงาน - Admin Panel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="admin-style.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <div class="header">
            <h1>รายงานและสถิติ</h1>
            <div class="header-actions">
                <div class="user-info">
                    <div class="user-avatar">A</div>
                    <div>
                        <div style="font-weight: 600; color: #2C3E50;"><?php echo $currentUser['full_name']; ?></div>
                        <div style="font-size: 12px; color: #7F8C8D;">ผู้ดูแลระบบ</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Report Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-icon blue">👥</div>
                </div>
                <div class="stat-value" id="totalUsers">0</div>
                <div class="stat-label">ผู้ใช้ทั้งหมด</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-icon green">🏪</div>
                </div>
                <div class="stat-value" id="totalSellers">0</div>
                <div class="stat-label">ผู้ขายทั้งหมด</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-icon purple">📦</div>
                </div>
                <div class="stat-value" id="totalProducts">0</div>
                <div class="stat-label">สินค้าทั้งหมด</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-icon orange">🛒</div>
                </div>
                <div class="stat-value">0</div>
                <div class="stat-label">คำสั่งซื้อทั้งหมด</div>
            </div>
        </div>
        
        <!-- Report Sections -->
        <div class="content-card">
            <h2>รายงานการใช้งานระบบ</h2>
            <p style="color: #7F8C8D; margin-bottom: 20px;">รายงานสถิติและข้อมูลการใช้งานระบบ</p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <div style="padding: 20px; background: #f8f9fa; border-radius: 8px;">
                    <h3 style="margin-bottom: 10px; color: #2C3E50;">📊 รายงานผู้ใช้</h3>
                    <p style="color: #7F8C8D; font-size: 14px;">สถิติการสมัครและการใช้งานของผู้ใช้</p>
                    <button class="btn btn-outline" style="margin-top: 10px;" onclick="alert('ฟีเจอร์นี้จะพัฒนาในเฟสถัดไป')">ดูรายงาน</button>
                </div>
                
                <div style="padding: 20px; background: #f8f9fa; border-radius: 8px;">
                    <h3 style="margin-bottom: 10px; color: #2C3E50;">🏪 รายงานผู้ขาย</h3>
                    <p style="color: #7F8C8D; font-size: 14px;">สถิติการอนุมัติและการขายของผู้ขาย</p>
                    <button class="btn btn-outline" style="margin-top: 10px;" onclick="alert('ฟีเจอร์นี้จะพัฒนาในเฟสถัดไป')">ดูรายงาน</button>
                </div>
                
                <div style="padding: 20px; background: #f8f9fa; border-radius: 8px;">
                    <h3 style="margin-bottom: 10px; color: #2C3E50;">📦 รายงานสินค้า</h3>
                    <p style="color: #7F8C8D; font-size: 14px;">สถิติสินค้าและหมวดหมู่ยอดนิยม</p>
                    <button class="btn btn-outline" style="margin-top: 10px;" onclick="alert('ฟีเจอร์นี้จะพัฒนาในเฟสถัดไป')">ดูรายงาน</button>
                </div>
                
                <div style="padding: 20px; background: #f8f9fa; border-radius: 8px;">
                    <h3 style="margin-bottom: 10px; color: #2C3E50;">💰 รายงานยอดขาย</h3>
                    <p style="color: #7F8C8D; font-size: 14px;">สถิติยอดขายและรายได้</p>
                    <button class="btn btn-outline" style="margin-top: 10px;" onclick="alert('ฟีเจอร์นี้จะพัฒนาในเฟสถัดไป')">ดูรายงาน</button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        async function loadStats() {
            try {
                const response = await fetch('../api/admin/dashboard.php');
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('totalUsers').textContent = data.stats.total_users;
                    document.getElementById('totalSellers').textContent = data.stats.active_sellers;
                    document.getElementById('totalProducts').textContent = data.stats.total_products || 0;
                }
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }
        
        loadStats();
        
        // Prevent back button
        window.history.pushState(null, "", window.location.href);
        window.onpopstate = function() {
            window.history.pushState(null, "", window.location.href);
        };
    </script>
</body>
</html>
