<?php require_once 'auth-check.php'; ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Construction Mart</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fa;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 260px;
            height: 100vh;
            background: linear-gradient(180deg, #2C3E50 0%, #34495E 100%);
            color: white;
            padding: 20px;
            overflow-y: auto;
        }
        
        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 30px;
        }
        
        .sidebar-logo-icon {
            font-size: 32px;
        }
        
        .sidebar-logo-text h1 {
            font-size: 20px;
            font-weight: 700;
        }
        
        .sidebar-logo-text p {
            font-size: 12px;
            opacity: 0.7;
        }
        
        .sidebar-menu {
            list-style: none;
        }
        
        .sidebar-menu li {
            margin-bottom: 8px;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        
        .sidebar-menu a.active {
            background: #FF6B35;
        }
        
        .menu-icon {
            font-size: 20px;
            width: 24px;
            text-align: center;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 260px;
            padding: 30px;
        }
        
        /* Header */
        .header {
            background: white;
            padding: 20px 30px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header h1 {
            font-size: 28px;
            color: #2C3E50;
        }
        
        .header-actions {
            display: flex;
            gap: 16px;
            align-items: center;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        }
        
        .stat-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        .stat-icon.blue { background: #E3F2FD; }
        .stat-icon.green { background: #E8F5E9; }
        .stat-icon.orange { background: #FFF3E0; }
        .stat-icon.purple { background: #F3E5F5; }
        
        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #2C3E50;
            margin-bottom: 8px;
        }
        
        .stat-label {
            color: #7F8C8D;
            font-size: 14px;
        }
        
        /* Content Card */
        .content-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        
        .content-card h2 {
            font-size: 20px;
            color: #2C3E50;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        /* Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .data-table thead {
            background: #f8f9fa;
        }
        
        .data-table th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #2C3E50;
            font-size: 14px;
        }
        
        .data-table td {
            padding: 12px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .data-table tr:hover {
            background: #f8f9fa;
        }
        
        /* Badge */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .badge.success {
            background: #d4edda;
            color: #155724;
        }
        
        .badge.warning {
            background: #fff3cd;
            color: #856404;
        }
        
        .badge.danger {
            background: #f8d7da;
            color: #721c24;
        }
        
        .badge.info {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        /* Button */
        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-success {
            background: #28a745;
            color: white;
        }
        
        .btn-success:hover {
            background: #218838;
        }
        
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        
        .btn-danger:hover {
            background: #c82333;
        }
        
        .btn-info {
            background: #17a2b8;
            color: white;
        }
        
        .btn-info:hover {
            background: #138496;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon">🏗️</div>
            <div class="sidebar-logo-text">
                <h1>Construction Mart</h1>
                <p>Admin Panel</p>
            </div>
        </div>
        
        <ul class="sidebar-menu">
            <li><a href="index.php" class="active"><span class="menu-icon">📊</span> Dashboard</a></li>
            <li><a href="users.php"><span class="menu-icon">👥</span> จัดการผู้ใช้</a></li>
            <li><a href="sellers.php"><span class="menu-icon">🏪</span> อนุมัติผู้ขาย</a></li>
            <li><a href="products.php"><span class="menu-icon">📦</span> สินค้าทั้งหมด</a></li>
            <li><a href="categories.php"><span class="menu-icon">📑</span> หมวดหมู่</a></li>
            <li><a href="reports.php"><span class="menu-icon">📈</span> รายงาน</a></li>
            <li><a href="settings.php"><span class="menu-icon">⚙️</span> ตั้งค่า</a></li>
            <li><a href="../api/logout.php" style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px;"><span class="menu-icon">🚪</span> ออกจากระบบ</a></li>
        </ul>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <h1>Dashboard</h1>
            <div class="header-actions">
                <div class="user-info">
                    <div class="user-avatar">A</div>
                    <div>
                        <div style="font-weight: 600; color: #2C3E50;">Admin</div>
                        <div style="font-size: 12px; color: #7F8C8D;">ผู้ดูแลระบบ</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards -->
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
                <div class="stat-label">ผู้ขายที่ใช้งาน</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-icon orange">⏳</div>
                </div>
                <div class="stat-value" id="pendingSellers">0</div>
                <div class="stat-label">รออนุมัติ</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-icon purple">📦</div>
                </div>
                <div class="stat-value" id="totalProducts">0</div>
                <div class="stat-label">สินค้าทั้งหมด</div>
            </div>
        </div>
        
        <!-- Pending Sellers -->
        <div class="content-card">
            <h2>ผู้ขายรออนุมัติ</h2>
            <table class="data-table" id="pendingSellersTable">
                <thead>
                    <tr>
                        <th>ชื่อ-นามสกุล</th>
                        <th>อีเมล</th>
                        <th>เบอร์โทร</th>
                        <th>วันที่สมัคร</th>
                        <th>สถานะ</th>
                        <th>การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: #7F8C8D;">
                            กำลังโหลดข้อมูล...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Recent Users -->
        <div class="content-card">
            <h2>ผู้ใช้ล่าสุด</h2>
            <table class="data-table" id="recentUsersTable">
                <thead>
                    <tr>
                        <th>ชื่อผู้ใช้</th>
                        <th>อีเมล</th>
                        <th>บทบาท</th>
                        <th>สถานะ</th>
                        <th>วันที่สร้าง</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px; color: #7F8C8D;">
                            กำลังโหลดข้อมูล...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
        // Load dashboard data
        async function loadDashboardData() {
            try {
                const response = await fetch('../api/admin/dashboard.php');
                const data = await response.json();
                
                if (data.success) {
                    // Update stats
                    document.getElementById('totalUsers').textContent = data.stats.total_users;
                    document.getElementById('totalSellers').textContent = data.stats.active_sellers;
                    document.getElementById('pendingSellers').textContent = data.stats.pending_sellers;
                    document.getElementById('totalProducts').textContent = data.stats.total_products || 0;
                    
                    // Load pending sellers
                    loadPendingSellers(data.pending_sellers);
                    
                    // Load recent users
                    loadRecentUsers(data.recent_users);
                }
            } catch (error) {
                console.error('Error loading dashboard:', error);
            }
        }
        
        function loadPendingSellers(sellers) {
            const tbody = document.querySelector('#pendingSellersTable tbody');
            
            if (!sellers || sellers.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 40px; color: #7F8C8D;">ไม่มีผู้ขายรออนุมัติ</td></tr>';
                return;
            }
            
            tbody.innerHTML = sellers.map(seller => `
                <tr>
                    <td>${seller.full_name}</td>
                    <td>${seller.email}</td>
                    <td>${seller.phone || '-'}</td>
                    <td>${new Date(seller.created_at).toLocaleDateString('th-TH')}</td>
                    <td><span class="badge warning">รออนุมัติ</span></td>
                    <td>
                        <button class="btn-sm btn-success" onclick="approveSeller(${seller.user_id})">อนุมัติ</button>
                        <button class="btn-sm btn-danger" onclick="rejectSeller(${seller.user_id})">ปฏิเสธ</button>
                    </td>
                </tr>
            `).join('');
        }
        
        function loadRecentUsers(users) {
            const tbody = document.querySelector('#recentUsersTable tbody');
            
            if (!users || users.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 40px; color: #7F8C8D;">ไม่มีข้อมูล</td></tr>';
                return;
            }
            
            tbody.innerHTML = users.map(user => {
                let statusBadge = '';
                if (user.status === 'active') statusBadge = '<span class="badge success">ใช้งาน</span>';
                else if (user.status === 'inactive') statusBadge = '<span class="badge warning">ไม่ใช้งาน</span>';
                else statusBadge = '<span class="badge danger">ระงับ</span>';
                
                let roleBadge = '';
                if (user.role === 'admin') roleBadge = '<span class="badge info">ผู้ดูแล</span>';
                else if (user.role === 'seller') roleBadge = '<span class="badge success">ผู้ขาย</span>';
                else roleBadge = '<span class="badge">ผู้ซื้อ</span>';
                
                return `
                    <tr>
                        <td>${user.username}</td>
                        <td>${user.email}</td>
                        <td>${roleBadge}</td>
                        <td>${statusBadge}</td>
                        <td>${new Date(user.created_at).toLocaleDateString('th-TH')}</td>
                    </tr>
                `;
            }).join('');
        }
        
        async function approveSeller(userId) {
            if (!confirm('คุณต้องการอนุมัติผู้ขายรายนี้ใช่หรือไม่?')) return;
            
            try {
                const response = await fetch('../api/admin/approve-seller.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ user_id: userId })
                });
                
                const data = await response.json();
                if (data.success) {
                    alert('อนุมัติผู้ขายสำเร็จ');
                    loadDashboardData();
                } else {
                    alert('เกิดข้อผิดพลาด: ' + data.message);
                }
            } catch (error) {
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
            }
        }
        
        async function rejectSeller(userId) {
            if (!confirm('คุณต้องการปฏิเสธผู้ขายรายนี้ใช่หรือไม่?')) return;
            
            try {
                const response = await fetch('../api/admin/reject-seller.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ user_id: userId })
                });
                
                const data = await response.json();
                if (data.success) {
                    alert('ปฏิเสธผู้ขายสำเร็จ');
                    loadDashboardData();
                } else {
                    alert('เกิดข้อผิดพลาด: ' + data.message);
                }
            } catch (error) {
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
            }
        }
        
        // Load data on page load
        loadDashboardData();
        
        // Prevent back button after logout
        window.history.pushState(null, "", window.location.href);
        window.onpopstate = function() {
            window.history.pushState(null, "", window.location.href);
        };
    </script>
</body>
</html>
