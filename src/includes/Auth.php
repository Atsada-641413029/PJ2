<?php
/**
 * Authentication Class
 * Handles user login, registration, and session management
 */

require_once __DIR__ . '/Database.php';

class Auth {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Register a new user (seller only)
     */
    public function register($fullname, $email, $phone, $password, $role = 'seller') {
        try {
            // Check if email already exists
            $stmt = $this->db->prepare("SELECT user_id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->fetch()) {
                return [
                    'success' => false,
                    'message' => 'อีเมลนี้ถูกใช้งานแล้ว'
                ];
            }
            
            // Hash password
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            
            // Create username from email
            $username = explode('@', $email)[0];
            
            // Insert new user
            $stmt = $this->db->prepare("
                INSERT INTO users (username, email, password_hash, full_name, phone, role, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            
            // Sellers need approval, set status to inactive
            $status = ($role === 'seller') ? 'inactive' : 'active';
            
            $stmt->execute([
                $username,
                $email,
                $passwordHash,
                $fullname,
                $phone,
                $role,
                $status
            ]);
            
            return [
                'success' => true,
                'message' => 'สมัครสมาชิกสำเร็จ',
                'user_id' => $this->db->lastInsertId()
            ];
            
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                return [
                    'success' => false,
                    'message' => 'Database Error: ' . $e->getMessage()
                ];
            }
            return [
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการสมัครสมาชิก'
            ];
        }
    }
    
    /**
     * Login user
     */
    public function login($email, $password) {
        try {
            $stmt = $this->db->prepare("
                SELECT user_id, username, email, password_hash, full_name, role, status 
                FROM users 
                WHERE email = ?
            ");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง'
                ];
            }
            
            // Verify password
            if (!password_verify($password, $user['password_hash'])) {
                return [
                    'success' => false,
                    'message' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง'
                ];
            }
            
            // Check if account is active
            if ($user['status'] !== 'active') {
                return [
                    'success' => false,
                    'message' => 'บัญชีของคุณยังไม่ได้รับการอนุมัติ กรุณารอการอนุมัติจากผู้ดูแลระบบ'
                ];
            }
            
            // Set session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['logged_in'] = true;
            
            return [
                'success' => true,
                'message' => 'เข้าสู่ระบบสำเร็จ',
                'user' => [
                    'user_id' => $user['user_id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'full_name' => $user['full_name'],
                    'role' => $user['role']
                ]
            ];
            
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                return [
                    'success' => false,
                    'message' => 'Database Error: ' . $e->getMessage()
                ];
            }
            return [
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการเข้าสู่ระบบ'
            ];
        }
    }
    
    /**
     * Logout user
     */
    public function logout() {
        session_destroy();
        return [
            'success' => true,
            'message' => 'ออกจากระบบสำเร็จ'
        ];
    }
    
    /**
     * Check if user is logged in
     */
    public function isLoggedIn() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }
    
    /**
     * Get current user
     */
    public function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        return [
            'user_id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'email' => $_SESSION['email'],
            'full_name' => $_SESSION['full_name'],
            'role' => $_SESSION['role']
        ];
    }
    
    /**
     * Check if user has specific role
     */
    public function hasRole($role) {
        return $this->isLoggedIn() && $_SESSION['role'] === $role;
    }
    
    /**
     * Require login (redirect if not logged in)
     */
    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            header('Location: /login.php');
            exit;
        }
    }
    
    /**
     * Require specific role (redirect if not authorized)
     */
    public function requireRole($role) {
        $this->requireLogin();
        
        if (!$this->hasRole($role)) {
            header('Location: /index.php');
            exit;
        }
    }
    /**
     * Check if user has specific permission
     * Automatically returns true for user_id 1 (Super Admin)
     */
    public function hasPermission($permissionName, $userId = null) {
        if (!$this->isLoggedIn() && !$userId) {
            return false;
        }

        if (!$userId) {
            $userId = $_SESSION['user_id'];
        }

        // Super Admin (ID 1) has all permissions
        if ($userId == 1) {
            return true;
        }

        try {
            // Check session cache first for current user
            if ($userId == $_SESSION['user_id'] && isset($_SESSION['permissions'])) {
                return in_array($permissionName, $_SESSION['permissions']);
            }

            $stmt = $this->db->prepare("SELECT 1 FROM admin_permissions WHERE user_id = ? AND permission_name = ?");
            $stmt->execute([$userId, $permissionName]);
            return $stmt->fetch() ? true : false;
        } catch (PDOException $e) {
            error_log("Permission check error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all permissions for a user
     */
    public function getUserPermissions($userId) {
        // Super Admin has all permissions
        if ($userId == 1) {
            return [
                'manage_admins', 'manage_users', 'manage_sellers', 
                'manage_products', 'manage_categories', 'view_reports', 'manage_settings'
            ];
        }

        try {
            $stmt = $this->db->prepare("SELECT permission_name FROM admin_permissions WHERE user_id = ?");
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Grant permission to user
     */
    public function grantPermission($userId, $permissionName) {
        if (!$this->hasRole('admin')) {
            return false;
        }

        try {
            $stmt = $this->db->prepare("INSERT IGNORE INTO admin_permissions (user_id, permission_name, granted_by) VALUES (?, ?, ?)");
            $stmt->execute([$userId, $permissionName, $_SESSION['user_id']]);
            return true;
        } catch (PDOException $e) {
            error_log("Grant permission error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Revoke permission from user
     */
    public function revokePermission($userId, $permissionName) {
        if (!$this->hasRole('admin')) {
            return false;
        }

        try {
            $stmt = $this->db->prepare("DELETE FROM admin_permissions WHERE user_id = ? AND permission_name = ?");
            $stmt->execute([$userId, $permissionName]);
            return true;
        } catch (PDOException $e) {
            error_log("Revoke permission error: " . $e->getMessage());
            return false;
        }
    }
}
