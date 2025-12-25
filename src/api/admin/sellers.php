<?php
/**
 * Get Sellers Data API
 * Returns pending and approved sellers with statistics
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../../includes/Auth.php';
require_once __DIR__ . '/../../includes/Database.php';

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
    
    $stmt = $db->query("SELECT COUNT(*) as count FROM users WHERE role = 'seller' AND status = 'inactive'");
    $stats['pending'] = $stmt->fetch()['count'];
    
    $stmt = $db->query("SELECT COUNT(*) as count FROM users WHERE role = 'seller' AND status = 'active'");
    $stats['approved'] = $stmt->fetch()['count'];
    
    $stmt = $db->query("SELECT COUNT(*) as count FROM users WHERE role = 'seller' AND status = 'suspended'");
    $stats['rejected'] = $stmt->fetch()['count'];
    
    // Get pending sellers
    $stmt = $db->prepare("
        SELECT user_id, username, email, full_name, phone, created_at 
        FROM users 
        WHERE role = 'seller' AND status = 'inactive'
        ORDER BY created_at DESC
    ");
    $stmt->execute();
    $pending_sellers = $stmt->fetchAll();
    
    // Get approved sellers
    $stmt = $db->prepare("
        SELECT user_id, username, email, full_name, phone, updated_at 
        FROM users 
        WHERE role = 'seller' AND status = 'active'
        ORDER BY updated_at DESC
        LIMIT 20
    ");
    $stmt->execute();
    $approved_sellers = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'stats' => $stats,
        'pending_sellers' => $pending_sellers,
        'approved_sellers' => $approved_sellers
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => DEBUG_MODE ? $e->getMessage() : 'Database error'
    ]);
}
