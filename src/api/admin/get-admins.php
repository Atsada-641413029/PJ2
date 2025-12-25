<?php
/**
 * Get Admins API
 * Returns list of all admin users and their permissions
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../../includes/Auth.php';
require_once __DIR__ . '/../../includes/Database.php';

$auth = new Auth();

// Check if user is logged in and has manage_admins permission
if (!$auth->isLoggedIn() || !$auth->hasRole('admin') || !$auth->hasPermission('manage_admins')) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

try {
    $db = Database::getInstance()->getConnection();
    
    // Get all admins
    $stmt = $db->prepare("
        SELECT user_id, username, email, full_name, phone, status, created_at 
        FROM users 
        WHERE role = 'admin'
        ORDER BY created_at DESC
    ");
    $stmt->execute();
    $admins = $stmt->fetchAll();
    
    // Enrich with permissions
    foreach ($admins as &$admin) {
        $stmt = $db->prepare("SELECT permission_name FROM admin_permissions WHERE user_id = ?");
        $stmt->execute([$admin['user_id']]);
        $admin['permissions'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Super admin has all permissions implicitly, but front-end might want to know distinctively
        if ($admin['user_id'] == 1) {
             $admin['is_super_admin'] = true;
             $admin['permissions'] = [
                'manage_admins', 'manage_users', 'manage_sellers', 
                'manage_products', 'manage_categories', 'view_reports', 'manage_settings'
            ];
        } else {
            $admin['is_super_admin'] = false;
        }
    }
    
    echo json_encode([
        'success' => true,
        'admins' => $admins
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => DEBUG_MODE ? $e->getMessage() : 'Database error'
    ]);
}
