<?php
/**
 * Admin Dashboard API
 * Returns statistics and data for admin dashboard
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../../includes/Auth.php';
require_once __DIR__ . '/../../includes/Database.php';

// Check if user is admin
$auth = new Auth();
if (!$auth->isLoggedIn() || !$auth->hasRole('admin')) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

try {
    $db = Database::getInstance()->getConnection();
    
    // Get statistics
    $stats = [];
    
    // Total users
    $stmt = $db->query("SELECT COUNT(*) as count FROM users");
    $stats['total_users'] = $stmt->fetch()['count'];
    
    // Active sellers
    $stmt = $db->query("SELECT COUNT(*) as count FROM users WHERE role = 'seller' AND status = 'active'");
    $stats['active_sellers'] = $stmt->fetch()['count'];
    
    // Pending sellers
    $stmt = $db->query("SELECT COUNT(*) as count FROM users WHERE role = 'seller' AND status = 'inactive'");
    $stats['pending_sellers'] = $stmt->fetch()['count'];
    
    // Total products (if table exists)
    try {
        $stmt = $db->query("SELECT COUNT(*) as count FROM products");
        $stats['total_products'] = $stmt->fetch()['count'];
    } catch (PDOException $e) {
        $stats['total_products'] = 0;
    }
    
    // Get pending sellers
    $stmt = $db->prepare("
        SELECT user_id, username, email, full_name, phone, created_at 
        FROM users 
        WHERE role = 'seller' AND status = 'inactive'
        ORDER BY created_at DESC
        LIMIT 10
    ");
    $stmt->execute();
    $pending_sellers = $stmt->fetchAll();
    
    // Get recent users
    $stmt = $db->prepare("
        SELECT user_id, username, email, role, status, created_at 
        FROM users 
        ORDER BY created_at DESC
        LIMIT 10
    ");
    $stmt->execute();
    $recent_users = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'stats' => $stats,
        'pending_sellers' => $pending_sellers,
        'recent_users' => $recent_users
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => DEBUG_MODE ? $e->getMessage() : 'Database error'
    ]);
}
