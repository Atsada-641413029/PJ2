<?php 
require_once 'auth-check.php'; 

// Check for manage_admins permission
if (!$auth->hasPermission('manage_admins')) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการ Admin - Admin Panel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="admin-style.css">
    <style>
        .permission-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 12px;
            margin-top: 10px;
        }
        
        .permission-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px;
            background: #f8f9fa;
            border-radius: 6px;
            border: 1px solid #eee;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }
        
        .modal.active {
            display: flex;
        }
        
        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 12px;
            width: 100%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            color: #2C3E50;
        }
        
        .checkbox-group {
            margin-top: 20px;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <div class="header">
            <h1>จัดการผู้ดูแลระบบ (Admin)</h1>
            <div class="header-actions">
                <button class="btn btn-primary" onclick="showAddModal()">+ เพิ่ม Admin ใหม่</button>
                <div class="user-info">
                    <div class="user-avatar">A</div>
                    <div>
                        <div style="font-weight: 600; color: #2C3E50;"><?php echo $currentUser['full_name']; ?></div>
                        <div style="font-size: 12px; color: #7F8C8D;">ผู้ดูแลระบบ</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Admins Table -->
        <div class="content-card">
            <h2>รายชื่อ Admin ทั้งหมด</h2>
            <table class="data-table" id="adminsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ชื่อผู้ใช้</th>
                        <th>ชื่อ-นามสกุล</th>
                        <th>สิทธิ์การใช้งาน</th>
                        <th>วันที่สร้าง</th>
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
    
    <!-- Add/Edit Admin Modal -->
    <div class="modal" id="adminModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">เพิ่ม Admin ใหม่</h2>
                <button class="btn-sm btn-danger" onclick="closeModal()">X</button>
            </div>
            
            <form id="adminForm" onsubmit="handleAdminSubmit(event)">
                <input type="hidden" id="adminId" name="adminId">
                <input type="hidden" id="mode" name="mode" value="create">
                
                <div id="credentialsFields">
                    <div class="form-group">
                        <label for="username">ชื่อผู้ใช้</label>
                        <input type="text" id="username" class="form-control" style="width: 100%;" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">อีเมล</label>
                        <input type="email" id="email" class="form-control" style="width: 100%;" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">รหัสผ่าน</label>
                        <input type="password" id="password" class="form-control" style="width: 100%;" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="fullname">ชื่อ-นามสกุล</label>
                        <input type="text" id="fullname" class="form-control" style="width: 100%;" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">เบอร์โทรศัพท์ (ถ้ามี)</label>
                        <input type="text" id="phone" class="form-control" style="width: 100%;">
                    </div>
                </div>
                
                <div id="readOnlyFields" style="display:none; margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                    <p><strong>ชื่อผู้ใช้:</strong> <span id="displayUsername"></span></p>
                    <p><strong>ชื่อ-นามสกุล:</strong> <span id="displayFullname"></span></p>
                </div>
                
                <div class="checkbox-group">
                    <label style="margin-bottom: 10px; display: block;">กำหนดสิทธิ์การใช้งาน</label>
                    <div class="permission-grid">
                        <div class="permission-item">
                            <input type="checkbox" id="perm_manage_users" name="permissions" value="manage_users">
                            <label for="perm_manage_users">จัดการผู้ใช้</label>
                        </div>
                        <div class="permission-item">
                            <input type="checkbox" id="perm_manage_sellers" name="permissions" value="manage_sellers">
                            <label for="perm_manage_sellers">อนุมัติ/จัดการผู้ขาย</label>
                        </div>
                        <div class="permission-item">
                            <input type="checkbox" id="perm_manage_products" name="permissions" value="manage_products">
                            <label for="perm_manage_products">จัดการสินค้า</label>
                        </div>
                        <div class="permission-item">
                            <input type="checkbox" id="perm_manage_categories" name="permissions" value="manage_categories">
                            <label for="perm_manage_categories">จัดการหมวดหมู่</label>
                        </div>
                        <div class="permission-item">
                            <input type="checkbox" id="perm_view_reports" name="permissions" value="view_reports">
                            <label for="perm_view_reports">ดูรายงาน</label>
                        </div>
                        <div class="permission-item">
                            <input type="checkbox" id="perm_manage_settings" name="permissions" value="manage_settings">
                            <label for="perm_manage_settings">จัดการตั้งค่า</label>
                        </div>
                        <div class="permission-item">
                            <input type="checkbox" id="perm_manage_admins" name="permissions" value="manage_admins">
                            <label for="perm_manage_admins" style="color: #c0392b; font-weight: 600;">จัดการ Admin</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-outline" onclick="closeModal()">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        const permissionLabels = {
            'manage_users': 'จัดการผู้ใช้',
            'manage_sellers': 'ผู้ขาย',
            'manage_products': 'สินค้า',
            'manage_categories': 'หมวดหมู่',
            'view_reports': 'รายงาน',
            'manage_settings': 'ตั้งค่า',
            'manage_admins': 'Admin'
        };
        
        let allAdmins = [];
        
        async function loadAdmins() {
            try {
                const response = await fetch('../api/admin/get-admins.php');
                const data = await response.json();
                
                if (data.success) {
                    allAdmins = data.admins;
                    displayAdmins(allAdmins);
                }
            } catch (error) {
                console.error('Error loading admins:', error);
            }
        }
        
        function displayAdmins(admins) {
            const tbody = document.querySelector('#adminsTable tbody');
            
            if (!admins || admins.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 40px; color: #7F8C8D;">ไม่พบข้อมูล</td></tr>';
                return;
            }
            
            tbody.innerHTML = admins.map(admin => {
                let permissionsHtml = '';
                
                if (admin.is_super_admin) {
                    permissionsHtml = '<span class="badge danger">Super Admin (All)</span>';
                } else if (!admin.permissions || admin.permissions.length === 0) {
                    permissionsHtml = '<span class="badge warning">ไม่มีสิทธิ์</span>';
                } else {
                    permissionsHtml = admin.permissions.map(p => 
                        `<span class="badge info" style="margin-right: 4px; margin-bottom: 4px;">${permissionLabels[p] || p}</span>`
                    ).join('');
                }
                
                let actionButtons = '';
                if (!admin.is_super_admin) {
                    actionButtons = `<button class="btn-sm btn-info" onclick="editPermissions(${admin.user_id})">แก้ไขสิทธิ์</button>`;
                } else {
                    actionButtons = '<span style="color: #ccc;">-</span>';
                }
                
                return `
                    <tr>
                        <td>${admin.user_id}</td>
                        <td>${admin.username}</td>
                        <td>${admin.full_name}</td>
                        <td><div style="display: flex; flex-wrap: wrap;">${permissionsHtml}</div></td>
                        <td>${new Date(admin.created_at).toLocaleDateString('th-TH')}</td>
                        <td>${actionButtons}</td>
                    </tr>
                `;
            }).join('');
        }
        
        function showAddModal() {
            document.getElementById('modalTitle').textContent = 'เพิ่ม Admin ใหม่';
            document.getElementById('mode').value = 'create';
            document.getElementById('adminId').value = '';
            
            // Show create fields
            document.getElementById('credentialsFields').style.display = 'block';
            document.getElementById('readOnlyFields').style.display = 'none';
            
            // Require password
            document.getElementById('password').required = true;
            document.getElementById('username').required = true;
            document.getElementById('email').required = true;
            document.getElementById('fullname').required = true;
            
            // Reset form
            document.getElementById('adminForm').reset();
            
            document.getElementById('adminModal').classList.add('active');
        }
        
        function editPermissions(userId) {
            const admin = allAdmins.find(a => a.user_id == userId);
            if (!admin) return;
            
            document.getElementById('modalTitle').textContent = 'แก้ไขสิทธิ์การใช้งาน';
            document.getElementById('mode').value = 'edit';
            document.getElementById('adminId').value = userId;
            
            // Hide create fields, show display fields
            document.getElementById('credentialsFields').style.display = 'none';
            document.getElementById('readOnlyFields').style.display = 'block';
            
            // Remove required attributes
            document.getElementById('password').required = false;
            document.getElementById('username').required = false;
            document.getElementById('email').required = false;
            document.getElementById('fullname').required = false;
            
            // Set display values
            document.getElementById('displayUsername').textContent = admin.username;
            document.getElementById('displayFullname').textContent = admin.full_name;
            
            // Set permissions checkboxes
            document.querySelectorAll('input[name="permissions"]').forEach(cb => {
                cb.checked = admin.permissions && admin.permissions.includes(cb.value);
            });
            
            document.getElementById('adminModal').classList.add('active');
        }
        
        function closeModal() {
            document.getElementById('adminModal').classList.remove('active');
        }
        
        async function handleAdminSubmit(e) {
            e.preventDefault();
            
            const mode = document.getElementById('mode').value;
            const permissions = Array.from(document.querySelectorAll('input[name="permissions"]:checked')).map(cb => cb.value);
            
            try {
                let url, body;
                
                if (mode === 'create') {
                    url = '../api/admin/create-admin.php';
                    body = {
                        username: document.getElementById('username').value,
                        email: document.getElementById('email').value,
                        password: document.getElementById('password').value,
                        full_name: document.getElementById('fullname').value,
                        phone: document.getElementById('phone').value,
                        permissions: permissions
                    };
                } else {
                    url = '../api/admin/update-admin-permissions.php';
                    body = {
                        user_id: document.getElementById('adminId').value,
                        permissions: permissions
                    };
                }
                
                const response = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(body)
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert(mode === 'create' ? 'เพิ่ม Admin สำเร็จ' : 'แก้ไขสิทธิ์สำเร็จ');
                    closeModal();
                    loadAdmins();
                } else {
                    alert('เกิดข้อผิดพลาด: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
            }
        }
        
        // Load data on start
        loadAdmins();
        
        // Prevent back button
        window.history.pushState(null, "", window.location.href);
        window.onpopstate = function() {
            window.history.pushState(null, "", window.location.href);
        };
    </script>
</body>
</html>
