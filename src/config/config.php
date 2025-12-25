<?php
/**
 * Database Configuration
 * Construction Materials Management System
 */

// Database credentials
define('DB_HOST', 'construction_mart_mysql');  // Docker service name
define('DB_USER', 'construction_user');
define('DB_PASS', 'construction_pass');
define('DB_NAME', 'construction_mart');
define('DB_CHARSET', 'utf8mb4');

// Application settings
define('APP_NAME', 'Construction Mart');
define('APP_URL', 'http://localhost:8080');

// Session settings
define('SESSION_LIFETIME', 3600); // 1 hour

// Error reporting (set to false in production)
define('DEBUG_MODE', true);

if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
