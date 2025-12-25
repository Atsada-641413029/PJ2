<?php
/**
 * Admin Authentication Check
 * Protects admin pages from unauthorized access
 */

require_once __DIR__ . '/../includes/Auth.php';

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

$auth = new Auth();

// Check if user is logged in and is admin
if (!$auth->isLoggedIn()) {
    header('Location: /login.php');
    exit;
}

if (!$auth->hasRole('admin')) {
    header('Location: /index.php');
    exit;
}

// Get current user info
$currentUser = $auth->getCurrentUser();
