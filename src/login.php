<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - ระบบจัดการวัสดุก่อสร้าง</title>
    
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
            <!-- Back to Home Button -->
            <div style="text-align: center; margin-bottom: var(--space-4);">
                <a href="index.php" class="btn btn-outline" style="display: inline-flex; align-items: center; gap: var(--space-2);">
                    ← กลับหน้าหลัก
                </a>
            </div>

            <!-- Logo Section -->
            <div class="auth-logo">
                <div class="auth-logo-icon">
                    🏗️
                </div>
                <h1>Construction Mart</h1>
                <p>ระบบจัดการวัสดุก่อสร้าง</p>
            </div>

            <!-- Title -->
            <h2 class="auth-title">เข้าสู่ระบบ</h2>
            <p class="auth-subtitle">กรุณากรอกข้อมูลเพื่อเข้าสู่ระบบ</p>

            <!-- Alert (Hidden by default) -->
            <div id="alert" class="alert alert-danger d-none" role="alert">
                <strong>เกิดข้อผิดพลาด!</strong> <span id="alert-message"></span>
            </div>

            <!-- Login Form -->
            <form id="loginForm" class="auth-form">
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

                <div class="form-group">
                    <label for="password" class="form-label required">รหัสผ่าน</label>
                    <div class="password-toggle">
                        <input 
                            type="password" 
                            class="form-control" 
                            id="password" 
                            name="password"
                            placeholder="••••••••"
                            required
                        >
                        <button type="button" class="password-toggle-btn" onclick="togglePassword()">
                            👁️
                        </button>
                    </div>
                    <span class="form-error d-none" id="password-error"></span>
                </div>

                <div class="remember-forgot">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" id="remember">
                        <span>จดจำฉันไว้</span>
                    </label>
                    <a href="#" class="forgot-link">ลืมรหัสผ่าน?</a>
                </div>

                <button type="submit" class="btn btn-primary btn-lg btn-block">
                    เข้าสู่ระบบ
                </button>
            </form>

            <!-- Divider -->
            <div class="divider">
                <span>หรือ</span>
            </div>

            <!-- Footer -->
            <div class="auth-footer">
                <p>ต้องการเป็นผู้ขาย? <a href="register.php">สมัครเป็นผู้ขาย</a></p>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="assets/js/auth.js"></script>
    <script>
        // Toggle Password Visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleBtn = document.querySelector('.password-toggle-btn');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleBtn.textContent = '🙈';
            } else {
                passwordInput.type = 'password';
                toggleBtn.textContent = '👁️';
            }
        }

        // Form Submission (Mock)
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const btn = this.querySelector('button[type="submit"]');
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            // Clear previous errors
            document.querySelectorAll('.form-error').forEach(el => {
                el.classList.add('d-none');
                el.textContent = '';
            });
            document.querySelectorAll('.form-control').forEach(el => {
                el.classList.remove('is-invalid');
            });
            document.getElementById('alert').classList.add('d-none');
            
            // Basic validation
            let hasError = false;
            
            if (!email) {
                showError('email', 'กรุณากรอกอีเมล');
                hasError = true;
            } else if (!isValidEmail(email)) {
                showError('email', 'รูปแบบอีเมลไม่ถูกต้อง');
                hasError = true;
            }
            
            if (!password) {
                showError('password', 'กรุณากรอกรหัสผ่าน');
                hasError = true;
            } else if (password.length < 6) {
                showError('password', 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร');
                hasError = true;
            }
            
            if (hasError) return;
            
            // Show loading state
            btn.classList.add('loading');
            btn.disabled = true;
            
            // Call login API
            fetch('/api/login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    email: email,
                    password: password
                })
            })
            .then(response => response.json())
            .then(data => {
                btn.classList.remove('loading');
                btn.disabled = false;
                
                if (data.success) {
                    // Redirect based on user role
                    const role = data.user.role;
                    if (role === 'admin') {
                        window.location.href = '/admin/index.php';
                    } else if (role === 'seller') {
                        window.location.href = '/seller/index.php';
                    } else {
                        window.location.href = '/index.php';
                    }
                } else {
                    showAlert(data.message);
                }
            })
            .catch(error => {
                btn.classList.remove('loading');
                btn.disabled = false;
                showAlert('เกิดข้อผิดพลาดในการเชื่อมต่อ กรุณาลองใหม่อีกครั้ง');
                console.error('Error:', error);
            });
        });
        
        function showError(fieldId, message) {
            const input = document.getElementById(fieldId);
            const error = document.getElementById(fieldId + '-error');
            
            input.classList.add('is-invalid');
            error.textContent = message;
            error.classList.remove('d-none');
        }
        
        function showAlert(message) {
            const alert = document.getElementById('alert');
            const alertMessage = document.getElementById('alert-message');
            
            alertMessage.textContent = message;
            alert.classList.remove('d-none');
            
            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
        
        function isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }
    </script>
</body>
</html>
