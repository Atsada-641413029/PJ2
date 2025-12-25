<?php
/**
 * Database Connection Test
 * Test if database connection is working properly
 */

// Display errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Database Connection Test</h1>";
echo "<hr>";

// Test 1: Check if config file exists
echo "<h2>1. Configuration File</h2>";
if (file_exists(__DIR__ . '/config/config.php')) {
    echo "✅ Config file found<br>";
    require_once __DIR__ . '/config/config.php';
    echo "✅ Config loaded successfully<br>";
    echo "Database Host: " . DB_HOST . "<br>";
    echo "Database Name: " . DB_NAME . "<br>";
    echo "Database User: " . DB_USER . "<br>";
} else {
    echo "❌ Config file not found<br>";
    exit;
}

echo "<hr>";

// Test 2: Test Database class
echo "<h2>2. Database Class</h2>";
try {
    require_once __DIR__ . '/includes/Database.php';
    echo "✅ Database class loaded<br>";
    
    $db = Database::getInstance();
    echo "✅ Database instance created<br>";
    
    $conn = $db->getConnection();
    echo "✅ Database connection established<br>";
    echo "Connection type: " . get_class($conn) . "<br>";
} catch (Exception $e) {
    echo "❌ Database connection failed<br>";
    echo "Error: " . $e->getMessage() . "<br>";
    exit;
}

echo "<hr>";

// Test 3: Test database queries
echo "<h2>3. Database Queries</h2>";
try {
    // Check if users table exists
    $stmt = $conn->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Users table exists<br>";
    } else {
        echo "❌ Users table not found<br>";
    }
    
    // Count users
    $stmt = $conn->query("SELECT COUNT(*) as count FROM users");
    $result = $stmt->fetch();
    echo "✅ Total users in database: " . $result['count'] . "<br>";
    
    // List all users (without passwords)
    $stmt = $conn->query("SELECT user_id, username, email, role, status FROM users");
    $users = $stmt->fetchAll();
    
    echo "<h3>Users List:</h3>";
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Status</th></tr>";
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>" . $user['user_id'] . "</td>";
        echo "<td>" . $user['username'] . "</td>";
        echo "<td>" . $user['email'] . "</td>";
        echo "<td>" . $user['role'] . "</td>";
        echo "<td>" . $user['status'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (PDOException $e) {
    echo "❌ Query failed<br>";
    echo "Error: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// Test 4: Test Auth class
echo "<h2>4. Authentication Class</h2>";
try {
    require_once __DIR__ . '/includes/Auth.php';
    echo "✅ Auth class loaded<br>";
    
    $auth = new Auth();
    echo "✅ Auth instance created<br>";
    
    // Check if logged in
    if ($auth->isLoggedIn()) {
        echo "✅ User is logged in<br>";
        $user = $auth->getCurrentUser();
        echo "Current user: " . $user['email'] . " (" . $user['role'] . ")<br>";
    } else {
        echo "ℹ️ No user logged in (this is normal for test page)<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Auth class failed<br>";
    echo "Error: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// Test 5: Check all tables
echo "<h2>5. Database Tables</h2>";
try {
    $stmt = $conn->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "✅ Found " . count($tables) . " tables:<br>";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>" . $table . "</li>";
    }
    echo "</ul>";
    
} catch (PDOException $e) {
    echo "❌ Failed to list tables<br>";
    echo "Error: " . $e->getMessage() . "<br>";
}

echo "<hr>";
echo "<h2>✅ All Tests Completed!</h2>";
echo "<p><a href='index.php'>← Back to Home</a></p>";
?>

<style>
    body {
        font-family: Arial, sans-serif;
        max-width: 1000px;
        margin: 20px auto;
        padding: 20px;
        background: #f5f5f5;
    }
    h1 { color: #2C3E50; }
    h2 { color: #34495E; margin-top: 20px; }
    table {
        background: white;
        margin: 10px 0;
        width: 100%;
    }
    th {
        background: #3498db;
        color: white;
        padding: 10px;
    }
    td {
        padding: 8px;
    }
    tr:nth-child(even) {
        background: #f9f9f9;
    }
</style>
