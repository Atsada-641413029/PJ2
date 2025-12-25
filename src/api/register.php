<?php
/**
 * Register API Endpoint
 * Handles user registration (sellers only)
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../includes/Auth.php';

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Validate required fields
$required = ['fullname', 'email', 'phone', 'password'];
foreach ($required as $field) {
    if (!isset($input[$field]) || empty(trim($input[$field]))) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน']);
        exit;
    }
}

$fullname = trim($input['fullname']);
$email = trim($input['email']);
$phone = trim($input['phone']);
$password = $input['password'];
$role = isset($input['role']) ? $input['role'] : 'seller'; // Default to seller

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'รูปแบบอีเมลไม่ถูกต้อง']);
    exit;
}

// Validate phone format (Thai format)
$phone = preg_replace('/[^0-9]/', '', $phone);
if (!preg_match('/^0[0-9]{9}$/', $phone)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'รูปแบบเบอร์โทรศัพท์ไม่ถูกต้อง']);
    exit;
}

// Validate password length
if (strlen($password) < 6) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร']);
    exit;
}

// Validate fullname length
if (strlen($fullname) < 3) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ชื่อ-นามสกุลต้องมีอย่างน้อย 3 ตัวอักษร']);
    exit;
}

// Attempt registration
$auth = new Auth();
$result = $auth->register($fullname, $email, $phone, $password, $role);

if ($result['success']) {
    http_response_code(201);
    echo json_encode($result);
} else {
    http_response_code(400);
    echo json_encode($result);
}
