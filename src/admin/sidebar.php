<!-- Sidebar Component -->
<div class="sidebar">
    <div class="sidebar-logo">
        <div class="sidebar-logo-icon">🏗️</div>
        <div class="sidebar-logo-text">
            <h1>Construction Mart</h1>
            <p>Admin Panel</p>
        </div>
    </div>
    
    <ul class="sidebar-menu">
        <li><a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>"><span class="menu-icon">📊</span> Dashboard</a></li>
        <li><a href="users.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : ''; ?>"><span class="menu-icon">👥</span> จัดการผู้ใช้</a></li>
        <li><a href="sellers.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'sellers.php' ? 'active' : ''; ?>"><span class="menu-icon">🏪</span> อนุมัติผู้ขาย</a></li>
        <li><a href="products.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : ''; ?>"><span class="menu-icon">📦</span> สินค้าทั้งหมด</a></li>
        <li><a href="categories.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'active' : ''; ?>"><span class="menu-icon">📑</span> หมวดหมู่</a></li>
        <li><a href="reports.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>"><span class="menu-icon">📈</span> รายงาน</a></li>
        <li><a href="settings.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>"><span class="menu-icon">⚙️</span> ตั้งค่า</a></li>
        <li><a href="../api/logout.php" style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px;"><span class="menu-icon">🚪</span> ออกจากระบบ</a></li>
    </ul>
</div>
