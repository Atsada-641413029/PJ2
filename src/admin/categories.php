<?php require_once 'auth-check.php'; ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการหมวดหมู่ - Admin Panel</title>
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
            <h1>จัดการหมวดหมู่สินค้า</h1>
            <div class="header-actions">
                <button class="btn btn-primary" onclick="showAddModal()">+ เพิ่มหมวดหมู่</button>
                <div class="user-info">
                    <div class="user-avatar">A</div>
                    <div>
                        <div style="font-weight: 600; color: #2C3E50;"><?php echo $currentUser['full_name']; ?></div>
                        <div style="font-size: 12px; color: #7F8C8D;">ผู้ดูแลระบบ</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Categories Table -->
        <div class="content-card">
            <h2>รายการหมวดหมู่</h2>
            <table class="data-table" id="categoriesTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ไอคอน</th>
                        <th>ชื่อหมวดหมู่</th>
                        <th>คำอธิบาย</th>
                        <th>จำนวนสินค้า</th>
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
    </div>
    
    <script>
        async function loadCategories() {
            try {
                const response = await fetch('../api/admin/categories.php');
                const data = await response.json();
                
                if (data.success) {
                    displayCategories(data.categories);
                }
            } catch (error) {
                console.error('Error loading categories:', error);
            }
        }
        
        function displayCategories(categories) {
            const tbody = document.querySelector('#categoriesTable tbody');
            
            if (!categories || categories.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 40px; color: #7F8C8D;">ไม่มีข้อมูล</td></tr>';
                return;
            }
            
            tbody.innerHTML = categories.map(cat => `
                <tr>
                    <td>${cat.category_id}</td>
                    <td style="font-size: 24px;">${cat.icon || '📦'}</td>
                    <td><strong>${cat.category_name}</strong></td>
                    <td>${cat.description || '-'}</td>
                    <td>${cat.product_count || 0}</td>
                    <td><span class="badge success">ใช้งาน</span></td>
                    <td>
                        <button class="btn-sm btn-info" onclick="editCategory(${cat.category_id})">แก้ไข</button>
                        <button class="btn-sm btn-danger" onclick="deleteCategory(${cat.category_id})">ลบ</button>
                    </td>
                </tr>
            `).join('');
        }
        
        function showAddModal() {
            alert('ฟีเจอร์เพิ่มหมวดหมู่จะพัฒนาในเฟสถัดไป');
        }
        
        function editCategory(id) {
            alert('ฟีเจอร์แก้ไขหมวดหมู่จะพัฒนาในเฟสถัดไป');
        }
        
        function deleteCategory(id) {
            if (!confirm('คุณต้องการลบหมวดหมู่นี้ใช่หรือไม่?')) return;
            alert('ฟีเจอร์ลบหมวดหมู่จะพัฒนาในเฟสถัดไป');
        }
        
        loadCategories();
        
        // Prevent back button
        window.history.pushState(null, "", window.location.href);
        window.onpopstate = function() {
            window.history.pushState(null, "", window.location.href);
        };
    </script>
</body>
</html>
