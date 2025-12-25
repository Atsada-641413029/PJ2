<?php 
require_once 'auth-check.php'; 

if (!$auth->hasPermission('manage_sellers')) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>อนุมัติผู้ขาย - Admin Panel</title>
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
            <h1>อนุมัติผู้ขาย</h1>
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
                    <div class="stat-icon orange">⏳</div>
                </div>
                <div class="stat-value" id="pendingCount">0</div>
                <div class="stat-label">รออนุมัติ</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-icon green">✅</div>
                </div>
                <div class="stat-value" id="approvedCount">0</div>
                <div class="stat-label">อนุมัติแล้ว</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-icon danger">❌</div>
                </div>
                <div class="stat-value" id="rejectedCount">0</div>
                <div class="stat-label">ปฏิเสธ</div>
            </div>
        </div>
        
        <!-- Pending Sellers -->
        <div class="content-card">
            <h2>ผู้ขายรออนุมัติ</h2>
            <table class="data-table" id="pendingSellersTable">
                <thead>
                    <tr>
                        <th>ID</th>
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
                        <td colspan="7" style="text-align: center; padding: 40px; color: #7F8C8D;">
                            กำลังโหลดข้อมูล...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Approved Sellers -->
        <div class="content-card">
            <h2>ผู้ขายที่อนุมัติแล้ว</h2>
            <table class="data-table" id="approvedSellersTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ชื่อ-นามสกุล</th>
                        <th>อีเมล</th>
                        <th>เบอร์โทร</th>
                        <th>วันที่อนุมัติ</th>
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
    </div>
    
    <script>
        async function loadSellers() {
            try {
                const response = await fetch('../api/admin/sellers.php');
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('pendingCount').textContent = data.stats.pending;
                    document.getElementById('approvedCount').textContent = data.stats.approved;
                    document.getElementById('rejectedCount').textContent = data.stats.rejected;
                    
                    displayPendingSellers(data.pending_sellers);
                    displayApprovedSellers(data.approved_sellers);
                }
            } catch (error) {
                console.error('Error loading sellers:', error);
            }
        }
        
        function displayPendingSellers(sellers) {
            const tbody = document.querySelector('#pendingSellersTable tbody');
            
            if (!sellers || sellers.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 40px; color: #7F8C8D;">ไม่มีผู้ขายรออนุมัติ</td></tr>';
                return;
            }
            
            tbody.innerHTML = sellers.map(seller => `
                <tr>
                    <td>${seller.user_id}</td>
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
        
        function displayApprovedSellers(sellers) {
            const tbody = document.querySelector('#approvedSellersTable tbody');
            
            if (!sellers || sellers.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 40px; color: #7F8C8D;">ไม่มีข้อมูล</td></tr>';
                return;
            }
            
            tbody.innerHTML = sellers.map(seller => `
                <tr>
                    <td>${seller.user_id}</td>
                    <td>${seller.full_name}</td>
                    <td>${seller.email}</td>
                    <td>${seller.phone || '-'}</td>
                    <td>${new Date(seller.updated_at).toLocaleDateString('th-TH')}</td>
                    <td>
                        <button class="btn-sm btn-danger" onclick="suspendSeller(${seller.user_id})">ระงับ</button>
                    </td>
                </tr>
            `).join('');
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
                    loadSellers();
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
                    loadSellers();
                } else {
                    alert('เกิดข้อผิดพลาด: ' + data.message);
                }
            } catch (error) {
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
            }
        }
        
        async function suspendSeller(userId) {
            if (!confirm('คุณต้องการระงับผู้ขายรายนี้ใช่หรือไม่?')) return;
            
            try {
                const response = await fetch('../api/admin/update-user-status.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ user_id: userId, status: 'suspended' })
                });
                
                const data = await response.json();
                if (data.success) {
                    alert('ระงับผู้ขายสำเร็จ');
                    loadSellers();
                } else {
                    alert('เกิดข้อผิดพลาด: ' + data.message);
                }
            } catch (error) {
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
            }
        }
        
        loadSellers();
        
        // Prevent back button
        window.history.pushState(null, "", window.location.href);
        window.onpopstate = function() {
            window.history.pushState(null, "", window.location.href);
        };
    </script>
</body>
</html>
