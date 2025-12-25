<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก - ระบบจัดการวัสดุก่อสร้าง</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <!-- Logo Section -->
            <div class="auth-logo">
                <div class="auth-logo-icon">
                    🏗️
                </div>
                <h1>Construction Mart</h1>
                <p>ระบบจัดการวัสดุก่อสร้าง</p>
            </div>

            <!-- Title -->
            <h2 class="auth-title">สมัครเป็นผู้ขาย</h2>
            <p class="auth-subtitle">เริ่มต้นขายวัสดุก่อสร้างออนไลน์กับเรา</p>

            <!-- Alert (Hidden by default) -->
            <div id="alert" class="alert alert-success d-none" role="alert">
                <strong>สำเร็จ!</strong> <span id="alert-message"></span>
            </div>

            <!-- Register Form -->
            <form id="registerForm" class="auth-form">
                <!-- Hidden Role Field (Always Seller) -->
                <input type="hidden" name="role" value="seller">

                <!-- Full Name -->
                <div class="form-group">
                    <label for="fullname" class="form-label required">ชื่อ-นามสกุล</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="fullname" 
                        name="fullname"
                        placeholder="กรอกชื่อ-นามสกุล"
                        required
                    >
                    <span class="form-error d-none" id="fullname-error"></span>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label required">อีเมล</label>
                    <input 
                        type="email" 
                        class="form-control" 
                        id="email" 
                        name="email"
                        placeholder="your.email@example.com"
                        required
                    >
                    <span class="form-error d-none" id="email-error"></span>
                </div>

                <!-- Phone -->
                <div class="form-group">
                    <label for="phone" class="form-label required">เบอร์โทรศัพท์</label>
                    <input 
                        type="tel" 
                        class="form-control" 
                        id="phone" 
                        name="phone"
                        placeholder="0812345678"
                        required
                    >
                    <span class="form-error d-none" id="phone-error"></span>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label required">รหัสผ่าน</label>
                    <div class="password-toggle">
                        <input 
                            type="password" 
                            class="form-control" 
                            id="password" 
                            name="password"
                            placeholder="อย่างน้อย 6 ตัวอักษร"
                            required
                        >
                        <button type="button" class="password-toggle-btn" onclick="togglePassword('password')">
                            👁️
                        </button>
                    </div>
                    <span class="form-error d-none" id="password-error"></span>
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="confirm-password" class="form-label required">ยืนยันรหัสผ่าน</label>
                    <div class="password-toggle">
                        <input 
                            type="password" 
                            class="form-control" 
                            id="confirm-password" 
                            name="confirm_password"
                            placeholder="กรอกรหัสผ่านอีกครั้ง"
                            required
                        >
                        <button type="button" class="password-toggle-btn" onclick="togglePassword('confirm-password')">
                            👁️
                        </button>
                    </div>
                    <span class="form-error d-none" id="confirm-password-error"></span>
                </div>

                <!-- Terms and Conditions -->
                <div class="form-group">
                    <label class="remember-me">
                        <input type="checkbox" name="terms" id="terms" required>
                        <span>ฉันยอมรับ <a href="#" class="text-primary">ข้อกำหนดและเงื่อนไข</a></span>
                    </label>
                    <span class="form-error d-none" id="terms-error"></span>
                </div>

                <button type="submit" class="btn btn-primary btn-lg btn-block">
                    สมัครเป็นผู้ขาย
                </button>
            </form>

            <!-- Divider -->
            <div class="divider">
                <span>หรือ</span>
            </div>

            <!-- Footer -->
            <div class="auth-footer">
                <p>มีบัญชีอยู่แล้ว? <a href="login.php">เข้าสู่ระบบ</a></p>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Toggle Password Visibility
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const toggleBtn = passwordInput.nextElementSibling;
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleBtn.textContent = '🙈';
            } else {
                passwordInput.type = 'password';
                toggleBtn.textContent = '👁️';
            }
        }

        // Form Submission (Mock)
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const btn = this.querySelector('button[type="submit"]');
            const formData = new FormData(this);
            
            // Clear previous errors
            document.querySelectorAll('.form-error').forEach(el => {
                el.classList.add('d-none');
                el.textContent = '';
            });
            document.querySelectorAll('.form-control').forEach(el => {
                el.classList.remove('is-invalid');
            });
            document.getElementById('alert').classList.add('d-none');
            
            // Validation
            let hasError = false;
            
            const fullname = formData.get('fullname');
            const email = formData.get('email');
            const phone = formData.get('phone');
            const password = formData.get('password');
            const confirmPassword = formData.get('confirm_password');
            const terms = formData.get('terms');
            const role = formData.get('role');
            
            if (!fullname || fullname.trim().length < 3) {
                showError('fullname', 'กรุณากรอกชื่อ-นามสกุลอย่างน้อย 3 ตัวอักษร');
                hasError = true;
            }
            
            if (!email) {
                showError('email', 'กรุณากรอกอีเมล');
                hasError = true;
            } else if (!isValidEmail(email)) {
                showError('email', 'รูปแบบอีเมลไม่ถูกต้อง');
                hasError = true;
            }
            
            if (!phone) {
                showError('phone', 'กรุณากรอกเบอร์โทรศัพท์');
                hasError = true;
            } else if (!isValidPhone(phone)) {
                showError('phone', 'รูปแบบเบอร์โทรศัพท์ไม่ถูกต้อง');
                hasError = true;
            }
            
            if (!password) {
                showError('password', 'กรุณากรอกรหัสผ่าน');
                hasError = true;
            } else if (password.length < 6) {
                showError('password', 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร');
                hasError = true;
            }
            
            if (!confirmPassword) {
                showError('confirm-password', 'กรุณายืนยันรหัสผ่าน');
                hasError = true;
            } else if (password !== confirmPassword) {
                showError('confirm-password', 'รหัสผ่านไม่ตรงกัน');
                hasError = true;
            }
            
            if (!terms) {
                showError('terms', 'กรุณายอมรับข้อกำหนดและเงื่อนไข');
                hasError = true;
            }
            
            if (hasError) return;
            
            // Show loading state
            btn.classList.add('loading');
            btn.disabled = true;
            
            // Call register API
            fetch('/api/register.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    fullname: fullname,
                    email: email,
                    phone: phone,
                    password: password,
                    role: role
                })
            })
            .then(response => response.json())
            .then(data => {
                btn.classList.remove('loading');
                btn.disabled = false;
                
                const alert = document.getElementById('alert');
                const alertMessage = document.getElementById('alert-message');
                
                if (data.success) {
                    // Success
                    alertMessage.textContent = 'สมัครเป็นผู้ขายสำเร็จ! กรุณารอการอนุมัติจากผู้ดูแลระบบ';
                    alert.classList.remove('d-none');
                    alert.classList.add('alert-success');
                    alert.classList.remove('alert-danger');
                    
                    // Redirect after 3 seconds
                    setTimeout(() => {
                        window.location.href = 'index.php';
                    }, 3000);
                } else {
                    // Error
                    alertMessage.textContent = data.message;
                    alert.classList.remove('d-none');
                    alert.classList.add('alert-danger');
                    alert.classList.remove('alert-success');
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            })
            .catch(error => {
                btn.classList.remove('loading');
                btn.disabled = false;
                
                const alert = document.getElementById('alert');
                const alertMessage = document.getElementById('alert-message');
                alertMessage.textContent = 'เกิดข้อผิดพลาดในการเชื่อมต่อ กรุณาลองใหม่อีกครั้ง';
                alert.classList.remove('d-none');
                alert.classList.add('alert-danger');
                alert.classList.remove('alert-success');
                console.error('Error:', error);
            });
        });
        
        function showError(fieldId, message) {
            const input = document.getElementById(fieldId);
            const error = document.getElementById(fieldId + '-error');
            
            if (input) {
                input.classList.add('is-invalid');
            }
            if (error) {
                error.textContent = message;
                error.classList.remove('d-none');
            }
        }
        
        function isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }
        
        function isValidPhone(phone) {
            return /^0[0-9]{9}$/.test(phone.replace(/[-\s]/g, ''));
        }
    </script>
</body>
</html>
