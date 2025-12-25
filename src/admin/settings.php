<?php require_once 'auth-check.php'; ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตั้งค่าระบบ - Admin Panel</title>
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
            <h1>ตั้งค่าระบบ</h1>
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
        
        <!-- Settings Sections -->
        <div class="content-card">
            <h2>ข้อมูลระบบ</h2>
            <table class="data-table">
                <tr>
                    <td style="width: 200px; font-weight: 600;">ชื่อระบบ</td>
                    <td>Construction Mart</td>
                </tr>
                <tr>
                    <td style="font-weight: 600;">เวอร์ชัน</td>
                    <td>1.0.0</td>
                </tr>
                <tr>
                    <td style="font-weight: 600;">ฐานข้อมูล</td>
                    <td>MySQL 8.0</td>
                </tr>
                <tr>
                    <td style="font-weight: 600;">PHP Version</td>
                    <td><?php echo phpversion(); ?></td>
                </tr>
            </table>
        </div>
        
        <div class="content-card">
            <h2>การตั้งค่าทั่วไป</h2>
            <div style="display: grid; gap: 20px;">
                <div style="padding: 20px; background: #f8f9fa; border-radius: 8px;">
                    <h3 style="margin-bottom: 10px; color: #2C3E50;">🔐 ความปลอดภัย</h3>
                    <p style="color: #7F8C8D; font-size: 14px; margin-bottom: 10px;">จัดการการตั้งค่าความปลอดภัยของระบบ</p>
                    <button class="btn btn-outline" onclick="alert('ฟีเจอร์นี้จะพัฒนาในเฟสถัดไป')">ตั้งค่า</button>
                </div>
                
                <div style="padding: 20px; background: #f8f9fa; border-radius: 8px;">
                    <h3 style="margin-bottom: 10px; color: #2C3E50;">📧 อีเมล</h3>
                    <p style="color: #7F8C8D; font-size: 14px; margin-bottom: 10px;">ตั้งค่าการส่งอีเมลแจ้งเตือน</p>
                    <button class="btn btn-outline" onclick="alert('ฟีเจอร์นี้จะพัฒนาในเฟสถัดไป')">ตั้งค่า</button>
                </div>
                
                <div style="padding: 20px; background: #f8f9fa; border-radius: 8px;">
                    <h3 style="margin-bottom: 10px; color: #2C3E50;">🤖 AI Settings</h3>
                    <p style="color: #7F8C8D; font-size: 14px; margin-bottom: 10px;">ตั้งค่า Google Gemini API</p>
                    <button class="btn btn-outline" onclick="alert('ฟีเจอร์นี้จะพัฒนาในเฟสถัดไป')">ตั้งค่า</button>
                </div>
                
                <div style="padding: 20px; background: #f8f9fa; border-radius: 8px;">
                    <h3 style="margin-bottom: 10px; color: #2C3E50;">🗄️ สำรองข้อมูล</h3>
                    <p style="color: #7F8C8D; font-size: 14px; margin-bottom: 10px;">สำรองและกู้คืนข้อมูลระบบ</p>
                    <button class="btn btn-outline" onclick="alert('ฟีเจอร์นี้จะพัฒนาในเฟสถัดไป')">จัดการ</button>
                </div>
            </div>
        </div>
        
        <div class="content-card">
            <h2>ข้อมูลผู้ดูแลระบบ</h2>
            <table class="data-table">
                <tr>
                    <td style="width: 200px; font-weight: 600;">ชื่อผู้ใช้</td>
                    <td><?php echo $currentUser['username']; ?></td>
                </tr>
                <tr>
                    <td style="font-weight: 600;">อีเมล</td>
                    <td><?php echo $currentUser['email']; ?></td>
                </tr>
                <tr>
                    <td style="font-weight: 600;">ชื่อ-นามสกุล</td>
                    <td><?php echo $currentUser['full_name']; ?></td>
                </tr>
                <tr>
                    <td style="font-weight: 600;">บทบาท</td>
                    <td><span class="badge info">ผู้ดูแลระบบ</span></td>
                </tr>
            </table>
            <button class="btn btn-primary" style="margin-top: 20px;" onclick="alert('ฟีเจอร์แก้ไขโปรไฟล์จะพัฒนาในเฟสถัดไป')">แก้ไขข้อมูล</button>
        </div>
    </div>
    
    <script>
        // Prevent back button
        window.history.pushState(null, "", window.location.href);
        window.onpopstate = function() {
            window.history.pushState(null, "", window.location.href);
        };
    </script>
</body>
</html>
