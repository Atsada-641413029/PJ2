<?php
/**
 * Update Admin Permissions API
 * Updates permissions for a specific admin user
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

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['user_id']) || !isset($input['permissions'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'User ID and permissions required']);
    exit;
}

$userId = intval($input['user_id']);
$permissions = $input['permissions'];

// Prevent modifying Super Admin (ID 1)
if ($userId == 1) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Cannot modify Super Admin permissions']);
    exit;
}

try {
    $db = Database::getInstance()->getConnection();
    
    // Check if user exists and is admin
    $stmt = $db->prepare("SELECT role FROM users WHERE user_id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    
    if (!$user || $user['role'] !== 'admin') {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Admin user not found']);
        exit;
    }
    
    // Begin transaction
    $db->beginTransaction();
    
    // Remove existing permissions
    $stmt = $db->prepare("DELETE FROM admin_permissions WHERE user_id = ?");
    $stmt->execute([$userId]);
    
    // Add new permissions
    if (!empty($permissions)) {
        $stmt = $db->prepare("INSERT INTO admin_permissions (user_id, permission_name, granted_by) VALUES (?, ?, ?)");
        $currentUserId = $_SESSION['user_id'];
        
        foreach ($permissions as $permission) {
            $stmt->execute([$userId, $permission, $currentUserId]);
        }
    }
    
    $db->commit();
    
    echo json_encode([
        'success' => true,
        'message' => 'Permissions updated successfully'
    ]);
    
} catch (PDOException $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => DEBUG_MODE ? $e->getMessage() : 'Database error'
    ]);
}
