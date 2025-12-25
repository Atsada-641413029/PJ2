// Common authentication utilities
const AuthUtils = {
    // Email validation
    isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    },
    
    // Phone validation (Thai format)
    isValidPhone(phone) {
        return /^0[0-9]{9}$/.test(phone.replace(/[-\s]/g, ''));
    },
    
    // Password strength check
    checkPasswordStrength(password) {
        const strength = {
            score: 0,
            message: '',
            class: ''
        };
        
        if (password.length >= 6) strength.score++;
        if (password.length >= 8) strength.score++;
        if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength.score++;
        if (/[0-9]/.test(password)) strength.score++;
        if (/[^a-zA-Z0-9]/.test(password)) strength.score++;
        
        if (strength.score <= 1) {
            strength.message = 'อ่อนแอ';
            strength.class = 'danger';
        } else if (strength.score <= 3) {
            strength.message = 'ปานกลาง';
            strength.class = 'warning';
        } else {
            strength.message = 'แข็งแรง';
            strength.class = 'success';
        }
        
        return strength;
    },
    
    // Show error message
    showError(fieldId, message) {
        const input = document.getElementById(fieldId);
        const error = document.getElementById(fieldId + '-error');
        
        if (input) {
            input.classList.add('is-invalid');
        }
        if (error) {
            error.textContent = message;
            error.classList.remove('d-none');
        }
    },
    
    // Clear all errors
    clearErrors() {
        document.querySelectorAll('.form-error').forEach(el => {
            el.classList.add('d-none');
            el.textContent = '';
        });
        document.querySelectorAll('.form-control').forEach(el => {
            el.classList.remove('is-invalid', 'is-valid');
        });
    },
    
    // Show alert
    showAlert(message, type = 'danger') {
        const alert = document.getElementById('alert');
        const alertMessage = document.getElementById('alert-message');
        
        if (alert && alertMessage) {
            alertMessage.textContent = message;
            alert.className = `alert alert-${type}`;
            alert.classList.remove('d-none');
            
            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    },
    
    // Hide alert
    hideAlert() {
        const alert = document.getElementById('alert');
        if (alert) {
            alert.classList.add('d-none');
        }
    },
    
    // Set loading state on button
    setButtonLoading(button, loading = true) {
        if (loading) {
            button.classList.add('loading');
            button.disabled = true;
        } else {
            button.classList.remove('loading');
            button.disabled = false;
        }
    },
    
    // Mock API call
    mockApiCall(endpoint, data, delay = 1000) {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                // Simulate success
                resolve({
                    success: true,
                    message: 'Operation successful',
                    data: data
                });
            }, delay);
        });
    },
    
    // Store user session (mock)
    setUserSession(user) {
        localStorage.setItem('user', JSON.stringify(user));
        localStorage.setItem('isLoggedIn', 'true');
    },
    
    // Get user session
    getUserSession() {
        const user = localStorage.getItem('user');
        return user ? JSON.parse(user) : null;
    },
    
    // Check if user is logged in
    isLoggedIn() {
        return localStorage.getItem('isLoggedIn') === 'true';
    },
    
    // Logout
    logout() {
        localStorage.removeItem('user');
        localStorage.removeItem('isLoggedIn');
        window.location.href = '/login.php';
    },
    
    // Redirect based on role
    redirectByRole(role) {
        const redirectMap = {
            'admin': '/admin/index.php',
            'seller': '/seller/index.php',
            'customer': '/customer/index.php'
        };
        
        window.location.href = redirectMap[role] || '/index.php';
    }
};

// Export for use in other files
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AuthUtils;
}
