<?php require_once 'auth-check.php'; ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สินค้าทั้งหมด - Admin Panel</title>
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
            <h1>สินค้าทั้งหมด</h1>
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
        
        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-icon purple">📦</div>
                </div>
                <div class="stat-value" id="totalProducts">0</div>
                <div class="stat-label">สินค้าทั้งหมด</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-icon green">✅</div>
                </div>
                <div class="stat-value" id="activeProducts">0</div>
                <div class="stat-label">กำลังขาย</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-icon orange">⏸️</div>
                </div>
                <div class="stat-value" id="inactiveProducts">0</div>
                <div class="stat-label">หยุดขาย</div>
            </div>
        </div>
        
        <!-- Products Table -->
        <div class="content-card">
            <h2>รายการสินค้า</h2>
            <div style="margin-bottom: 20px;">
                <input type="text" id="searchInput" class="form-control" placeholder="ค้นหาสินค้า..." style="max-width: 300px;">
            </div>
            <table class="data-table" id="productsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ชื่อสินค้า</th>
                        <th>หมวดหมู่</th>
                        <th>ร้านค้า</th>
                        <th>ราคา</th>
                        <th>สถานะ</th>
                        <th>การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px; color: #7F8C8D;">
                            ยังไม่มีสินค้าในระบบ<br>
                            <small>สินค้าจะถูกเพิ่มโดยผู้ขายหลังจากได้รับการอนุมัติ</small>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
        // Placeholder for future implementation
        document.getElementById('totalProducts').textContent = '0';
        document.getElementById('activeProducts').textContent = '0';
        document.getElementById('inactiveProducts').textContent = '0';
        
        // Prevent back button
        window.history.pushState(null, "", window.location.href);
        window.onpopstate = function() {
            window.history.pushState(null, "", window.location.href);
        };
    </script>
</body>
</html>
