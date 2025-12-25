CREATE TABLE IF NOT EXISTS admin_permissions (
    permission_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    permission_name VARCHAR(50) NOT NULL,
    granted_by INT,
    granted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (granted_by) REFERENCES users(user_id) ON DELETE SET NULL,
    UNIQUE KEY unique_user_permission (user_id, permission_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Grant all permissions to the first admin (superuser)
INSERT INTO admin_permissions (user_id, permission_name, granted_by)
SELECT user_id, 'manage_admins', user_id FROM users WHERE user_id = 1
ON DUPLICATE KEY UPDATE permission_id=permission_id;

INSERT INTO admin_permissions (user_id, permission_name, granted_by)
SELECT user_id, 'manage_users', user_id FROM users WHERE user_id = 1
ON DUPLICATE KEY UPDATE permission_id=permission_id;

INSERT INTO admin_permissions (user_id, permission_name, granted_by)
SELECT user_id, 'manage_sellers', user_id FROM users WHERE user_id = 1
ON DUPLICATE KEY UPDATE permission_id=permission_id;

INSERT INTO admin_permissions (user_id, permission_name, granted_by)
SELECT user_id, 'manage_products', user_id FROM users WHERE user_id = 1
ON DUPLICATE KEY UPDATE permission_id=permission_id;

INSERT INTO admin_permissions (user_id, permission_name, granted_by)
SELECT user_id, 'manage_categories', user_id FROM users WHERE user_id = 1
ON DUPLICATE KEY UPDATE permission_id=permission_id;

INSERT INTO admin_permissions (user_id, permission_name, granted_by)
SELECT user_id, 'view_reports', user_id FROM users WHERE user_id = 1
ON DUPLICATE KEY UPDATE permission_id=permission_id;

INSERT INTO admin_permissions (user_id, permission_name, granted_by)
SELECT user_id, 'manage_settings', user_id FROM users WHERE user_id = 1
ON DUPLICATE KEY UPDATE permission_id=permission_id;
