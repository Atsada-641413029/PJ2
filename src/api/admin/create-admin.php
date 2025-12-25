<?php
/**
 * Create Admin API
 * Creates a new admin user and assigns initial permissions
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

if (!isset($input['username']) || !isset($input['email']) || !isset($input['password']) || !isset($input['full_name'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$username = $input['username'];
$email = $input['email'];
$password = $input['password'];
$fullName = $input['full_name'];
$phone = isset($input['phone']) ? $input['phone'] : null;
$permissions = isset($input['permissions']) ? $input['permissions'] : [];

try {
    $db = Database::getInstance()->getConnection();
    
    // Check if username or email already exists
    $stmt = $db->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    
    if ($stmt->fetch()) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Username or email already exists']);
        exit;
    }
    
    // Hash password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    // Begin transaction
    $db->beginTransaction();
    
    // Create user
    $stmt = $db->prepare("
        INSERT INTO users (username, email, password_hash, full_name, phone, role, status) 
        VALUES (?, ?, ?, ?, ?, 'admin', 'active')
    ");
    $stmt->execute([$username, $email, $passwordHash, $fullName, $phone]);
    $newUserId = $db->lastInsertId();
    
    // Assign permissions
    if (!empty($permissions)) {
        $stmt = $db->prepare("INSERT INTO admin_permissions (user_id, permission_name, granted_by) VALUES (?, ?, ?)");
        $currentUserId = $_SESSION['user_id'];
        
        foreach ($permissions as $permission) {
            $stmt->execute([$newUserId, $permission, $currentUserId]);
        }
    }
    
    $db->commit();
    
    echo json_encode([
        'success' => true,
        'message' => 'Admin created successfully',
        'user_id' => $newUserId
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
