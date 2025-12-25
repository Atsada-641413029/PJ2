<?php
require_once '../includes/Auth.php';
require_once '../includes/Database.php';

$auth = new Auth();
$currentUser = $auth->getCurrentUser();

echo "<h1>Debug Auth</h1>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

if ($currentUser) {
    echo "<h2>Current User</h2>";
    echo "ID: " . $currentUser['user_id'] . "<br>";
    echo "Role: " . $currentUser['role'] . "<br>";
    
    echo "<h2>Permissions Check</h2>";
    $permissions = [
        'manage_users', 'manage_sellers', 'manage_products', 
        'manage_categories', 'view_reports', 'manage_settings', 'manage_admins'
    ];
    
    foreach ($permissions as $perm) {
        $has = $auth->hasPermission($perm) ? 'YES' : 'NO';
        echo "$perm: $has<br>";
    }
    
    echo "<h2>Database Permissions</h2>";
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT * FROM admin_permissions WHERE user_id = ?");
    $stmt->execute([$currentUser['user_id']]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($rows);
    echo "</pre>";
} else {
    echo "Not logged in";
}
