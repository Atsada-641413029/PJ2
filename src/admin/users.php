<?php 
require_once 'auth-check.php'; 

if (!$auth->hasPermission('manage_users')) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการผู้ใช้ - Admin Panel</title>
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
            <h1>จัดการผู้ใช้</h1>
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
        
        <!-- Filter Section -->
        <div class="content-card">
            <div style="display: flex; gap: 16px; margin-bottom: 20px;">
                <select id="roleFilter" class="form-control" style="max-width: 200px;">
                    <option value="">ทุกบทบาท</option>
                    <option value="admin">ผู้ดูแล</option>
                    <option value="seller">ผู้ขาย</option>
                    <option value="customer">ผู้ซื้อ</option>
                </select>
                
                <select id="statusFilter" class="form-control" style="max-width: 200px;">
                    <option value="">ทุกสถานะ</option>
                    <option value="active">ใช้งาน</option>
                    <option value="inactive">ไม่ใช้งาน</option>
                    <option value="suspended">ระงับ</option>
                </select>
                
                <input type="text" id="searchInput" class="form-control" placeholder="ค้นหาชื่อหรืออีเมล..." style="max-width: 300px;">
            </div>
        </div>
        
        <!-- Users Table -->
        <div class="content-card">
            <h2>รายชื่อผู้ใช้ทั้งหมด</h2>
            <table class="data-table" id="usersTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ชื่อผู้ใช้</th>
                        <th>อีเมล</th>
                        <th>ชื่อ-นามสกุล</th>
                        <th>บทบาท</th>
                        <th>สถานะ</th>
                        <th>วันที่สร้าง</th>
                        <th>การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px; color: #7F8C8D;">
                            กำลังโหลดข้อมูล...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
        let allUsers = [];
        
        async function loadUsers() {
            try {
                const response = await fetch('../api/admin/users.php');
                const data = await response.json();
                
                if (data.success) {
                    allUsers = data.users;
                    displayUsers(allUsers);
                }
            } catch (error) {
                console.error('Error loading users:', error);
            }
        }
        
        function displayUsers(users) {
            const tbody = document.querySelector('#usersTable tbody');
            
            if (!users || users.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" style="text-align: center; padding: 40px; color: #7F8C8D;">ไม่พบข้อมูล</td></tr>';
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
                        <td>${user.user_id}</td>
                        <td>${user.username}</td>
                        <td>${user.email}</td>
                        <td>${user.full_name}</td>
                        <td>${roleBadge}</td>
                        <td>${statusBadge}</td>
                        <td>${new Date(user.created_at).toLocaleDateString('th-TH')}</td>
                        <td>
                            ${user.status === 'active' ? 
                                `<button class="btn-sm btn-danger" onclick="suspendUser(${user.user_id})">ระงับ</button>` :
                                `<button class="btn-sm btn-success" onclick="activateUser(${user.user_id})">เปิดใช้</button>`
                            }
                            <button class="btn-sm btn-info" onclick="viewUser(${user.user_id})">ดูข้อมูล</button>
                        </td>
                    </tr>
                `;
            }).join('');
        }
        
        function filterUsers() {
            const role = document.getElementById('roleFilter').value;
            const status = document.getElementById('statusFilter').value;
            const search = document.getElementById('searchInput').value.toLowerCase();
            
            let filtered = allUsers;
            
            if (role) {
                filtered = filtered.filter(u => u.role === role);
            }
            
            if (status) {
                filtered = filtered.filter(u => u.status === status);
            }
            
            if (search) {
                filtered = filtered.filter(u => 
                    u.username.toLowerCase().includes(search) ||
                    u.email.toLowerCase().includes(search) ||
                    u.full_name.toLowerCase().includes(search)
                );
            }
            
            displayUsers(filtered);
        }
        
        async function activateUser(userId) {
            if (!confirm('คุณต้องการเปิดใช้งานผู้ใช้รายนี้ใช่หรือไม่?')) return;
            
            try {
                const response = await fetch('../api/admin/update-user-status.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ user_id: userId, status: 'active' })
                });
                
                const data = await response.json();
                if (data.success) {
                    alert('เปิดใช้งานสำเร็จ');
                    loadUsers();
                } else {
                    alert('เกิดข้อผิดพลาด: ' + data.message);
                }
            } catch (error) {
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
            }
        }
        
        async function suspendUser(userId) {
            if (!confirm('คุณต้องการระงับผู้ใช้รายนี้ใช่หรือไม่?')) return;
            
            try {
                const response = await fetch('../api/admin/update-user-status.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ user_id: userId, status: 'suspended' })
                });
                
                const data = await response.json();
                if (data.success) {
                    alert('ระงับผู้ใช้สำเร็จ');
                    loadUsers();
                } else {
                    alert('เกิดข้อผิดพลาด: ' + data.message);
                }
            } catch (error) {
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
            }
        }
        
        function viewUser(userId) {
            alert('ฟีเจอร์ดูข้อมูลผู้ใช้จะพัฒนาในเฟสถัดไป');
        }
        
        // Event listeners
        document.getElementById('roleFilter').addEventListener('change', filterUsers);
        document.getElementById('statusFilter').addEventListener('change', filterUsers);
        document.getElementById('searchInput').addEventListener('input', filterUsers);
        
        // Load data
        loadUsers();
        
        // Prevent back button
        window.history.pushState(null, "", window.location.href);
        window.onpopstate = function() {
            window.history.pushState(null, "", window.location.href);
        };
    </script>
</body>
</html>
