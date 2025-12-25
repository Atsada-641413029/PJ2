<?php
/**
 * Logout API Endpoint
 * Destroys session and redirects to home page
 */

require_once __DIR__ . '/../includes/Auth.php';

$auth = new Auth();
$auth->logout();

// Redirect to home page
header('Location: /index.php');
exit;
